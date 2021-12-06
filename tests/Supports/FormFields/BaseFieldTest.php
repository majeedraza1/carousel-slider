<?php

namespace Supports\FormFields;

use CarouselSlider\Supports\FormFields\BaseField;

class BaseFieldTest extends \WP_UnitTestCase {
	protected $instance;

	public function setUp() {
		parent::setUp();
		$this->instance = $this->getMockForAbstractClass( BaseField::class );
	}

	public function test_set_and_get_value() {
		$this->instance->set_value( 'Sayful' );
		$this->assertEquals( 'Sayful', $this->instance->get_value() );
	}

	public function test_set_and_get_name() {
		$this->instance->set_name( '_test_field_name' );
		$this->assertEquals( '_test_field_name', $this->instance->get_name() );
	}

	public function test_set_and_get_setting() {
		$this->instance->set_setting( 'data-key', 'Test Data' );
		$this->assertArrayHasKey( 'data-key', $this->instance->get_settings() );

		$this->instance->set_settings( [ 'field_class' => 'some-custom-class' ] );
		$this->assertEquals( 'some-custom-class', $this->instance->get_setting( 'field_class' ) );
	}
}
