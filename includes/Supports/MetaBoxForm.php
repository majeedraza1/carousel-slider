<?php

namespace CarouselSlider\Supports;

use CarouselSlider\Helper;
use CarouselSlider\Interfaces\MetaboxFieldInterface;
use CarouselSlider\Supports\MetaboxApi\Fields\BaseField;
use CarouselSlider\Supports\MetaboxApi\Fields\ButtonGroup;
use CarouselSlider\Supports\MetaboxApi\Fields\Checkbox;
use CarouselSlider\Supports\MetaboxApi\Fields\Color;
use CarouselSlider\Supports\MetaboxApi\Fields\Select;
use CarouselSlider\Supports\MetaboxApi\Fields\Spacing;
use CarouselSlider\Supports\MetaboxApi\Fields\Text;
use CarouselSlider\Supports\MetaboxApi\Fields\Textarea;

defined( 'ABSPATH' ) || exit;

class MetaBoxForm {

	/**
	 * Map field settings
	 *
	 * @param array $settings
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
	 * @param string $type
	 *
	 * @return BaseField|MetaboxFieldInterface
	 */
	public function get_field_class( string $type = 'text' ) {
		$types = [
			'text'         => Text::class,
			'textarea'     => Textarea::class,
			'spacing'      => Spacing::class,
			'checkbox'     => Checkbox::class,
			'button_group' => ButtonGroup::class,
			'select'       => Select::class,
			'color'        => Color::class,
		];

		$className = array_key_exists( $type, $types ) ? $types[ $type ] : $types['text'];

		return new $className;
	}

	/**
	 * Generate text field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function field( array $args ): string {
		list( $name, $value ) = $this->get_name_and_value( $args );

		$field = self::get_field_class( $args['type'] ?? 'text' );
		$field->set_settings( $this->map_field_settings( $args ) );
		$field->set_name( $name );
		$field->set_value( $value );

		$html = $this->field_before( $args );
		$html .= $field->render();
		$html .= $this->field_after( $args );

		return $html;
	}

	/**
	 * Generate select field
	 *
	 * @param $args
	 */
	public function select( $args ) {
		$args['type']        = 'select';
		$args['field_class'] = 'select2 sp-input-text';

		echo $this->field( $args );
	}

	/**
	 * Generate posts list dropdown
	 * Also support for any custom post type
	 *
	 * @param $args
	 */
	public function posts_list( $args ) {
		$posts = get_posts( [
			'post_type'      => $args['post_type'] ?? 'post',
			'post_status'    => 'publish',
			'posts_per_page' => - 1
		] );

		$args['type']        = 'select';
		$args['field_class'] = 'select2 sp-input-text';
		$args['choices']     = [];
		foreach ( $posts as $post ) {
			$args['choices'][ $post->ID ] = $post->post_title;
		}

		echo $this->field( $args );
	}

	/**
	 * @param array $args
	 */
	public function upload_iframe( array $args ) {
		if ( ! isset( $args['id'], $args['name'] ) ) {
			return;
		}
		list( $name, $value ) = $this->get_name_and_value( $args );
		$class       = isset( $args['class'] ) ? esc_attr( $args['class'] ) : 'sp-input-hidden';
		$button_text = $value ? __( 'Update Image', 'carousel-slider' ) : __( 'Set Image', 'carousel-slider' );

		global $post;
		$attrs = [
			'class'            => 'button slide_image_add',
			'href'             => esc_url( get_upload_iframe_src( 'image', $post->ID ) ),
			'data-title'       => esc_attr__( 'Select or Upload Slide Background Image', 'carousel-slider' ),
			'data-button-text' => esc_attr( $button_text ),
		];

		$html = $this->field_before( $args );
		$html .= '<input type="hidden" class="' . $class . '" name="' . $name . '" value="' . $value . '" />';
		$html .= '<a ' . implode( ' ', Helper::array_to_attribute( $attrs ) ) . '>' . esc_html( $button_text ) . '</a>';
		$html .= $this->field_after( $args );
		echo $html;
	}

