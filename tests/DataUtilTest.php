<?php

use CarouselSlider\Abstracts\Data;

class DataUtilTest extends WP_UnitTestCase {

	/**
	 * @var Data
	 */
	protected $data;

	protected function set_up() {
		parent::set_up();

		$this->data = new Data;
	}

	public function test_set_data() {
		$this->data->set_prop( 'prop1', 'prop1 value' );
		$this->data['prop_int_str']   = '10';
		$this->data['prop_float_str'] = '10.549';

		$this->assertEquals( 'prop1 value', $this->data->prop1 );
		$this->assertEquals( 'prop1 value', $this->data['prop1'] );
		$this->assertEquals( 'prop1 value', $this->data->get_prop( 'prop1' ) );
		$this->assertEquals( '10', $this->data->get_prop( 'prop_int_str' ) );
		$this->assertEquals( '10.549', $this->data->get_prop( 'prop_float_str' ) );
	}

	public function test_has_data() {
		$this->data->set_prop( 'prop2', 'prop2 value' );

		$this->assertTrue( $this->data->has_prop( 'prop2' ) );
		$this->assertTrue( isset( $this->data['prop2'] ) );
		$this->assertTrue( isset( $this->data->prop2 ) );
		$this->assertFalse( $this->data->has_prop( 'prop3' ) );
	}

	public function test_remove_data() {
		$this->data->set_prop( 'prop1', 'prop1 value' );
		$this->data->set_prop( 'prop2', 'prop2 value' );
		$this->data->set_prop( 'prop3', 'prop3 value' );

		$this->data->remove_prop( 'prop1' );
		unset( $this->data['prop2'] );
		unset( $this->data['prop3'] );

		$this->assertFalse( $this->data->has_prop( 'prop1' ) );
		$this->assertFalse( $this->data->has_prop( 'prop2' ) );
		$this->assertFalse( $this->data->has_prop( 'prop3' ) );
	}

	public function test_it_return_default_value_if_props_not_exits() {
		$this->assertEquals( 'default value', $this->data->get_prop( 'key_not_exists', 'default value' ) );
	}

	public function test_it_returns_json_string_of_data_when_echo_class() {
		$this->data->set_prop( 'prop5', 'prop5 value' );
		$this->assertEquals( $this->data, wp_json_encode( $this->data->to_array() ) );
		$this->assertEquals( wp_json_encode( $this->data ), wp_json_encode( $this->data->to_array() ) );
	}
}
