<?php

namespace Kunoichi\Hagakure;


use Kunoichi\Hagakure\Pattern\Singleton;

/**
 * Extra error handler.
 *
 * @package
 */
class ErrorHandler extends Singleton {

	/**
	 * {@inheritdoc}
	 */
	protected function init() {
		set_error_handler( [ $this, 'hagakure_error_handler' ], $this->error_levels() );
	}

	/**
	 * Error handler.
	 *
	 * @param int    $err_no   Error number.
	 * @param string $err_str  Error string.
	 * @param string $err_file Error file.
	 * @param int    $err_line Line no.
	 *
	 * @return false
	 */
	public function hagakure_error_handler( $err_no, $err_str, $err_file = '', $err_line = 0 ) {
		$stack  = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS );
		$prefix = 'PHP ';
		$return = true;
		switch ( $err_no ) {
			case E_NOTICE:
			case E_USER_NOTICE:
				$prefix .= 'Notice: ';
				break;
			case E_USER_WARNING:
			case E_WARNING:
				$prefix .= 'Warning: ';
				break;
			case E_USER_ERROR:
				$prefix .= 'Fatal error: ';
				$return  = false;
				break;
			default:
				$prefix .= 'Error: ';
				break;
		}
		$message = $this->backtrace_to_message( $prefix . $err_str . sprintf( "\t%s:%s", $err_file, $err_line ), $stack );
		error_log( $message );
		return $return;
	}

	/**
	 * Get message.
	 *
	 * @param string $err_str   Error string.
	 * @param array  $backtrace Debug backtrace.
	 *
	 * @return string
	 */
	protected function backtrace_to_message( $err_str, $backtrace ) {
		$lines = [ trim( $err_str ) ];
		foreach ( $backtrace as $detail ) {
			if ( ! empty( $detail['function'] ) && ( 'hagakure_error_handler' === $detail['function'] ) ) {
				continue;
			}
			$file      = sprintf( '%s:%s', ( $detail['file'] ?? 'UNKNOWN_FILE' ), ( $detail['line'] ?? 'n' ) );
			$functions = [];
			foreach ( [ 'function', 'type', 'class' ] as $key ) {
				if ( ! empty( $detail[ $key ] ) ) {
					array_unshift( $functions, $detail[ $key ] );
				}
			}
			$function = implode( '', $functions );
			$lines[]  = sprintf( "%s\t%s", $function, $file );
		}
		return implode( "\n", $lines );
	}

	/**
	 * Get error level.
	 *
	 * @return int
	 */
	public function error_levels() {
		if ( defined( 'HAGAKURE_ERROR_LEVEL' ) ) {
			return HAGAKURE_ERROR_LEVEL;
		}
		return E_NOTICE | E_USER_NOTICE | E_USER_WARNING | E_WARNING | E_USER_ERROR;
	}
}
