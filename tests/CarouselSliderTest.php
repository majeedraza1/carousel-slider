<?php

class CarouselSliderTest extends WP_UnitTestCase {

	protected $instance;

	public function setUp() {
		parent::setUp();
		$this->instance = Carousel_Slider::instance();
	}

	function test_wordpress_and_plugin_are_loaded() {
		$this->assertTrue( function_exists( 'do_action' ) );
		$this->assertTrue( class_exists( Carousel_Slider::class ) );
		$this->assertTrue( defined( 'CAROUSEL_SLIDER' ) );
		$this->assertTrue( $this->instance instanceof Carousel_Slider );
	}

	function test_wp_phpunit_is_loaded_via_composer() {
		$this->assertStringStartsWith(
			dirname( __DIR__ ) . '/vendor/',
			getenv( 'WP_PHPUNIT__DIR' )
		);

		$this->assertStringStartsWith(
			dirname( __DIR__ ) . '/vendor/',
			( new ReflectionClass( 'WP_UnitTestCase' ) )->getFileName()
		);
	}
}
