<?php

namespace CarouselSlider\Supports;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Form {

	/**
	 * @var self
	 */
	private static $instance;

	/**
	 * Settings panels array
	 * @var array
	 */
	private $panels = array();

	/**
	 * Settings sections array
	 * @var array
	 */
	private $sections = array();

	/**
	 * Settings fields array
	 * @var array
	 */
	private $fields = array();

	/**
	 * Default field attributes
	 *
	 * @var array
	 */
	private static $default_attributes = array(
		'type'              => 'text',
		'id'                => '',
		'label'             => '',
		'description'       => '',
		'priority'          => 10,
		'section'           => '',
		'default'           => '',
		'choices'           => array(),
		'input_attributes'  => array(),
		'sanitize_callback' => '',
		'validate_callback' => ''
	);

	/**
	 * @var array
	 */
	private static $text_input_type = array(
		'text',
		'email',
		'password',
		'number',
		'url',
		'tel',
		'date',
		'time',
		'hidden',
		'search',
	);

	/**
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get metabox panels sorted by priority
	 *
	 * @return array
	 */
	public function get_panels() {
		$panels = $this->panels;
		// Sort by priority
		usort( $panels, array( $this, 'sort_by_priority' ) );

		return $panels;
	}

	/**
	 * Set metabox panels
	 *
	 * @param array $panels
	 */
	public function set_panels( $panels ) {
		foreach ( $panels as $panel ) {
			$this->set_panel( $panel );
		}
	}

	/**
	 * Get metabox sections sorted by priority
	 *
	 * @return array
	 */
	public function get_sections() {
		$sections = $this->sections;
		// Sort by priority
		usort( $sections, array( $this, 'sort_by_priority' ) );

		return $sections;
	}

	/**
	 * Set metabox sections
	 *
	 * @param array $sections
	 */
	public function set_sections( $sections ) {
		foreach ( $sections as $section ) {
			$this->set_section( $section );
		}
	}

	/**
	 * Get metabox fields sorted by priority
	 *
	 * @return array
	 */
	public function get_fields() {
		$fields = $this->fields;
		// Sort by priority
		usort( $fields, array( $this, 'sort_by_priority' ) );

		return $fields;
	}

	/**
	 * Set metabox fields
	 *
	 * @param array $fields
	 */
	public function set_fields( $fields ) {
		foreach ( $fields as $field ) {
			$this->set_field( $field );
		}
	}

	/**
	 * Set metabox panel
	 *
	 * @param array $args
	 */
	public function set_panel( $args = array() ) {
		$default = array( 'id' => '', 'title' => '', 'description' => '', 'priority' => 10, );

		if ( isset( $args['id'], $args['title'] ) ) {
			$this->panels[] = wp_parse_args( $args, $default );
		}
	}

	/**
	 * Set metabox section
	 *
	 * @param array $args
	 */
	public function set_section( $args = array() ) {
		$default = array( 'id' => '', 'title' => '', 'description' => '', 'priority' => 10, 'panel' => '', );

		if ( isset( $args['id'], $args['title'] ) ) {
			$this->sections[] = wp_parse_args( $args, $default );
		}
	}

	/**
	 * Set metabox field
	 *
	 * @param array $args
	 */
	public function set_field( $args = array() ) {
		if ( isset( $args['id'], $args['label'] ) ) {
			$this->fields[] = wp_parse_args( $args, self::$default_attributes );
		}
	}

	/**
	 * Sort array by it its priority value
	 *
	 * @param array $array1
	 * @param array $array2
	 *
	 * @return mixed
	 */
	private static function sort_by_priority( $array1, $array2 ) {
		return $array1['priority'] - $array2['priority'];
	}

