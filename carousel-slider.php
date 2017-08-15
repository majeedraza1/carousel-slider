<?php
/*
Plugin Name: 	Carousel Slider
Plugin URI: 	http://wordpress.org/plugins/carousel-slider
Description: 	The Easiest Way to Create SEO friendly Image, Logo, Video, Post and WooCommerce Product Carousel.
Version: 		1.7.2
Author: 		Sayful Islam
Author URI: 	https://sayfulislam.com
Text Domain: 	carousel-slider
Domain Path: 	/languages/
License: 		GPLv2 or later
License URI:	http://www.gnu.org/licenses/gpl-2.0.txt
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Define Carousel Slider Version
if ( ! defined( 'CAROUSEL_SLIDER_VERSION' ) ) {
	define( 'CAROUSEL_SLIDER_VERSION', '1.7.2' );
}

if ( ! class_exists( 'Carousel_Slider' ) ):

	class Carousel_Slider {
		private $plugin_name;
		private $plugin_version;
		private $plugin_url;
		private $plugin_path;

		protected static $instance = null;

		/**
		 * Main Carousel_Slider Instance
		 *
		 * Ensures only one instance of Carousel_Slider is loaded or can be loaded.
		 *
		 * @since 1.6.0
		 * @static
		 * @see Carousel_Slider()
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
			$this->plugin_name    = 'carousel-slider';
			$this->plugin_version = CAROUSEL_SLIDER_VERSION;

			register_activation_hook( __FILE__, array( $this, 'activation' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ), 15 );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 10 );
			add_action( 'wp_footer', array( $this, 'inline_script' ), 30 );
			add_action( 'init', array( $this, 'load_textdomain' ) );
			add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ) );
			$this->includes();
		}

		/**
		 * Load plugin textdomain
		 */
		public function load_textdomain() {
			// Set filter for plugin's languages directory
			$lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), 'carousel-slider' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'carousel-slider', $locale );

			// Setup paths to current locale file
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/carousel-slider/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/carousel-slider folder
				load_textdomain( $this->plugin_name, $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/carousel-slider/languages/ folder
				load_textdomain( $this->plugin_name, $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( $this->plugin_name, false, $lang_dir );
			}
		}

		/**
		 * To be run when the plugin is activated
		 * @return void
		 */
		public function activation() {
			require_once $this->plugin_path() . '/includes/class-carousel-slider-activation.php';
			Carousel_Slider_Activation::activate();
		}

		/**
		 * To be run when the plugin is deactivated
		 * @return void
		 */
		public function deactivation() {
			flush_rewrite_rules();
		}

		/**
		 * Include admin and front facing files
		 */
		public function includes() {
			if ( is_admin() ) {
				$this->admin_includes();
			}
			if ( ! is_admin() ) {
				$this->frontend_includes();
			}

			require_once $this->plugin_path() . '/widgets/widget-carousel_slider.php';
			require_once $this->plugin_path() . '/includes/class-carousel-slider-product.php';
		}

		/**
		 * Include admin files
		 */
		public function admin_includes() {
			require_once $this->plugin_path() . '/includes/class-carousel-slider-vc-element.php';
			require_once $this->plugin_path() . '/includes/class-carousel-slider-documentation.php';
			require_once $this->plugin_path() . '/includes/class-carousel-slider-form.php';
			require_once $this->plugin_path() . '/includes/class-carousel-slider-admin.php';

			new Carousel_Slider_Admin( $this->plugin_path(), $this->plugin_url() );
		}

		/**
		 * Load front facing files
		 */
		public function frontend_includes() {
			require_once $this->plugin_path() . '/shortcodes/class-carousel-slider-shortcode.php';
			require_once $this->plugin_path() . '/shortcodes/class-carousel-slider-deprecated-shortcode.php';
			require_once $this->plugin_path() . '/includes/class-carousel-slider-structured-data.php';

			new Carousel_Slider_Shortcode( $this->plugin_path(), $this->plugin_url() );
			new Carousel_Slider_Deprecated_Shortcode( $this->plugin_path() );
		}

		/**
		 * Load frontend scripts
		 */
		public function frontend_scripts() {
			wp_register_style( $this->plugin_name, $this->plugin_url() . '/assets/css/style.css', array(), $this->plugin_version, 'all' );
			wp_register_script( 'owl-carousel', $this->plugin_url() . '/assets/js/owl.carousel.min.js', array( 'jquery' ), '2.2.0', true );
			wp_register_script( 'magnific-popup', $this->plugin_url() . '/assets/js/jquery.magnific-popup.min.js', array(), '1.1.0', true );

			if ( $this->should_load_scripts() ) {
				wp_enqueue_style( $this->plugin_name );
				wp_enqueue_script( 'owl-carousel' );
			}
		}

		/**
		 * Load admin scripts
		 * @param $hook
		 */
		public function admin_scripts( $hook ) {
			global $post;

			if ( $hook == 'post-new.php' || $hook == 'post.php' ) {

				if ( is_a( $post, 'WP_Post' ) && 'carousels' == $post->post_type ) {

					wp_enqueue_media();
					wp_enqueue_style( 'wp-color-picker' );
					wp_enqueue_style( $this->plugin_name . '-admin', $this->plugin_url() . '/assets/css/admin.css', array(), $this->plugin_version, 'all' );
					wp_enqueue_script( 'select2', $this->plugin_url() . '/assets/js/select2.min.js', array( 'jquery' ), '4.0.3', true );
					wp_enqueue_script( 'livequery', $this->plugin_url() . '/assets/js/jquery.livequery.js', array( 'jquery' ), '1.3.6', true );
					wp_enqueue_script( $this->plugin_name . '-admin', $this->plugin_url() . '/assets/js/admin.js', array( 'jquery', 'wp-color-picker', 'jquery-ui-accordion', 'jquery-ui-datepicker', 'jquery-ui-sortable', 'select2', 'livequery' ), $this->plugin_version, true );

					wp_localize_script( $this->plugin_name . '-admin', 'CarouselSlider', array(
						'post_id'           => $post->ID,
						'image_ids'         => get_post_meta( $post->ID, '_wpdh_image_ids', true ),
						'nonce'             => wp_create_nonce( 'carousel_slider_ajax' ),
						'create_btn_text'   => __( 'Create Gallery', 'carousel-slider' ),
						'edit_btn_text'     => __( 'Edit Gallery', 'carousel-slider' ),
						'save_btn_text'     => __( 'Save Gallery', 'carousel-slider' ),
						'progress_btn_text' => __( 'Saving...', 'carousel-slider' ),
						'insert_btn_text'   => __( 'Insert', 'carousel-slider' ),
					) );
				}
			}
		}

		/**
		 * Load front end inline script
		 */
		public function inline_script() {
			if ( $this->should_load_scripts() ):
				?>
				<script type="text/javascript">
					jQuery(document).ready(function ($) {

						$('body').find('.carousel-slider').each(function () {
							var _this = $(this);
							var isVideo = _this.data('slide-type') == 'video-carousel' ? true : false;
							var videoWidth = isVideo ? _this.data('video-width') : false;
							var videoHeight = isVideo ? _this.data('video-height') : false;
							var autoWidth = isVideo ? true : false;

					    	if (jQuery().magnificPopup) {
					    		var popupType = _this.data('slide-type') == 'product-carousel' ? 'ajax' : 'image';
					    		var popupGallery = _this.data('slide-type') != 'product-carousel' ? true : false;
						    	$(this).find('.magnific-popup').magnificPopup({
						    		type: popupType,
						    		gallery:{
									    enabled: popupGallery
									},
									zoom: {
									    enabled: popupGallery,
									    duration: 300,
									    easing: 'ease-in-out'
									}
						    	});
					    	}
					    	
							if (jQuery().owlCarousel) {
								_this.owlCarousel({
									nav: _this.data('nav'),
									dots: _this.data('dots'),
									margin: _this.data('margin'),
									loop: _this.data('loop'),
									autoplay: _this.data('autoplay'),
									autoplayTimeout: _this.data('autoplay-timeout'),
									autoplaySpeed: _this.data('autoplay-speed'),
									autoplayHoverPause: _this.data('autoplay-hover-pause'),
									slideBy: _this.data('slide-by'),
									lazyLoad: _this.data('lazy-load'),
									video: isVideo,
									videoWidth: videoWidth,
									videoHeight: videoHeight,
									autoWidth: autoWidth,
									navText: [_this.data('nav-previous-icon'), _this.data('nav-next-icon')],
									responsive: {
										320: {items: _this.data('colums-mobile')},
										600: {items: _this.data('colums-small-tablet')},
										768: {items: _this.data('colums-tablet')},
										993: {items: _this.data('colums-small-desktop')},
										1200: {items: _this.data('colums-desktop')},
										1921: {items: _this.data('colums')}
									}
								});
							}
						});
					});
				</script><?php
			endif;
		}

		/**
		 * Check if it should load frontend scripts
		 *
		 * @return mixed|void
		 */
		private function should_load_scripts() {
			global $post;
			$load_scripts = is_active_widget( false, false, 'widget_carousel_slider', true ) || ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'carousel_slide' ) ) || ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'carousel' ) );

			return apply_filters( 'carousel_slider_load_scripts', $load_scripts );
		}

		/**
		 * Add custom footer text on plugins page.
		 *
		 * @param string $text
		 */
		public function admin_footer_text( $text )
		{
			global $post_type, $hook_suffix;

			$footer_text = sprintf(__('If you like %1$s Carousel Slider %2$s please leave us a %3$s rating. A huge thanks in advance!', 'carousel-slider' ), '<strong>', '</strong>', '<a href="https://wordpress.org/support/view/plugin-reviews/carousel-slider?filter=5#postform" target="_blank" data-rated="Thanks :)">&starf;&starf;&starf;&starf;&starf;</a>');

			if ($post_type == 'carousels' || $hook_suffix == 'carousels_page_carousel-slider-documentation') {
				return $footer_text;
			}

			return $text;
		}

		/**
		 * Plugin path.
		 *
		 * @return string Plugin path
		 */
		private function plugin_path() {
			if ( $this->plugin_path ) {
				return $this->plugin_path;
			}

			return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

		/**
		 * Plugin url.
		 *
		 * @return string Plugin url
		 */
		private function plugin_url() {
			if ( $this->plugin_url ) {
				return $this->plugin_url;
			}

			return $this->plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );
		}
	}

endif;

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
Carousel_Slider::instance();
