<?php

namespace CarouselSlider\Supports\SettingApi;

use CarouselSlider\Interfaces\FieldInterface;
use CarouselSlider\Interfaces\FormBuilderInterface;
use CarouselSlider\Supports\FormFields\BaseField;
use CarouselSlider\Supports\FormFields\Breakpoint;
use CarouselSlider\Supports\FormFields\ButtonGroup;
use CarouselSlider\Supports\FormFields\Checkbox;
use CarouselSlider\Supports\FormFields\CheckboxSwitch;
use CarouselSlider\Supports\FormFields\Color;
use CarouselSlider\Supports\FormFields\ImagesGallery;
use CarouselSlider\Supports\FormFields\ImageUploader;
use CarouselSlider\Supports\FormFields\ImageUrl;
use CarouselSlider\Supports\FormFields\MultiCheckbox;
use CarouselSlider\Supports\FormFields\Radio;
use CarouselSlider\Supports\FormFields\Select;
use CarouselSlider\Supports\FormFields\SelectImageSize;
use CarouselSlider\Supports\FormFields\SelectPosts;
use CarouselSlider\Supports\FormFields\SelectTerms;
use CarouselSlider\Supports\FormFields\Spacing;
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
				$desc   = is_array( $field['description'] ) ?
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
	 * @param array  $fields The fields settings.
	 * @param string $option_name The option name.
	 * @param array  $values The values.
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
				'text'           => Text::class,
				'textarea'       => Textarea::class,
				'spacing'        => Spacing::class,
				'checkbox'       => Checkbox::class,
				'multi_checkbox' => MultiCheckbox::class,
				'button_group'   => ButtonGroup::class,
				'color'          => Color::class,
				'images_gallery' => ImagesGallery::class,
				'upload_iframe'  => ImageUploader::class,
				'images_url'     => ImageUrl::class,
				'select'         => Select::class,
				'posts_list'     => SelectPosts::class,
				'post_terms'     => SelectTerms::class,
				'image_sizes'    => SelectImageSize::class,
				'radio'          => Radio::class,
				'switch'         => CheckboxSwitch::class,
				'breakpoint'     => Breakpoint::class,
			]
		);

		if ( array_key_exists( $type, $types ) ) {
			return new $types[ $type ]();
		}

		return new $types['text']();
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
