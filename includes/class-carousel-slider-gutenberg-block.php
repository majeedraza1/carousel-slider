<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

if ( ! class_exists( 'Carousel_Slider_Gutenberg_Block' ) ) {

	class Carousel_Slider_Gutenberg_Block {

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
			wp_register_script( 'carousel-slider-gutenberg-block',
				CAROUSEL_SLIDER_ASSETS . '/js/gutenberg-block.js',
				array( 'underscore', 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n' )
			);
			wp_register_style( 'carousel-slider-gutenberg-style',
				CAROUSEL_SLIDER_ASSETS . '/css/gutenberg-block.css',
				array( 'wp-edit-blocks' )
			);
			wp_localize_script( 'carousel-slider-gutenberg-block',
				'carousel_slider_gutenberg_block', $this->block()
			);

			register_block_type( 'carousel-slider/slider', array(
				'editor_script' => 'carousel-slider-gutenberg-block',
				'editor_style'  => 'carousel-slider-gutenberg-style',
			) );
		}

		private function block() {
			$_sliders = get_posts( array(
				'posts_per_page' => - 1,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'post_type'      => 'carousels',
				'post_status'    => 'publish',
			) );
			$sliders  = array();
			foreach ( $_sliders as $form ) {
				if ( ! $form instanceof \WP_Post ) {
					continue;
				}
				$sliders[] = array(
					'value' => absint( $form->ID ),
					'label' => esc_attr( $form->post_title ),
				);
			}

			return array(
				'sliders'         => $sliders,
				'site_url'        => site_url(),
				'block_logo'      => CAROUSEL_SLIDER_ASSETS . '/static-images/logo.svg',
				'block_title'     => __( 'Carousel Slider', 'carousel-slider' ),
				'select_slider'   => __( 'Select a Slider', 'carousel-slider' ),
				'selected_slider' => __( 'Current Selected Slider', 'carousel-slider' ),
				'filter_slider'   => __( 'Type to filter sliders', 'carousel-slider' ),
			);
		}
	}
}

Carousel_Slider_Gutenberg_Block::init();
