<?php

namespace CarouselSlider\Modules\ImageCarousel;

use CarouselSlider\Abstracts\AbstractView;
use CarouselSlider\Supports\Validate;
use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * View class
 *
 * @package Modules/ImageCarousel
 */
class View extends AbstractView {

	/**
	 * Render html content
	 *
	 * @inheritDoc
	 */
	public function render(): string {
		$slider_id = $this->get_slider_id();
		$ids       = get_post_meta( $slider_id, '_wpdh_image_ids', true );
		if ( is_string( $ids ) ) {
			$ids = array_filter( explode( ',', $ids ) );
		}
		$shuffle_images = get_post_meta( $slider_id, '_shuffle_images', true );
		if ( Validate::checked( $shuffle_images ) ) {
			shuffle( $ids );
		}
		$image_target            = get_post_meta( $slider_id, '_image_target', true );
		$image_target            = in_array( $image_target, [ '_self', '_blank' ], true ) ? $image_target : '_self';
		$image_size              = get_post_meta( $slider_id, '_image_size', true );
		$image_size              = in_array( $image_size, get_intermediate_image_sizes(), true ) ? $image_size : 'medium_large';
		$lazy_load_image         = Validate::checked( get_post_meta( $slider_id, '_lazy_load_image', true ) );
		$show_attachment_title   = Validate::checked( get_post_meta( $slider_id, '_show_attachment_title', true ) );
		$show_attachment_caption = Validate::checked( get_post_meta( $slider_id, '_show_attachment_caption', true ) );
		$show_lightbox           = get_post_meta( $slider_id, '_image_lightbox', true );

		$html = $this->start_wrapper_html();
		foreach ( $ids as $id ) {
			$_post = get_post( $id );
			if ( ! $_post instanceof WP_Post ) {
				continue;
			}
			do_action( 'carousel_slider_image_gallery_loop', $_post );

			$image_link_url = get_post_meta( $id, '_carousel_slider_link_url', true );

			$full_caption = $this->get_caption_html( $_post, $show_attachment_title, $show_attachment_caption );

			$image = $this->get_image_html( $id, $image_size, $lazy_load_image );

			$item_html = '<div class="carousel-slider__item">';
			if ( Validate::checked( $show_lightbox ) ) {
				$image_src  = wp_get_attachment_image_src( $id, 'full' );
				$item_html .= sprintf(
					'<a class="magnific-popup" href="%1$s">%2$s%3$s</a>',
					esc_url( $image_src[0] ),
					$image,
					$full_caption
				);
			} elseif ( Validate::url( $image_link_url ) ) {
				$item_html .= sprintf(
					'<a  href="%1$s" target="%4$s">%2$s%3$s</a>',
					esc_url( $image_link_url ),
					$image,
					$full_caption,
					$image_target
				);
			} else {
				$item_html .= $image;
				$item_html .= $full_caption;
			}
			$item_html .= '</div>' . PHP_EOL;

			$html .= apply_filters( 'carousel_slider/loop/image-carousel', $item_html, $this->get_slider_id(), $this->get_slider_setting() );
		}
		$html .= $this->end_wrapper_html();

		return apply_filters( 'carousel_slider_gallery_images_carousel', $html );
	}

	/**
	 * Get image html
	 *
	 * @param int    $image_id The image id.
	 * @param string $image_size The image size.
	 * @param bool   $lazy_load_image Lazy load image.
	 *
	 * @return string
	 */
	protected function get_image_html( int $image_id, string $image_size, bool $lazy_load_image ): string {
		$image_alt_text = trim( wp_strip_all_tags( get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ) );
		if ( $lazy_load_image ) {
			$image_src = wp_get_attachment_image_src( $image_id, $image_size );

			return sprintf(
				'<img class="owl-lazy" data-src="%1$s" width="%2$s" height="%3$s" alt="%4$s" />',
				$image_src[0],
				$image_src[1],
				$image_src[2],
				$image_alt_text
			);

		}

		return wp_get_attachment_image( $image_id, $image_size, false, [ 'alt' => $image_alt_text ] );
	}

	/**
	 * Get caption html
	 *
	 * @param WP_Post $post The WP_Post object.
	 * @param bool    $show_title Show title.
	 * @param bool    $show_caption Show caption.
	 *
	 * @return string
	 */
	protected function get_caption_html( WP_Post $post, bool $show_title, bool $show_caption ): string {
		$show_title_and_caption = $show_title && $show_caption;
		$title                  = '';
		$caption                = '';

		if ( ! empty( $post->post_title ) ) {
			$title = '<h4 class="title">' . esc_html( $post->post_title ) . '</h4>';
		}
		if ( ! empty( $post->post_excerpt ) ) {
			$caption = '<p class="caption">' . esc_html( $post->post_excerpt ) . '</p>';
		}

		if ( $show_title_and_caption && ( $title || $caption ) ) {
			return sprintf( '<div class="carousel-slider__caption">%1$s%2$s</div>', $title, $caption );
		}

		if ( $show_title && $title ) {
			return sprintf( '<div class="carousel-slider__caption">%s</div>', $title );
		}

		if ( $show_caption && $caption ) {
			return sprintf( '<div class="carousel-slider__caption">%s</div>', $caption );
		}

		return '';
	}
}
