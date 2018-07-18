<?php

namespace CarouselSlider\Supports;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Metabox {

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
	 * Generate select input field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function select( array $args ) {
		$value = self::get_value( $args );

		$html = self::field_before( $args );
		$html .= '<select ' . self::build_attributes( $args ) . '>';
		foreach ( $args['choices'] as $key => $label ) {
			$option   = trim( $key );
			$selected = ( $value == $option ) ? 'selected' : '';
			$html     .= '<option value="' . $option . '" ' . $selected . '>' . $label . '</option>';
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
		$value = self::get_value( $args );

		$html = self::field_before( $args );
		$html .= '<div class="buttonset">';
		foreach ( $args['choices'] as $key => $option ) {
			$input_id = $id . '_' . $key;
			$checked  = ( $value == $key ) ? ' checked="checked"' : '';
			$html     .= '<input class="switch-input" id="' . $input_id . '" type="radio" value="' . $key . '"
                       name="' . $name . '" ' . $checked . '>';
			$html     .= '<label class="switch-label switch-label-on" for="' . $input_id . '">' . $option . '</label></input>';
		}
		$html .= '</div>';
		$html .= self::field_after( $args );

		return $html;
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
		$input_type       = $args['type'];
		$input_attributes = is_array( $args['input_attributes'] ) ? $args['input_attributes'] : array();
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
			if ( in_array( $attribute, array( 'id', 'name', 'type', 'value', 'multiple', 'required' ) ) ) {
				continue;
			}
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

		$value = get_post_meta( $post->ID, $args['id'], true );

		if ( empty( $value ) && isset( $args['default'] ) ) {
			return $args['default'];
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
		$group = isset( $args['group'] ) ? $args['group'] : false;
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
