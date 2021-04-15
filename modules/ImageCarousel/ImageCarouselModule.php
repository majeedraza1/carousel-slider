<?php

namespace CarouselSlider\Modules\ImageCarousel;

use CarouselSlider\Frontend\Shortcode;
use CarouselSlider\Supports\Validate;
use CarouselSlider\Helper;
use WP_Post;

defined( 'ABSPATH' ) || exit;

class ImageCarouselModule {
	/**
	 * The instance of the class
	 *
	 * @var self
	 */
	protected static $instance;

	/**
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_action( 'carousel_slider/save_slider', [ self::$instance, 'save_slider' ] );
			add_filter( 'carousel_slider/view', [ self::$instance, 'view' ], 10, 3 );

			// Add custom link to media gallery
			add_filter( "attachment_fields_to_edit", [ self::$instance, "attachment_fields_to_edit" ], 10, 2 );
			add_filter( "attachment_fields_to_save", [ self::$instance, "attachment_fields_to_save" ], 10, 2 );
		}

		return self::$instance;
	}

	/**
	 * @param string $html
	 * @param int $slider_id
	 * @param string $slider_type
	 *
	 * @return string
	 */
	public function view( string $html, int $slider_id, string $slider_type ): string {
		if ( 'image-carousel' == $slider_type ) {
			return self::get_image_carousel_view( $slider_id );
		}
		if ( 'image-carousel-url' == $slider_type ) {
			return ImageCarouselUrl::get_view( $slider_id );
		}

		return $html;
	}

	/**
	 * Get view
	 *
	 * @param int $slider_id
	 *
	 * @return string
	 */
	public static function get_image_carousel_view( int $slider_id ): string {
		$ids = get_post_meta( $slider_id, '_wpdh_image_ids', true );
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
		$css_vars    = Helper::get_css_variable( $slider_id );
		$styles      = [];
		foreach ( $css_vars as $key => $var ) {
			$styles[] = sprintf( "%s:%s", $key, $var );
		}

		$options = ( new Shortcode )->carousel_options( $slider_id );
		$html    = '<div class="' . join( ' ', $css_classes ) . '" style="' . implode( ';', $styles ) . '">';
		$html    .= '<div ' . join( " ", $options ) . '>';
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

	/**
	 * Save slider info
	 *
	 * @param int $slider_id
	 */
	public function save_slider( int $slider_id ) {
		if ( isset( $_POST['_images_urls'] ) ) {
			$url      = $_POST['_images_urls']['url'];
			$title    = $_POST['_images_urls']['title'];
			$caption  = $_POST['_images_urls']['caption'];
			$alt      = $_POST['_images_urls']['alt'];
			$link_url = $_POST['_images_urls']['link_url'];

			$urls = array();

			for ( $i = 0; $i < count( $url ); $i ++ ) {
				$urls[] = array(
					'url'      => esc_url_raw( $url[ $i ] ),
					'title'    => sanitize_text_field( $title[ $i ] ),
					'caption'  => sanitize_text_field( $caption[ $i ] ),
					'alt'      => sanitize_text_field( $alt[ $i ] ),
					'link_url' => esc_url_raw( $link_url[ $i ] ),
				);
			}
			update_post_meta( $slider_id, '_images_urls', $urls );
		}
	}


	/**
	 * Adding our custom fields to the $form_fields array
	 *
	 * @param array $form_fields
	 * @param WP_Post $post
	 *
	 * @return array
	 */
	public function attachment_fields_to_edit( array $form_fields, WP_Post $post ): array {
		$value = get_post_meta( $post->ID, "_carousel_slider_link_url", true );
		$field = [
			"label"      => __( "Link to URL", "carousel-slider" ),
			"input"      => "textarea",
			"value"      => $value,
			"extra_rows" => [
				'carouselSliderInfo' => __( '"Link to URL" only works on Carousel Slider for linking image to a custom url.', 'carousel-slider' ),
			]
		];

		$form_fields["carousel_slider_link_url"] = $field;

		return $form_fields;
	}

	/**
	 * Save custom field value
	 *
	 * @param array $post
	 * @param array $attachment
	 *
	 * @return array
	 */
	public function attachment_fields_to_save( array $post, array $attachment ): array {
		$slider_link_url = $attachment['carousel_slider_link_url'] ?? null;

		if ( Validate::url( $slider_link_url ) ) {
			update_post_meta( $post['ID'], '_carousel_slider_link_url', esc_url_raw( $slider_link_url ) );
		} else {
			delete_post_meta( $post['ID'], '_carousel_slider_link_url' );
		}

		return $post;
	}
}
