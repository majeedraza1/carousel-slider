<?php

// If this file is called directly, abort.
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Carousel_Slider_Setting' ) ) {
	class Carousel_Slider_Setting {

		/**
		 * Instance of current class
		 *
		 * @var self
		 */
		private static $instance;

		/**
		 * @return Carousel_Slider_Setting
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();

				add_action( 'init', array( self::$instance, 'settings' ) );
			}

			return self::$instance;
		}

		/**
		 * Plugin setting fields
		 *
		 * @throws Exception
		 */
		public function settings() {
			$settings = new Carousel_Slider_Setting_API();
			$settings->add_menu( array(
				'page_title'  => __( 'Carousel Slider Settings', 'carousel-slider' ),
				'menu_title'  => __( 'Settings', 'carousel-slider' ),
				'about_text'  => __( 'Thank you for choosing Carousel Slider. We hope you enjoy it!', 'carousel-slider' ),
				'menu_slug'   => 'settings',
				'parent_slug' => 'edit.php?post_type=carousels',
				'option_name' => 'carousel_slider_settings',
			) );

			// Add settings page tab
			$settings->add_tab( array(
				'id'    => 'general',
				'title' => __( 'General', 'carousel-slider' ),
			) );

			$settings->add_field( array(
				'id'      => 'load_scripts',
				'type'    => 'radio',
				'std'     => 'optimized',
				'name'    => __( 'Style & Scrips', 'carousel-slider' ),
				'desc'    => __( 'If you choose Optimized, then scrips and styles will be loaded only on page where you are using shortcode. If Optimized is not working for you then choose Always.', 'carousel-slider' ),
				'options' => array(
					'always'    => __( 'Always', 'carousel-slider' ),
					'optimized' => __( 'Optimized (recommended)', 'carousel-slider' ),
				),
				'tab'     => 'general',
			) );
		}
	}
}

Carousel_Slider_Setting::init();
