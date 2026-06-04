<?php
/**
 * Test the error handler.
 *
 * @package hagakure
 */

/**
 * Ensure the error handler respects the @ operator / error_reporting().
 */
class ErrorHandlerTest extends WP_UnitTestCase {

	/**
	 * Original error_log ini setting.
	 *
	 * @var string|false
	 */
	private $original_log;

	/**
	 * Path to the temporary log file.
	 *
	 * @var string
	 */
	private $log_file;

	/**
	 * Redirect error_log() output to a temporary file.
	 */
	public function set_up() {
		parent::set_up();
		$this->log_file     = tempnam( sys_get_temp_dir(), 'hagakure-log-' );
		$this->original_log = ini_get( 'error_log' );
		ini_set( 'error_log', $this->log_file );
	}

	/**
	 * Restore the error_log setting and remove the temporary file.
	 */
	public function tear_down() {
		ini_set( 'error_log', false === $this->original_log ? '' : $this->original_log );
		if ( file_exists( $this->log_file ) ) {
			unlink( $this->log_file );
		}
		parent::tear_down();
	}

	/**
	 * The contents of the temporary log file.
	 *
	 * @return string
	 */
	private function log_contents() {
		return file_exists( $this->log_file ) ? file_get_contents( $this->log_file ) : '';
	}

	/**
	 * Errors suppressed with @ must not be logged.
	 */
	public function test_suppressed_error_is_ignored() {
		// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		@trigger_error( 'hagakure-suppressed-warning', E_USER_WARNING );
		$this->assertStringNotContainsString(
			'hagakure-suppressed-warning',
			$this->log_contents(),
			'An error suppressed with the @ operator must not be logged.'
		);
	}

	/**
	 * Errors that are not suppressed must still be logged.
	 */
	public function test_visible_error_is_logged() {
		trigger_error( 'hagakure-visible-warning', E_USER_WARNING );
		$this->assertStringContainsString(
			'hagakure-visible-warning',
			$this->log_contents(),
			'An error that is not suppressed must be logged.'
		);
	}
}
