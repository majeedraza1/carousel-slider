<?php

namespace CarouselSlider\Admin;

use CarouselSlider\Helper;
use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * GutenbergBlock class
 * The admin gutenberg editor functionality specific class of the plugin
 *
 * @package CarouselSlider/Admin
 */
class GutenbergBlock {
	/**
	 * The instance of the class
	 *
	 * @var self
	 */
	protected static $instance;

	/**
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_action( 'init', array( self::$instance, 'gutenberg_block' ) );
		}

		return self::$instance;
	}

	/**
	 * Register gutenberg block
	 */
	public function gutenberg_block() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}
		wp_register_script(
			'carousel-slider-gutenberg-block',
			CAROUSEL_SLIDER_ASSETS . '/js/admin-gutenberg-block.js',
			[ 'wp-blocks', 'wp-components', 'wp-block-editor' ],
			CAROUSEL_SLIDER_VERSION,
			true
		);
		wp_register_style(
			'carousel-slider-gutenberg-style',
			CAROUSEL_SLIDER_ASSETS . '/css/admin-gutenberg-block.css',
			[ 'wp-edit-blocks' ],
			CAROUSEL_SLIDER_VERSION
		);
		wp_localize_script(
			'carousel-slider-gutenberg-block',
			'i18nCarouselSliderBlock',
			$this->block_localize_data()
		);

		register_block_type(
			'carousel-slider/slider',
			[
				'editor_script' => 'carousel-slider-gutenberg-block',
				'editor_style'  => 'carousel-slider-gutenberg-style',
			]
		);
	}

	/**
	 * Get localize data
	 *
	 * @return array
	 */
	private function block_localize_data(): array {
		$_sliders = Helper::get_sliders();
		$sliders  = [
			[
				'value' => '',
				'label' => __( 'Select a Slider', 'carousel-slider' ),
			],
		];
		foreach ( $_sliders as $form ) {
			if ( ! $form instanceof WP_Post ) {
				continue;
			}
			$sliders[] = [
				'value' => absint( $form->ID ),
				'label' => esc_attr( $form->post_title ),
			];
		}

		return [
			'sliders'       => $sliders,
			'site_url'      => site_url(),
			'block_logo'    => CAROUSEL_SLIDER_ASSETS . '/static-images/logo.svg',
			'block_title'   => __( 'Carousel Slider', 'carousel-slider' ),
			'select_slider' => __( 'Select a Slider', 'carousel-slider' ),
		];
	}
}
