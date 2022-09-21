<?php

namespace CarouselSlider\Supports;

use CarouselSlider\Helper;
use CarouselSlider\Interfaces\FieldInterface;
use CarouselSlider\Supports\FormFields\BaseField;
use CarouselSlider\Supports\FormFields\Breakpoint;
use CarouselSlider\Supports\FormFields\ButtonGroup;
use CarouselSlider\Supports\FormFields\Checkbox;
use CarouselSlider\Supports\FormFields\CheckboxSwitch;
use CarouselSlider\Supports\FormFields\Color;
use CarouselSlider\Supports\FormFields\ResponsiveControl;
use CarouselSlider\Supports\FormFields\ImagesGallery;
use CarouselSlider\Supports\FormFields\ImageUploader;
use CarouselSlider\Supports\FormFields\ImageUrl;
use CarouselSlider\Supports\FormFields\Radio;
use CarouselSlider\Supports\FormFields\SelectImageSize;
use CarouselSlider\Supports\FormFields\SelectPosts;
use CarouselSlider\Supports\FormFields\Select;
use CarouselSlider\Supports\FormFields\Spacing;
use CarouselSlider\Supports\FormFields\SelectTerms;
use CarouselSlider\Supports\FormFields\Text;
use CarouselSlider\Supports\FormFields\Textarea;

defined( 'ABSPATH' ) || exit;

/**
 * MetaBoxForm class
 *
 * @method void text( array $args )
 * @method void date( array $args )
 * @method void textarea( array $args )
 * @method void spacing( array $args )
 * @method void button_group( array $args )
 * @method void color( array $args )
 * @method void images_gallery( array $args )
 * @method void upload_iframe( array $args )
 * @method void images_url( array $args )
 * @method void posts_list( array $args )
 * @method void number( array $args )
 * @method void checkbox( array $args )
 * @method void post_terms( array $args )
 * @method void image_sizes( array $args )
 * @method void radio( array $args )
 * @method void switch ( array $args )
 * @method void select( array $args )
 * @method void breakpoint( array $args )
 */
class MetaBoxForm {

	/**
	 * Map field settings
	 *
	 * @param array $settings The settings arguments.
	 *
	 * @return array
	 */
	private static function map_field_settings( array $settings ): array {
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

	/**
	 * Get field class
	 *
	 * @param string $type The field type.
	 *
	 * @return BaseField|FieldInterface
	 */
	private static function get_field_class( string $type = 'text' ) {
		$types = [
			'text'               => Text::class,
			'textarea'           => Textarea::class,
			'spacing'            => Spacing::class,
			'checkbox'           => Checkbox::class,
			'button_group'       => ButtonGroup::class,
			'color'              => Color::class,
			'images_gallery'     => ImagesGallery::class,
			'upload_iframe'      => ImageUploader::class,
			'images_url'         => ImageUrl::class,
			'select'             => Select::class,
			'posts_list'         => SelectPosts::class,
			'post_terms'         => SelectTerms::class,
			'image_sizes'        => SelectImageSize::class,
			'radio'              => Radio::class,
			'switch'             => CheckboxSwitch::class,
			'breakpoint'         => Breakpoint::class,
			'responsive_control' => ResponsiveControl::class,
		];

		$class = array_key_exists( $type, $types ) ? $types[ $type ] : $types['text'];

		return new $class();
	}

	/**
	 * Generate field name and field value
	 *
	 * @param array $args The settings arguments.
	 *
	 * @return array
	 */
	private static function get_name_and_value( array $args ): array {
		global $post;
		$input_attributes = $args['input_attributes'] ?? [];
		// Meta Name.
		if ( isset( $input_attributes['name'] ) ) {
			$name = $input_attributes['name'];
		} else {
			$group    = $args['group'] ?? 'carousel_slider';
			$multiple = isset( $args['multiple'] ) ? '[]' : '';
			$name     = sprintf( '%s[%s]%s', $group, $args['id'], $multiple );
		}

		// Meta Value.
		$default = $args['default'] ?? '';
		if ( isset( $input_attributes['value'] ) ) {
			$value = ! empty( $input_attributes['value'] ) ? $input_attributes['value'] : $default;
		} else {
			$meta  = get_post_meta( $post->ID, $args['id'], true );
			$value = ! empty( $meta ) ? $meta : $default;
		}

		if ( 'zero' === $value ) {
			$value = 0;
		}

		return [ $name, $value ];
	}

	/**
	 * Generate field before template
	 *
	 * @param array $args The settings arguments.
	 *
	 * @return string
	 */
	private static function field_before( array $args ): string {
		$_normal  = sprintf( '<div class="sp-input-group" id="field-%s">', $args['id'] );
		$_normal .= '<div class="sp-input-label">';
		$_normal .= sprintf( '<label for="%1$s">%2$s</label>', $args['id'], $args['label'] ?? '' );
		if ( ! empty( $args['description'] ) ) {
			$_normal .= sprintf( '<p class="sp-input-desc">%s</p>', $args['description'] );
		}
		$_normal .= '</div>';
		$_normal .= '<div class="sp-input-field">';

		if ( isset( $args['context'] ) && 'side' === $args['context'] ) {
			$_side  = '<div id="field-' . $args['id'] . '" class="cs-flex cs-flex-wrap cs-justify-between cs-my-4">';
			$_side .= '<span class="cs-inline-flex cs-space-x-1">';
			$_side .= '<label for="' . $args['id'] . '"><strong>' . $args['label'] . '</strong></label>';
			if ( ! empty( $args['description'] ) ) {
				$_side .= '<span class="cs-tooltip" title="' . esc_attr( $args['description'] ) . '"></span>';
			}
			$_side .= '</span>';

			return $_side;
		}

		return $_normal;
	}

	/**
	 * Generate field after template
	 *
	 * @param array $args The settings arguments.
	 *
	 * @return string
	 */
	private static function field_after( array $args = [] ): string {

		if ( isset( $args['context'] ) && 'side' === $args['context'] ) {
			return '</div>';
		}

		return '</div></div>';
	}

	/**
	 * Generate text field
	 *
	 * @param array $args The settings arguments.
	 *
	 * @return string
	 */
	public static function field( array $args ): string {
		$is_pro_only = isset( $args['pro_only'] ) && $args['pro_only'];
		if ( $is_pro_only && Helper::show_pro_features() === false ) {
			return '';
		}
		$settings = self::map_field_settings( $args );

		list( $name, $value ) = self::get_name_and_value( $settings );

		$field = self::get_field_class( $args['type'] ?? 'text' );
		$field->set_settings( $settings );
		$field->set_name( $name );
		$field->set_value( $value );

		$html  = self::field_before( $settings );
		$html .= $field->render();
		$html .= self::field_after( $settings );

		return $html;
	}

	/**
	 * Handle wildcard method call
	 *
	 * @param string $name The method name.
	 * @param array  $arguments The arguments for the method.
	 *
	 * @return void
	 */
	public function __call( string $name, array $arguments = [] ) {
		$args = array_merge( ( is_array( $arguments[0] ) ? $arguments[0] : [] ), [ 'type' => $name ] );
		Helper::print_unescaped_internal_string( self::field( $args ) );
	}
}
