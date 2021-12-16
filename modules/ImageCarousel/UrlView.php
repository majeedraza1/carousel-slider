<?php

namespace CarouselSlider\Modules\ImageCarousel;

use CarouselSlider\Abstracts\AbstractView;
use CarouselSlider\Supports\Validate;

defined( 'ABSPATH' ) || exit;

/**
 * UrlView class
 *
 * @package Modules/ImageCarousel
 */
class UrlView extends AbstractView {

	/**
	 * Render html content
	 *
	 * @inheritDoc
	 */
	public function render(): string {
		$slider_id   = $this->get_slider_id();
		$images_urls = (array) get_post_meta( $slider_id, '_images_urls', true );
		if ( count( $images_urls ) < 1 ) {
			return '';
		}
		$image_target            = get_post_meta( $slider_id, '_image_target', true );
		$image_target            = in_array( $image_target, [ '_self', '_blank' ], true ) ? $image_target : '_self';
		$lazy_load_image         = get_post_meta( $slider_id, '_lazy_load_image', true );
		$show_attachment_title   = get_post_meta( $slider_id, '_show_attachment_title', true );
		$show_attachment_caption = get_post_meta( $slider_id, '_show_attachment_caption', true );

		$html = $this->start_wrapper_html();

		foreach ( $images_urls as $images_url ) {
			$title   = sprintf( '<h4 class="title">%1$s</h4>', esc_html( $images_url['title'] ) );
			$caption = sprintf( '<p class="caption">%1$s</p>', esc_html( $images_url['caption'] ) );

			if ( Validate::checked( $show_attachment_title ) && Validate::checked( $show_attachment_caption ) ) {
				$full_caption = sprintf( '<div class="carousel-slider__caption">%1$s%2$s</div>', $title, $caption );
			} elseif ( Validate::checked( $show_attachment_title ) ) {
				$full_caption = sprintf( '<div class="carousel-slider__caption">%s</div>', $title );
			} elseif ( Validate::checked( $show_attachment_caption ) ) {
				$full_caption = sprintf( '<div class="carousel-slider__caption">%s</div>', $caption );
			} else {
				$full_caption = '';
			}

			if ( Validate::checked( $lazy_load_image ) ) {
				$image = sprintf(
					'<img class="owl-lazy" data-src="%1$s" alt="%2$s" />',
					$images_url['url'],
					$images_url['alt']
				);
			} else {
				$image = sprintf( '<img src="%1$s" alt="%2$s" />', $images_url['url'], $images_url['alt'] );
			}

			$html .= '<div class="carousel-slider__item">';
			if ( Validate::url( $images_url['link_url'] ) ) {
				$html .= sprintf(
					'<a href="%1$s" target="%4$s">%2$s %3$s</a>',
					$images_url['link_url'],
					$image,
					$full_caption,
					$image_target
				);
			} else {
				$html .= $image;
				$html .= $full_caption;
			}
			$html .= '</div>' . PHP_EOL;
		}

		$html .= $this->end_wrapper_html();

		return apply_filters( 'carousel_slider_link_images_carousel', $html, $slider_id );
	}
}
