<?php

namespace CarouselSlider;

use CarouselSlider\Supports\Validate;

defined( 'ABSPATH' ) || exit;

class Helper {

	/**
	 * Get carousel slider available slide type
	 *
	 * @return array
	 */
	public static function get_slide_types(): array {
		return apply_filters( 'carousel_slider_slide_type', [
			'image-carousel'     => __( 'Image Carousel', 'carousel-slider' ),
			'image-carousel-url' => __( 'Image Carousel (URL)', 'carousel-slider' ),
			'post-carousel'      => __( 'Post Carousel', 'carousel-slider' ),
			'product-carousel'   => __( 'Product Carousel', 'carousel-slider' ),
			'video-carousel'     => __( 'Video Carousel', 'carousel-slider' ),
			'hero-banner-slider' => __( 'Hero Carousel', 'carousel-slider' ),
		] );
	}

	/**
	 * Get default settings
	 * @return array
	 */
	public static function get_default_settings(): array {
		return apply_filters( 'carousel_slider_default_settings', [
			'product_title_color'       => '#323232',
			'product_button_bg_color'   => '#00d1b2',
			'product_button_text_color' => '#f1f1f1',
			'nav_color'                 => '#f1f1f1',
			'nav_active_color'          => '#00d1b2',
			'margin_right'              => 10,
			'lazy_load_image'           => 'off',
		] );
	}

	/**
	 * Get slider CSS style variables
	 *
	 * @param int $slider_id
	 *
	 * @return array
	 */
	public static function get_css_variable( int $slider_id ): array {
		$nav_color        = get_post_meta( $slider_id, '_nav_color', true );
		$active_nav_color = get_post_meta( $slider_id, '_nav_active_color', true );
		$arrow_size       = get_post_meta( $slider_id, '_arrow_size', true );
		$arrow_size       = is_numeric( $arrow_size ) ? absint( $arrow_size ) : 48;
		$bullet_size      = get_post_meta( $slider_id, '_bullet_size', true );
		$bullet_size      = is_numeric( $bullet_size ) ? absint( $bullet_size ) : 10;
		$css_var          = [
			"--carousel-slider-nav-color"        => $nav_color,
			"--carousel-slider-active-nav-color" => $active_nav_color,
			"--carousel-slider-arrow-size"       => $arrow_size . 'px',
			"--carousel-slider-bullet-size"      => $bullet_size . 'px',
		];

		return apply_filters( 'carousel_slider/css_var', $css_var, $slider_id );
	}

	/**
	 * Get slider css classes
	 *
	 * @param int $slider_id
	 *
	 * @return string[]
	 */
	public static function get_css_classes( int $slider_id ): array {
		$nav_button      = get_post_meta( $slider_id, '_nav_button', true );
		$arrow_position  = get_post_meta( $slider_id, '_arrow_position', true );
		$dot_nav         = get_post_meta( $slider_id, '_dot_nav', true );
		$bullet_position = get_post_meta( $slider_id, '_bullet_position', true );
		$bullet_shape    = get_post_meta( $slider_id, '_bullet_shape', true );

		$class = [ 'owl-carousel', 'carousel-slider' ];

		// Arrows position
		if ( $arrow_position == 'inside' ) {
			$class[] = 'arrows-inside';
		} else {
			$class[] = 'arrows-outside';
		}

		// Arrows visibility
		if ( $nav_button == 'always' ) {
			$class[] = 'arrows-visible-always';
		} elseif ( $nav_button == 'off' ) {
			$class[] = 'arrows-hidden';
		} else {
			$class[] = 'arrows-visible-hover';
		}

		// Dots visibility
		if ( $dot_nav == 'on' ) {
			$class[] = 'dots-visible-always';
		} elseif ( $dot_nav == 'off' ) {
			$class[] = 'dots-hidden';
		} else {
			$class[] = 'dots-visible-hover';
		}

		// Dots position
		if ( $bullet_position == 'left' ) {
			$class[] = 'dots-left';
		} elseif ( $bullet_position == 'right' ) {
			$class[] = 'dots-right';
		} else {
			$class[] = 'dots-center';
		}

		// Dots shape
		if ( $bullet_shape == 'circle' ) {
			$class[] = 'dots-circle';
		} else {
			$class[] = 'dots-square';
		}

		return $class;
	}