	/**
	 * Build form field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function field( array $args ) {
		if ( method_exists( __CLASS__, $args['type'] ) ) {
			return call_user_func( array( __CLASS__, $args['type'] ), $args );
		}

		if ( in_array( $args['type'], self::$text_input_type ) ) {
			return self::text( $args );
		}

		return '';
	}

	/**
	 * Generate text based input field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function text( array $args ) {
		$html = self::field_before( $args );
		$html .= '<input ' . self::build_attributes( $args ) . '>';
		$html .= self::field_after( $args );

		return $html;
	}

	/**
	 * Generate color picker input field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function color( array $args ) {
		$args['input_attributes'] = array(
			'class'              => 'color-picker',
			'data-alpha'         => true,
			'data-default-color' => isset( $args['default'] ) ? $args['default'] : '',
		);

		$html         = self::field_before( $args );
		$args['type'] = 'text';
		$html         .= '<input ' . self::build_attributes( $args ) . '>';
		$args['type'] = 'color';
		$html         .= self::field_after( $args );

		return $html;
	}

	/**
	 * Generate date input field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function date( array $args ) {
		$value = self::get_value( $args );
		if ( ! empty( $value ) ) {
			$value = date( 'Y-m-d', strtotime( $value ) );

			$args['input_attributes']['value'] = $value;
		}

		$args['input_attributes']['pattern'] = "[0-9]{4}-[0-9]{2}-[0-9]{2}";

		return self::text( $args );
	}

	/**
	 * Generate textarea field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function textarea( array $args ) {
		$args['type'] = 'textarea';
		$value        = self::get_value( $args );

		$html = self::field_before( $args );
		$html .= '<textarea ' . self::build_attributes( $args ) . '>' . esc_textarea( $value ) . '</textarea>';
		$html .= self::field_after( $args );

		return $html;
	}

	/**
	 * Generate select input field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function select( array $args ) {
		$args['type'] = 'select';
		$value        = self::get_value( $args );

		$html = self::field_before( $args );
		$html .= '<select ' . self::build_attributes( $args ) . '>';
		foreach ( $args['choices'] as $key => $label ) {
			$option = trim( $key );
			if ( self::is_multiple( $args ) && is_array( $value ) ) {
				$selected = in_array( $option, $value ) ? 'selected' : '';
			} else {
				$selected = ( $value == $option ) ? 'selected' : '';
			}
			$html .= '<option value="' . $option . '" ' . $selected . '>' . $label . '</option>';
		}
		$html .= '</select>';
		$html .= self::field_after( $args );

		return $html;
	}

	/**
	 * Generate post terms input field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function post_terms( array $args ) {
		global $wp_version;
		$taxonomy = isset( $args['taxonomy'] ) ? $args['taxonomy'] : 'category';

		if ( version_compare( $wp_version, '4.5.0', '>=' ) ) {
			$_terms = get_terms( array( 'taxonomy' => $taxonomy ) );
		} else {
			$_terms = get_terms( $taxonomy );
		}

		$args['choices'] = array();
		if ( ! is_wp_error( $_terms ) ) {
			foreach ( $_terms as $term ) {
				$args['choices'][ $term->term_id ] = sprintf( '%s (%s)', $term->name, $term->count );
			}
		}

		$value = self::get_value( $args );
		if ( is_string( $value ) ) {
			$value = explode( ',', strip_tags( rtrim( $value, ',' ) ) );
		}

		$html = self::field_before( $args );
		$html .= '<select ' . self::build_attributes( $args ) . '>';
		foreach ( $args['choices'] as $key => $label ) {
			$option   = trim( $key );
			$selected = ( $value == $option ) ? 'selected' : '';
			if ( self::is_multiple( $args ) ) {
				$selected = in_array( $option, $value ) ? 'selected' : '';
			}
			$html .= '<option value="' . $option . '" ' . $selected . '>' . $label . '</option>';
		}
		$html .= '</select>';
		$html .= self::field_after( $args );

		return $html;
	}

	/**
	 * Generate posts list input field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function posts_list( array $args ) {
		$post_type = isset( $args['post_type'] ) ? $args['post_type'] : 'post';
		$posts     = get_posts( array(
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'posts_per_page' => 500
		) );

		$args['choices'] = array();
		foreach ( $posts as $post ) {
			$args['choices'][ $post->ID ] = $post->post_title;
		}

		$value = self::get_value( $args );
		if ( is_string( $value ) ) {
			$value = explode( ',', strip_tags( rtrim( $value, ',' ) ) );
		}

		$html = self::field_before( $args );
		$html .= '<select ' . self::build_attributes( $args ) . '>';
		foreach ( $args['choices'] as $key => $label ) {
			$option   = trim( $key );
			$selected = ( $value == $option ) ? 'selected' : '';
			if ( self::is_multiple( $args ) ) {
				$selected = in_array( $option, $value ) ? 'selected' : '';
			}
			$html .= '<option value="' . $option . '" ' . $selected . '>' . $label . '</option>';
		}
		$html .= '</select>';
		$html .= self::field_after( $args );

		return $html;
	}

	/**
	 * Generate buttonset input field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function buttonset( array $args ) {
		list( $id, $name ) = self::get_name_and_id( $args );
		$value       = self::get_value( $args );
		$attributes  = isset( $args['input_attributes'] ) ? $args['input_attributes'] : array();
		$input_class = empty( $attributes['class'] ) ? 'switch-input' : 'switch-input ' . $attributes['class'];

		$html = self::field_before( $args );
		$html .= '<div class="buttonset">';
		foreach ( $args['choices'] as $key => $option ) {
			$input_id    = $id . '_' . $key;
			$checked     = ( $value == $key ) ? ' checked="checked"' : '';
			$label_class = ( $value == $key ) ? 'switch-label switch-label-on' : 'switch-label switch-label-off';

			$html .= '<input class="' . $input_class . '" id="' . $input_id . '" type="radio" value="' . $key . '"
                       name="' . $name . '" ' . $checked . '>';
			$html .= '<label class="' . $label_class . '" for="' . $input_id . '">' . $option . '</label></input>';
		}
		$html .= '</div>';
		$html .= self::field_after( $args );

		return $html;
	}

	/**
	 * Generate toggle input field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function toggle( array $args ) {
		list( $id, $name ) = self::get_name_and_id( $args );
		$value = self::get_value( $args );

		$input_attribute = array(
			'type'    => 'checkbox',
			'id'      => $id,
			'name'    => $name,
			'class'   => 'screen-reader-text',
			'value'   => 'on',
			'checked' => 'on' == $value ? true : false
		);

		$hidden_attributes = array(
			'type'         => 'hidden',
			'name'         => $name,
			'spellcheck'   => false,
			'tabindex'     => '-1',
			'autocomplete' => 'off',
			'value'        => 'off',
		);


		$html = self::field_before( $args );
		$html .= '<div class="carousel-slider-toggle">';
		$html .= '<input ' . self::array_to_attributes( $hidden_attributes ) . '>';
		$html .= '<label for="' . $id . '">';
		$html .= '<input ' . self::array_to_attributes( $input_attribute ) . '>';
		$html .= '<span class="switch"></span>';
		$html .= '</label>';
		$html .= '</div>';
		$html .= self::field_after( $args );

		return $html;
	}

	/**
	 * Generate image sizes input field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function image_sizes( array $args ) {
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

		$args['choices'] = array_merge( $sizes, array( 'full' => 'original uploaded image' ) );

		return self::select( $args );
	}

	/**
	 * Generate image gallery input field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function image_gallery( array $args ) {
		global $post;

		list( $id, $name ) = self::get_name_and_id( $args );
		$value       = self::get_value( $args );
		$value       = strip_tags( trim( $value, ',' ) );
		$button_text = esc_html__( 'Add Gallery', 'carousel-slider' );
		if ( ! empty( $value ) ) {
			$button_text = esc_html__( 'Edit Gallery', 'carousel-slider' );
		}
		$input_attributes  = array(
			'type'  => 'hidden',
			'value' => $value,
			'id'    => '_carousel_slider_images_ids',
			'name'  => $name,
		);
		$button_attributes = array(
			'href'          => '#',
			'class'         => 'button',
			'id'            => 'carousel_slider_gallery_btn',
			'data-id'       => $post->ID,
			'data-target'   => $id,
			'data-ids'      => $value,
			'data-create'   => esc_attr__( 'Create Gallery', 'carousel-slider' ),
			'data-edit'     => esc_attr__( 'Edit Gallery', 'carousel-slider' ),
			'data-save'     => esc_attr__( 'Save Gallery', 'carousel-slider' ),
			'data-progress' => esc_attr__( 'Saving...', 'carousel-slider' ),
			'data-insert'   => esc_attr__( 'Insert', 'carousel-slider' ),
		);

		$html = self::field_before( $args );
		$html .= '<div class="carousel_slider_images">';
		$html .= '<input ' . self::array_to_attributes( $input_attributes ) . '>';
		$html .= '<a ' . self::array_to_attributes( $button_attributes ) . '>' . $button_text . '</a>';
		$html .= '<ul class="carousel_slider_gallery_list">';
		if ( $value ) {
			$thumbs = explode( ',', $value );
			foreach ( $thumbs as $thumb ) {
				$html .= '<li>' . wp_get_attachment_image( $thumb, array( 50, 50 ) ) . '</li>';
			}
		}
		$html .= '</ul>';
		$html .= '</div>';
		$html .= self::field_after( $args );

		return $html;
	}

	/**
	 * Generate image urls input field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function images_url( array $args ) {
		$value    = self::get_value( $args );
		$btn_text = $value ? __( 'Edit URLs', 'carousel-slider' ) : __( 'Add URLs', 'carousel-slider' );

		$html = self::field_before( $args );
		$html .= '<a id="_images_urls_btn" class="button" href="#">' . $btn_text . '</a>';
		$html .= '<ul class="carousel_slider_url_images_list">';
		if ( is_array( $value ) && count( $value ) > 0 ) {
			foreach ( $value as $image ) {
				$html .= '<li><img src="' . $image['url'] . '" alt="' . $image['alt'] . '" width="75" height="75"></li>';
			}
		}
		$html .= '</ul>';
		$html .= self::field_after( $args );

		return $html;
	}


	/**
	 * Generate spacing input field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function spacing( array $args ) {
		list( $id, $name ) = self::get_name_and_id( $args );
		$value = self::get_value( $args );

		$default = isset( $args['default'] ) ? $args['default'] : array();

		$html = self::field_before( $args );

		// Top
		if ( isset( $default['top'] ) ) {
			$top_name  = $name . "[top]";
			$top_value = isset( $value['top'] ) ? esc_attr( $value['top'] ) : $default['top'];
			$html      .= '<div class="carousel-slider-dimension">';
			$html      .= '<span class="add-on"><i class="dashicons dashicons-arrow-up-alt"></i></span>';
			$html      .= '<input type="text" name="' . $top_name . '" value="' . $top_value . '">';
			$html      .= '</div>';
		}

		// Right
		if ( isset( $default['right'] ) ) {
			$right_name  = $name . "[right]";
			$right_value = isset( $value['right'] ) ? esc_attr( $value['right'] ) : $default['right'];
			$html        .= '<div class="carousel-slider-dimension">';
			$html        .= '<span class="add-on"><i class="dashicons dashicons-arrow-right-alt"></i></span>';
			$html        .= '<input type="text" name="' . $right_name . '" value="' . $right_value . '">';
			$html        .= '</div>';
		}
		// Bottom
		if ( isset( $default['bottom'] ) ) {
			$bottom_name  = $name . "[bottom]";
			$bottom_value = isset( $value['bottom'] ) ? esc_attr( $value['bottom'] ) : $default['bottom'];
			$html         .= '<div class="carousel-slider-dimension">';
			$html         .= '<span class="add-on"><i class="dashicons dashicons-arrow-down-alt"></i></span>';
			$html         .= '<input type="text" name="' . $bottom_name . '" value="' . $bottom_value . '">';
			$html         .= '</div>';
		}
		// Bottom
		if ( isset( $default['left'] ) ) {
			$left_name  = $name . "[left]";
			$left_value = isset( $value['left'] ) ? esc_attr( $value['left'] ) : $default['left'];
			$html       .= '<div class="carousel-slider-dimension">';
			$html       .= '<span class="add-on"><i class="dashicons dashicons-arrow-left-alt"></i></span>';
			$html       .= '<input type="text" name="' . $left_name . '" value="' . $left_value . '">';
			$html       .= '</div>';
		}

		$html .= self::field_after( $args );

		return $html;
	}

	/**
	 * Generate slider input field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function slider( array $args ) {
		$default = isset( $args['default'] ) ? $args['default'] : 0;
		$value   = intval( self::get_value( $args ) );

		$args['type'] = 'range';

		$args['input_attributes']['data-reset_value'] = $default;
		if ( ! isset( $args['input_attributes']['min'] ) ) {
			$args['input_attributes']['min'] = 0;
		}
		if ( ! isset( $args['input_attributes']['max'] ) ) {
			$args['input_attributes']['max'] = 100;
		}

		$number_attributes = array(
			'type'  => 'number',
			'class' => 'value',
			'value' => $value,
			'min'   => $args['input_attributes']['min'],
			'max'   => $args['input_attributes']['max'],
		);

		$html = self::field_before( $args );
		$html .= '<div class="carousel-slider-range-wrapper">';
		$html .= '<input ' . self::build_attributes( $args ) . '>';
		$html .= '<div class="range-value">';
		$html .= '<input ' . self::array_to_attributes( $number_attributes ) . '>';
		if ( ! empty( $args['choices']['suffix'] ) ) {
			$html .= esc_attr( $args['choices']['suffix'] );
		}
		$html .= '</div>';
		$html .= '<div class="carousel-slider-range-reset" title="' . esc_attr__( 'Reset to default value', 'carousel-slider' ) . '">';
		$html .= '<span class="dashicons dashicons-image-rotate"></span>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= self::field_after( $args );

		return $html;
	}

	/**
	 * Generate range input field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function range( array $args ) {
		return self::slider( $args );
	}

	/**
	 * Generate input attribute
	 *
	 * @param array $args
	 * @param bool $echo
	 *
	 * @return array|string
	 */
	private static function build_attributes( array $args, $echo = true ) {
		$input_type       = isset( $args['type'] ) ? $args['type'] : 'text';
		$input_attributes = isset( $args['input_attributes'] ) ? $args['input_attributes'] : array();
		list( $id, $name ) = self::get_name_and_id( $args );

		$attributes = array( 'id' => $id, 'name' => $name, );

		if ( ! in_array( $input_type, array( 'textarea', 'select' ) ) ) {
			$attributes['type'] = $input_type;
		}

		if ( ! in_array( $input_type, array( 'textarea', 'file', 'password', 'select' ) ) ) {
			$attributes['value'] = self::get_value( $args );
		}

		if ( 'email' === $input_type || 'file' === $input_type ) {
			$attributes['multiple'] = self::is_multiple( $args );
		}

		if ( 'hidden' === $input_type ) {
			$attributes['spellcheck']   = false;
			$attributes['tabindex']     = '-1';
			$attributes['autocomplete'] = 'off';
		}

		if ( ! in_array( $input_type, array( 'hidden', 'image', 'submit', 'reset', 'button' ) ) ) {
			$attributes['required'] = self::is_required( $args );
		}

		foreach ( $input_attributes as $attribute => $value ) {
			$attributes[ $attribute ] = $value;
		}

		if ( $echo ) {
			return self::array_to_attributes( $attributes );
		}

		return array_filter( $attributes );
	}

