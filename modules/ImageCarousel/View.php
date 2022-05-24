<?php

namespace CarouselSlider\Modules\ImageCarousel;

use CarouselSlider\Abstracts\AbstractView;
use CarouselSlider\Abstracts\SliderSetting;
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
	 * Get slider setting
	 *
	 * @return SliderSetting|Setting
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
		$setting = $this->get_slider_setting();

		$html = $this->start_wrapper_html();
		foreach ( $setting->get_image_ids() as $id ) {
			$_post = get_post( $id );
			if ( ! $_post instanceof WP_Post ) {
				continue;
			}
			do_action( 'carousel_slider_image_gallery_loop', $_post );

			$image_link_url = get_post_meta( $id, '_carousel_slider_link_url', true );

			$full_caption = $this->get_caption_html( $_post, $setting->get_prop( 'show_title' ), $setting->get_prop( 'show_caption' ) );

			$image = $this->get_image_html( $id, $setting->get_image_size(), $setting->get_prop( 'lazy_load' ) );

			$item_html = '<div class="carousel-slider__item">';
			if ( Validate::checked( $setting->get_prop( 'show_lightbox' ) ) ) {
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
					$setting->get_image_target()
				);
			} else {
				$item_html .= $image;
				$item_html .= $full_caption;
			}
			$item_html .= '</div>' . PHP_EOL;

			$html .= apply_filters( 'carousel_slider/loop/image-carousel', $item_html, $_post, $this->get_slider_setting() );
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
