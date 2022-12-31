<?php
/**
 * Plugin Name: Carousel Slider
 * Plugin URI: https://sayfulislam.com/?utm_source=wp-plugins&utm_campaign=plugin-uri&utm_medium=wp-dash
 * Description: <strong>Carousel Slider</strong> allows you to create beautiful, touch enabled, responsive carousels and sliders. It let you create SEO friendly Image carousel from Media Library or from custom URL, Video carousel using Youtube and Vimeo video, Post carousel, Hero banner slider and various types of WooCommerce products carousels.
 * Version: 2.2.0
 * Author: Sayful Islam
 * Author URI: https://sayfulislam.com/?utm_source=wp-plugins&utm_campaign=author-uri&utm_medium=wp-dash
 * Requires PHP: 7.0
 * Requires at least: 5.6
 * Tested up to: 6.1
 *
 * WC requires at least: 3.0
 * WC tested up to: 7.2
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
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Carousel_Slider' ) ) {
	/**
	 * The core plugin class.
	 * This is used to define internationalization, admin-specific hooks, and public-facing site hooks.
	 * Also maintains the unique identifier of this plugin as well as the current version of the plugin.
	 */
	final class Carousel_Slider {

		/**
		 * Plugin name slug
		 *
		 * @var string
		 */
		private $plugin_name = 'carousel-slider';

		/**
		 * Plugin custom post type
		 *
		 * @var string
		 */
		private $post_type = 'carousels';

		/**
		 * Plugin version
		 *
		 * @var string
		 */
		private $version = '2.2.0';

		/**
		 * Minimum PHP version required
		 *
		 * @var string
		 */
		private $min_php = '7.0';

		/**
		 * The instance of the class
		 *
		 * @var self
		 */
		private static $instance;

		/**
		 * Main Carousel_Slider Instance
		 * Ensures only one instance of the class is loaded or can be loaded.
		 *
		 * @return Carousel_Slider - Main instance
		 * @since 1.6.0
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();

				// define plugin constants.
				self::$instance->define_constants();

				// Register autoloader.
				self::$instance->register_autoloader();

				// Check if PHP version is supported for our plugin.
				if ( ! self::$instance->is_supported_php() ) {
					register_activation_hook( __FILE__, [ self::$instance, 'auto_deactivate' ] );
					add_action( 'admin_notices', [ self::$instance, 'php_version_notice' ] );

					return self::$instance;
				}

				do_action( 'carousel_slider/init' );

				// bootstrap plugin main class.
				self::$instance->bootstrap_plugin();

				register_activation_hook( __FILE__, [ self::$instance, 'activation' ] );
				register_deactivation_hook( __FILE__, [ self::$instance, 'deactivation' ] );

				do_action( 'carousel_slider/loaded' );
			}

			return self::$instance;
		}

		/**
		 * Define plugin constants
		 */
		public function define_constants() {
			define( 'CAROUSEL_SLIDER', $this->plugin_name );
			define( 'CAROUSEL_SLIDER_VERSION', $this->version );
			define( 'CAROUSEL_SLIDER_POST_TYPE', $this->post_type );
			define( 'CAROUSEL_SLIDER_FILE', __FILE__ );
			define( 'CAROUSEL_SLIDER_PATH', dirname( CAROUSEL_SLIDER_FILE ) );
			define( 'CAROUSEL_SLIDER_URL', plugins_url( '', CAROUSEL_SLIDER_FILE ) );
			define( 'CAROUSEL_SLIDER_ASSETS', CAROUSEL_SLIDER_URL . '/assets' );
			$this->define_constant( 'CAROUSEL_SLIDER_PRO_PROMOTION', false );
		}

		/**
		 * Define constant if not defined
		 *
		 * @param string $constant_name The constant name.
		 * @param mixed  $value The constant value.
		 *
		 * @return void
		 */
		public function define_constant( string $constant_name, $value ) {
			if ( ! defined( $constant_name ) ) {
				define( $constant_name, $value );
			}
		}

		/**
		 * Load plugin classes
		 */
		private function register_autoloader() {
			if ( file_exists( CAROUSEL_SLIDER_PATH . '/vendor/autoload.php' ) ) {
				include CAROUSEL_SLIDER_PATH . '/vendor/autoload.php';
			} else {
				include_once CAROUSEL_SLIDER_PATH . '/includes/Autoloader.php';

				// instantiate the loader.
				$loader = new CarouselSlider\Autoloader();

				// register the base directories for the namespace prefix.
				$loader->add_namespace( 'CarouselSlider', CAROUSEL_SLIDER_PATH . '/includes' );
				$loader->add_namespace( 'CarouselSlider\Modules', CAROUSEL_SLIDER_PATH . '/modules' );

				// register the autoloader.
				$loader->register();
			}
		}

		/**
		 * Instantiate the required classes
		 *
		 * @return void
		 */
		public function bootstrap_plugin() {
			CarouselSlider\Plugin::init();
		}

		/**
		 * To be run when the plugin is activated
		 *
		 * @return void
		 */
		public function activation() {
			do_action( 'carousel_slider/activation' );
		}

		/**
		 * To be run when the plugin is deactivated
		 *
		 * @return void
		 */
		public function deactivation() {
			do_action( 'carousel_slider/deactivation' );
		}

		/**
		 * Show notice about PHP version
		 *
		 * @return void
		 */
		public function php_version_notice() {

			if ( $this->is_supported_php() || ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$error  = __( 'Your installed PHP Version is: ', 'carousel-slider' ) . PHP_VERSION . '. ';
			$error .= sprintf(
			/* translators: 1: min php version requires */
				__( 'The Carousel Slider plugin requires PHP version %s or greater.', 'carousel-slider' ),
				$this->min_php
			);
			?>
			<div class="error">
				<p><?php printf( esc_html( $error ) ); ?></p>
			</div>
			<?php
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

			deactivate_plugins( plugin_basename( __FILE__ ) );

			$error  = '<h1>' . __( 'An Error Occurred', 'carousel-slider' ) . '</h1>';
			$error .= '<h2>' . __( 'Your installed PHP Version is: ', 'carousel-slider' ) . PHP_VERSION . '</h2>';
			/* translators: 1: min php version requires */
			$error .= '<p>' . sprintf( __( 'The Carousel Slider plugin requires PHP version %s or greater', 'carousel-slider' ), $this->min_php ) . '</p>';
			$error .= '<p>' . sprintf(
				/* translators: 1: php doc page link start, 2: php doc page link end */
				__( 'The version of your PHP is %1$s unsupported and old %2$s. ', 'carousel-slider' ),
				'<a href="https://php.net/supported-versions.php" target="_blank"><strong>',
				'</strong></a>'
			);
			$error .= __( 'You should update your PHP software or contact your host regarding this matter.', 'carousel-slider' ) . '</p>';

			$title = __( 'Plugin Activation Error', 'carousel-slider' );
			wp_die( wp_kses_post( $error ), esc_html( $title ), [ 'back_link' => true ] );
		}

		/**
		 * Check if the PHP version is supported
		 *
		 * @return bool
		 */
		private function is_supported_php() {
			return ! version_compare( PHP_VERSION, $this->min_php, '<=' );
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
