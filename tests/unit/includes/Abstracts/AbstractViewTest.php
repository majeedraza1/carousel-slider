<?php

namespace CarouselSlider\Test\Abstracts;

use CarouselSlider\Abstracts\AbstractView;

class AbstractViewTest extends \WP_UnitTestCase {
	protected $instance;

	public function setUp() {
		parent::setUp();
		$this->instance = $this->getMockForAbstractClass( AbstractView::class );
	}

	public function test_setter_of_the_class() {
		$this->instance->set_slider_id( 100 );
		$this->instance->set_slider_type( 'image-carousel' );

		$this->assertEquals( 100, $this->instance->get_slider_id() );
		$this->assertEquals( 'image-carousel', $this->instance->get_slider_type() );
	}
}
