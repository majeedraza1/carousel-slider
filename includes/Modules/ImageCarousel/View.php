<?php

namespace CarouselSlider\Modules\ImageCarousel;

use CarouselSlider\Abstracts\AbstractView;
use CarouselSlider\Supports\Utils;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class View extends AbstractView {

	/**
	 * Render element.
	 * Generates the final HTML on the frontend.
	 */
	public function render() {
		$images_ids   = $this->image_ids();
		$images_count = count( $images_ids );

		$this->set_total_slides( $images_count );

		if ( $images_count < 1 ) {
			return '';
		}

		$_html = $this->slider_wrapper_start();

		foreach ( $images_ids as $image_id ) {

			$_post = get_post( $image_id );
			do_action( 'carousel_slider_image_gallery_loop', $_post );

			$image_link_url = get_post_meta( $image_id, "_carousel_slider_link_url", true );

			$html = '<div class="carousel-slider__item">';

			$image        = $this->get_image( $_post );
			$full_caption = $this->get_attachment_caption( $_post );
			$content      = $image . $full_caption;

			if ( $this->show_image_lightbox() ) {
				$image_src = wp_get_attachment_image_src( $image_id, 'full' );

				$html .= '<a href="' . esc_url( $image_src[0] ) . '" class="magnific-popup">' . $content . '</a>';
			} elseif ( Utils::is_url( $image_link_url ) ) {
				$html .= '<a href="' . esc_url( $image_link_url ) . '" target="' . $this->image_target() . '">' . $content . '</a>';
			} else {
				$html .= $content;
			}

			$html .= '</div>';

			$_html .= apply_filters( 'carousel_slider/view/image', $html, $_post );
		}

		$_html .= $this->slider_wrapper_end();

		return $_html;
	}

	/**
	 * Get image
	 *
	 * @param \WP_Post $post
	 *
	 * @return string
	 */
	protected function get_image( $post ) {
		$image_alt_text = trim( strip_tags( get_post_meta( $post->ID, '_wp_attachment_image_alt', true ) ) );
		if ( $this->lazy_load_image() ) {
			$image_src = wp_get_attachment_image_src( $post->ID, $this->image_size() );
			$image     = sprintf( '<img class="owl-lazy" data-src="%1$s" width="%2$s" height="%3$s" alt="%4$s" />',
				$image_src[0], $image_src[1], $image_src[2], $image_alt_text );

			return $image;
		}

		return wp_get_attachment_image( $post->ID, $this->image_size(), false, array( 'alt' => $image_alt_text ) );
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return string
	 */
	protected function get_attachment_caption( $post ) {
		$title   = sprintf( '<h4 class="title">%1$s</h4>', $post->post_title );
		$caption = sprintf( '<p class="caption">%1$s</p>', $post->post_excerpt );

		if ( $this->show_attachment_title() && $this->show_attachment_caption() ) {
			return sprintf( '<div class="carousel-slider__caption">%1$s%2$s</div>', $title, $caption );
		}

		if ( $this->show_attachment_title() ) {
			return sprintf( '<div class="carousel-slider__caption">%s</div>', $title );
		}

		if ( $this->show_attachment_caption() ) {
			return sprintf( '<div class="carousel-slider__caption">%s</div>', $caption );
		}

		return '';
	}

	/**
	 * Get image ids
	 *
	 * @return array
	 */
	protected function image_ids() {
		$ids        = $this->get_meta( '_wpdh_image_ids' );
		$images_ids = array_filter( explode( ',', $ids ) );

		return $images_ids;
	}

	/**
	 * Get image target
	 *
	 * @return string
	 */
	protected function image_target() {
		$_image_target = $this->get_meta( '_image_target' );

		return in_array( $_image_target, array( '_self', '_blank' ) ) ? $_image_target : '_self';
	}

	/**
	 * @return bool
	 */
	protected function show_attachment_title() {
		return $this->checked( $this->get_meta( '_show_attachment_title' ) );
	}

	/**
	 * @return bool
	 */
	protected function show_attachment_caption() {
		return $this->checked( $this->get_meta( '_show_attachment_caption' ) );
	}

	/**
	 * @return bool
	 */
	protected function show_image_lightbox() {
		return $this->checked( $this->get_meta( '_image_lightbox' ) );
	}
}
