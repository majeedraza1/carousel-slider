<?php

namespace CarouselSlider\Modules\ImageCarousel;

use CarouselSlider\Abstracts\AbstractView;
use CarouselSlider\Abstracts\SliderSetting;
use CarouselSlider\Supports\Validate;
use CarouselSlider\TemplateParserBase;

defined( 'ABSPATH' ) || exit;

/**
 * UrlView class
 *
 * @package Modules/ImageCarousel
 */
class UrlView extends AbstractView {

	/**
	 * Get slider setting
	 *
	 * @return SliderSetting
	 */
	public function get_slider_setting(): SliderSetting {
		if ( ! $this->slider_setting instanceof SliderSetting ) {
			$this->slider_setting = new Setting( $this->get_slider_id() );
		}

		return $this->slider_setting;
	}

	/**
	 * Render html content
	 *
	 * @inheritDoc
	 */
	public function render(): string {
		$setting     = $this->get_slider_setting();
		$slider_id   = $this->get_slider_id();
		$images_urls = (array) get_post_meta( $slider_id, '_images_urls', true );
		if ( count( $images_urls ) < 1 ) {
			return '';
		}
		$template = new TemplateParserBase( $setting );
		$template->set_template( 'loop/image-carousel-url.php' );

		$html = $this->start_wrapper_html();
		foreach ( $images_urls as $images_url ) {
			$item = new ExternalImageItem( $images_url );
			$template->set_object( $item );

			$html .= $this->start_item_wrapper_html();
			$html .= apply_filters( 'carousel_slider/loop/image-carousel-url', $template->render(), $item, $this->get_slider_setting() );
			$html .= $this->end_item_wrapper_html();
		}
		$html .= $this->end_wrapper_html();

		return apply_filters( 'carousel_slider_link_images_carousel', $html, $slider_id );
	}
}
