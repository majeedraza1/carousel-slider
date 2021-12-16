<?php

namespace CarouselSlider\Supports;

use CarouselSlider\Helper;
use CarouselSlider\Interfaces\FieldInterface;
use CarouselSlider\Supports\FormFields\BaseField;
use CarouselSlider\Supports\FormFields\ButtonGroup;
use CarouselSlider\Supports\FormFields\Checkbox;
use CarouselSlider\Supports\FormFields\Color;
use CarouselSlider\Supports\FormFields\ImagesGallery;
use CarouselSlider\Supports\FormFields\Select;
use CarouselSlider\Supports\FormFields\Spacing;
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
 * @method void number( array $args )
 * @method void checkbox( array $args )
 */
class MetaBoxForm {

	/**
	 * Map field settings
	 *
	 * @param array $settings The settings arguments.
	 *
	 * @return array
	 */
	public function map_field_settings( array $settings ): array {
		$attrs = [
			'name'    => 'label',
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
	public function get_field_class( string $type = 'text' ) {
		$types = [
			'text'           => Text::class,
			'textarea'       => Textarea::class,
			'spacing'        => Spacing::class,
			'checkbox'       => Checkbox::class,
			'button_group'   => ButtonGroup::class,
			'select'         => Select::class,
			'color'          => Color::class,
			'images_gallery' => ImagesGallery::class,
		];

		$class = array_key_exists( $type, $types ) ? $types[ $type ] : $types['text'];

		return new $class();
	}

	/**
	 * Generate text field
	 *
	 * @param array $args The settings arguments.
	 *
	 * @return string
	 */
	public function field( array $args ): string {
		list( $name, $value ) = $this->get_name_and_value( $args );

		$field = self::get_field_class( $args['type'] ?? 'text' );
		$field->set_settings( $this->map_field_settings( $args ) );
		$field->set_name( $name );
		$field->set_value( $value );

		$html  = $this->field_before( $args );
		$html .= $field->render();
		$html .= $this->field_after( $args );

		return $html;
	}

	/**
	 * Generate select field
	 *
	 * @param array $args The settings arguments.
	 */
	public function select( array $args ) {
		$args['type']        = 'select';
		$args['field_class'] = 'select2 sp-input-text';

		echo $this->field( $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Generate posts list dropdown
	 * Also support for any custom post type
	 *
	 * @param array $args The settings arguments.
	 */
	public function posts_list( array $args ) {
		$posts = get_posts(
			[
				'post_type'      => $args['post_type'] ?? 'post',
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
			]
		);

		$args['type']        = 'select';
		$args['field_class'] = 'select2 sp-input-text';
		$args['choices']     = [];
		foreach ( $posts as $post ) {
			$args['choices'][ $post->ID ] = $post->post_title;
		}

		echo $this->field( $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Upload iFrame field
	 *
	 * @param array $args The settings arguments.
	 */
	public function upload_iframe( array $args ) {
		if ( ! isset( $args['id'], $args['name'] ) ) {
			return;
		}
		list( $name, $value ) = $this->get_name_and_value( $args );
		$class                = isset( $args['class'] ) ? esc_attr( $args['class'] ) : 'sp-input-hidden';
		$button_text          = $value ? __( 'Update Image', 'carousel-slider' ) : __( 'Set Image', 'carousel-slider' );

		global $post;
		$attrs = [
			'class'            => 'button slide_image_add',
			'href'             => esc_url( get_upload_iframe_src( 'image', $post->ID ) ),
			'data-title'       => esc_attr__( 'Select or Upload Slide Background Image', 'carousel-slider' ),
			'data-button-text' => esc_attr( $button_text ),
		];

		$html  = $this->field_before( $args );
		$html .= '<input type="hidden" class="' . $class . '" name="' . $name . '" value="' . $value . '" />';
		$html .= '<a ' . implode( ' ', Helper::array_to_attribute( $attrs ) ) . '>' . esc_html( $button_text ) . '</a>';
		$html .= $this->field_after( $args );
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Generate image gallery list from images URL
	 *
	 * @param array $args The settings arguments.
	 */
	public function images_url( array $args ) {
		if ( ! isset( $args['id'], $args['name'] ) ) {
			return;
		}

		global $post;

		$std_value = $args['std'] ?? '';
		$meta      = get_post_meta( $post->ID, $args['id'], true );
		$value     = ! empty( $meta ) ? $meta : $std_value;

		$btn_text = $value ? __( 'Edit URLs', 'carousel-slider' ) : __( 'Add URLs', 'carousel-slider' );

		$html  = $this->field_before( $args );
		$html .= sprintf( '<a id="_images_urls_btn" class="button" href="#">%s</a>', $btn_text );
		$html .= '<ul class="carousel_slider_url_images_list">';
		if ( is_array( $value ) && count( $value ) > 0 ) {
			foreach ( $value as $image ) {
				$html .= '<li><img src="' . $image['url'] . '" alt="' . $image['alt'] . '" width="75" height="75"></li>';
			}
		}
		$html .= '</ul>';
		$html .= $this->field_after( $args );
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Generate image sizes dropdown from available image sizes
	 *
	 * @param array $args The settings arguments.
	 */
	public function image_sizes( array $args ) {
		$args['type']    = 'select';
		$args['choices'] = Helper::get_available_image_sizes();

		echo $this->field( $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Get post terms drowdown list
	 *
	 * @param array $args The settings arguments.
	 */
	public function post_terms( array $args ) {
		$terms = get_terms( [ 'taxonomy' => $args['taxonomy'] ?? 'category' ] );

		$args['type']    = 'select';
		$args['choices'] = [];
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$args['choices'][ $term->term_id ] = sprintf( '%s (%s)', $term->name, $term->count );
			}
		}

		echo $this->field( $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Generate field name and field value
	 *
	 * @param array $args The settings arguments.
	 *
	 * @return array
	 */
	private function get_name_and_value( array $args ): array {
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
		$default = $args['std'] ?? '';
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
	private function field_before( array $args ): string {
		$_normal  = sprintf( '<div class="sp-input-group" id="field-%s">', $args['id'] );
		$_normal .= '<div class="sp-input-label">';
		$_normal .= sprintf( '<label for="%1$s">%2$s</label>', $args['id'], $args['name'] );
		if ( ! empty( $args['desc'] ) ) {
			$_normal .= sprintf( '<p class="sp-input-desc">%s</p>', $args['desc'] );
		}
		$_normal .= '</div>';
		$_normal .= '<div class="sp-input-field">';

		if ( isset( $args['context'] ) && 'side' === $args['context'] ) {
			$_side  = '<p id="field-' . $args['id'] . '">';
			$_side .= '<label for="' . $args['id'] . '"><strong>' . $args['name'] . '</strong></label>';

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
	private function field_after( array $args = [] ): string {

		if ( isset( $args['context'] ) && 'side' === $args['context'] ) {
			$_side = '';
			if ( ! empty( $args['desc'] ) ) {
				$_side .= '<span class="cs-tooltip" title="' . esc_attr( $args['desc'] ) . '"></span>';
			}
			$_side .= '</p>';

			return $_side;
		}

		return '</div></div>';
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
		echo $this->field( $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
