<?php

namespace Kunoichi\Hagakure;


use Kunoichi\Hagakure\Pattern\Singleton;
use Kunoichi\Hagakure\Utility\EnvInfo;

/**
 * Log DB call stack.
 *
 * @package hagakure
 */
class DbLogger extends Singleton {

	use EnvInfo;

	private $last_backtrace = [];

	/**
	 * Constructor
	 */
	protected function init() {
		add_filter( 'query', [ $this, 'filter_query' ] );
		register_shutdown_function( [ $this, 'on_shut_down' ] );
	}

	/**
	 * Register backtrace before send query.
	 *
	 * @param string $query
	 *
	 * @return string
	 */
	public function filter_query( $query ) {
		$this->last_backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS );
		return $query;
	}

	/**
	 * Shutdown handler.
	 */
	public function on_shut_down() {
		$error = error_get_last();
		if ( null === $error ) {
			// No error.
			return;
		}
		if ( ! in_array( $error['type'], [ E_ERROR, E_CORE_ERROR ], true ) ) {
			return;
		}
		if ( isset( $error['message'], $error['file'] ) ) {
			if ( str_contains( $error['message'], 'memory size' ) || in_array( basename( $error['file'] ), [ 'wp-db.php', 'db.php' ], true ) ) {
				// Maybe this is db error.
				$rows    = array_map( [ $this, 'filter_row' ], $this->last_backtrace );
				$rows [] = sprintf( 'URI: %s', $this->uri_info() );
				error_log( "wpdb Error Backtrace:\n" . implode( "\n", $rows ) );
			} else {
				// This is normal Fatal Error.
				error_log( sprintf( '[Error Request] URI: %s', $_SERVER['REQUEST_URI'] ?? 'UNKNOWN REQUEST' ) );
			}
		}
	}

	/**
	 * Format backtrace lines.
	 *
	 * @param  array $row
	 *
	 * @return string
	 */
	public function filter_row( $row ) {
		static $line_no = 0;
		++$line_no;
		if ( isset( $row['class'] ) ) {
			return sprintf( '#%5$d	%3$s->%4$s()	%1$s	Line %2$s', $row['file'] ?? ' ', $row['line'] ?? 'N/A', $row['class'], $row['function'], $line_no );
		} else {
			return sprintf( '#%4$d	%3$s	%1$s	Line %2$s', $row['file'] ?? ' ', $row['line'] ?? 'N/A', $row['function'], $line_no );
		}
	}
}
