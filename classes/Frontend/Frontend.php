<?php

namespace CarouselSlider\Frontend;

use CarouselSlider\Helper;

defined( 'ABSPATH' ) || exit;

class Frontend {

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

			add_shortcode( 'carousel_slide', [ self::$instance, 'carousel_slide' ] );
			add_action( 'wp_enqueue_scripts', [ self::$instance, 'frontend_scripts' ], 15 );
		}

		return self::$instance;
	}

	/**
	 * A shortcode for rendering the carousel slide.
	 *
	 * @param array $attributes Shortcode attributes.
	 *
	 * @return string  The shortcode output
	 */
	public function carousel_slide( array $attributes ): string {
		if ( empty( $attributes['id'] ) ) {
			return '';
		}

		$slider_id  = intval( $attributes['id'] );
		$slide_type = get_post_meta( $slider_id, '_slide_type', true );
		$slide_type = array_key_exists( $slide_type, Helper::get_slide_types() ) ? $slide_type : 'image-carousel';

		return apply_filters( 'carousel_slider/view', '', $slider_id, $slide_type );
	}

	/**
	 * Load frontend scripts
	 */
	public function frontend_scripts() {
		if ( ! $this->should_load_scripts() ) {
			return;
		}

		wp_enqueue_style( 'carousel-slider-frontend' );
		wp_enqueue_script( 'carousel-slider-frontend' );
	}

	/**
	 * Check if it should load frontend scripts
	 *
	 * @return bool
	 */
	private function should_load_scripts(): bool {
		$settings = get_option( 'carousel_slider_settings' );
		$settings = is_array( $settings ) ? $settings : [];
		if ( isset( $settings['load_scripts'] ) && 'always' == $settings['load_scripts'] ) {
			return true;
		}

		global $post;
		$load_scripts = is_active_widget( false, false, 'widget_carousel_slider', true ) ||
		                ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'carousel_slide' ) ) ||
		                ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'carousel' ) );

		return apply_filters( 'carousel_slider_load_scripts', $load_scripts );
	}
}
