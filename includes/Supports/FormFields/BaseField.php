<?php

namespace CarouselSlider\Supports\FormFields;

use CarouselSlider\Interfaces\FieldInterface;

/**
 * BaseField class
 */
abstract class BaseField implements FieldInterface {
	/**
	 * Field settings
	 *
	 * @var array
	 */
	protected $settings = [];

	/**
	 * The name to be used for input field name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * The value of the field
	 *
	 * @var mixed
	 */
	protected $value;

	/**
	 * Render field
	 *
	 * @return string
	 */
	abstract public function render(): string;

	/**
	 * Get setting
	 *
	 * @param string $key The setting key.
	 * @param mixed  $default The default value for the setting.
	 *
	 * @return mixed
	 */
	public function get_setting( string $key, $default = null ) {
		return $this->settings[ $key ] ?? $default;
	}

	/**
	 * Set setting
	 *
	 * @param string $key The setting key.
	 * @param mixed  $value The value for the setting.
	 */
	public function set_setting( string $key, $value ) {
		$this->settings[ $key ] = $value;
	}

	/**
	 * Get settings
	 *
	 * @return array
	 */
	public function get_settings(): array {
		return $this->settings;
	}

	/**
	 * Set Settings
	 *
	 * @param array $settings The field settings.
	 */
	public function set_settings( array $settings ) {
		$default        = [
			'type'        => 'text',
			'id'          => '',
			'section'     => 'default',
			'label'       => '',
			'description' => '',
			'priority'    => 10,
			'default'     => '',
			'choices'     => [],
			'field_class' => 'sp-input-text',
			'label_class' => '',
		];
		$this->settings = wp_parse_args( $settings, $default );
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function get_name(): string {
		return $this->name;
	}

	/**
	 * Set name
	 *
	 * @param string $name The name of the field.
	 */
	public function set_name( string $name ) {
		$this->name = $name;
	}

	/**
	 * Get value
	 *
	 * @return mixed
	 */
	public function get_value() {
		return $this->value;
	}

	/**
	 * Set value
	 *
	 * @param mixed $value The value of the field.
	 */
	public function set_value( $value ) {
		$this->value = $value;
	}

	/**
	 * Generate input attribute
	 *
	 * @param bool $to_string Convert attribute to string.
	 *
	 * @return array|string
	 */
	protected function build_attributes( bool $to_string = true ) {
		$input_type = $this->get_setting( 'type' );
		$attributes = [
			'id'          => $this->get_setting( 'id' ),
			'class'       => $this->get_setting( 'field_class' ),
			'name'        => $this->get_name(),
			'placeholder' => $this->get_setting( 'placeholder' ),
		];

		if ( ! in_array( $input_type, [ 'textarea', 'select' ], true ) ) {
			$attributes['type'] = $input_type;
		}

		$this->add_extra_attributes( $attributes );

		$input_attributes = (array) $this->get_setting( 'input_attributes' );
		foreach ( $input_attributes as $attr_name => $attr_val ) {
			$attributes[ $attr_name ] = $attr_val;
		}

		if ( $to_string ) {
			return $this->array_to_attributes( $attributes );
		}

		return array_filter( $attributes );
	}

	/**
	 * Convert array to input attributes
	 *
	 * @param array $attributes The attributes list.
	 *
	 * @return string
	 */
	protected function array_to_attributes( array $attributes ): string {
		$string = array_map(
			function ( $key, $value ) {
				if ( empty( $value ) && 'value' !== $key ) {
					return null;
				}
				if ( in_array( $key, array( 'required', 'checked', 'multiple', 'disabled' ), true ) && $value ) {
					return $key;
				}

				// If boolean value.
				if ( is_bool( $value ) ) {
					return sprintf( '%s="%s"', $key, $value ? 'true' : 'false' );
				}

				// If array value.
				if ( is_array( $value ) ) {
					return sprintf( '%s="%s"', $key, implode( ',', $value ) );
				}

				// If string value.
				return sprintf( '%s="%s"', $key, esc_attr( $value ) );

			},
			array_keys( $attributes ),
			array_values( $attributes )
		);

		return implode( ' ', array_filter( $string ) );
	}

	/**
	 * Add extra attributes
	 *
	 * @param array $attributes The attributes list.
	 */
	protected function add_extra_attributes( array &$attributes ) {
		$input_type       = $this->get_setting( 'type' );
		$extra_attributes = [
			[
				'include_types' => [ 'textarea' ],
				'attrs'         => [ 'rows' => $this->get_setting( 'rows' ) ],
			],
			[
				'include_types' => [ 'file' ],
				'attrs'         => [ 'accept' => $this->get_setting( 'accept' ) ],
			],
			[
				'include_types' => [ 'number', 'date' ],
				'attrs'         => [
					'max' => $this->get_setting( 'max' ),
					'min' => $this->get_setting( 'min' ),
				],
			],
			[
				'include_types' => [ 'number' ],
				'attrs'         => [ 'step' => $this->get_setting( 'step' ) ],
			],
			[
				'include_types' => [ 'email', 'file', 'select' ],
				'attrs'         => [ 'multiple' => $this->get_setting( 'multiple' ) ],
			],
			[
				'include_types' => [ 'hidden' ],
				'attrs'         => [
					'spellcheck'   => 'false',
					'tabindex'     => '-1',
					'autocomplete' => 'off',
				],
			],
			[
				'exclude_types' => [ 'textarea', 'file' ],
				'attrs'         => [ 'autocomplete' => $this->get_setting( 'autocomplete' ) ],
			],
			[
				'exclude_types' => [ 'textarea', 'file', 'password', 'select' ],
				'attrs'         => [ 'value' => $this->get_value() ],
			],
			[
				'exclude_types' => [ 'hidden', 'image', 'submit', 'reset', 'button' ],
				'attrs'         => [ 'required' => $this->get_setting( 'required' ) ],
			],
		];

		foreach ( $extra_attributes as $attribute ) {
			if ( isset( $attribute['include_types'] ) && in_array( $input_type, $attribute['include_types'], true ) ) {
				foreach ( $attribute['attrs'] as $attr_key => $attr_val ) {
					$attributes[ $attr_key ] = $attr_val;
				}
			}
			if ( isset( $attribute['exclude_types'] ) && ! in_array( $input_type, $attribute['exclude_types'], true ) ) {
				foreach ( $attribute['attrs'] as $attr_key => $attr_val ) {
					$attributes[ $attr_key ] = $attr_val;
				}
			}
		}
	}
}
