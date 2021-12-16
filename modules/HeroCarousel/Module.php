<?php

namespace CarouselSlider\Modules\HeroCarousel;

use CarouselSlider\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Module class
 *
 * @package Modules/HeroCarousel
 */
class Module {
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

			add_filter( 'carousel_slider/register_view', [ self::$instance, 'view' ] );
			add_action( 'carousel_slider/save_slider', [ self::$instance, 'save_slider' ] );

			if ( Helper::is_request( 'admin' ) ) {
				Admin::init();
				Ajax::init();
			}
		}

		return self::$instance;
	}

	/**
	 * Register view for hero carousel
	 *
	 * @param array $views List of views.
	 *
	 * @return array
	 */
	public function view( array $views ): array {
		$views['hero-banner-slider'] = new View();

		return $views;
	}

	/**
	 * Save slider content and settings
	 *
	 * @param int $slider_id The slider id.
	 */
	public function save_slider( int $slider_id ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( isset( $_POST['carousel_slider_content'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			$_content_slides = is_array( $_POST['carousel_slider_content'] ) ? $_POST['carousel_slider_content'] : [];
			$_slides         = array_map(
				function ( $slide ) {
					return Item::sanitize( $slide );
				},
				$_content_slides
			);

			update_post_meta( $slider_id, '_content_slider', $_slides );
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( isset( $_POST['content_settings'] ) ) {
			$this->update_content_settings( $slider_id );
		}
	}

	/**
	 * Update hero carousel settings
	 *
	 * @param int $post_id post id.
	 */
	private function update_content_settings( int $post_id ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$setting   = $_POST['content_settings'] ?? [];
		$_settings = [
			'slide_height'      => sanitize_text_field( $setting['slide_height'] ),
			'content_width'     => sanitize_text_field( $setting['content_width'] ),
			'content_animation' => sanitize_text_field( $setting['content_animation'] ),
			'slide_padding'     => [
				'top'    => sanitize_text_field( $setting['slide_padding']['top'] ),
				'right'  => sanitize_text_field( $setting['slide_padding']['right'] ),
				'bottom' => sanitize_text_field( $setting['slide_padding']['bottom'] ),
				'left'   => sanitize_text_field( $setting['slide_padding']['left'] ),
			],
		];
		update_post_meta( $post_id, '_content_slider_settings', $_settings );
	}
}
