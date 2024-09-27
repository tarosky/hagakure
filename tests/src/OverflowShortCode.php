<?php

namespace Kunoichi\Hagakure\Tests;


use Kunoichi\Hagakure\Pattern\Singleton;

/**
 * Register Overflow short code.
 */
class OverflowShortCode extends Singleton {

	/**
	 * {@inheritdoc}
	 */
	protected function init() {
		add_shortcode( 'overflow', [ $this, 'overflow' ] );
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
}
