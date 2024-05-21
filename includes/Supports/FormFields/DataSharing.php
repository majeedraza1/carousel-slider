<?php

namespace CarouselSlider\Supports\FormFields;

use CarouselSlider\TrackingData;

/**
 * DataSharing class
 */
class DataSharing extends BaseField {

	/**
	 * Render html content
	 *
	 * @inheritDoc
	 */
	public function render(): string {
		$attributes = array(
			'type'       => 'checkbox',
			'id'         => $this->get_setting( 'id' ),
			'class'      => 'screen-reader-text',
			'name'       => 'carousel_slider_allow_tracking',
			'value'      => 'yes',
			'checked'    => 'yes' === get_option( 'carousel_slider_allow_tracking', 'no' ),
			'data-token' => wp_create_nonce( 'carousel_slider_tracker' ),
		);

		$html = '<div class="switch-container">';
		$html .= '<input type="hidden" name="carousel_slider_allow_tracking" value="no">';
		$html .= '<label for="carousel_slider_allow_tracking" class="switch-label">';
		$html .= '<input ' . $this->array_to_attributes( $attributes ) . '>';
		$html .= '<span class="switch"></span>';
		$html .= '</label>';
		$html .= '<span class="switch-label-text">' . $this->get_setting( 'label' ) . '</span>';
		$html .= '</div>';

		$html .= '<div style="max-width: 600px;max-height:400px;background-color: #ddd;overflow: auto;margin-top:1rem;">';
		$html .= '<pre>';
		$html .= wp_json_encode( TrackingData::all(), \JSON_PRETTY_PRINT );
		$html .= '</pre>';
		$html .= '</div>';

		return $html;
	}
}