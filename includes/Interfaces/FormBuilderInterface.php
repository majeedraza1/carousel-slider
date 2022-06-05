<?php

namespace CarouselSlider\Interfaces;

defined( 'ABSPATH' ) || exit;

interface FormBuilderInterface {
	/**
	 * Set fields settings
	 *
	 * @param array $settings The setting array.
	 */
	public function set_fields_settings( array $settings );

	/**
	 * Set option name
	 *
	 * @param string $option_name The option name.
	 */
	public function set_option_name( string $option_name );

	/**
	 * Set values
	 *
	 * @param array $values The values.
	 */
	public function set_values( array $values );

	/**
	 * Render form
	 *
	 * @return string
	 */
	public function render(): string;
}
