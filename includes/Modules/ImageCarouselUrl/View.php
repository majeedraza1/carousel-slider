<?php

namespace CarouselSlider\Modules\ImageCarouselUrl;

use CarouselSlider\Modules\ImageCarousel\View as ImageCarouselView;
use CarouselSlider\Supports\Utils;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class View extends ImageCarouselView {

	/**
	 * Render element.
	 * Generates the final HTML on the frontend.
	 */
	public function render() {
		$_images_urls = $this->images_urls();
		$images_count = count( $_images_urls );

		$this->set_total_slides( $images_count );

		$_html = $this->slider_wrapper_start();

		foreach ( $_images_urls as $imageInfo ) {

			$html = '<div class="carousel-slider__item">';

			$image        = $this->get_image( $imageInfo );
			$full_caption = $this->get_attachment_caption( $imageInfo );
			$content      = $image . $full_caption;

			if ( Utils::is_url( $imageInfo['link_url'] ) ) {
				$html .= '<a href="' . $imageInfo['link_url'] . '" target="' . $this->image_target() . '">' . $content . '</a>';
			} else {
				$html .= $content;
			}

			$html .= '</div>';

			$_html .= apply_filters( 'carousel_slider/view/image_url', $html, $imageInfo );
		}

		$_html .= $this->slider_wrapper_end();

		return $_html;
	}

	/**
	 * @param array $imageInfo
	 *
	 * @return string
	 */
	protected function get_attachment_caption( $imageInfo ) {
		$title   = sprintf( '<h4 class="title">%1$s</h4>', $imageInfo['title'] );
		$caption = sprintf( '<p class="caption">%1$s</p>', $imageInfo['caption'] );

		if ( $this->show_attachment_title() && $this->show_attachment_caption() ) {
			return '<div class="carousel-slider__caption">' . $title . $caption . '</div>';
		}

		if ( $this->show_attachment_title() ) {
			return '<div class="carousel-slider__caption">' . $title . '</div>';
		}

		if ( $this->show_attachment_caption() ) {
			return '<div class="carousel-slider__caption">' . $caption . '</div>';
		}

		return '';
	}

	/**
	 * @param array $imageInfo
	 *
	 * @return string
	 */
	protected function get_image( $imageInfo ) {
		if ( $this->lazy_load_image() ) {
			return '<img class="owl-lazy" data-src="' . $imageInfo['url'] . '" alt="' . $imageInfo['alt'] . '" />';
		}

		return '<img src="' . $imageInfo['url'] . '" alt="' . $imageInfo['alt'] . '" />';
	}

	/**
	 * Get image urls
	 *
	 * @return array
	 */
	protected function images_urls() {
		return $this->get_meta( '_images_urls' );
	}
}
