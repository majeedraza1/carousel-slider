<?php

namespace CarouselSlider\Supports\SettingApi;

use CarouselSlider\Interfaces\FieldInterface;
use CarouselSlider\Interfaces\FormBuilderInterface;
use CarouselSlider\Supports\FormFields\BaseField;
use CarouselSlider\Supports\FormFields\Breakpoint;
use CarouselSlider\Supports\FormFields\Checkbox;
use CarouselSlider\Supports\FormFields\Color;
use CarouselSlider\Supports\FormFields\Html;
use CarouselSlider\Supports\FormFields\Radio;
use CarouselSlider\Supports\FormFields\Select;
use CarouselSlider\Supports\FormFields\SelectImageSize;
use CarouselSlider\Supports\FormFields\Text;
use CarouselSlider\Supports\FormFields\Textarea;

// If this file is called directly, abort.
defined( 'ABSPATH' ) || die;

/**
 * FormBuilder class
 */
class FormBuilder implements FormBuilderInterface {

	/**
	 * The option name
	 *
	 * @var string|null
	 */
	protected $option_name = null;

	/**
	 * The fields settings
	 *
	 * @var array
	 */
	protected $fields_settings = [];

	/**
	 * The values of the fields
	 *
	 * @var array
	 */
	protected $values = [];

	/**
	 * Set field settings
	 *
	 * @param array $settings The settings arguments.
	 *
	 * @return void
	 */
	public function set_fields_settings( array $settings ) {
		$this->fields_settings = $settings;
	}

	/**
	 * Set option name
	 *
	 * @param string $option_name The option name.
	 *
	 * @return void
	 */
	public function set_option_name( string $option_name ) {
		$this->option_name = $option_name;
	}

	/**
	 * Set fields values
	 *
	 * @param array $values The values.
	 *
	 * @return void
	 */
	public function set_values( array $values ) {
		$this->values = $values;
	}

	/**
	 * Render settings html
	 *
	 * @return string
	 */
	public function render(): string {
		$table = "<table class='form-table'>";

		foreach ( $this->fields_settings as $field ) {
			$type        = $field['type'] ?? 'text';
			$field_class = self::get_field_class( $type );
			if ( ! $field_class instanceof FieldInterface ) {
				continue;
			}
			$name  = sprintf( '%s[%s]', $this->option_name, $field['id'] );
			$value = $this->values[ $field['id'] ] ?? '';

			$table .= '<tr>';
			if ( ! empty( $field['title'] ) ) {
				$table .= sprintf( '<th scope="row"><label for="%1$s">%2$s</label></th>', $field['id'], $field['title'] );
			}
			$table .= '<td>';

			$field_class->set_settings( $this->map_field_settings( $field ) );
			$field_class->set_name( $name );
			$field_class->set_value( $value );
			$table .= $field_class->render();

			if ( ! empty( $field['description'] ) ) {
				$desc  = is_array( $field['description'] ) ?
					implode( '<br>', $field['description'] ) :
					$field['description'];
				$table .= sprintf( '<p class="description">%s</p>', $desc );
			}
			$table .= '</td>';
			$table .= '</tr>';
		}

		$table .= '</table>';

		return $table;
	}

	/**
	 * Settings fields
	 *
	 * @param array $fields The fields settings.
	 * @param string $option_name The option name.
	 * @param array $values The values.
	 *
	 * @return string
	 */
	public function get_fields_html( array $fields, string $option_name, array $values = [] ): string {
		$this->set_fields_settings( $fields );
		$this->set_option_name( $option_name );
		$this->set_values( $values );

		return $this->render();
	}

	/**
	 * Get field class
	 *
	 * @param string $type The field type.
	 *
	 * @return BaseField|FieldInterface|null
	 */
	public function get_field_class( string $type = 'text' ) {
		$types = apply_filters(
			'carousel_slider/settings/available_fields',
			[
				'text'        => Text::class,
				'textarea'    => Textarea::class,
				'color'       => Color::class,
				'radio'       => Radio::class,
				'checkbox'    => Checkbox::class,
				'select'      => Select::class,
				'image_sizes' => SelectImageSize::class,
				'html'        => Html::class,
				'breakpoint'  => Breakpoint::class,
			]
		);

		if ( array_key_exists( $type, $types ) ) {
			return new $types[ $type ]();
		}

		return null;
	}

	/**
	 * Map field settings.
	 *
	 * @param array $settings The settings.
	 *
	 * @return array
	 */
	private function map_field_settings( array $settings ): array {
		$attrs = [
			'name'    => 'label',
			'title'   => 'label',
			'desc'    => 'description',
			'class'   => 'field_class',
			'options' => 'choices',
			'std'     => 'default',
		];
		foreach ( $settings as $key => $value ) {
			if ( isset( $attrs[ $key ] ) ) {
				$settings[ $attrs[ $key ] ] = $value;
				unset( $settings[ $key ] );
			}
		}

		return $settings;
	}
}
