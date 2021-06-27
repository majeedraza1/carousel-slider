<?php

namespace CarouselSlider\Supports;

use CarouselSlider\Helper;

defined( 'ABSPATH' ) || exit;

class MetaBoxForm {

	/**
	 * Generate text field
	 *
	 * @param array $args
	 */
	public function text( array $args ) {
		if ( ! isset( $args['id'], $args['name'] ) ) {
			return;
		}

		list( $name, $value ) = $this->get_name_and_value( $args );
		$class = isset( $args['class'] ) ? esc_attr( $args['class'] ) : 'sp-input-text';

		echo $this->field_before( $args );
		echo sprintf( '<input type="text" class="' . $class . '" value="%1$s" id="%2$s" name="%3$s">', $value, $args['id'], $name );
		echo $this->field_after( $args );
	}

	/**
	 * Generate textarea field
	 *
	 * @param array $args
	 */
	public function textarea( array $args ) {
		if ( ! isset( $args['id'], $args['name'] ) ) {
			return;
		}

		list( $name, $value ) = $this->get_name_and_value( $args );
		$cols = $args['cols'] ?? 35;
		$rows = $args['rows'] ?? 6;

		echo $this->field_before( $args );
		echo sprintf( '<textarea class="sp-input-textarea" id="%2$s" name="%3$s" cols="%4$d" rows="%5$d">%1$s</textarea>', esc_textarea( $value ), $args['id'], $name, $cols, $rows );
		echo $this->field_after( $args );
	}

	/**
	 * Generate color picker field
	 *
	 * @param array $args
	 */
	public function color( array $args ) {
		if ( ! isset( $args['id'], $args['name'] ) ) {
			return;
		}

		list( $name, $value ) = $this->get_name_and_value( $args );
		$std_value = $args['std'] ?? '';

		echo $this->field_before( $args );
		echo sprintf( '<input type="text" class="color-picker" value="%1$s" id="%2$s" name="%3$s" data-alpha="true" data-default-color="%4$s">', $value, $args['id'], $name, $std_value );
		echo $this->field_after( $args );
	}

	/**
	 * Generate date picker field
	 *
	 * @param array $args
	 */
	public function date( array $args ) {
		if ( ! isset( $args['id'], $args['name'] ) ) {
			return;
		}

		list( $name, $value ) = $this->get_name_and_value( $args );

		echo $this->field_before( $args );
		echo sprintf( '<input type="date" class="sp-input-text" value="%1$s" id="%2$s" name="%3$s">', $value, $args['id'], $name );
		echo $this->field_after( $args );
	}

	/**
	 * Generate number field
	 *
	 * @param array $args
	 */
	public function number( array $args ) {
		if ( ! isset( $args['id'], $args['name'] ) ) {
			return;
		}

		list( $name, $value ) = $this->get_name_and_value( $args );
		$class = isset( $args['class'] ) ? esc_attr( $args['class'] ) : 'sp-input-text';

		echo $this->field_before( $args );
		echo sprintf( '<input type="number" class="' . $class . '" value="%1$s" id="%2$s" name="%3$s">', $value, $args['id'], $name );
		echo $this->field_after( $args );
	}

	/**
	 * Generate checkbox field
	 *
	 * @param array $args
	 */
	public function checkbox( array $args ) {
		if ( ! isset( $args['id'], $args['name'] ) ) {
			return;
		}

		list( $name, $value ) = $this->get_name_and_value( $args );
		$checked = ( $value == 'on' ) ? ' checked' : '';
		$label   = $args['label'] ?? '';

		echo $this->field_before( $args );
		echo sprintf( '<input type="hidden" name="%1$s" value="off">', $name );
		echo sprintf( '<label for="%2$s"><input type="checkbox" ' . $checked . ' value="on" id="%2$s" name="%1$s">%3$s</label>', $name, $args['id'], $label );
		echo $this->field_after( $args );
	}

	/**
	 * Generate select field
	 *
	 * @param $args
	 */
	public function select( $args ) {
		if ( ! isset( $args['id'], $args['name'] ) ) {
			return;
		}

		list( $name, $value ) = $this->get_name_and_value( $args );
		$multiple = isset( $args['multiple'] ) ? 'multiple' : '';
		$class    = isset( $args['class'] ) ? esc_attr( $args['class'] ) : 'select2 sp-input-text';

		echo $this->field_before( $args );
		echo sprintf( '<select name="%1$s" id="%2$s" class="' . $class . '" %3$s>', $name, $args['id'], $multiple );
		foreach ( $args['options'] as $key => $option ) {
			$selected = ( $value == $key ) ? ' selected="selected"' : '';
			echo '<option value="' . $key . '" ' . $selected . '>' . $option . '</option>';
		}
		echo '</select>';
		echo $this->field_after( $args );
	}

