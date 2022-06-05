<?php

namespace CarouselSlider\Interfaces;

defined( 'ABSPATH' ) || exit;

interface FieldInterface {
	/**
	 * Set settings
	 *
	 * @param array $settings The settings array.
	 *
	 * @return mixed
	 */
	public function set_settings( array $settings );

	/**
	 * Set field name
	 *
	 * @param string $name the field name.
	 *
	 * @return mixed
	 */
	public function set_name( string $name );

	/**
	 * Set field value
	 *
	 * @param mixed $value The field value.
	 *
	 * @return mixed
	 */
	public function set_value( $value );

	/**
	 * Render field html
	 *
	 * @return string
	 */
	public function render(): string;
}
