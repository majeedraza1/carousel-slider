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

		public function navigation_settings_callback( $post ) {
			$_nav_button       = get_post_meta( $post->ID, '_nav_button', true );
			$_nav_button       = in_array( $_nav_button, array( 'on', 'off' ) ) ? $_nav_button : 'on';
			$_dot_nav          = get_post_meta( $post->ID, '_dot_nav', true );
			$_dot_nav          = in_array( $_dot_nav, array( 'on', 'off' ) ) ? $_dot_nav : 'off';
			$_nav_color        = get_post_meta( $post->ID, '_nav_color', true );
			$_nav_color        = empty( $_nav_color ) ? '#f1f1f1' : $_nav_color;
			$_nav_active_color = get_post_meta( $post->ID, '_nav_active_color', true );
			$_nav_active_color = empty( $_nav_active_color ) ? '#00d1b2' : $_nav_active_color;
			?>
            <p>
                <label for="_nav_button">
                    <input type="hidden" name="carousel_slider[_nav_button]" value="off">
                    <input type="checkbox" value="on" id="_nav_button" name="carousel_slider[_nav_button]"
						<?php checked( $_nav_button, 'on' ); ?>>
					<?php esc_html_e( 'Enable next/prev navigation icons', 'carousel-slider' ); ?>
                </label>
            </p>
            <p>
                <label for="_dot_nav">
                    <input type="hidden" name="carousel_slider[_dot_nav]" value="off">
                    <input type="checkbox" value="on" name="carousel_slider[_dot_nav]" id="_dot_nav"
						<?php checked( $_dot_nav, 'on' ); ?>>
					<?php esc_html_e( 'Enable dots navigation', 'carousel-slider' ); ?>
                </label>
            </p>
            <p>
                <label for="_nav_color">
                    <strong><?php esc_html_e( 'Navigation & Dots Color', 'carousel-slider' ); ?></strong>
                </label>
                <span class="cs-tooltip"
                      title="<?php esc_html_e( 'Pick a color for navigation and dots.', 'carousel-slider' ); ?>"></span>
                <br>
                <input type="text" class="colorpicker" value="<?php echo $_nav_color; ?>" id="_nav_color"
                       name="carousel_slider[_nav_color]" data-default-color="#f1f1f1">
            </p>
            <p>
                <label for="_nav_active_color">
                    <strong><?php esc_html_e( 'Navigation & Dots Hover Color', 'carousel-slider' ); ?></strong>
                </label>
                <span class="cs-tooltip"
                      title="<?php esc_html_e( 'Pick a color for navigation and dots for active and hover effect.', 'carousel-slider' ); ?>"></span>
                <br>
                <input type="text" class="colorpicker" value="<?php echo $_nav_active_color; ?>" id="_nav_active_color"
                       name="carousel_slider[_nav_active_color]" data-default-color="#00d1b2">
            </p>
			<?php
		}

		/**
		 * @param WP_Post $post
		 */
		public function autoplay_settings_callback( $post ) {
			$_autoplay         = get_post_meta( $post->ID, '_autoplay', true );
			$_autoplay         = in_array( $_autoplay, array( 'on', 'off' ) ) ? $_autoplay : 'on';
			$_autoplay_pause   = get_post_meta( $post->ID, '_autoplay_pause', true );
			$_autoplay_pause   = in_array( $_autoplay_pause, array( 'on', 'off' ) ) ? $_autoplay : 'off';
			$_autoplay_timeout = get_post_meta( $post->ID, '_autoplay_timeout', true );
			$_autoplay_timeout = $_autoplay_timeout ? absint( $_autoplay_timeout ) : 5000;
			$_autoplay_speed   = get_post_meta( $post->ID, '_autoplay_speed', true );
			$_autoplay_speed   = $_autoplay_speed ? absint( $_autoplay_speed ) : 500;
			?>
            <p>
                <label for="_autoplay">
                    <input type="hidden" name="carousel_slider[_autoplay]" value="off">
                    <input type="checkbox" value="on" id="_autoplay" name="carousel_slider[_autoplay]"
						<?php checked( $_autoplay, 'on' ); ?>>
					<?php esc_html_e( 'Enable autoplay', 'carousel-slider' ); ?>
                </label>
            </p>
            <p>
                <label for="_autoplay_pause">
                    <input type="hidden" name="carousel_slider[_autoplay_pause]" value="off">
                    <input type="checkbox" value="on" name="carousel_slider[_autoplay_pause]" id="_autoplay_pause"
						<?php checked( $_autoplay_pause, 'on' ); ?>>
					<?php esc_html_e( 'Pause autoplay on mouse hover', 'carousel-slider' ); ?>
                </label>
            </p>
            <p>
                <label for="_autoplay_timeout">
                    <strong><?php esc_html_e( 'Autoplay Timeout', 'carousel-slider' ); ?></strong>
                </label>
                <input type="number" name="carousel_slider[_autoplay_timeout]" id="_autoplay_timeout" class="small-text"
                       value="<?php echo $_autoplay_timeout; ?>">
                <span class="cs-tooltip"
                      title="<?php esc_html_e( 'Automatic play interval timeout in millisecond. Default: 5000', 'carousel-slider' ); ?>"></span>
            </p><!-- Autoplay Timeout -->
            <p>
                <label for="_autoplay_speed">
                    <strong><?php esc_html_e( 'Autoplay Speed', 'carousel-slider' ); ?></strong>
                </label>
                <input type="number" name="carousel_slider[_autoplay_speed]" id="_autoplay_speed" class="small-text"
                       value="<?php echo $_autoplay_speed; ?>">
                <span class="cs-tooltip"
                      title="<?php esc_html_e( 'Automatic play speed in millisecond. Default: 500', 'carousel-slider' ); ?>"></span>
            </p><!-- Columns -->
			<?php
		}

		/**
		 * Renders the meta box.
		 *
		 * @param WP_Post $post
		 */
		public function responsive_settings_callback( $post ) {
			$_items = get_post_meta( $post->ID, '_items', true );
			$_items = $_items ? absint( $_items ) : 4;

			$_items_desktop = get_post_meta( $post->ID, '_items_desktop', true );
			$_items_desktop = $_items_desktop ? absint( $_items_desktop ) : 4;

			$_items_small_desktop = get_post_meta( $post->ID, '_items_small_desktop', true );
			$_items_small_desktop = $_items_small_desktop ? absint( $_items_small_desktop ) : 4;

			$_items_tablet = get_post_meta( $post->ID, '_items_portrait_tablet', true );
			$_items_tablet = $_items_tablet ? absint( $_items_tablet ) : 3;

			$_items_small_tablet = get_post_meta( $post->ID, '_items_small_portrait_tablet', true );
			$_items_small_tablet = $_items_small_tablet ? absint( $_items_small_tablet ) : 2;

			$_items_mobile = get_post_meta( $post->ID, '_items_portrait_mobile', true );
			$_items_mobile = $_items_mobile ? absint( $_items_mobile ) : 1;
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
