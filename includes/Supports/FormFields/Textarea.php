<?php

namespace CarouselSlider\Supports\FormFields;

class Textarea extends BaseField {

	/**
	 * @inheritDoc
	 */
	public function render(): string {
		return '<textarea ' . $this->build_attributes() . '>' . esc_textarea( $this->get_value() ) . '</textarea>';
	}
}
