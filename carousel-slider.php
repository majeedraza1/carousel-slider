<?php
/**
 * Plugin Name: Carousel Slider
 * Plugin URI: http://wordpress.org/plugins/carousel-slider
 * Description: The Easiest Way to Create SEO friendly Image, Logo, Video, Post and WooCommerce Product Carousel.
 * Version: 1.8.9
 * Author: Sayful Islam
 * Author URI: https://sayfulislam.com
 * Requires at least: 4.4
 * Tested up to: 4.9
 *
 * WC requires at least: 2.5
 * WC tested up to: 3.4
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
if ( ! defined( 'ABSPATH' ) ) {
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
		private $version = '1.8.9';

		/**
		 * Minimum PHP version required
		 *
		 * @var string
		 */
		private $min_php = '5.3.0';

		/**
		 * Holds various class instances
		 *
		 * @var array
		 */
		private $container = array();

		/**
		 * @var object
		 */
		private static $instance;

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

				// Define plugin constants
				self::$instance->define_constants();

				// Check if PHP version is supported for our plugin
				if ( ! self::$instance->is_supported_php() ) {
					register_activation_hook( __FILE__, array( self::$instance, 'auto_deactivate' ) );
					add_action( 'admin_notices', array( self::$instance, 'php_version_notice' ) );

					return self::$instance;
				}

				// Load plugin textdomain
				add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
				add_filter( 'admin_footer_text', array( self::$instance, 'admin_footer_text' ) );

				// Register autoload for plugin classes
				self::$instance->register_autoload();

				// initialize the classes
				self::$instance->init_classes();

				register_activation_hook( __FILE__, array( self::$instance, 'activation' ) );
				register_deactivation_hook( __FILE__, array( self::$instance, 'deactivation' ) );

				do_action( 'carousel_slider/loaded' );
			}

			return self::$instance;
		}

		/**
		 * Define plugin constants
		 */
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
		 * Include classes
		 */
		private function register_autoload() {
			spl_autoload_register( function ( $className ) {
				if ( class_exists( $className ) ) {
					return;
				}
				// project-specific namespace prefix
				$prefix = 'CarouselSlider\\';
				// base directory for the namespace prefix
				$base_dir = CAROUSEL_SLIDER_INCLUDES . DIRECTORY_SEPARATOR;
				// does the class use the namespace prefix?
				$len = strlen( $prefix );
				if ( strncmp( $prefix, $className, $len ) !== 0 ) {
					// no, move to the next registered autoloader
					return;
				}
				// get the relative class name
				$relative_class = substr( $className, $len );
				// replace the namespace prefix with the base directory, replace namespace
				// separators with directory separators in the relative class name, append
				// with .php
				$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';
				// if the file exists, require it
				if ( file_exists( $file ) ) {
					require_once $file;
				}
			} );
		}

		/**
		 * Instantiate the required classes
		 *
		 * @return void
		 */
		private function init_classes() {
			$this->container['activator'] = \CarouselSlider\Activator::init();
			$this->container['script']    = \CarouselSlider\Script::init();
			$this->container['preview']   = \CarouselSlider\Display\Preview::init();
			$this->container['product']   = \CarouselSlider\Product::init();

			if ( $this->is_request( 'admin' ) ) {
				$this->container['admin']      = \CarouselSlider\Admin\Admin::init();
				$this->container['metabox']    = \CarouselSlider\Admin\MetaBox::init();
				$this->container['vc-element'] = \CarouselSlider\Admin\VisualComposerElement::init();
				$this->container['doc']        = \CarouselSlider\Admin\Documentation::init();
				$this->container['admin-ajax'] = \CarouselSlider\Admin\Ajax::init();
			}

			if ( $this->is_request( 'frontend' ) ) {
				$this->container['structured-data'] = \CarouselSlider\Display\StructuredData::init();
				$this->container['shortcode']       = \CarouselSlider\Display\Shortcode::init();
			}

			// Widgets
			add_action( 'widgets_init', array( 'CarouselSlider\\Widgets\\CarouselSlider', 'register' ) );

			// Product quick view
			add_action( 'wp_ajax_carousel_slider_quick_view', array( 'CarouselSlider\\QuickView', 'product' ) );
			add_action( 'wp_ajax_nopriv_carousel_slider_quick_view', array( 'CarouselSlider\\QuickView', 'product' ) );
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

		/**
		 * Add custom footer text on plugins page.
		 *
		 * @param string $text
		 *
		 * @return string
		 */
		public function admin_footer_text( $text ) {
			global $post_type, $hook_suffix;

			$footer_text = sprintf(
				__( 'If you like %1$s Carousel Slider %2$s please leave us a %3$s rating. A huge thanks in advance!', 'carousel-slider' ),
				'<strong>',
				'</strong>',
				'<a href="https://wordpress.org/support/view/plugin-reviews/carousel-slider?filter=5#postform" target="_blank" data-rated="Thanks :)">&starf;&starf;&starf;&starf;&starf;</a>'
			);

			if ( $post_type == 'carousels' || $hook_suffix == 'carousels_page_carousel-slider-documentation' ) {
				return $footer_text;
			}

			return $text;
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
		 * Show notice about PHP version
		 *
		 * @return void
		 */
		public function php_version_notice() {

			if ( $this->is_supported_php() || ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$error = __( 'Your installed PHP Version is: ', 'carousel-slider' ) . PHP_VERSION . '. ';
			$error .= sprintf( __( 'The Carousel Slider plugin requires PHP version %s or greater.',
				'carousel-slider' ), $this->min_php );
			?>
            <div class="error">
                <p><?php printf( $error ); ?></p>
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

			$error = '<h1>' . __( 'An Error Occurred', 'carousel-slider' ) . '</h1>';
			$error .= '<h2>' . __( 'Your installed PHP Version is: ', 'carousel-slider' ) . PHP_VERSION . '</h2>';
			$error .= '<p>' . sprintf( __( 'The Carousel Slider plugin requires PHP version %s or greater',
					'carousel-slider' ), $this->min_php ) . '</p>';
			$error .= '<p>' . sprintf( __( 'The version of your PHP is %s unsupported and old %s. ',
					'carousel-slider' ),
					'<a href="http://php.net/supported-versions.php" target="_blank"><strong>',
					'</strong></a>'
				);
			$error .= __( 'You should update your PHP software or contact your host regarding this matter.',
					'carousel-slider' ) . '</p>';

			wp_die( $error, __( 'Plugin Activation Error', 'carousel-slider' ), array( 'back_link' => true ) );
		}

		/**
		 * What type of request is this?
		 *
		 * @param  string $type admin, ajax, cron or frontend.
		 *
		 * @return bool
		 */
		public function is_request( $type ) {
			switch ( $type ) {
				case 'admin':
					return is_admin();
				case 'ajax':
					return defined( 'DOING_AJAX' );
				case 'cron':
					return defined( 'DOING_CRON' );
				case 'frontend':
					return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
			}

			return false;
		}

		/**
		 * Check if the PHP version is supported
		 *
		 * @param null $min_php
		 *
		 * @return bool
		 */
		private function is_supported_php( $min_php = null ) {
			$min_php = $min_php ? $min_php : $this->min_php;

			if ( version_compare( PHP_VERSION, $min_php, '<=' ) ) {
				return false;
			}

			return true;
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
