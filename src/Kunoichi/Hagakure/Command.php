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
	 * @synopsis <message>
	 * @param array $args
	 *
	 */
	public function error( $args ) {
		list( $message ) = $args;
		trigger_error( $message, E_USER_ERROR );
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
		ob_flush();
		// Retries post randomly and store it to array.
		$posts = [];
		while ( true ) {
			$posts = array_merge( $posts, get_posts( [
				'post_type'      => 'any',
				'post_status'    => 'any',
				'posts_per_page' => 100,
			] ) );
			echo '.';
			ob_flush();
		}

	}
}
