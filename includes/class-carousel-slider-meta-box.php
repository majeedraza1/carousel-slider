<?php
if ( ! class_exists( 'Carousel_Slider_Meta_Box' ) ):
	class Carousel_Slider_Meta_Box {

		protected static $instance = null;
		private $post_type = 'carousels';

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
		}

		/**
		 * Add carousel slider meta box
		 */
		public function add_meta_boxes() {
			add_meta_box(
				"carousel-slider-responsive-settings",
				__( "Responsive Settings", 'carousel-slider' ),
				array( $this, 'responsive_settings_callback' ),
				$this->post_type,
				"side",
				"low"
			);
		}

		/**
		 * Renders the meta box.
		 *
		 * @param WP_Post $post
		 */
		public function responsive_settings_callback( $post ) {
			$_items               = absint( get_post_meta( $post->ID, '_items', true ) );
			$_items_desktop       = absint( get_post_meta( $post->ID, '_items_desktop', true ) );
			$_items_small_desktop = absint( get_post_meta( $post->ID, '_items_small_desktop', true ) );
			$_items_tablet        = absint( get_post_meta( $post->ID, '_items_portrait_tablet', true ) );
			$_items_small_tablet  = absint( get_post_meta( $post->ID, '_items_small_portrait_tablet', true ) );
			$_items_mobile        = absint( get_post_meta( $post->ID, '_items_portrait_mobile', true ) );
			?>
            <p>
                <label for="_items">
                    <strong><?php esc_html_e( 'Columns', 'carousel-slider' ); ?></strong>
                </label>
                <input type="number" name="carousel_slider[_items]" id="_items" class="small-text"
                       value="<?php echo $_items; ?>">
                <span class="cs-tooltip"
                      title="<?php esc_html_e( 'The number of items you want to see on the Extra Large Desktop Layout (Screens size greater than 1921 pixels DP)', 'carousel-slider' ); ?>"></span>
            </p><!-- Columns -->
            <p>
                <label for="_items_desktop">
                    <strong><?php esc_html_e( 'Columns : Desktop', 'carousel-slider' ); ?></strong>
                </label>
                <input type="number" name="carousel_slider[_items_desktop]" id="_items_desktop" class="small-text"
                       value="<?php echo $_items_desktop; ?>">
                <span class="cs-tooltip"
                      title="<?php esc_html_e( 'The number of items you want to see on the Desktop Layout (Screens size from 1200 pixels DP to 1920 pixels DP)', 'carousel-slider' ); ?>"></span>
            </p><!-- Columns : Desktop -->
            <p>
                <label for="_items_small_desktop">
                    <strong><?php esc_html_e( 'Columns : Small Desktop', 'carousel-slider' ); ?></strong>
                </label>
                <input type="number" name="carousel_slider[_items_small_desktop]" id="_items_small_desktop"
                       class="small-text"
                       value="<?php echo $_items_small_desktop; ?>">
                <span class="cs-tooltip"
                      title="<?php esc_html_e( 'The number of items you want to see on the Small Desktop Layout (Screens size from 993 pixels DP to 1199 pixels DP)', 'carousel-slider' ); ?>"></span>
            </p><!-- Columns : Small Desktop -->
            <p>
                <label for="_items_portrait_tablet">
                    <strong><?php esc_html_e( 'Columns : Tablet', 'carousel-slider' ); ?></strong>
                </label>
                <input type="number" name="carousel_slider[_items_portrait_tablet]" id="_items_portrait_tablet"
                       class="small-text"
                       value="<?php echo $_items_tablet; ?>">
                <span class="cs-tooltip"
                      title="<?php esc_html_e( 'The number of items you want to see on the Tablet Layout (Screens size from 768 pixels DP to 992 pixels DP)', 'carousel-slider' ); ?>"></span>
            </p><!-- Columns : Tablet -->
            <p>
                <label for="_items_small_portrait_tablet">
                    <strong><?php esc_html_e( 'Columns : Small Tablet', 'carousel-slider' ); ?></strong>
                </label>
                <input type="number" name="carousel_slider[_items_small_portrait_tablet]"
                       id="_items_small_portrait_tablet"
                       class="small-text"
                       value="<?php echo $_items_small_tablet; ?>">
                <span class="cs-tooltip"
                      title="<?php esc_html_e( 'The number of items you want to see on the Small Tablet Layout(Screens size from 600 pixels DP to 767 pixels DP)', 'carousel-slider' ); ?>"></span>
            </p><!-- Columns : Small Tablet -->
            <p>
                <label for="_items_portrait_mobile">
                    <strong><?php esc_html_e( 'Columns : Mobile', 'carousel-slider' ); ?></strong>
                </label>
                <input type="number" name="carousel_slider[_items_portrait_mobile]"
                       id="_items_portrait_mobile"
                       class="small-text"
                       value="<?php echo $_items_mobile; ?>">
                <span class="cs-tooltip"
                      title="<?php esc_html_e( 'The number of items you want to see on the Mobile Layout (Screens size from 320 pixels DP to 599 pixels DP)', 'carousel-slider' ); ?>"></span>
            </p><!-- Columns : Mobile -->
			<?php
		}
	}
endif;

Carousel_Slider_Meta_Box::init();
