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

		$html  = '<div class="switch-container">';
		$html .= '<input type="hidden" name="carousel_slider_allow_tracking" value="no">';
		$html .= '<label for="carousel_slider_allow_tracking" class="switch-label">';
		$html .= '<input ' . $this->array_to_attributes( $attributes ) . '>';
		$html .= '<span class="switch"></span>';
		$html .= '</label>';
		$html .= '<span class="switch-label-text">' . $this->get_setting( 'label' ) . '</span>';
		$html .= '</div>';

		$html .= '<div class="admin-data-sharing-container">';
		$html .= '<div class="admin-data-sharing-header">' . esc_html__( 'If data sharing is enabled, the following data will be shared occasionally (normally once a week).', 'carousel-slider' ) . '</div>';
		$html .= '<div class="admin-data-sharing-code">';
		$html .= '<pre>';
		$html .= wp_json_encode( TrackingData::all(), \JSON_PRETTY_PRINT );
		$html .= '</pre>';
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}
}
