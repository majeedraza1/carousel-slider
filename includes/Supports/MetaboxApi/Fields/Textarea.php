<?php

namespace CarouselSlider\Supports\MetaboxApi\Fields;

class Textarea extends BaseField {

	/**
	 * @inheritDoc
	 */
	public function render(): string {
		return '<textarea ' . $this->build_attributes() . '>' . esc_textarea( $this->get_value() ) . '</textarea>';
	}
}
