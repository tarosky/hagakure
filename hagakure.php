<?php
/**
 * Plugin Name: Hagakure - Yet Another Error Reporter
 * Version: nightly
 * Description: A WordPress plugin to clarify meaningless errors like "Allowed memory size of xxxxxxxx bytes exhausted".
 * Author: Tarosky
 * Author URI: https://tarosky.co.jp
 * Requires at least: 5.9
 * Requires PHP: 7.4
 * Text Domain: hagakure
 * Domain Path: /languages
 */

// Do not load directly.
defined( 'ABSPATH' ) || die();

require __DIR__ . '/vendor/autoload.php';

// Register DB loggers.
\Kunoichi\Hagakure\DbLogger::get_instance();
// Set error handler.
\Kunoichi\Hagakure\ErrorHandler::get_instance();
// Register Slow Query logger.
\Kunoichi\Hagakure\SlowQuery::get_instance();

// Register utility commands if under CLI
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 'hagakure', Kunoichi\Hagakure\Command::class );
}

// Register shortcodes for test environment.
if ( class_exists( 'Kunoichi\Hagakure\Tests\InappropriateShortCode' ) ) {
	\Kunoichi\Hagakure\Tests\InappropriateShortCode::get_instance();
}
