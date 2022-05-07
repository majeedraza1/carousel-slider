<?php

namespace CarouselSlider\Supports\FormFields;

/**
 * CheckboxSwitch class
 */
class CheckboxSwitch extends BaseField {

	/**
	 * Render html content
	 *
	 * @inheritDoc
	 */
	public function render(): string {
		$attributes = array(
			'type'    => 'checkbox',
			'id'      => $this->get_setting( 'id' ),
			'class'   => 'screen-reader-text',
			'name'    => $this->get_name(),
			'value'   => 'on',
			'checked' => 'on' === $this->get_value(),
		);

		$html  = '<div class="switch-container">';
		$html .= '<input type="hidden" name="' . $this->get_name() . '" value="off">';
		$html .= '<label for="' . $this->get_setting( 'id' ) . '" class="switch-label">';
		$html .= '<input ' . $this->array_to_attributes( $attributes ) . '>';
		$html .= '<span class="switch">' . $this->get_setting( 'label' ) . '</span>';
		$html .= '</label>';
		$html .= '</div>';

		return $html;
	}
}
