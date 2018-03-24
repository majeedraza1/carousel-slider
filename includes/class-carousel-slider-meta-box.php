<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Carousel_Slider_Meta_Box' ) ):

	class Carousel_Slider_Meta_Box {

		protected static $instance = null;
		private $post_type = 'carousels';
		private $form;

		/**
		 * Ensures only one instance of this class is loaded or can be loaded.
		 *
		 * @return Carousel_Slider_Meta_Box
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

			$this->form = new Carousel_Slider_Form();
		}

		/**
		 * Add carousel slider meta box
		 */
		public function add_meta_boxes() {
			add_meta_box(
				"carousel-slider-meta-boxes",
				__( "Carousel Slider", 'carousel-slider' ),
				array( $this, 'carousel_slider_meta_boxes' ),
				"carousels",
				"normal",
				"high"
			);
			add_meta_box(
				"carousel-slider-usages-info",
				__( "Usage (Shortcode)", 'carousel-slider' ),
				array( $this, 'usages_callback' ),
				"carousels",
				"side",
				"high"
			);
			add_meta_box(
				"carousel-slider-navigation-settings",
				__( "Navigation Settings", 'carousel-slider' ),
				array( $this, 'navigation_settings_callback' ),
				$this->post_type,
				"side",
				"low"
			);
			add_meta_box(
				"carousel-slider-autoplay-settings",
				__( "Autoplay Settings", 'carousel-slider' ),
				array( $this, 'autoplay_settings_callback' ),
				$this->post_type,
				"side",
				"low"
			);
			add_meta_box(
				"carousel-slider-responsive-settings",
				__( "Responsive Settings", 'carousel-slider' ),
				array( $this, 'responsive_settings_callback' ),
				$this->post_type,
				"side",
				"low"
			);
			add_meta_box(
				"carousel-slider-general-settings",
				__( "General Settings", 'carousel-slider' ),
				array( $this, 'general_settings_callback' ),
				$this->post_type,
				"advanced",
				"low"
			);
		}

		/**
		 * Load meta box content
		 *
		 * @param WP_Post $post
		 */
		public function carousel_slider_meta_boxes( $post ) {
			wp_nonce_field( 'carousel_slider_nonce', '_carousel_slider_nonce' );

			$slide_type = get_post_meta( $post->ID, '_slide_type', true );
			$slide_type = in_array( $slide_type, carousel_slider_slide_type() ) ? $slide_type : 'image-carousel';

			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/types.php';
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/images-media.php';
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/images-url.php';
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/post-carousel.php';
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/product-carousel.php';
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/video-carousel.php';
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/hero-banner-slider.php';
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/images-settings.php';
		}

		/**
		 * Renders the meta box.
		 *
		 * @param WP_Post $post
		 */
		public function general_settings_callback( $post ) {
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/general.php';
		}

		/**
		 * Renders the meta box.
		 *
		 * @param WP_Post $post
		 */
		public function navigation_settings_callback( $post ) {
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/navigation.php';
		}

		/**
		 * Renders the meta box.
		 *
		 * @param WP_Post $post
		 */
		public function autoplay_settings_callback( $post ) {
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/autoplay.php';
		}

		/**
		 * Renders the meta box.
		 *
		 * @param WP_Post $post
		 */
		public function responsive_settings_callback( $post ) {
			$_settings = Carousel_Slider_Setting::responsive( $post->ID );
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/responsive.php';
		}

		/**
		 * Render short code meta box content
		 *
		 * @param WP_Post $post
		 */
		public function usages_callback( $post ) {
			ob_start(); ?>
            <p><strong>
					<?php esc_html_e( 'Copy the following shortcode and paste in post or page where you want to show.', 'carousel-slider' ); ?>
                </strong>
            </p>
            <input
                    type="text"
                    onmousedown="this.clicked = 1;"
                    onfocus="if (!this.clicked) this.select(); else this.clicked = 2;"
                    onclick="if (this.clicked === 2) this.select(); this.clicked = 0;"
                    value="[carousel_slide id='<?php echo $post->ID; ?>']"
                    style="background-color: #f1f1f1; width: 100%; padding: 8px;"
            >
			<?php echo ob_get_clean();
		}
	}
endif;

Carousel_Slider_Meta_Box::init();