	/**
	 * Convert array to input attributes
	 *
	 * @param array $attributes
	 *
	 * @return string
	 */
	private static function array_to_attributes( $attributes ) {
		$string = array_map( function ( $key, $value ) {
			if ( empty( $value ) && 'value' !== $key ) {
				return null;
			}
			if ( in_array( $key, array( 'required', 'checked', 'multiple' ) ) && $value ) {
				return $key;
			}

			// If boolean value
			if ( is_bool( $value ) ) {
				return sprintf( '%s="%s"', $key, $value ? 'true' : 'false' );
			}

			// If array value
			if ( is_array( $value ) ) {
				return sprintf( '%s="%s"', $key, implode( " ", $value ) );
			}

			// If string value
			return sprintf( '%s="%s"', $key, esc_attr( $value ) );

		}, array_keys( $attributes ), array_values( $attributes ) );

		return implode( ' ', array_filter( $string ) );
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

		$default = isset( $args['default'] ) ? $args['default'] : '';
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
	 * @return mixed|string
	 */
	private static function get_name_and_id( array $args ) {
		$group = isset( $args['group'] ) ? $args['group'] : 'carousel_slider';
		$index = isset( $args['index'] ) ? $args['index'] : false;
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

		return array( $id, $name );
	}

	/**
	 * Check if input support multiple value
	 *
	 * @param array $args
	 *
	 * @return bool
	 */
	private static function is_multiple( $args ) {
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
	private static function is_required( $args ) {
		if ( isset( $args['required'] ) && $args['required'] ) {
			return true;
		}

		if ( isset( $args['input_attributes']['required'] ) && $args['input_attributes']['required'] ) {
			return true;
		}

		return false;
	}

	/**
	 * Generate field before template
	 *
	 * @param $args
	 *
	 * @return string
	 */
	private static function field_before( array $args ) {
		list( $input_id, $name ) = self::get_name_and_id( $args );

		$_normal = '<div class="sp-input-group" id="field-' . $input_id . '">';

		$_normal .= '<div class="sp-input-label">';
		$_normal .= '<label for="' . $input_id . '">' . $args['label'] . '</label>';
		if ( ! empty( $args['description'] ) ) {
			$_normal .= '<p class="sp-input-desc">' . $args['description'] . '</p>';
		}
		$_normal .= '</div>';

		$_normal .= '<div class="sp-input-field">';

		if ( isset( $args['context'] ) && 'side' == $args['context'] ) {
			$_side = '<p id="field-' . $input_id . '">';
			$_side .= '<label for="' . $input_id . '"><strong>' . $args['label'] . '</strong></label>';

			if ( isset( $args['type'] ) && 'color' == $args['type'] ) {
				$_side .= '<span class="cs-tooltip" title="' . esc_attr( $args['description'] ) . '"></span><br>';
			}

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
	private static function field_after( $args = array() ) {

		if ( isset( $args['context'] ) && 'side' == $args['context'] ) {
			$_side = '';
			if ( ! empty( $args['description'] ) ) {
				$_side .= '<span class="cs-tooltip" title="' . esc_attr( $args['description'] ) . '"></span>';
			}
			// For Color reset tooltip
			if ( isset( $args['type'] ) && 'color' == $args['type'] ) {
				$_side = '';
			}
			$_side .= '</p>';

			return $_side;
		}

		return '</div></div>';
	}
}
