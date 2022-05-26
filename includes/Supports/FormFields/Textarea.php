<?php

namespace CarouselSlider\Supports\FormFields;

/**
 * Textarea class
 */
class Textarea extends BaseField {

	/**
	 * Render field html
	 *
	 * @inheritDoc
	 */
	public function render(): string {
		$this->set_setting( 'type', 'textarea' );
		$input_class = $this->get_setting( 'field_class' );
		$this->set_setting( 'field_class', str_replace( 'sp-input-text', 'sp-input-textarea', $input_class ) );

		return '<textarea ' . $this->build_attributes() . '>' . esc_textarea( $this->get_value() ) . '</textarea>';
	}
}
