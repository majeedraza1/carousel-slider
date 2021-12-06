<?php

namespace CarouselSlider\Supports\FormFields;

class Text extends BaseField {

	/**
	 * @inheritDoc
	 */
	public function render(): string {
		return '<input ' . $this->build_attributes() . ' />';
	}
}
