<?php

namespace CarouselSlider\Test\Abstracts;

use CarouselSlider\Abstracts\AbstractTemplate;

class AbstractTemplateTest extends \WP_UnitTestCase {
	protected $template;

	protected function setUp() {
		parent::setUp();
		$this->template = $this->getMockForAbstractClass( AbstractTemplate::class );
	}

	public function test_create_slider() {
		$title     = 'Test slider';
		$slider_id = $this->template->create_slider( $title );
		$post      = get_post( $slider_id );
		$this->assertIsNumeric( $slider_id );
		$this->assertEquals( $title, $post->post_title );
	}

	public function test_get_images() {
		$image_paths = [
			CAROUSEL_SLIDER_PATH . '/tests/assets/logo.jpg',
			CAROUSEL_SLIDER_PATH . '/tests/assets/logo.gif',
		];
		foreach ( $image_paths as $image_path ) {
			$post          = $this->factory()->post->create_and_get();
			$attachment_id = $this->factory()->attachment->create_upload_object( $image_path, $post->ID );
			$this->assertIsNumeric( $attachment_id );
		}

		$images = $this->template->get_images( 'full', 5 );
		$this->assertTrue( is_array( $images ) );
		$this->assertNotEmpty( count( $images ) );
		foreach ( $images as $image ) {
			$this->assertIsArray( $image );
			$this->assertTrue( isset( $image['id'] ) );
		}
	}

	public function test_default_setting() {
		$settings = $this->template->get_default_settings();
		$this->assertIsArray( $settings );
	}
}
