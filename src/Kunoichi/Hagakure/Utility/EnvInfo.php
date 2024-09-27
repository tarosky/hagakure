<?php

namespace Kunoichi\Hagakure\Utility;

/**
 * Environment info.
 */
trait EnvInfo {

	/**
	 * Get URI info.
	 *
	 * @return string
	 */
	private function uri_info() {
		if ( ! empty( $_SERVER['REQUEST_URI'] ) ) {
			$uri = $_SERVER['REQUEST_URI'];
		} elseif ( defined( 'DOING_CRON' ) && DOING_CRON ) {
			$uri = '/wp-cron.php';
		} elseif ( defined( 'WP_CLI' ) && WP_CLI ) {
			return 'WP-CLI';
		} elseif ( 'cli' === php_sapi_name() ) {
			return 'CLI';
		} else {
			$uri = 'UNDEFINED';
		}
		return $uri;
	}
}
