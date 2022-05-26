<?php

namespace CarouselSlider\Supports\FormFields;

use CarouselSlider\Helper;

/**
 * ImageUploader class
 */
class ImageUploader extends BaseField {

	/**
	 * Render html content
	 *
	 * @inheritDoc
	 */
	public function render(): string {
		$value       = $this->get_value();
		$button_text = $value ? __( 'Update Image', 'carousel-slider' ) : __( 'Set Image', 'carousel-slider' );
		global $post;
		$attrs = [
			'class'            => 'button slide_image_add',
			'href'             => esc_url( get_upload_iframe_src( 'image', $post->ID ) ),
			'data-title'       => esc_attr__( 'Select or Upload Slide Background Image', 'carousel-slider' ),
			'data-button-text' => esc_attr( $button_text ),
		];

		$input_attrs = [
			'type'  => 'hidden',
			'class' => $this->get_setting( 'field_class' ),
			'name'  => $this->get_name(),
			'value' => $value,
		];

		$html  = '<input ' . implode( ' ', Helper::array_to_attribute( $input_attrs ) ) . ' />';
		$html .= '<a ' . implode( ' ', Helper::array_to_attribute( $attrs ) ) . '>' . esc_html( $button_text ) . '</a>';

		return $html;
	}
}
