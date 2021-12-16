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

		return '<textarea ' . $this->build_attributes() . '>' . esc_textarea( $this->get_value() ) . '</textarea>';
	}
}
