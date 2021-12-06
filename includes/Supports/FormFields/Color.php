<?php

namespace CarouselSlider\Supports\FormFields;

class Color extends BaseField {

	/**
	 * @inheritDoc
	 */
	public function render(): string {
		$input_attributes = (array) $this->get_setting( 'input_attributes' );
		$this->set_setting( 'type', 'text' );
		$this->set_setting( 'field_class', 'color-picker' );
		$this->set_setting( 'input_attributes', array_merge( $input_attributes, [
			'data-alpha-enabled' => 'true',
			'data-default-color' => $this->get_setting( 'default' ),
		] ) );

		return '<br><input ' . $this->build_attributes() . ' />';
	}
}
