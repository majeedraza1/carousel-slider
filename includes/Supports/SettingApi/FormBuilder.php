<?php

namespace CarouselSlider\Supports\SettingApi;

use CarouselSlider\Interfaces\FormBuilderInterface;
use CarouselSlider\Supports\Validate;

// If this file is called directly, abort.
defined( 'ABSPATH' ) || die;

class FormBuilder implements FormBuilderInterface {

	protected $option_name     = null;
	protected $fields_settings = [];
	protected $values          = [];

	public function set_fields_settings( array $settings ) {
		$this->fields_settings = $settings;
	}

	public function set_option_name( string $option_name ) {
		$this->option_name = $option_name;
	}

	public function set_values( array $values ) {
		$this->values = $values;
	}

	public function render(): string {
		$table = "<table class='form-table'>";

		foreach ( $this->fields_settings as $field ) {
			$type  = $field['type'] ?? 'text';
			$name  = sprintf( '%s[%s]', $this->option_name, $field['id'] );
			$value = $this->values[ $field['id'] ] ?? '';

			$table .= '<tr>';
			if ( ! empty( $field['title'] ) ) {
				$table .= sprintf( '<th scope="row"><label for="%1$s">%2$s</label></th>', $field['id'], $field['title'] );
			}
			$table .= '<td>';

			if ( method_exists( $this, $type ) ) {
				$table .= $this->$type( $field, $name, $value );
			} else {
				$table .= $this->text( $field, $name, $value );
			}

			if ( ! empty( $field['description'] ) ) {
				$desc   = is_array( $field['description'] ) ? implode( '<br>', $field['description'] ) : $field['description'];
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
	 * @param array  $fields
	 * @param string $option_name
	 * @param array  $values
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
	 * text input field
	 *
	 * @param array  $field
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @return string
	 */
	public function text( array $field, string $name, $value ): string {
		$types = [ 'email', 'number', 'url', 'date', 'time' ];
		$type  = in_array( $field['type'], $types ) ? $field['type'] : 'text';

		return sprintf(
			'<input class="regular-text" value="%1$s" id="%2$s" name="%3$s" type="%4$s">',
			esc_attr( $value ),
			esc_attr( $field['id'] ),
			esc_attr( $name ),
			esc_attr( $type )
		);
	}

	/**
	 * password input field
	 *
	 * @param array  $field
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @return string
	 */
	public function password( array $field, string $name, $value ): string {
		return sprintf(
			'<input type="password" class="regular-text" value="" id="%1$s" name="%2$s">',
			$field['id'],
			$name
		);
	}

	/**
	 * color input field
	 *
	 * @param array  $field
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @return string
	 */
	public function color( array $field, string $name, $value ): string {
		$default_color = $field['default'] ?? '';

		return sprintf(
			'<input type="text" class="color-picker" value="%1$s" id="%2$s" name="%3$s" data-alpha="true" data-default-color="%4$s">',
			$value,
			$field['id'],
			$name,
			$default_color
		);
	}

	/**
	 * textarea input field
	 *
	 * @param array  $field
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @return string
	 */
	public function textarea( array $field, string $name, $value ): string {
		$rows        = ( isset( $field['rows'] ) ) ? $field['rows'] : 5;
		$cols        = ( isset( $field['cols'] ) ) ? $field['cols'] : 40;
		$placeholder = ( isset( $field['placeholder'] ) ) ? sprintf(
			'placeholder="%s"',
			esc_attr( $field['placeholder'] )
		) : '';

		return sprintf(
			"<textarea id='%s' name='%s' rows='%s' cols='%s' " . $placeholder . '>' . esc_textarea( $value ) . '</textarea>',
			esc_attr( $field['id'] ),
			esc_attr( $name ),
			esc_attr( $rows ),
			esc_attr( $cols )
		);
	}

	/**
	 * checkbox input field
	 *
	 * @param array  $field
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @return string
	 */
	public function checkbox( array $field, string $name, $value ): string {
		$true_value  = isset( $field['true-value'] ) ? esc_attr( $field['true-value'] ) : '1';
		$false_value = isset( $field['false-value'] ) ? esc_attr( $field['false-value'] ) : '0';

		$checked = Validate::checked( $value ) ? 'checked' : '';
		$table   = '<input type="hidden" name="' . $name . '" value="' . $false_value . '">';
		$table  .= '<fieldset><legend class="screen-reader-text"><span>' . $field['title'] . '</span></legend>';
		$table  .= '<label for="' . $field['id'] . '">';
		$table  .= '<input type="checkbox" value="' . $true_value . '" id="' . $field['id'] . '" name="' . $name . '" ' . $checked . '>';
		$table  .= $field['title'] . '</label></fieldset>';

		return $table;
	}

	/**
	 * multi checkbox input field
	 *
	 * @param array  $field
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @return string
	 */
	public function multi_checkbox( array $field, string $name, $value ): string {
		$table = '<fieldset>';
		$name  = $name . '[]';

		$table .= sprintf( '<input type="hidden" name="%1$s" value="0">', $name );
		foreach ( $field['options'] as $key => $label ) {
			$checked = ( in_array( $key, $value ) ) ? 'checked="checked"' : '';
			$table  .= '<label for="' . $key . '"><input type="checkbox" value="' . $key . '" id="' . $key . '" name="' . $name . '" ' . $checked . '>' . $label . '</label><br>';
		}
		$table .= '</fieldset>';

		return $table;
	}

	/**
	 * radio input field
	 *
	 * @param array  $field
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @return string
	 */
	public function radio( array $field, string $name, $value ): string {
		$table = '<fieldset><legend class="screen-reader-text"><span>' . $field['title'] . '</span></legend><p>';

		foreach ( $field['options'] as $key => $label ) {

			$checked = ( $value == $key ) ? 'checked="checked"' : '';
			$table  .= '<label><input type="radio" ' . $checked . ' value="' . $key . '" name="' . $name . '">' . $label . '</label><br>';
		}
		$table .= '</p></fieldset>';

		return $table;
	}

	/**
	 * select input field
	 *
	 * @param array  $field
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @return string
	 */
	public function select( array $field, string $name, $value ): string {
		$table = sprintf( '<select id="%1$s" name="%2$s" class="regular-text">', $field['id'], $name );
		foreach ( $field['options'] as $key => $label ) {
			$selected = ( $value == $key ) ? 'selected="selected"' : '';
			$table   .= '<option value="' . $key . '" ' . $selected . '>' . $label . '</option>';
		}
		$table .= '</select>';

		return $table;
	}

	/**
	 * Get available image sizes
	 *
	 * @param array  $field
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @return string
	 */
	public function image_sizes( array $field, string $name, $value ): string {

		global $_wp_additional_image_sizes;

		$sizes = [];

		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, [ 'thumbnail', 'medium', 'medium_large', 'large' ] ) ) {

				$width  = get_option( "{$_size}_size_w" );
				$height = get_option( "{$_size}_size_h" );
				$crop   = get_option( "{$_size}_crop" ) ? 'hard' : 'soft';

				$sizes[ $_size ] = "{$_size} - {$width}x{$height} ($crop crop)";

			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

				$width  = $_wp_additional_image_sizes[ $_size ]['width'];
				$height = $_wp_additional_image_sizes[ $_size ]['height'];
				$crop   = $_wp_additional_image_sizes[ $_size ]['crop'] ? 'hard' : 'soft';

				$sizes[ $_size ] = "{$_size} - {$width}x{$height} ($crop crop)";
			}
		}

		$sizes = array_merge( $sizes, array( 'full' => 'original uploaded image' ) );

		$table = '<select name="' . $name . '" id="' . $field['id'] . '" class="regular-text select2">';
		foreach ( $sizes as $key => $option ) {
			$selected = ( $value == $key ) ? ' selected="selected"' : '';
			$table   .= '<option value="' . $key . '" ' . $selected . '>' . $option . '</option>';
		}
		$table .= '</select>';

		return $table;
	}

	/**
	 * wp_editor input field
	 *
	 * @param array  $field
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @return string
	 */
	public function wp_editor( array $field, string $name, $value ): string {
		ob_start();
		echo "<div class='sp-wp-editor-container'>";
		wp_editor(
			$value,
			$field['id'],
			array(
				'textarea_name' => $name,
				'tinymce'       => false,
				'media_buttons' => false,
				'textarea_rows' => $field['rows'] ?? 6,
				'quicktags'     => array( 'buttons' => 'strong,em,link,img,ul,li,ol' ),
			)
		);
		echo '</div>';

		return ob_get_clean();
	}

	/**
	 * Get html field
	 *
	 * @param array  $field
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @return mixed
	 */
	public function html( array $field, string $name, $value ): string {
		if ( isset( $field['html'] ) && is_string( $field['html'] ) ) {
			return $field['html'];
		}

		return '';
	}
}
