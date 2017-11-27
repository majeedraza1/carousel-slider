<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Carousel_Slider_Shortcode' ) ):

	class Carousel_Slider_Shortcode {

		protected static $instance = null;

		/**
		 * Ensures only one instance of this class is loaded or can be loaded.
		 *
		 * @return Carousel_Slider_Shortcode
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * CarouselSliderShortcode constructor.
		 */
		public function __construct() {
			add_shortcode( 'carousel_slide', array( $this, 'carousel_slide' ) );
		}

		/**
		 * A shortcode for rendering the carousel slide.
		 *
		 * @param  array $attributes Shortcode attributes.
		 * @param  string $content The text content for shortcode. Not used.
		 *
		 * @return string  The shortcode output
		 */
		public function carousel_slide( $attributes, $content = null ) {
			if ( empty( $attributes['id'] ) ) {
				return '';
			}

			$id            = intval( $attributes['id'] );
			$slide_type    = get_post_meta( $id, '_slide_type', true );
			$slide_type    = in_array( $slide_type, carousel_slider_slide_type() ) ? $slide_type : 'image-carousel';
			$slide_options = $this->carousel_options( $id );

			do_action( 'carousel_slider_view', $id, $slide_type, $slide_options );
		}

		/**
		 * Generate carousel options for slider
		 *
		 * @param int $id Carousel Slider ID
		 *
		 * @return array
		 */
		private function carousel_options( $id ) {
			$_nav_color        = get_post_meta( $id, '_nav_color', true );
			$_nav_active_color = get_post_meta( $id, '_nav_active_color', true );
			$_nav_button       = get_post_meta( $id, '_nav_button', true );
			$_arrow_position   = get_post_meta( $id, '_arrow_position', true );
			$_dot_nav          = get_post_meta( $id, '_dot_nav', true );
			$_bullet_position  = get_post_meta( $id, '_bullet_position', true );
			$_bullet_shape     = get_post_meta( $id, '_bullet_shape', true );

			$class = 'owl-carousel carousel-slider';

			// Arrows position
			if ( $_arrow_position == 'inside' ) {
				$class .= ' arrows-inside';
			} else {
				$class .= ' arrows-outside';
			}

			// Arrows visibility
			if ( $_nav_button == 'always' ) {
				$class .= ' arrows-visible-always';
			} elseif ( $_nav_button == 'off' ) {
				$class .= ' arrows-hidden';
			} else {
				$class .= ' arrows-visible-hover';
			}

			// Dots visibility
			if ( $_dot_nav == 'on' ) {
				$class .= ' dots-visible-always';
			} elseif ( $_dot_nav == 'off' ) {
				$class .= ' dots-hidden';
			} else {
				$class .= ' dots-visible-hover';
			}

			// Dots position
			if ( $_bullet_position == 'left' ) {
				$class .= ' dots-left';
			} elseif ( $_bullet_position == 'right' ) {
				$class .= ' dots-right';
			} else {
				$class .= ' dots-center';
			}

			// Dots shape
			if ( $_bullet_shape == 'circle' ) {
				$class .= ' dots-circle';
			} else {
				$class .= ' dots-square';
			}

			$options_array = array(
				'id'                        => 'id-' . $id,
				'class'                     => $class,
				// General
				'data-slide-type'           => $this->get_meta( $id, '_slide_type', 'image-carousel' ),
				'data-margin'               => $this->get_meta( $id, '_margin_right', '10' ),
				'data-slide-by'             => $this->get_meta( $id, '_slide_by', '1' ),
				'data-loop'                 => $this->get_meta( $id, '_inifnity_loop', 'true' ),
				'data-lazy-load'            => $this->get_meta( $id, '_lazy_load_image', 'false' ),
				'data-stage-padding'        => $this->get_meta( $id, '_stage_padding', '0' ),
				'data-auto-width'           => $this->get_meta( $id, '_auto_width', 'false' ),
				// Navigation
				'data-dots'                 => $this->get_meta( $id, '_dot_nav', 'false' ),
				'data-nav'                  => $this->get_meta( $id, '_nav_button', 'false' ),
				// Video
				'data-video-width'          => $this->get_meta( $id, '_video_width', 'false' ),
				'data-video-height'         => $this->get_meta( $id, '_video_height', 'false' ),
				// Autoplay
				'data-autoplay'             => $this->get_meta( $id, '_autoplay', 'true' ),
				'data-autoplay-timeout'     => $this->get_meta( $id, '_autoplay_timeout', '5000' ),
				'data-autoplay-speed'       => $this->get_meta( $id, '_autoplay_speed', '500' ),
				'data-autoplay-hover-pause' => $this->get_meta( $id, '_autoplay_pause', 'false' ),
				// Responsive
				'data-colums'               => $this->get_meta( $id, '_items', '4' ),
				'data-colums-desktop'       => $this->get_meta( $id, '_items_desktop', '4' ),
				'data-colums-small-desktop' => $this->get_meta( $id, '_items_small_desktop', '4' ),
				'data-colums-tablet'        => $this->get_meta( $id, '_items_portrait_tablet', '3' ),
				'data-colums-small-tablet'  => $this->get_meta( $id, '_items_small_portrait_tablet', '2' ),
				'data-colums-mobile'        => $this->get_meta( $id, '_items_portrait_mobile', '1' ),
			);

			return $options_array;
		}

		/**
		 * Get post meta by id and key
		 *
		 * @param $id
		 * @param $key
		 * @param null $default
		 *
		 * @return string
		 */
		public function get_meta( $id, $key, $default = null ) {
			return carousel_slider_get_meta( $id, $key, $default );
		}
	}

endif;

Carousel_Slider_Shortcode::init();
