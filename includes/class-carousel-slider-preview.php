<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

if ( ! class_exists( 'Carousel_Slider_Preview' ) ) {

	class Carousel_Slider_Preview {

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

				add_action( 'template_include', array( self::$instance, 'template_include' ) );
			}

			return self::$instance;
		}

		public function template_include( $template ) {
			if ( isset( $_GET['carousel_slider_preview'], $_GET['carousel_slider_iframe'], $_GET['slider_id'] ) ) {
				if ( current_user_can( 'edit_pages' ) ) {
					$template = CAROUSEL_SLIDER_TEMPLATES . '/public/preview-slider.php';
				}
			}

			return $template;
		}
	}
}

Carousel_Slider_Preview::init();
