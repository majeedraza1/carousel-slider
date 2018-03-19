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
		 * @param WP_Post $post
		 */
		public function general_settings_callback( $post ) {
			$this->form = new Carousel_Slider_Form();
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/general.php';
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

		public function navigation_settings_callback() {
			 require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/navigation.php';
		}

		/**
		 * @param WP_Post $post
		 */
		public function autoplay_settings_callback( $post ) {
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/autoplay.php';
		}

		/**
		 * Renders the meta box.
		 */
		public function responsive_settings_callback() {
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/responsive.php';
		}
	}
endif;

Carousel_Slider_Meta_Box::init();
