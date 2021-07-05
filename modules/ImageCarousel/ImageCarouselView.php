<?php

namespace CarouselSlider\Modules\ImageCarousel;

use CarouselSlider\Abstracts\View;
use CarouselSlider\Helper;
use CarouselSlider\Supports\Validate;

class ImageCarouselView extends View {

	/**
	 * @inheritDoc
	 */
	public function render(): string {
		$slider_type = $this->get_slider_type();
		$slider_id   = $this->get_slider_id();
		$ids         = get_post_meta( $slider_id, '_wpdh_image_ids', true );
		if ( is_string( $ids ) ) {
			$ids = array_filter( explode( ',', $ids ) );
		}
		$image_target            = get_post_meta( $slider_id, '_image_target', true );
		$image_target            = in_array( $image_target, [ '_self', '_blank' ] ) ? $image_target : '_self';
		$image_size              = get_post_meta( $slider_id, '_image_size', true );
		$image_size              = in_array( $image_size, get_intermediate_image_sizes() ) ? $image_size : 'medium_large';
		$lazy_load_image         = get_post_meta( $slider_id, '_lazy_load_image', true );
		$show_attachment_title   = get_post_meta( $slider_id, '_show_attachment_title', true );
		$show_attachment_caption = get_post_meta( $slider_id, '_show_attachment_caption', true );
		$show_title_and_caption  = Validate::checked( $show_attachment_title ) &&
		                           Validate::checked( $show_attachment_caption );
		$show_lightbox           = get_post_meta( $slider_id, '_image_lightbox', true );

		$css_classes = [
			"carousel-slider-outer",
			"carousel-slider-outer-images",
			"carousel-slider-outer-{$slider_id}"
		];

		$attributes_array = Helper::get_slider_attributes( $slider_id, $slider_type );

		$html = '<div class="' . join( ' ', $css_classes ) . '">';
		$html .= "<div " . join( " ", $attributes_array ) . ">";
		foreach ( $ids as $id ) {
			$_post = get_post( $id );
			do_action( 'carousel_slider_image_gallery_loop', $_post );

			$title          = sprintf( '<h4 class="title">%1$s</h4>', esc_html( $_post->post_title ) );
			$caption        = sprintf( '<p class="caption">%1$s</p>', esc_html( $_post->post_excerpt ) );
			$image_alt_text = trim( strip_tags( get_post_meta( $id, '_wp_attachment_image_alt', true ) ) );
			$image_link_url = get_post_meta( $id, "_carousel_slider_link_url", true );

			if ( $show_title_and_caption ) {
				$full_caption = sprintf( '<div class="carousel-slider__caption">%1$s%2$s</div>', $title, $caption );
			} elseif ( Validate::checked( $show_attachment_title ) ) {
				$full_caption = sprintf( '<div class="carousel-slider__caption">%s</div>', $title );
			} elseif ( Validate::checked( $show_attachment_caption ) ) {
				$full_caption = sprintf( '<div class="carousel-slider__caption">%s</div>', $caption );
			} else {
				$full_caption = '';
			}

			if ( Validate::checked( $lazy_load_image ) ) {
				$image_src = wp_get_attachment_image_src( $id, $image_size );
				$image     = sprintf(
					'<img class="owl-lazy" data-src="%1$s" width="%2$s" height="%3$s" alt="%4$s" />',
					$image_src[0], $image_src[1], $image_src[2], $image_alt_text
				);

			} else {
				$image = wp_get_attachment_image( $id, $image_size, false, [ 'alt' => $image_alt_text ] );
			}

			if ( Validate::checked( $show_lightbox ) ) {
				wp_enqueue_script( 'magnific-popup' );
				$image_src = wp_get_attachment_image_src( $id, 'full' );
				$html      .= sprintf(
					'<a href="%1$s" class="magnific-popup">%2$s%3$s</a>',
					esc_url( $image_src[0] ), $image, $full_caption
				);
			} elseif ( Validate::url( $image_link_url ) ) {
				$html .= sprintf(
					'<a href="%1$s" target="%4$s">%2$s%3$s</a>',
					esc_url( $image_link_url ), $image, $full_caption, $image_target
				);
			} else {
				$html .= $image;
				$html .= $full_caption;
			}
		}
		$html .= '</div>';
		$html .= '</div>';

		return apply_filters( 'carousel_slider_gallery_images_carousel', $html );
	}
}
