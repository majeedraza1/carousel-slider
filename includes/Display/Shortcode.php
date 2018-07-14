<?php

namespace CarouselSlider\Display;

use CarouselSlider\Supports\DynamicStyle;
use CarouselSlider\Supports\Utils;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Shortcode {
	protected static $instance = null;

	/**
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @return Shortcode
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_shortcode( 'carousel_slide', array( self::$instance, 'carousel_slide' ) );
		}

		return self::$instance;
	}

	/**
	 * A shortcode for rendering the carousel slide.
	 *
	 * @param  array $attributes Shortcode attributes.
	 *
	 * @return string  The shortcode output
	 */
	public function carousel_slide( $attributes ) {
		if ( empty( $attributes['id'] ) ) {
			return '';
		}

		$id = intval( $attributes['id'] );

		$slide_type = get_post_meta( $id, '_slide_type', true );
		$slide_type = in_array( $slide_type, Utils::get_slide_types() ) ? $slide_type : 'image-carousel';

		if ( $slide_type == 'post-carousel' ) {
			$view = new \CarouselSlider\Modules\PostCarousel\View();
			$view->set_slider_id( $id );
			$html = $view->render();

			return apply_filters( 'carousel_slider/view/post_carousel', $html, $id );
		}

		if ( $slide_type == 'video-carousel' ) {
			$view = new \CarouselSlider\Modules\VideoCarousel\View();
			$view->set_slider_id( $id );
			$html = $view->render();

			return apply_filters( 'carousel_slider/view/video_carousel', $html, $id );
		}

		if ( $slide_type == 'image-carousel-url' ) {
			ob_start();
			require CAROUSEL_SLIDER_TEMPLATES . '/public/images-carousel-url.php';
			$html = ob_get_contents();
			ob_end_clean();

			return apply_filters( 'carousel_slider_link_images_carousel', $html, $id );
		}

		if ( $slide_type == 'image-carousel' ) {
			$view = new \CarouselSlider\Modules\ImageCarousel\View();
			$view->set_slider_id( $id );
			$html = $view->render();

			return apply_filters( 'carousel_slider/view/image_carousel', $html, $id );
		}

		if ( $slide_type == 'product-carousel' ) {

			$query_type    = get_post_meta( $id, '_product_query_type', true );
			$query_type    = empty( $query_type ) ? 'query_porduct' : $query_type;
			$product_query = get_post_meta( $id, '_product_query', true );

			if ( $query_type == 'query_porduct' && $product_query == 'product_categories_list' ) {
				ob_start();

				echo $this->product_categories( $id );
				$html = ob_get_contents();
				ob_end_clean();

				return apply_filters( 'carousel_slider_product_carousel', $html, $id );
			}

			ob_start();
			require CAROUSEL_SLIDER_TEMPLATES . '/public/product-carousel.php';
			$html = ob_get_contents();
			ob_end_clean();

			return apply_filters( 'carousel_slider_product_carousel', $html, $id );
		}

		if ( $slide_type == 'hero-banner-slider' ) {

			ob_start();
			require CAROUSEL_SLIDER_TEMPLATES . '/public/hero-banner-slider.php';
			$html = ob_get_contents();
			ob_end_clean();

			return apply_filters( 'carousel_slider_hero_carousel', $html, $id );
		}

		return '';
	}

	/**
	 * Generate carousel options for slider
	 *
	 * @param $id
	 *
	 * @return array
	 */
	private function carousel_options( $id ) {
		$_nav_button      = get_post_meta( $id, '_nav_button', true );
		$_arrow_position  = get_post_meta( $id, '_arrow_position', true );
		$_dot_nav         = get_post_meta( $id, '_dot_nav', true );
		$_bullet_position = get_post_meta( $id, '_bullet_position', true );
		$_bullet_shape    = get_post_meta( $id, '_bullet_shape', true );

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

		$_dot_nav    = ( get_post_meta( $id, '_dot_nav', true ) != 'off' );
		$_nav_button = ( get_post_meta( $id, '_nav_button', true ) != 'off' );

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
			'data-dots'                 => $_dot_nav,
			'data-nav'                  => $_nav_button,
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

		return $this->array_to_data( $options_array );
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
		$meta = get_post_meta( $id, $key, true );

		if ( empty( $meta ) && $default ) {
			$meta = $default;
		}

		if ( $meta == 'zero' ) {
			$meta = '0';
		}
		if ( $meta == 'on' ) {
			$meta = 'true';
		}
		if ( $meta == 'off' ) {
			$meta = 'false';
		}
		if ( $key == '_margin_right' && $meta == 0 ) {
			$meta = '0';
		}

		return esc_attr( $meta );
	}

	/**
	 * Convert array to html data attribute
	 *
	 * @param $array
	 *
	 * @return array|string
	 */
	public function array_to_data( $array ) {
		if ( ! is_array( $array ) ) {
			return '';
		}

		$attribute = array_map( function ( $key, $value ) {
			// If boolean value
			if ( is_bool( $value ) ) {
				if ( $value ) {

					return sprintf( '%s="%s"', $key, 'true' );
				} else {

					return sprintf( '%s="%s"', $key, 'false' );
				}
			}
			// If array value
			if ( is_array( $value ) ) {

				return sprintf( '%s="%s"', $key, implode( " ", $value ) );
			}

			// If string value
			return sprintf( '%s="%s"', $key, esc_attr( $value ) );

		}, array_keys( $array ), array_values( $array ) );

		return $attribute;
	}

	/**
	 * Get product categories list carousel
	 *
	 * @param int $id
	 *
	 * @return string
	 */
	private function product_categories( $id = 0 ) {

		$product_carousel   = new \CarouselSlider\Product();
		$product_categories = $product_carousel->product_categories();

		$options = $this->carousel_options( $id );
		$options = join( " ", $options );

		ob_start();
		if ( $product_categories ) {
			echo '<div class="carousel-slider-outer carousel-slider-outer-products carousel-slider-outer-' . $id . '">';
			echo DynamicStyle::generate( $id );
			echo '<div ' . $options . '>';


			foreach ( $product_categories as $category ) {
				echo '<div class="product carousel-slider__product">';
				do_action( 'woocommerce_before_subcategory', $category );
				do_action( 'woocommerce_before_subcategory_title', $category );
				do_action( 'woocommerce_shop_loop_subcategory_title', $category );
				do_action( 'woocommerce_after_subcategory_title', $category );
				do_action( 'woocommerce_after_subcategory', $category );
				echo '</div>';
			}

			echo '</div>';
			echo '</div>';
		}

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}
}
