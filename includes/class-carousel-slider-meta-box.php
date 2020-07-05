<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

if ( ! class_exists( 'Carousel_Slider_Meta_Box' ) ):

	class Carousel_Slider_Meta_Box {

		/**
		 * The instance of the class
		 *
		 * @var self
		 */
		private static $instance = null;

		/**
		 * Post type
		 *
		 * @var string
		 */
		private $post_type;

		/**
		 * @var Carousel_Slider_Form
		 */
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
			$this->post_type = CAROUSEL_SLIDER_POST_TYPE;
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

		public function navigation_settings_callback( $post ) {
			$_nav_button = get_post_meta( $post->ID, '_nav_button', true );
			$_nav_button = in_array( $_nav_button, array( 'on', 'off', 'always' ) ) ? $_nav_button : 'on';

			$_dot_nav = get_post_meta( $post->ID, '_dot_nav', true );
			$_dot_nav = in_array( $_dot_nav, array( 'on', 'off', 'hover' ) ) ? $_dot_nav : 'off';

			$_slide_by = get_post_meta( $post->ID, '_slide_by', true );
			$_slide_by = empty( $_slide_by ) ? 1 : $_slide_by;

			$_nav_color = get_post_meta( $post->ID, '_nav_color', true );
			$_nav_color = empty( $_nav_color ) ? '#f1f1f1' : $_nav_color;

			$_nav_active_color = get_post_meta( $post->ID, '_nav_active_color', true );
			$_nav_active_color = empty( $_nav_active_color ) ? '#00d1b2' : $_nav_active_color;

			$_arrow_position = get_post_meta( $post->ID, '_arrow_position', true );
			$_arrow_position = empty( $_arrow_position ) ? 'outside' : $_arrow_position;

			$_arrow_size = get_post_meta( $post->ID, '_arrow_size', true );
			$_arrow_size = empty( $_arrow_size ) ? 48 : absint( $_arrow_size );

			$_bullet_size = get_post_meta( $post->ID, '_bullet_size', true );
			$_bullet_size = empty( $_bullet_size ) ? 10 : absint( $_bullet_size );

			$_bullet_position = get_post_meta( $post->ID, '_bullet_position', true );
			$_bullet_position = empty( $_bullet_position ) ? 'center' : $_bullet_position;

			$_bullet_shape = get_post_meta( $post->ID, '_bullet_shape', true );
			$_bullet_shape = empty( $_bullet_shape ) ? 'square' : $_bullet_shape;
			?>
            <p>
                <label for="_nav_button">
                    <strong><?php esc_html_e( 'Show Arrow Nav', 'carousel-slider' ); ?></strong>
                </label>
                <select name="carousel_slider[_nav_button]" id="_nav_button" class="small-text">
                    <option value="off" <?php selected( $_nav_button, 'off' ); ?>><?php esc_html_e( 'Never', 'carousel-slider' ); ?></option>
                    <option value="on" <?php selected( $_nav_button, 'on' ); ?>><?php esc_html_e( 'Mouse Over', 'carousel-slider' ); ?></option>
                    <option value="always" <?php selected( $_nav_button, 'always' ); ?>><?php esc_html_e( 'Always', 'carousel-slider' ); ?></option>
                </select>
                <span class="cs-tooltip"
                      title="<?php esc_html_e( 'Choose when to show arrow navigator.', 'carousel-slider' ); ?>"></span>
            </p><!-- Show Arrow Nav -->
            <p>
                <label for="_slide_by">
                    <strong><?php esc_html_e( 'Arrow Steps', 'carousel-slider' ); ?></strong>
                </label>
                <input class="small-text" id="_slide_by" name="carousel_slider[_slide_by]" type="text"
                       value="<?php echo esc_attr( $_slide_by ); ?>">
                <span class="cs-tooltip"
                      title="<?php esc_html_e( 'Steps to go for each navigation request. Write "page" with inverted comma to slide by page.', 'carousel-slider' ); ?>"></span>
            </p><!-- Arrow Steps -->
            <p>
                <label for="_arrow_position">
                    <strong><?php esc_html_e( 'Arrow Position', 'carousel-slider' ); ?></strong>
                </label>
                <select name="carousel_slider[_arrow_position]" id="_arrow_position" class="small-text">
                    <option value="outside" <?php selected( $_arrow_position, 'outside' ); ?>><?php esc_html_e( 'Outside', 'carousel-slider' ); ?></option>
                    <option value="inside" <?php selected( $_arrow_position, 'inside' ); ?>><?php esc_html_e( 'Inside', 'carousel-slider' ); ?></option>
                </select>
                <span class="cs-tooltip"
                      title="<?php esc_html_e( 'Choose where to show arrow. Inside slider or outside slider.', 'carousel-slider' ); ?>"></span>
            </p><!-- Arrow Position -->
            <p>
                <label for="_arrow_size">
                    <strong><?php esc_html_e( 'Arrow Size', 'carousel-slider' ); ?></strong>
                </label>
                <input class="small-text" id="_arrow_size" name="carousel_slider[_arrow_size]" type="number"
                       value="<?php echo $_arrow_size; ?>">
                <span class="cs-tooltip"
                      title="<?php esc_html_e( 'Enter arrow size in pixels.', 'carousel-slider' ); ?>"></span>
            </p><!-- Arrow Size -->

            <hr>
            <p>
                <label for="_dot_nav">
                    <strong><?php esc_html_e( 'Show Bullet Nav', 'carousel-slider' ); ?></strong>
                </label>
                <select name="carousel_slider[_dot_nav]" id="_dot_nav" class="small-text">
                    <option value="off" <?php selected( $_dot_nav, 'off' ); ?>><?php esc_html_e( 'Never', 'carousel-slider' ); ?></option>
                    <option value="on" <?php selected( $_dot_nav, 'on' ); ?>><?php esc_html_e( 'Always', 'carousel-slider' ); ?></option>
                    <option value="hover" <?php selected( $_dot_nav, 'hover' ); ?>><?php esc_html_e( 'Mouse Over', 'carousel-slider' ); ?></option>
                </select>
                <span class="cs-tooltip"
                      title="<?php esc_html_e( 'Choose when to show bullet navigator.', 'carousel-slider' ); ?>"></span>
            </p><!-- Show Bullet Nav -->
            <p>
                <label for="_bullet_position">
                    <strong><?php esc_html_e( 'Bullet Position', 'carousel-slider' ); ?></strong>
                </label>
                <select name="carousel_slider[_bullet_position]" id="_bullet_position" class="small-text">
                    <option value="left" <?php selected( $_bullet_position, 'left' ); ?>><?php esc_html_e( 'Left', 'carousel-slider' ); ?></option>
                    <option value="center" <?php selected( $_bullet_position, 'center' ); ?>><?php esc_html_e( 'Center', 'carousel-slider' ); ?></option>
                    <option value="right" <?php selected( $_bullet_position, 'right' ); ?>><?php esc_html_e( 'Right', 'carousel-slider' ); ?></option>
                </select>
                <span class="cs-tooltip"
                      title="<?php esc_html_e( 'Choose where to show bullets.', 'carousel-slider' ); ?>"></span>
            </p><!-- Arrow Position -->
            <p>
                <label for="_bullet_size">
                    <strong><?php esc_html_e( 'Bullet Size', 'carousel-slider' ); ?></strong>
                </label>
                <input class="small-text" id="_bullet_size" name="carousel_slider[_bullet_size]" type="number"
                       value="<?php echo $_bullet_size; ?>">
                <span class="cs-tooltip"
                      title="<?php esc_html_e( 'Enter bullet size in pixels.', 'carousel-slider' ); ?>"></span>
            </p><!-- Arrow Size -->
            <p>
                <label for="_bullet_shape">
                    <strong><?php esc_html_e( 'Bullet Shape', 'carousel-slider' ); ?></strong>
                </label>
                <select name="carousel_slider[_bullet_shape]" id="_bullet_shape" class="small-text">
                    <option value="square" <?php selected( $_bullet_shape, 'square' ); ?>><?php esc_html_e( 'Square', 'carousel-slider' ); ?></option>
                    <option value="circle" <?php selected( $_bullet_shape, 'circle' ); ?>><?php esc_html_e( 'Circle', 'carousel-slider' ); ?></option>
                </select>
                <span class="cs-tooltip"
                      title="<?php esc_html_e( 'Choose bullet nav shape.', 'carousel-slider' ); ?>"></span>
            </p><!-- Arrow Position -->

            <hr>
            <p>
                <label for="_nav_color">
                    <strong><?php esc_html_e( 'Arrows & Dots Color', 'carousel-slider' ); ?></strong>
                </label>
                <span class="cs-tooltip"
                      title="<?php esc_html_e( 'Pick a color for navigation and dots.', 'carousel-slider' ); ?>"></span>
                <br>
                <input type="text" class="color-picker" value="<?php echo $_nav_color; ?>" id="_nav_color"
                       name="carousel_slider[_nav_color]" data-alpha="true"
                       data-default-color="<?php echo carousel_slider_default_settings()->nav_color; ?>">
            </p><!-- Arrows & Dots Color -->

            <p>
                <label for="_nav_active_color">
                    <strong><?php esc_html_e( 'Arrows & Dots Hover Color', 'carousel-slider' ); ?></strong>
                </label>
                <span class="cs-tooltip"
                      title="<?php esc_html_e( 'Pick a color for navigation and dots for active and hover effect.', 'carousel-slider' ); ?>"></span>
                <br>
                <input type="text" class="color-picker" value="<?php echo $_nav_active_color; ?>" id="_nav_active_color"
                       name="carousel_slider[_nav_active_color]" data-alpha="true"
                       data-default-color="<?php echo carousel_slider_default_settings()->nav_active_color; ?>">
            </p><!-- Arrows & Dots Hover Color -->
			<?php
		}

		/**
		 * @param WP_Post $post
		 */
		public function autoplay_settings_callback( $post ) {
			$_autoplay         = get_post_meta( $post->ID, '_autoplay', true );
			$_autoplay         = in_array( $_autoplay, array( 'on', 'off' ) ) ? $_autoplay : 'on';
			$_autoplay_pause   = get_post_meta( $post->ID, '_autoplay_pause', true );
			$_autoplay_pause   = in_array( $_autoplay_pause, array( 'on', 'off' ) ) ? $_autoplay_pause : 'off';
			$_autoplay_timeout = get_post_meta( $post->ID, '_autoplay_timeout', true );
			$_autoplay_timeout = $_autoplay_timeout ? absint( $_autoplay_timeout ) : 5000;
			$_autoplay_speed   = get_post_meta( $post->ID, '_autoplay_speed', true );
			$_autoplay_speed   = $_autoplay_speed ? absint( $_autoplay_speed ) : 500;
			?>
            <p>
                <label for="_autoplay">
                    <strong><?php esc_html_e( 'AutoPlay', 'carousel-slider' ); ?></strong>
                </label>
                <select name="carousel_slider[_autoplay]" id="_autoplay" class="small-text">
                    <option value="on" <?php selected( $_autoplay, 'on' ); ?>><?php esc_html_e( 'Enable', 'carousel-slider' ); ?></option>
                    <option value="off" <?php selected( $_autoplay, 'off' ); ?>><?php esc_html_e( 'Disable', 'carousel-slider' ); ?></option>
                </select>
                <span class="cs-tooltip"
                      title="<?php esc_html_e( 'Choose whether slideshow should play automatically.', 'carousel-slider' ); ?>"></span>
            </p>
            <p>
                <label for="_autoplay_pause">
                    <strong><?php esc_html_e( 'Pause On Hover', 'carousel-slider' ); ?></strong>
                </label>
                <select name="carousel_slider[_autoplay_pause]" id="_autoplay_pause" class="small-text">
                    <option value="on" <?php selected( $_autoplay_pause, 'on' ); ?>><?php esc_html_e( 'Enable', 'carousel-slider' ); ?></option>
                    <option value="off" <?php selected( $_autoplay_pause, 'off' ); ?>><?php esc_html_e( 'Disable', 'carousel-slider' ); ?></option>
                </select>
                <span class="cs-tooltip"
                      title="<?php esc_html_e( 'Pause automatic play on mouse hover.', 'carousel-slider' ); ?>"></span>
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
