<?php

namespace Kunoichi\Hagakure;


/**
 * Command utility for Hagakure
 *
 * @package Hagakure
 */
class Command extends \WP_CLI_Command {

	/**
	 * Trigger error to watch log. For debug only.
	 *
	 * ## OPTIONS
	 *
	 * <message>
	 * : Error message.
	 *
	 * <type>
	 * : Error type. notice, warning, error.
	 *
	 * @synopsis <message> <type>
	 * @param array $args
	 *
	 */
	public function error( $args ) {
		list( $message, $type ) = $args;
		switch ( $type ) {
			case 'warning':
				$error_lv = E_USER_WARNING;
				break;
			case 'notice':
				$error_lv = E_USER_NOTICE;
				break;
			default:
				$error_lv = E_USER_ERROR;
				break;
		}
		trigger_error( $message, $error_lv );
	}

	/**
	 * Overflow memory for Debug.
	 *
	 * ## OPTIONS
	 *
	 * [--memory=<memory>]
	 * : Memory size. Default, 16M.
	 *
	 * @synopsis [--memory=<memory>]
	 *
	 * @param array $args
	 * @param array $assoc
	 * @throws \Exception
	 */
	public function overflow( $args, $assoc ) {
		$limit = $assoc['memory'] ?? '16M';
		if ( false === ini_set( 'memory_limit', $limit ) ) {
			\WP_CLI::error( sprintf( 'Failed to set memory: %s', $limit ) );
		}
		\WP_CLI::line( sprintf( 'Start exhausting memory limit: %s', $limit ) );
		\WP_CLI::line( '=================' );
		\WP_CLI::line( '' );
		// Retries post randomly and store it to array.
		$posts = [];
		while ( true ) {
			$posts = array_merge( $posts, get_posts( [
				'post_type'      => 'any',
				'post_status'    => 'any',
				'posts_per_page' => 100,
			] ) );
			echo '.';
		}
	}

	/**
	 * Insert dummy contents.
	 *
	 * @synopsis <amount> [--title_length=<title_length>]
	 * @param array $args
	 * @param array $assoc
	 *
	 * @return void
	 */
	public function insert_dummy( $args, $assoc ) {
		list( $total ) = $args;
		$title_length  = $assoc['title_length'] ?? 80;
		$src           = dirname( __DIR__, 3 ) . '/doc/three-ghost-story.txt';
		if ( ! file_exists( $src ) ) {
			\WP_CLI::error( sprintf( 'File not found at %s', $src ) );
		}
		$lines       = array_values( array_filter( explode( "\n\n", file_get_contents( $src ) ) ) );
		$titles      = array_values( array_filter( $lines, function( $line ) use ( $title_length ) {
			return strlen( $line ) < $title_length;
		} ) );
		$line_count  = count( $lines );
		$title_count = count( $titles );
		$inserted    = 0;
		$range       = range( 1, $line_count );
		global $wpdb;
		for ( $i = 0; $i < $total; $i++ ) {
			$title   = preg_replace( '/^“(.*)”$/u', '$1', $titles[ array_rand( $titles, 1 ) ] );
			$indices = array_rand( $lines, $range[ array_rand( $range ) ] );
			$content = [];
			foreach ( $indices as $index ) {
				$content[] = $lines[ $index ];
			}
			$post_args = [
				'post_title'   => $title,
				'post_content' => implode( "\n", $content ),
				'post_status'  => 'publish',
				'post_type'    => 'post',
			];
			$result    = wp_insert_post( $post_args );
			echo $result ? '.' : 'x';
			if ( $result ) {
				$inserted++;
			}
			// Clear savequreries cache.
			$wpdb->queries = [];
		}

		\WP_CLI::line( '' );
		\WP_CLI::success( sprintf( '%d posts inserted.', $inserted ) );
	}
}