	/**
	 * Generate posts list dropdown
	 * Also support for any custom post type
	 *
	 * @param $args
	 */
	public function posts_list( $args ) {
		if ( ! isset( $args['id'], $args['name'] ) ) {
			return;
		}

		list( $name, $value ) = $this->get_name_and_value( $args );
		$value     = explode( ',', $value );
		$multiple  = isset( $args['multiple'] ) ? 'multiple' : '';
		$post_type = $args['post_type'] ?? 'post';

		echo $this->field_before( $args );
		echo '<select name="' . $name . '" id="' . $args['id'] . '" class="select2 sp-input-text" ' . $multiple . '>';
		$posts = get_posts( array(
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'posts_per_page' => - 1
		) );

		foreach ( $posts as $post ) {
			$selected = in_array( $post->ID, $value ) ? ' selected="selected"' : '';
			echo '<option value="' . $post->ID . '" ' . $selected . '>' . $post->post_title . '</option>';
		}
		echo '</select>';
		echo $this->field_after( $args );
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

	public function file( array $args ) {
		if ( ! isset( $args['id'], $args['name'] ) ) {
			return;
		}

		list( $name, $value ) = $this->get_name_and_value( $args );

		echo $this->field_before( $args );
		echo sprintf( '<input type="text" class="sp-input-text" value="%1$s" id="%2$s" name="%3$s">', $value, $args['id'], $name );
		echo sprintf( '<input type="button" class="button" id="carousel_slider_video_btn" value="%s">', __( 'Browse', 'carousel-slider' ) );
		echo $this->field_after( $args );
	}

	/**
	 * Generate image sizes dropdown from available image sizes
	 *
	 * @param array $args
	 */
	public function image_sizes( array $args ) {
		if ( ! isset( $args['id'], $args['name'] ) ) {
			return;
		}

		list( $name, $value ) = $this->get_name_and_value( $args );

		global $_wp_additional_image_sizes;

		$sizes = array();

		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {

				$width  = get_option( "{$_size}_size_w" );
				$height = get_option( "{$_size}_size_h" );
				$crop   = (bool) get_option( "{$_size}_crop" ) ? 'hard' : 'soft';

				$sizes[ $_size ] = "{$_size} - $crop:{$width}x{$height}";

			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

				$width  = $_wp_additional_image_sizes[ $_size ]['width'];
				$height = $_wp_additional_image_sizes[ $_size ]['height'];
				$crop   = $_wp_additional_image_sizes[ $_size ]['crop'] ? 'hard' : 'soft';

				$sizes[ $_size ] = "{$_size} - $crop:{$width}x{$height}";
			}
		}

		$sizes = array_merge( $sizes, array( 'full' => 'original uploaded image' ) );


		echo $this->field_before( $args );
		echo '<select name="' . $name . '" id="' . $args['id'] . '" class="select2 sp-input-text">';
		foreach ( $sizes as $key => $option ) {
			$selected = ( $value == $key ) ? ' selected="selected"' : '';
			echo '<option value="' . $key . '" ' . $selected . '>' . $option . '</option>';
		}
		echo '</select>';
		echo $this->field_after( $args );
	}

	/**
	 * Get post terms drowdown list
	 *
	 * @param array $args
	 */
	public function post_terms( array $args ) {
		global $wp_version;

		if ( ! isset( $args['id'], $args['name'] ) ) {
			return;
		}
		list( $name, $value ) = $this->get_name_and_value( $args );

		$value    = explode( ',', strip_tags( rtrim( $value, ',' ) ) );
		$multiple = isset( $args['multiple'] ) ? 'multiple' : '';
		$taxonomy = $args['taxonomy'] ?? 'category';

		if ( version_compare( $wp_version, '4.5.0', '>=' ) ) {
			$terms = get_terms( array( 'taxonomy' => $taxonomy ) );
		} else {
			$terms = get_terms( $taxonomy );
		}

		if ( is_wp_error( $terms ) ) {
			$terms = array();
		}

		echo $this->field_before( $args );

		echo '<select name="' . $name . '" id="' . $args['id'] . '" class="select2 sp-input-text" ' . $multiple . '>';
		foreach ( $terms as $term ) {
			$title    = sprintf( '%s (%s)', $term->name, $term->count );
			$selected = in_array( $term->term_id, $value ) ? ' selected="selected"' : '';
			echo '<option value="' . $term->term_id . '" ' . $selected . '>' . $title . '</option>';
		}
		echo '</select>';

		echo $this->field_after( $args );
	}

