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
		$name = $this->get_name();

		$attributes = array(
			'type'    => 'checkbox',
			'id'      => $this->get_setting( 'id' ),
			'name'    => $name,
			'value'   => 'on',
			'checked' => 'on' === $this->get_value(),
		);

		$html  = '<input type="hidden" name="' . $name . '" value="off">';
		$html .= '<label for="' . $this->get_setting( 'id' ) . '">';
		$html .= '<input ' . $this->array_to_attributes( $attributes ) . '>';
		$html .= '<span>' . $this->get_setting( 'label' ) . '</span></label>';

		return $html;
	}
}
