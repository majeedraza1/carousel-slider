<?php

namespace CarouselSlider\Test\Supports\FormFields;

use CarouselSlider\Supports\FormFields\Text;

class TextTest extends \WP_UnitTestCase {
	protected $instance;

	public function setUp() {
		parent::setUp();
		$this->instance = new Text();
		$this->instance->set_name( '_text_text_input' );
		$this->instance->set_value( 'Sayful' );
		$this->instance->set_settings( [
			'label'            => 'Test Label',
			'required'         => true,
			'input_attributes' => [
				'data-active' => true,
				'data-custom' => 'custom value',
				'data-ids'    => [ 1, 2, 3 ]
			]
		] );
	}

	public function test_render() {
		$view = $this->instance->render();
		$this->assertStringContainsString( '_text_text_input', $view );
		$this->assertStringContainsString( 'Sayful', $view );
		$this->assertStringContainsString( 'data-custom', $view );
		$this->assertStringContainsString( 'required', $view );
		$this->assertStringContainsString( '1,2,3', $view );
		$this->assertStringContainsString( 'data-active="true"', $view );
	}
}
