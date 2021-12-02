<?php

use CarouselSlider\Helper;

class HelperTest extends \WP_UnitTestCase {
	public function test_get_setting() {
		$this->assertEquals( 'optimized', Helper::get_setting( 'load_scripts' ) );
	}
}
