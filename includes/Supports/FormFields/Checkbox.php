<?php

namespace CarouselSlider\Supports\FormFields;

/**
 * Checkbox class
 */
class Checkbox extends BaseField {

	/**
	 * Render field html
	 *
	 * @inheritDoc
	 */
	public function render(): string {
		$true_value  = $this->get_setting( 'true_value', 'on' );
		$false_value = $this->get_setting( 'false_value', 'off' );

		$attributes = array(
			'type'    => 'checkbox',
			'id'      => $this->get_setting( 'id' ),
			'name'    => $this->get_name(),
			'value'   => $true_value,
			'checked' => $true_value === $this->get_value(),
		);

		$html  = '<input type="hidden" name="' . $this->get_name() . '" value="' . esc_attr( $false_value ) . '">';
		$html .= '<label for="' . $this->get_setting( 'id' ) . '">';
		$html .= '<input ' . $this->array_to_attributes( $attributes ) . '>';
		$html .= '<span>' . $this->get_setting( 'label' ) . '</span></label>';

		return $html;
	}
}
