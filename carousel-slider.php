<?php
/**
 * Plugin Name: Carousel Slider
 * Plugin URI: http://wordpress.org/plugins/carousel-slider
 * Description: The Easiest Way to Create SEO friendly Image, Logo, Video, Post and WooCommerce Product Carousel.
 * Version: 1.8.8
 * Author: Sayful Islam
 * Author URI: https://sayfulislam.com
 * Requires at least: 4.4
 * Tested up to: 4.9
 *
 * WC requires at least: 2.5
 * WC tested up to: 3.3
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

		/**
		 * Plugin name slug
		 *
		 * @var string
		 */
		private $plugin_name = 'carousel-slider';

		/**
		 * Plugin version
		 *
		 * @var string
		 */
		private $version = '1.8.8';

		/**
		 * Minimum PHP version required
		 *
		 * @var string
		 */
		private $min_php = '5.3.0';

		/**
		 * @var object
		 */
		protected static $instance;

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
			define( 'CAROUSEL_SLIDER_VERSION', $this->version );
			define( 'CAROUSEL_SLIDER_FILE', __FILE__ );
			define( 'CAROUSEL_SLIDER_PATH', dirname( CAROUSEL_SLIDER_FILE ) );
			define( 'CAROUSEL_SLIDER_INCLUDES', CAROUSEL_SLIDER_PATH . '/includes' );
			define( 'CAROUSEL_SLIDER_TEMPLATES', CAROUSEL_SLIDER_PATH . '/templates' );
			define( 'CAROUSEL_SLIDER_WIDGETS', CAROUSEL_SLIDER_PATH . '/widgets' );
			define( 'CAROUSEL_SLIDER_URL', plugins_url( '', CAROUSEL_SLIDER_FILE ) );
			define( 'CAROUSEL_SLIDER_ASSETS', CAROUSEL_SLIDER_URL . '/assets' );
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
		private function includes() {
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-i18n.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/functions-carousel-slider.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-activator.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-product.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-script.php';
			require_once CAROUSEL_SLIDER_WIDGETS . '/widget-carousel_slider.php';

			if ( is_admin() ) {
				require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-credit.php';
				require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-documentation.php';
				require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-vc-element.php';
				require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-form.php';
				require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-admin.php';
				require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-meta-box.php';
				require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-hero-carousel.php';
			}

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
