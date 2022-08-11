<?php

namespace CarouselSlider\Frontend;

use CarouselSlider\Assets;
use CarouselSlider\Helper;
use CarouselSlider\Interfaces\SliderViewInterface;
use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * Frontend class
 *
 * The frontend functionality specific class of the plugin
 */
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

		$slider_id = intval( $attributes['id'] );

		// Check if id is valid or not.
		$post = get_post( $slider_id );
		if ( ! ( $post instanceof WP_Post && CAROUSEL_SLIDER_POST_TYPE === $post->post_type ) ) {
			return '';
		}

		$slide_type = get_post_meta( $slider_id, '_slide_type', true );
		$slide_type = array_key_exists( $slide_type, Helper::get_slide_types() ) ? $slide_type : 'image-carousel';

		// If script & style is not enqueued yet, then enqueued it now.
		$this->load_scripts_if_not_loaded();

		$view = Helper::get_slider_view( $slide_type );
		if ( $view instanceof SliderViewInterface ) {
			$view->set_slider_id( $slider_id );
			$view->set_slider_type( $slide_type );

			return $view->render();
		}

		return apply_filters( 'carousel_slider/view', '', $slider_id, $slide_type );
	}

	/**
	 * Load frontend scripts
	 */
	public function frontend_scripts() {
		if ( ! $this->should_load_scripts() ) {
			return;
		}

		if ( Helper::is_using_swiper() ) {
			wp_enqueue_style( 'carousel-slider-frontend-v2' );
			wp_enqueue_script( 'carousel-slider-frontend-v2' );
		} else {
			wp_enqueue_style( 'carousel-slider-frontend' );
			wp_enqueue_script( 'carousel-slider-frontend' );
		}
	}

	/**
	 * Check if it should load frontend scripts
	 *
	 * @return bool
	 */
	private function should_load_scripts(): bool {
		$load_scripts = Helper::get_setting( 'load_scripts', 'optimized' );
		if ( 'always' === $load_scripts ) {
			return true;
		}

		global $post;
		$load_scripts = is_active_widget( false, false, 'widget_carousel_slider', true ) ||
						( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'carousel_slide' ) );

		return apply_filters( 'carousel_slider_load_scripts', $load_scripts );
	}

	/**
	 * Load scripts if not loaded yet
	 *
	 * @return void
	 */
	protected function load_scripts_if_not_loaded() {
		if ( wp_script_is( 'carousel-slider-frontend', 'enqueued' ) ) {
			return;
		}
		if ( 'optimized-loader' !== Helper::get_setting( 'load_scripts' ) ) {
			return;
		}
		wp_enqueue_script( 'carousel-slider-frontend' );
		add_action(
			'wp_footer',
			function () {
				Helper::print_unescaped_internal_string( Assets::get_style_loader_script() );
			},
			0
		);
	}
}
