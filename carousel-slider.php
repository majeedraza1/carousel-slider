<?php
/**
 * Plugin Name: Carousel Slider
 * Plugin URI: http://wordpress.org/plugins/carousel-slider
 * Description: The Easiest Way to Create SEO friendly Image, Logo, Video, Post and WooCommerce Product Carousel.
 * Version: 1.8.1
 * Author: Sayful Islam
 * Author URI: https://github.com/sayful1
 * Requires at least: 4.4
 * Tested up to: 4.8
 *
 * Text Domain: carousel-slider
 *
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package Carousel_Slider
 * @author Sayful Islam
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Carousel_Slider' ) ) {

	final class Carousel_Slider {
		private $plugin_name = 'carousel-slider';
		private $version = '1.8.1';

		protected static $instance = null;

		/**
		 * Main Carousel_Slider Instance
		 * Ensures only one instance of Carousel_Slider is loaded or can be loaded.
		 *
		 * @since 1.6.0
		 * @return Carousel_Slider - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Carousel_Slider constructor.
		 */
		public function __construct() {
			$this->define_constants();
			$this->includes();

			register_activation_hook( __FILE__, array( $this, 'activation' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );

			do_action( 'carousel_slider_init' );
		}

		public function define_constants() {
			$this->define( 'CAROUSEL_SLIDER_VERSION', $this->version );
			$this->define( 'CAROUSEL_SLIDER_FILE', __FILE__ );
			$this->define( 'CAROUSEL_SLIDER_PATH', dirname( CAROUSEL_SLIDER_FILE ) );
			$this->define( 'CAROUSEL_SLIDER_INCLUDES', CAROUSEL_SLIDER_PATH . '/includes' );
			$this->define( 'CAROUSEL_SLIDER_TEMPLATES', CAROUSEL_SLIDER_PATH . '/templates' );
			$this->define( 'CAROUSEL_SLIDER_WIDGETS', CAROUSEL_SLIDER_PATH . '/widgets' );
			$this->define( 'CAROUSEL_SLIDER_URL', plugins_url( '', CAROUSEL_SLIDER_FILE ) );
			$this->define( 'CAROUSEL_SLIDER_ASSETS', CAROUSEL_SLIDER_URL . '/assets' );
		}

		/**
		 * Define constant if not already set.
		 *
		 * @param  string $name
		 * @param  string|bool $value
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Include admin and front facing files
		 */
		public function includes() {
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-i18n.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-number-to-word.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/functions-carousel-slider.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-activator.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-product.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-script.php';
			require_once CAROUSEL_SLIDER_WIDGETS . '/widget-carousel_slider.php';

			if ( is_admin() ) {
				$this->admin_includes();
			}
			if ( ! is_admin() ) {
				$this->frontend_includes();
			}
		}

		/**
		 * Include admin files
		 */
		public function admin_includes() {
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-credit.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-vc-element.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-documentation.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-form.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-admin.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-content-carousel.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-meta-box.php';
		}

		/**
		 * Load front facing files
		 */
		public function frontend_includes() {
			require_once CAROUSEL_SLIDER_PATH . '/shortcodes/class-carousel-slider-shortcode.php';
			require_once CAROUSEL_SLIDER_PATH . '/shortcodes/class-carousel-slider-deprecated-shortcode.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-structured-data.php';
		}

		/**
		 * To be run when the plugin is activated
		 * @return void
		 */
		public function activation() {
			do_action( 'carousel_slider_activation' );
			flush_rewrite_rules();
		}

		/**
		 * To be run when the plugin is deactivated
		 * @return void
		 */
		public function deactivation() {
			do_action( 'carousel_slider_deactivation' );
			flush_rewrite_rules();
		}
	}
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
Carousel_Slider::instance();
