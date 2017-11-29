<?php
/**
 * Plugin Name: Carousel Slider
 * Plugin URI: http://wordpress.org/plugins/carousel-slider
 * Description: The Easiest Way to Create SEO friendly Image, Logo, Video, Post and WooCommerce Product Carousel.
 * Version: 1.8.2
 * Author: Sayful Islam
 * Author URI: https://sayfulislam.com
 * Requires at least: 4.4
 * Tested up to: 4.9
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 3.2
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
		private $version = '1.8.2';

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
			// dry check on older PHP versions, if found deactivate itself with an error
			register_activation_hook( __FILE__, array( $this, 'auto_deactivate' ) );

			if ( ! $this->is_supported_php() ) {
				return;
			}

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
			define( 'CAROUSEL_SLIDER_WIDGETS', CAROUSEL_SLIDER_INCLUDES . '/widgets' );
			define( 'CAROUSEL_SLIDER_MODULES', CAROUSEL_SLIDER_PATH . '/modules' );
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
		public function includes() {
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-i18n.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/functions.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-activator.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-script.php';
			require_once CAROUSEL_SLIDER_WIDGETS . '/widget-carousel_slider.php';

			// Admin related files
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-form.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-admin.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-meta-box.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-vc-element.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-credit.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-documentation.php';

			// Public facing script
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-structured-data.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/shortcodes/class-carousel-slider-shortcode.php';

			// Hero Carousel
			require_once CAROUSEL_SLIDER_MODULES . '/hero-carousel/class-meta-box.php';
			require_once CAROUSEL_SLIDER_MODULES . '/hero-carousel/class-hero-carousel.php';
			require_once CAROUSEL_SLIDER_MODULES . '/hero-carousel/class-ajax-action.php';
			require_once CAROUSEL_SLIDER_MODULES . '/hero-carousel/class-view.php';

			// Post Carousel
			require_once CAROUSEL_SLIDER_MODULES . '/post-carousel/class-meta-box.php';
			require_once CAROUSEL_SLIDER_MODULES . '/post-carousel/class-post-carousel.php';
			require_once CAROUSEL_SLIDER_MODULES . '/post-carousel/class-view.php';

			// Video Carousel
			require_once CAROUSEL_SLIDER_MODULES . '/video-carousel/class-meta-box.php';
			require_once CAROUSEL_SLIDER_MODULES . '/video-carousel/class-video-carousel.php';
			require_once CAROUSEL_SLIDER_MODULES . '/video-carousel/class-view.php';

			// Image Carousel from Media
			require_once CAROUSEL_SLIDER_MODULES . '/image-carousel/class-meta-box.php';
			require_once CAROUSEL_SLIDER_MODULES . '/image-carousel/class-image-carousel.php';
			require_once CAROUSEL_SLIDER_MODULES . '/image-carousel/class-view.php';

			// Image Carousel from URL
			require_once CAROUSEL_SLIDER_MODULES . '/image-carousel-url/class-meta-box.php';
			require_once CAROUSEL_SLIDER_MODULES . '/image-carousel-url/class-image-carousel-url.php';
			require_once CAROUSEL_SLIDER_MODULES . '/image-carousel-url/class-view.php';

			// Product Carousel
			require_once CAROUSEL_SLIDER_MODULES . '/product-carousel/class-product.php';
			require_once CAROUSEL_SLIDER_MODULES . '/product-carousel/class-meta-box.php';
			require_once CAROUSEL_SLIDER_MODULES . '/product-carousel/class-product-carousel.php';
			require_once CAROUSEL_SLIDER_MODULES . '/product-carousel/class-view.php';
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

		/**
		 * Bail out if the php version is lower than
		 *
		 * @return void
		 */
		public function auto_deactivate() {

			if ( $this->is_supported_php() ) {
				return;
			}

			deactivate_plugins( basename( __FILE__ ) );

			$error = __( '<h2>An Error Occurred</h2>', 'carousel-slider' );
			$error .= __( '<p>Your installed PHP Version is: ', 'carousel-slider' ) . PHP_VERSION . '</p>';
			$error .= __( '<p>The <strong>Carousel Slider</strong> plugin requires PHP version <strong>', 'carousel-slider' ) . $this->min_php . __( '</strong> or greater', 'carousel-slider' );
			$error .= __( '<p>The version of your PHP is ', 'carousel-slider' ) . '<a href="http://php.net/supported-versions.php" target="_blank"><strong>' . __( 'unsupported and old', 'carousel-slider' ) . '</strong></a>.';
			$error .= __( 'You should update your PHP software or contact your host regarding this matter.</p>', 'carousel-slider' );

			wp_die(
				$error,
				__( 'Plugin Activation Error', 'carousel-slider' ),
				array( 'response' => 200, 'back_link' => true )
			);
		}

		/**
		 * Check if the PHP version is supported
		 *
		 * @return bool
		 */
		private function is_supported_php() {
			if ( version_compare( PHP_VERSION, $this->min_php, '>=' ) ) {
				return true;
			}

			return false;
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