	/**
	 * Generate spacing input field
	 *
	 * @param array $args
	 */
	public function spacing( array $args ) {
		list( $id, $name ) = self::get_name_and_id( $args );
		$value   = self::get_value( $args );
		$default = $args['default'] ?? [];

		$html = self::field_before( $args );
		$html .= '<div class="sp-field-spacing flex">';

		// Top
		if ( isset( $default['top'] ) ) {
			$top_value = $value['top'] ?? $default['top'];
			$html      .= '<div class="carousel-slider-dimension">';
			$html      .= '<span class="add-on"><i class="dashicons dashicons-arrow-up-alt"></i></span>';
			$html      .= '<input type="text" name="' . $name . "[top]" . '" value="' . esc_attr( $top_value ) . '">';
			$html      .= '</div>';
		}

		// Right
		if ( isset( $default['right'] ) ) {
			$right_value = isset( $value['right'] ) ? esc_attr( $value['right'] ) : $default['right'];
			$html        .= '<div class="carousel-slider-dimension">';
			$html        .= '<span class="add-on"><i class="dashicons dashicons-arrow-right-alt"></i></span>';
			$html        .= '<input type="text" name="' . $name . "[right]" . '" value="' . $right_value . '">';
			$html        .= '</div>';
		}
		// Bottom
		if ( isset( $default['bottom'] ) ) {
			$bottom_value = isset( $value['bottom'] ) ? esc_attr( $value['bottom'] ) : $default['bottom'];
			$html         .= '<div class="carousel-slider-dimension">';
			$html         .= '<span class="add-on"><i class="dashicons dashicons-arrow-down-alt"></i></span>';
			$html         .= '<input type="text" name="' . $name . "[bottom]" . '" value="' . $bottom_value . '">';
			$html         .= '</div>';
		}
		// Bottom
		if ( isset( $default['left'] ) ) {
			$left_value = isset( $value['left'] ) ? esc_attr( $value['left'] ) : $default['left'];
			$html       .= '<div class="carousel-slider-dimension">';
			$html       .= '<span class="add-on"><i class="dashicons dashicons-arrow-left-alt"></i></span>';
			$html       .= '<input type="text" name="' . $name . "[left]" . '" value="' . $left_value . '">';
			$html       .= '</div>';
		}

		$html .= '</div>';
		$html .= self::field_after( $args );

		echo $html;
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

	/**
	 * Get meta value
	 *
	 * @param array $args
	 *
	 * @return mixed
	 */
	private static function get_value( array $args ) {
		global $post;

		$default = $args['default'] ?? '';
		$meta    = get_post_meta( $post->ID, $args['id'], true );
		$value   = ! empty( $meta ) ? $meta : $default;

		if ( isset( $args['meta_key'] ) ) {
			$meta  = get_post_meta( $post->ID, $args['meta_key'], true );
			$value = ! empty( $meta[ $args['id'] ] ) ? $meta[ $args['id'] ] : $default;

			if ( isset( $args['index'] ) ) {
				$value = ! empty( $meta[ $args['index'] ][ $args['id'] ] ) ? $meta[ $args['index'] ][ $args['id'] ] : $default;
			}
		}

		if ( $value == 'zero' ) {
			$value = 0;
		}

		return $value;
	}

	/**
	 * Get input attribute name
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	private static function get_name_and_id( array $args ): array {
		$group = $args['group'] ?? 'carousel_slider';
		$index = $args['index'] ?? false;
		$id    = $args['id'];
		$name  = $id;

		if ( $group ) {
			if ( false !== $index ) {
				$name = $group . '[' . $index . ']' . '[' . $name . ']';
				$id   = $group . '_' . $index . '_' . $id;
			} else {
				$name = $group . '[' . $name . ']';
				$id   = $group . '_' . $id;
			}
		}

		if ( self::is_multiple( $args ) ) {
			$name = $name . '[]';
		}

		return [ $id, $name ];
	}

	/**
	 * Check if input support multiple value
	 *
	 * @param array $args
	 *
	 * @return bool
	 */
	private static function is_multiple( array $args ): bool {
		if ( isset( $args['multiple'] ) && $args['multiple'] ) {
			return true;
		}

		if ( isset( $args['input_attributes']['multiple'] ) && $args['input_attributes']['multiple'] ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if input is required
	 *
	 * @param array $args
	 *
	 * @return bool
	 */
	private static function is_required( array $args ): bool {
		if ( isset( $args['required'] ) && $args['required'] ) {
			return true;
		}

		if ( isset( $args['input_attributes']['required'] ) && $args['input_attributes']['required'] ) {
			return true;
		}

		return false;
	}
}
