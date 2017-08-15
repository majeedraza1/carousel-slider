<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Carousel_Slider_Documentation' ) ):

	class Carousel_Slider_Documentation {
		private $plugin_path;

		public function __construct() {
			$this->plugin_path = CAROUSEL_SLIDER_PATH;
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		}

		public function admin_menu() {
			add_submenu_page(
				'edit.php?post_type=carousels',
				'Documentation',
				'Documentation',
				'manage_options',
				'carousel-slider-documentation',
				array( $this, 'submenu_page_callback' )
			);
		}

		public function submenu_page_callback() {
			include_once CAROUSEL_SLIDER_TEMPLATES . '/documentation.php';
		}
	}

endif;

new Carousel_Slider_Documentation();