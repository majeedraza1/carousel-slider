<?php

namespace CarouselSlider\Supports\MetaboxApi\Fields;

class Text extends BaseField {

	/**
	 * @inheritDoc
	 */
	public function render(): string {
		return '<input ' . $this->build_attributes() . ' />';
	}
}
