<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @class   Carousel_Slider_i8n
 * @since   1.7.3
 * @author  Sayful Islam <sayful.islam001@gmail.com>
 */
if ( ! class_exists( 'Carousel_Slider_i8n' ) ):

	class Carousel_Slider_i8n {
		protected static $instance = null;
		protected $plugin_name = 'carousel-slider';

		/**
		 * Ensures only one instance of this class is loaded or can be loaded.
		 *
		 * @return Carousel_Slider_i8n
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {
			add_action( 'init', array( $this, 'load_textdomain' ) );
		}

		/**
		 * Load plugin textdomain
		 */
		public function load_textdomain() {
			$locale_file = sprintf( '%1$s-%2$s.mo', 'carousel-slider', get_locale() );
			$global_file = join( DIRECTORY_SEPARATOR, array( WP_LANG_DIR, 'carousel-slider', $locale_file ) );

			// Look in global /wp-content/languages/carousel-slider folder
			if ( file_exists( $global_file ) ) {
				load_textdomain( $this->plugin_name, $global_file );
			}
		}
	}

endif;

Carousel_Slider_i8n::init();
