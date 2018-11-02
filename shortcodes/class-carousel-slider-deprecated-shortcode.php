<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

if ( ! class_exists( 'Carousel_Slider_Deprecated_Shortcode' ) ):

	class Carousel_Slider_Deprecated_Shortcode {

		protected static $instance = null;

		/**
		 * Ensures only one instance of this class is loaded or can be loaded.
		 *
		 * @return Carousel_Slider_Deprecated_Shortcode
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * class constructor.
		 */
		public function __construct() {
			// Deprecated since version 1.6.0
			add_shortcode( 'carousel', array( $this, 'carousel' ) );
			add_shortcode( 'item', array( $this, 'item' ) );
		}

		/**
		 * A shortcode for rendering the carousel slide.
		 *
		 * @param  array $atts Shortcode attributes.
		 * @param  string $content The text content for shortcode. Not used.
		 *
		 * @return string  The shortcode output
		 */
		public function carousel( $atts, $content = null ) {
			extract( shortcode_atts( array(
				'id'                  => rand( 1, 10 ),
				'items_desktop_large' => '4',
				'items'               => '4',
				'items_desktop'       => '4',
				'items_desktop_small' => '3',
				'items_tablet'        => '2',
				'items_mobile'        => '1',
				'auto_play'           => 'true',
				'stop_on_hover'       => 'true',
				'navigation'          => 'true',
				'pagination'          => 'false',
				'nav_color'           => '#f1f1f1',
				'nav_active_color'    => '#4caf50',
				'margin_right'        => '10',
				'inifnity_loop'       => 'true',
				'autoplay_timeout'    => '5000',
				'autoplay_speed'      => '500',
				'slide_by'            => '1',
			), $atts ) );

			ob_start();

			$options_array = array(
				'id'                        => 'id-' . $id,
				'class'                     => 'owl-carousel carousel-slider',
				// General
				'data-slide-type'           => 'image-carousel-url',
				'data-margin'               => $margin_right,
				'data-slide-by'             => $slide_by,
				'data-loop'                 => $inifnity_loop,
				'data-lazy-load'            => 'false',
				'data-stage-padding'        => 0,
				'data-auto-width'           => 'false',
				// Navigation
				'data-nav'                  => $navigation,
				'data-dots'                 => $pagination,
				// Autoplay
				'data-autoplay'             => $auto_play,
				'data-autoplay-timeout'     => $autoplay_timeout,
				'data-autoplay-speed'       => $autoplay_speed,
				'data-autoplay-hover-pause' => $stop_on_hover,
				// Responsive
				'data-colums'               => $items_desktop_large,
				'data-colums-desktop'       => $items,
				'data-colums-small-desktop' => $items_desktop,
				'data-colums-tablet'        => $items_desktop_small,
				'data-colums-small-tablet'  => $items_tablet,
				'data-colums-mobile'        => $items_mobile,
			);
			?>
            <style>
                #id-<?php echo $id; ?> .owl-dots .owl-dot span {
                    background-color: <?php echo $nav_color; ?>
                }

                #id-<?php echo $id; ?> .owl-dots .owl-dot.active span,
                #id-<?php echo $id; ?> .owl-dots .owl-dot:hover span {
                    background-color: <?php echo $nav_active_color; ?>
                }

                #id-<?php echo $id; ?> .carousel-slider-nav-icon {
                    fill: <?php echo $nav_color; ?>;
                }

                #id-<?php echo $id; ?> .carousel-slider-nav-icon:hover {
                    fill: <?php echo $nav_active_color; ?>;
                }
            </style>
			<?php
			if ( current_user_can( 'manage_options' ) ) {
				printf(
					'<div style="background-color: #ffdddd;border-left: 0.375rem solid #f44336; margin-bottom: 1rem;
    margin-top: 1rem;padding: 0.01rem 1rem;"><p><strong>%s</strong><br>%s<br>%s</p></div> ',
					esc_html__( 'Admin Only Notice!', 'carousel-slider' ),
					esc_html__( 'From carousel slider version 1.6.0, [carousel] and [item] shortcode has been deprecated.', 'carousel-slider' ),
					esc_html__( 'Both [carousel] and [item] shortcode will be removed on carousel slider version 2.0.0', 'carousel-slider' )
				);
			}
			?>
            <div <?php echo $this->array_to_data( $options_array ); ?>>
				<?php echo do_shortcode( $content ); ?>
            </div><!-- .carousel-slider -->
			<?php
			$html = ob_get_contents();
			ob_end_clean();

			return $html;

		}

		/**
		 * A shortcode for rendering the carousel slide.
		 *
		 * @param  array $attributes Shortcode attributes.
		 * @param  string $content The text content for shortcode. Not used.
		 *
		 * @return string  The shortcode output
		 */
		public function item( $attributes, $content = null ) {
			extract( shortcode_atts( array(
				'img_link' => '',
				'href'     => '',
				'target'   => '_self',
			), $attributes ) );

			if ( ! $this->is_valid_url( $img_link ) ) {
				return '';
			}

			if ( $this->is_valid_url( $href ) ) {

				return sprintf( '<div><a target="%3$s" href="%2$s"><img src="%1$s"></a></div>', esc_url( $img_link ), esc_url( $href ), $target );
			} else {

				return sprintf( '<div><img src="%s"></div>', esc_url( $img_link ) );
			}
		}

		/**
		 * Check if url is valid as per RFC 2396 Generic Syntax
		 *
		 * @param  string $url
		 *
		 * @return boolean
		 */
		private function is_valid_url( $url ) {
			return carousel_slider_is_url( $url );
		}

		/**
		 * Convert array to html attributes
		 *
		 * @param  array $array
		 *
		 * @return string
		 */
		private function array_to_data( array $array ) {
			$array_map = array_map( function ( $key, $value ) {
				return sprintf( '%s="%s"', $key, esc_attr( $value ) );

			}, array_keys( $array ), array_values( $array ) );

			return join( " ", $array_map );
		}
	}

endif;

Carousel_Slider_Deprecated_Shortcode::init();
