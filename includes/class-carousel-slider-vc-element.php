<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

if ( ! class_exists( 'Carousel_Slider_Visual_Composer_Element' ) ) {

	class Carousel_Slider_Visual_Composer_Element {

		/**
		 * The instance of the class
		 *
		 * @var self
		 */
		private static $instance = null;

		/**
		 * Ensures only one instance of this class is loaded or can be loaded.
		 *
		 * @return self
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();

				add_action( 'init', array( self::$instance, 'integrate_with_vc' ) );
			}

			return self::$instance;
		}

		/**
		 * Integrate with visual composer
		 */
		public function integrate_with_vc() {
			// Check if Visual Composer is installed
			if ( ! function_exists( 'vc_map' ) ) {
				return;
			}

			vc_map( array(
				"name"        => __( "Carousel Slider", 'carousel-slider' ),
				"description" => __( "Place Carousel Slider.", 'carousel-slider' ),
				"base"        => "carousel_slide",
				"controls"    => "full",
				"icon"        => CAROUSEL_SLIDER_ASSETS . '/img/logo.svg',
				"category"    => __( 'Content', 'carousel-slider' ),
				"params"      => array(
					array(
						"type"       => "dropdown",
						"holder"     => "div",
						"class"      => "carousel-slider-id",
						"param_name" => "id",
						"value"      => $this->carousels_list(),
						"heading"    => __( "Choose Carousel Slide", 'carousel-slider' ),
					),
				),
			) );
		}

		/**
		 * Generate array for carousel slider
		 *
		 * @return array
		 */
		private function carousels_list() {
			$carousels = get_posts( array(
				'post_type'      => 'carousels',
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
			) );

			if ( count( $carousels ) < 1 ) {
				return array();
			}

			$result = array();

			foreach ( $carousels as $carousel ) {
				$result[ esc_html( $carousel->post_title ) ] = $carousel->ID;
			}

			return $result;
		}
	}
}

Carousel_Slider_Visual_Composer_Element::init();
