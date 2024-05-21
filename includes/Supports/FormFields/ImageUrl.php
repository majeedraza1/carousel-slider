<?php

namespace CarouselSlider\Supports\FormFields;

/**
 * ImageUrl class
 */
class ImageUrl extends BaseField {

	/**
	 * Render html content
	 *
	 * @inheritDoc
	 */
	public function render(): string {
		$value    = $this->get_value();
		$btn_text = $value ? __( 'Edit URLs', 'carousel-slider' ) : __( 'Add URLs', 'carousel-slider' );

		$html  = sprintf( '<a id="_images_urls_btn" class="button" href="#">%s</a>', $btn_text );
		$html .= '<ul class="carousel_slider_url_images_list">';
		if ( is_array( $value ) && count( $value ) > 0 ) {
			foreach ( $value as $image ) {
				$html .= '<li><img src="' . esc_url( $image['url'] ) . '" alt="' . esc_attr( $image['alt'] ) . '" width="75" height="75"></li>';
			}
		}
		$html .= '</ul>';

		return $html;
	}
}
