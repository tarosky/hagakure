<?php

namespace Kunoichi\Hagakure\Tests;


use Kunoichi\Hagakure\Pattern\Singleton;

/**
 * Register Overflow short code.
 */
class InappropriateShortCode extends Singleton {

	/**
	 * {@inheritdoc}
	 */
	protected function init() {
		add_shortcode( 'overflow', [ $this, 'overflow' ] );
		add_shortcode( 'fatal_error', [ $this, 'fatal_error' ] );
	}

	/**
	 * Overflow short code.
	 *
	 * @param array $atts Attributes.
	 * @param string $content Content.
	 *
	 * @return string
	 */
	public function overflow( $atts, $content = '' ) {
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
		return '<div style="overflow: hidden;">' . $content . '</div>';
	}

	/**
	 * Raise fatal error.
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public function fatal_error( $atts, $content = '' ) {
		// Call undefined function.
		// phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		this_function_is_never_defined();
		return '<div style="overflow: hidden;">' . $content . '</div>';
	}
}
