<?php

namespace CarouselSlider\Supports\MetaboxApi\Fields;

class Select extends BaseField {

	public function render(): string {
		$is_multiple = $this->get_setting( 'multiple' );
		$value       = $this->get_value();
		if ( $is_multiple && is_string( $value ) ) {
			$value = explode( ',', strip_tags( rtrim( $value, ',' ) ) );
		}
		$html = '<select ' . $this->build_attributes() . '>';
		foreach ( $this->get_setting( 'choices' ) as $key => $label ) {
			if ( $is_multiple ) {
				$selected = in_array( $key, $value ) ? 'selected' : '';
			} else {
				$selected = $this->get_value() == $key ? 'selected' : '';
			}
			$html .= '<option value="' . esc_attr( $key ) . '" ' . $selected . '>' . esc_html( $label ) . '</option>';
		}
		$html .= '</select>';

		return $html;
	}
}
