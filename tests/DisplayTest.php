<?php
/**
 * Test stab.
 *
 * @package hagakure
 */

class DisplayTest extends WP_UnitTestCase {

    public function test_get_the_date() {
		$handler = \Kunoichi\Hagakure\ErrorHandler::get_instance();
        $this->assertTrue( is_int( $handler->error_levels() ) );
    }
}
