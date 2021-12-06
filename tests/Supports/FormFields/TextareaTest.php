<?php

namespace Supports\FormFields;

use CarouselSlider\Supports\FormFields\Textarea;

class TextareaTest extends \WP_UnitTestCase {

	protected $instance;

	public function setUp() {
		parent::setUp();
		$this->instance = new Textarea();
		$this->instance->set_name( '_text_text_input' );
		$this->instance->set_value( 'Sayful' );
		$this->instance->set_settings( [
			'label'            => 'Test Label',
			'rows'             => 10,
			'input_attributes' => [
				'data-active' => true,
				'data-custom' => 'custom value',
				'data-ids'    => [ 1, 2, 3 ]
			]
		] );
	}

	public function test_render() {
		$view = $this->instance->render();
		$this->assertStringContainsString( 'textarea', $view );
		$this->assertStringContainsString( 'rows="10"', $view );
	}
}
