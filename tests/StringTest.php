<?php

/**
 * Test strings
 */
class StringTest extends WP_UnitTestCase {

	use \Kunoichi\Hagakure\Utility\EnvInfo;

	/**
	 * Test string
	 *
	 * @return void
	 */
	public function test_sapi() {
		$this->assertEquals( 'CLI', $this->uri_info() );
	}

}
