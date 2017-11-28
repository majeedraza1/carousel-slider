<?php

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
			$this->form = new Carousel_Slider_Form();
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
				$this->post_type,
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
			$this->form->image_sizes( array(
				'id'   => esc_html__( '_image_size', 'carousel-slider' ),
				'name' => esc_html__( 'Carousel Image size', 'carousel-slider' ),
				'desc' => sprintf(
					esc_html__( 'Choose "original uploaded image" for full size image or your desired image size for carousel image. You can change the default size for thumbnail, medium and large from %1$s Settings >> Media %2$s.', 'carousel-slider' ),
					'<a target="_blank" href="' . admin_url( 'options-media.php' ) . '">',
					'</a>'
				),
			) );
			$this->form->select( array(
				'id'      => '_lazy_load_image',
				'name'    => esc_html__( 'Lazy Loading', 'carousel-slider' ),
				'desc'    => esc_html__( 'Enable image with lazy loading.', 'carousel-slider' ),
				'std'     => carousel_slider_default_settings()->lazy_load_image,
				'options' => array(
					'on'  => esc_html__( 'Enable' ),
					'off' => esc_html__( 'Disable' ),
				),
			) );
			$this->form->number( array(
				'id'   => '_margin_right',
				'name' => esc_html__( 'Item Spacing.', 'carousel-slider' ),
				'desc' => esc_html__( 'Space between two slide. Enter 10 for 10px', 'carousel-slider' ),
				'std'  => carousel_slider_default_settings()->margin_right
			) );
			$this->form->select( array(
				'id'      => '_inifnity_loop',
				'name'    => esc_html__( 'Infinity loop', 'carousel-slider' ),
				'desc'    => esc_html__( 'Enable or disable loop(circular) of carousel.', 'carousel-slider' ),
				'std'     => 'on',
				'options' => array(
					'on'  => esc_html__( 'Enable' ),
					'off' => esc_html__( 'Disable' ),
				),
			) );
			$this->form->number( array(
				'id'   => '_stage_padding',
				'name' => esc_html__( 'Stage Padding', 'carousel-slider' ),
				'desc' => esc_html__( 'Add left and right padding on carousel slider stage wrapper.', 'carousel-slider' ),
				'std'  => '0',
			) );
			$this->form->select( array(
				'id'      => '_auto_width',
				'name'    => esc_html__( 'Auto Width', 'carousel-slider' ),
				'desc'    => esc_html__( 'Set item width according to its content width. Use width style on item to get the result you want. ', 'carousel-slider' ),
				'std'     => 'off',
				'options' => array(
					'on'  => esc_html__( 'Enable' ),
					'off' => esc_html__( 'Disable' ),
				),
			) );
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

			$this->form->select( array(
				'id'      => '_nav_button',
				'name'    => esc_html__( 'Show Arrow Nav', 'carousel-slider' ),
				'desc'    => esc_html__( 'Choose when to show arrow navigator.', 'carousel-slider' ),
				'std'     => 'on',
				'class'   => 'small-text',
				'context' => 'side',
				'options' => array(
					'off'    => esc_html__( 'Never', 'carousel-slider' ),
					'on'     => esc_html__( 'Mouse Over', 'carousel-slider' ),
					'always' => esc_html__( 'Always', 'carousel-slider' ),
				),
			) );
			?>
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
			$this->form->select( array(
				'id'      => '_autoplay',
				'name'    => esc_html__( 'AutoPlay', 'carousel-slider' ),
				'desc'    => esc_html__( 'Choose whether slideshow should play automatically.', 'carousel-slider' ),
				'std'     => 'on',
				'class'   => 'small-text',
				'context' => 'side',
				'options' => array(
					'on'  => esc_html__( 'Enable', 'carousel-slider' ),
					'off' => esc_html__( 'Disable', 'carousel-slider' ),
				),
			) );
			$this->form->select( array(
				'id'      => '_autoplay_pause',
				'name'    => esc_html__( 'Pause On Hover', 'carousel-slider' ),
				'desc'    => esc_html__( 'Pause automatic play on mouse hover.', 'carousel-slider' ),
				'std'     => 'on',
				'class'   => 'small-text',
				'context' => 'side',
				'options' => array(
					'on'  => esc_html__( 'Enable', 'carousel-slider' ),
					'off' => esc_html__( 'Disable', 'carousel-slider' ),
				),
			) );
			$this->form->number( array(
				'id'      => '_autoplay_timeout',
				'name'    => esc_html__( 'Autoplay Timeout', 'carousel-slider' ),
				'desc'    => esc_html__( 'Automatic play interval timeout in millisecond. Default: 5000', 'carousel-slider' ),
				'std'     => 5000,
				'class'   => 'small-text',
				'context' => 'side',
			) );
			$this->form->number( array(
				'id'      => '_autoplay_speed',
				'name'    => esc_html__( 'Autoplay Speed', 'carousel-slider' ),
				'desc'    => esc_html__( 'Automatic play speed in millisecond. Default: 500', 'carousel-slider' ),
				'std'     => 500,
				'class'   => 'small-text',
				'context' => 'side',
			) );
		}

		/**
		 * Renders the meta box.
		 *
		 * @param WP_Post $post
		 */
		public function responsive_settings_callback( $post ) {
			$this->form->number( array(
				'id'      => '_items',
				'name'    => esc_html__( 'Columns', 'carousel-slider' ),
				'desc'    => esc_html__( 'The number of items you want to see on the Extra Large Desktop Layout (Screens size greater than 1921 pixels DP)', 'carousel-slider' ),
				'std'     => 4,
				'class'   => 'small-text',
				'context' => 'side',
			) );
			$this->form->number( array(
				'id'      => '_items_desktop',
				'name'    => esc_html__( 'Columns : Desktop', 'carousel-slider' ),
				'desc'    => esc_html__( 'The number of items you want to see on the Desktop Layout (Screens size from 1200 pixels DP to 1920 pixels DP)', 'carousel-slider' ),
				'std'     => 4,
				'class'   => 'small-text',
				'context' => 'side',
			) );
			$this->form->number( array(
				'id'      => '_items_small_desktop',
				'name'    => esc_html__( 'Columns : Small Desktop', 'carousel-slider' ),
				'desc'    => esc_html__( 'The number of items you want to see on the Small Desktop Layout (Screens size from 993 pixels DP to 1199 pixels DP)', 'carousel-slider' ),
				'std'     => 4,
				'class'   => 'small-text',
				'context' => 'side',
			) );
			$this->form->number( array(
				'id'      => '_items_portrait_tablet',
				'name'    => esc_html__( 'Columns : Tablet', 'carousel-slider' ),
				'desc'    => esc_html__( 'The number of items you want to see on the Tablet Layout (Screens size from 768 pixels DP to 992 pixels DP)', 'carousel-slider' ),
				'std'     => 3,
				'class'   => 'small-text',
				'context' => 'side',
			) );
			$this->form->number( array(
				'id'      => '_items_small_portrait_tablet',
				'name'    => esc_html__( 'Columns : Small Tablet', 'carousel-slider' ),
				'desc'    => esc_html__( 'The number of items you want to see on the Small Tablet Layout(Screens size from 600 pixels DP to 767 pixels DP)', 'carousel-slider' ),
				'std'     => 2,
				'class'   => 'small-text',
				'context' => 'side',
			) );
			$this->form->number( array(
				'id'      => '_items_portrait_mobile',
				'name'    => esc_html__( 'Columns : Mobile', 'carousel-slider' ),
				'desc'    => esc_html__( 'The number of items you want to see on the Mobile Layout (Screens size from 320 pixels DP to 599 pixels DP)', 'carousel-slider' ),
				'std'     => 1,
				'class'   => 'small-text',
				'context' => 'side',
			) );
		}
	}
endif;

Carousel_Slider_Meta_Box::init();