	/**
	 * Get available image sizes
	 *
	 * @return array
	 */
	public static function get_available_image_sizes(): array {
		global $_wp_additional_image_sizes;

		$sizes = [];
		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, [ 'thumbnail', 'medium', 'medium_large', 'large' ] ) ) {

				$width  = get_option( "{$_size}_size_w" );
				$height = get_option( "{$_size}_size_h" );
				$crop   = get_option( "{$_size}_crop" ) ? 'hard' : 'soft';

				$sizes[ $_size ] = "{$_size} - $crop:{$width}x{$height}";

			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

				$width  = $_wp_additional_image_sizes[ $_size ]['width'];
				$height = $_wp_additional_image_sizes[ $_size ]['height'];
				$crop   = $_wp_additional_image_sizes[ $_size ]['crop'] ? 'hard' : 'soft';

				$sizes[ $_size ] = "{$_size} - $crop:{$width}x{$height}";
			}
		}

		return array_merge( $sizes, [ 'full' => 'original uploaded image' ] );
	}

	/**
	 * Check if WooCommerce is active
	 *
	 * @return bool
	 */
	public static function is_woocommerce_active(): bool {
		return in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ) ) ||
		       defined( 'WC_VERSION' ) ||
		       defined( 'WOOCOMMERCE_VERSION' );
	}

	/**
	 * Convert array to html data attribute
	 *
	 * @param array $array
	 *
	 * @return array
	 */
	public static function array_to_attribute( array $array ): array {
		return array_map( function ( $key, $value ) {
			// If boolean value
			if ( is_bool( $value ) ) {
				return sprintf( '%s="%s"', $key, ( $value ? 'true' : 'false' ) );
			}
			// If array value
			if ( is_array( $value ) ) {
				return sprintf( '%s="%s"', $key, implode( " ", $value ) );
			}

			if ( is_string( $value ) && self::is_json( $value ) ) {
				return sprintf( "%s='%s'", $key, $value );
			}

			// If string value
			return sprintf( '%s="%s"', $key, esc_attr( $value ) );

		}, array_keys( $array ), array_values( $array ) );
	}

	/**
	 * Check if value is json
	 *
	 * @param mixed $string
	 *
	 * @return bool
	 */
	public static function is_json( $string ): bool {
		json_decode( $string );

		return ( json_last_error() == JSON_ERROR_NONE );
	}

	/**
	 * Array to style
	 *
	 * @param array $styles
	 *
	 * @return string
	 */
	public static function array_to_style( array $styles ): string {
		$_styles = [];
		foreach ( $styles as $key => $value ) {
			if ( ! is_string( $key ) || empty( $value ) ) {
				continue;
			}
			$_styles[] = sprintf( "%s:%s", $key, $value );
		}

		return implode( ';', $_styles );
	}

	/**
	 * Get post meta by id and key
	 *
	 * @param int $id
	 * @param string $key
	 * @param null $default
	 *
	 * @return string
	 */
	public static function get_meta( int $id, string $key, $default = null ): string {
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
	 * Get Owl Carousel Settings
	 *
	 * @param int $slider_id
	 *
	 * @return array
	 */
	public static function get_owl_carousel_settings( int $slider_id ): array {
		$slide_by = self::get_meta( $slider_id, '_slide_by', 1 );
		$slide_by = in_array( $slide_by, [ 'page', '-1', - 1 ], true ) ? 'page' : intval( $slide_by );

		return [
			"stagePadding"       => (int) self::get_meta( $slider_id, '_stage_padding', 0 ),
			"nav"                => get_post_meta( $slider_id, '_nav_button', true ) != 'off',
			"dots"               => get_post_meta( $slider_id, '_dot_nav', true ) != 'off',
			"margin"             => (int) self::get_meta( $slider_id, '_margin_right', 10 ),
			"loop"               => Validate::checked( self::get_meta( $slider_id, '_infinity_loop', true ) ),
			"autoplay"           => Validate::checked( self::get_meta( $slider_id, '_autoplay', true ) ),
			"autoplayTimeout"    => (int) self::get_meta( $slider_id, '_autoplay_timeout', 5000 ),
			"autoplaySpeed"      => (int) self::get_meta( $slider_id, '_autoplay_speed', 500 ),
			"autoplayHoverPause" => Validate::checked( self::get_meta( $slider_id, '_autoplay_pause', false ) ),
			"slideBy"            => $slide_by,
			"lazyLoad"           => Validate::checked( self::get_meta( $slider_id, '_lazy_load_image', true ) ),
			"autoWidth"          => Validate::checked( self::get_meta( $slider_id, '_auto_width', false ) ),
			"responsive"         => [
				300  => [
					"items" => (int) self::get_meta( $slider_id, '_items_portrait_mobile', 1 )
				],
				600  => [
					"items" => (int) self::get_meta( $slider_id, '_items_small_portrait_tablet', 2 )
				],
				768  => [
					"items" => (int) self::get_meta( $slider_id, '_items_portrait_tablet', 3 )
				],
				1024 => [
					"items" => (int) self::get_meta( $slider_id, '_items_small_desktop', 4 )
				],
				1200 => [
					"items" => (int) self::get_meta( $slider_id, '_items_desktop', 4 )
				],
				1921 => [
					"items" => (int) self::get_meta( $slider_id, '_items', 4 )
				],
			]
		];
	}

	/**
	 * Get slider default attributes
	 *
	 * @param int $slider_id
	 * @param string $slider_type
	 * @param array $args
	 *
	 * @return array
	 */
	public static function get_slider_attributes( int $slider_id, string $slider_type, array $args = [] ): array {
		$attributes = array_merge( [
			'id'                => 'id-' . $slider_id,
			'class'             => implode( ' ', Helper::get_css_classes( $slider_id ) ),
			'style'             => Helper::array_to_style( Helper::get_css_variable( $slider_id ) ),
			'data-slide-type'   => $slider_type,
			'data-owl-settings' => wp_json_encode( Helper::get_owl_carousel_settings( $slider_id ) ),
		], $args );

		return Helper::array_to_attribute( $attributes );
	}
}
