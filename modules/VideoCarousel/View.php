<?php

namespace CarouselSlider\Modules\VideoCarousel;

use CarouselSlider\Abstracts\AbstractView;
use CarouselSlider\Modules\VideoCarousel\Helper as VideoCarouselHelper;
use CarouselSlider\Supports\Validate;

defined( 'ABSPATH' ) || exit;

/**
 * View class
 *
 * @package Modules/VideoCarousel
 */
class View extends AbstractView {
	/**
	 * Render html view
	 *
	 * @inheritDoc
	 */
	public function render(): string {
		$slider_id       = $this->get_slider_id();
		$urls            = get_post_meta( $slider_id, '_video_url', true );
		$lazy_load_image = get_post_meta( $slider_id, '_lazy_load_image', true );
		if ( is_string( $urls ) ) {
			$urls = array_filter( explode( ',', $urls ) );
		}
		$urls = VideoCarouselHelper::get_video_url( $urls );

		$html = $this->start_wrapper_html();
		foreach ( $urls as $url ) {
			$item_html  = '<div class="carousel-slider-item-video">';
			$item_html .= '<div class="carousel-slider-video-wrapper">';
			$item_html .= '<a class="magnific-popup" href="' . esc_url( $url['url'] ) . '">';
			$item_html .= '<div class="carousel-slider-video-play-icon"></div>';
			$item_html .= '<div class="carousel-slider-video-overlay"></div>';
			if ( Validate::checked( $lazy_load_image ) ) {
				$item_html .= '<img class="owl-lazy" data-src="' . esc_url( $url['thumbnail']['large'] ) . '"/>';
			} else {
				$item_html .= '<img src="' . esc_url( $url['thumbnail']['large'] ) . '"/>';
			}
			$item_html .= '</a>';
			$item_html .= '</div>';
			$item_html .= '</div>' . PHP_EOL;

			$html .= apply_filters( 'carousel_slider/loop/video-carousel', $item_html, $url, $this->get_slider_setting() );
		}

		$html .= $this->end_wrapper_html();

		return apply_filters( 'carousel_slider_videos_carousel', $html, $slider_id );
	}
}