	/**
	 * Generate image gallery field
	 *
	 * @param $args
	 */
	public function images_gallery( $args ) {
		if ( ! isset( $args['id'], $args['name'] ) ) {
			return;
		}
		list( $name, $value ) = $this->get_name_and_value( $args );

		$btn_text = $value ? 'Edit Gallery' : 'Add Gallery';
		$value    = strip_tags( rtrim( $value, ',' ) );
		$output   = '';
		global $post;

		if ( $value ) {
			$thumbs = explode( ',', $value );
			foreach ( $thumbs as $thumb ) {
				$output .= '<li>' . wp_get_attachment_image( $thumb, array( 50, 50 ) ) . '</li>';
			}
		}

		$html = $this->field_before( $args );
		$html .= '<div class="carousel_slider_images">';
		$html .= sprintf( '<input type="hidden" value="%1$s" id="_carousel_slider_images_ids" name="%2$s">', $value, $name );
		$html .= sprintf(
			'<a href="#" id="%1$s" class="button" data-id="%2$s" data-ids="%3$s" data-create="%5$s" data-edit="%6$s" data-save="%7$s" data-progress="%8$s" data-insert="%9$s">%4$s</a>',
			'carousel_slider_gallery_btn',
			$post->ID,
			$value,
			$btn_text,
			esc_html__( 'Create Gallery', 'carousel-slider' ),
			esc_html__( 'Edit Gallery', 'carousel-slider' ),
			esc_html__( 'Save Gallery', 'carousel-slider' ),
			esc_html__( 'Saving...', 'carousel-slider' ),
			esc_html__( 'Insert', 'carousel-slider' )
		);
		$html .= sprintf( '<ul class="carousel_slider_gallery_list">%s</ul>', $output );
		$html .= '</div>';
		$html .= $this->field_after( $args );
		echo $html;
	}

	/**
	 * Generate image gallery list from images URL
	 *
	 * @param array $args
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

		$html = $this->field_before( $args );
		$html .= sprintf( '<a id="_images_urls_btn" class="button" href="#">%s</a>', $btn_text );
		$html .= '<ul class="carousel_slider_url_images_list">';
		if ( is_array( $value ) && count( $value ) > 0 ) {
			foreach ( $value as $image ) {
				$html .= '<li><img src="' . $image['url'] . '" alt="' . $image['alt'] . '" width="75" height="75"></li>';
			}
		}
		$html .= '</ul>';
		$html .= $this->field_after( $args );
		echo $html;
	}

	/**
	 * Generate image sizes dropdown from available image sizes
	 *
	 * @param array $args
	 */
	public function image_sizes( array $args ) {
		$args['type']    = 'select';
		$args['choices'] = Helper::get_available_image_sizes();

		echo $this->field( $args );
	}

	/**
	 * Get post terms drowdown list
	 *
	 * @param array $args
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

		echo $this->field( $args );
	}

	/**
	 * Generate field name and field value
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	private function get_name_and_value( array $args ): array {
		global $post;
		$input_attributes = $args['input_attributes'] ?? [];
		// Meta Name
		if ( isset( $input_attributes['name'] ) ) {
			$name = $input_attributes['name'];
		} else {
			$group    = $args['group'] ?? 'carousel_slider';
			$multiple = isset( $args['multiple'] ) ? '[]' : '';
			$name     = sprintf( '%s[%s]%s', $group, $args['id'], $multiple );
		}

		// Meta Value
		$default = $args['std'] ?? '';
		if ( isset( $input_attributes['value'] ) ) {
			$value = ! empty( $input_attributes['value'] ) ? $input_attributes['value'] : $default;
		} else {
			$meta  = get_post_meta( $post->ID, $args['id'], true );
			$value = ! empty( $meta ) ? $meta : $default;
		}

		if ( $value == 'zero' ) {
			$value = 0;
		}

		return [ $name, $value ];
	}

	/**
	 * Generate field before template
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	private function field_before( array $args ): string {
		$_normal = sprintf( '<div class="sp-input-group" id="field-%s">', $args['id'] );
		$_normal .= '<div class="sp-input-label">';
		$_normal .= sprintf( '<label for="%1$s">%2$s</label>', $args['id'], $args['name'] );
		if ( ! empty( $args['desc'] ) ) {
			$_normal .= sprintf( '<p class="sp-input-desc">%s</p>', $args['desc'] );
		}
		$_normal .= '</div>';
		$_normal .= '<div class="sp-input-field">';

		if ( isset( $args['context'] ) && 'side' == $args['context'] ) {
			$_side = '<p id="field-' . $args['id'] . '">';
			$_side .= '<label for="' . $args['id'] . '"><strong>' . $args['name'] . '</strong></label>';

			return $_side;
		}

		return $_normal;
	}

	/**
	 * Generate field after template
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	private function field_after( array $args = [] ): string {

		if ( isset( $args['context'] ) && 'side' == $args['context'] ) {
			$_side = '';
			if ( ! empty( $args['desc'] ) ) {
				$_side .= '<span class="cs-tooltip" title="' . esc_attr( $args['desc'] ) . '"></span>';
			}
			$_side .= '</p>';

			return $_side;
		}

		return '</div></div>';
	}

	public function __call( $name, $arguments ) {
		$args = array_merge( ( is_array( $arguments[0] ) ? $arguments[0] : [] ), [ 'type' => $name ] );
		echo $this->field( $args );
	}
}
