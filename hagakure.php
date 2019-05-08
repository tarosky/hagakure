<?php
/**
 * Plugin Name: Hagakure - Yet Another Error Reporter
 * Version: 0.7.0
 * Description: A WordPress plugin to clarify meaningless errors like "Allowed memory size of xxxxxxxx bytes exhausted".
 * Author: Kunoichi
 * Author URI: https://kunoichiwp.com
 * Text Domain: hagakure
 * Domain Path: /languages
 */

// Do not load directly.
defined( 'ABSPATH' ) || die();

require __DIR__ . '/vendor/autoload.php';

// Register DB loggers.
\Kunoichi\Hagakure\DbLogger::get_instance();

// Register utility commands if under CLI
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 'hagakure', Kunoichi\Hagakure\Command::class );
}
