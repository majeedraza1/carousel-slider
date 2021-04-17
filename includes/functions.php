<?php

use CarouselSlider\Modules\HeroCarousel\HeroCarouselHelper;
use CarouselSlider\Modules\PostCarousel\PostCarouselHelper;
use CarouselSlider\Modules\ProductCarousel\ProductCarouselHelper;
use CarouselSlider\Supports\Sanitize;
use CarouselSlider\Supports\Validate;
use CarouselSlider\Helper;

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'carousel_slider_is_url' ) ) {
	/**
	 * Check if url is valid as per RFC 2396 Generic Syntax
	 *
	 * @param mixed $url
	 *
	 * @return boolean
	 */
	function carousel_slider_is_url( $url ): bool {
		return Validate::url( $url );
	}
}

if ( ! function_exists( 'carousel_slider_sanitize_color' ) ) {
	/**
	 * Sanitizes a Hex, RGB or RGBA color
	 *
	 * @param $color
	 *
	 * @return string
	 */
	function carousel_slider_sanitize_color( $color ) {
		return Sanitize::color( $color );
	}
}

if ( ! function_exists( 'carousel_slider_get_meta' ) ) {
	/**
	 * Get post meta by id and key
	 *
	 * @param $id
	 * @param $key
	 * @param null $default
	 *
	 * @return string
	 */
	function carousel_slider_get_meta( $id, $key, $default = null ) {
		return Helper::get_meta( $id, $key, $default );
	}
}

if ( ! function_exists( 'carousel_slider_array_to_attribute' ) ) {
	/**
	 * Convert array to html data attribute
	 *
	 * @param $array
	 *
	 * @return array
	 */
	function carousel_slider_array_to_attribute( $array ): array {
		return Helper::array_to_attribute( (array) $array );
	}
}

if ( ! function_exists( 'carousel_slider_is_woocommerce_active' ) ) {
	/**
	 * Check if WooCommerce is active
	 *
	 * @return bool
	 */
	function carousel_slider_is_woocommerce_active(): bool {
		return Helper::is_woocommerce_active();
	}
}

if ( ! function_exists( 'carousel_slider_posts' ) ) {
	/**
	 * Get posts by carousel slider ID
	 *
	 * @param $carousel_id
	 *
	 * @return array
	 */
	function carousel_slider_posts( $carousel_id ): array {
		return PostCarouselHelper::get_posts( intval( $carousel_id ) );
	}
}

/**
 * Get products by carousel slider ID
 *
 * @param $carousel_id
 *
 * @return array|WP_Post[]
 */
function carousel_slider_products( $carousel_id ): array {
	$ids = ProductCarouselHelper::get_products( $carousel_id, [ 'return' => 'ids' ] );
	if ( count( $ids ) ) {
		return get_posts( [ 'post__in' => $ids ] );
	}

	return [];
}

if ( ! function_exists( 'carousel_slider_slide_type' ) ) {
	/**
	 * Get carousel slider available slide type
	 *
	 * @param bool $key_only
	 *
	 * @return array
	 */
	function carousel_slider_slide_type( $key_only = true ): array {
		$types = Helper::get_slide_types();

		if ( $key_only ) {
			return array_keys( $types );
		}

		return $types;
	}
}

if ( ! function_exists( 'carousel_slider_background_position' ) ) {
	/**
	 * @param bool $key_only
	 *
	 * @return array
	 */
	function carousel_slider_background_position( $key_only = false ): array {
		return HeroCarouselHelper::background_position( $key_only );
	}
}

if ( ! function_exists( 'carousel_slider_background_size' ) ) {
	/**
	 * @param bool $key_only
	 *
	 * @return array
	 */
	function carousel_slider_background_size( $key_only = false ): array {
		return HeroCarouselHelper::background_size( $key_only );
	}
}

if ( ! function_exists( 'carousel_slider_default_settings' ) ) {
	function carousel_slider_default_settings() {
		$options = Helper::get_default_settings();

		return json_decode( json_encode( $options ) );
	}
}

if ( ! function_exists( 'carousel_slider_inline_style' ) ) {
	/**
	 * Get carousel slider inline style
	 *
	 * @param $carousel_id
	 */
	function carousel_slider_inline_style( $carousel_id ) {
		$id                      = $carousel_id;
		$_nav_color              = get_post_meta( $id, '_nav_color', true );
		$_nav_active_color       = get_post_meta( $id, '_nav_active_color', true );
		$_product_title_color    = get_post_meta( $id, '_product_title_color', true );
		$_product_btn_bg_color   = get_post_meta( $id, '_product_button_bg_color', true );
		$_product_btn_text_color = get_post_meta( $id, '_product_button_text_color', true );

		$slide_type = get_post_meta( $id, '_slide_type', true );
		$slide_type = in_array( $slide_type, carousel_slider_slide_type() ) ? $slide_type : 'image-carousel';

		$_arrow_size = get_post_meta( $id, '_arrow_size', true );
		$_arrow_size = empty( $_arrow_size ) ? 48 : absint( $_arrow_size );

		$_bullet_size = get_post_meta( $id, '_bullet_size', true );
		$_bullet_size = empty( $_bullet_size ) ? 10 : absint( $_bullet_size );

		echo "<style type=\"text/css\">";

		// Arrows Nav
		echo "
            #id-{$id} .carousel-slider-nav-icon {
                fill: {$_nav_color}
            }
            #id-{$id} .carousel-slider-nav-icon:hover {
                fill: {$_nav_active_color}
            }
            #id-{$id} .owl-prev,
            #id-{$id} .owl-next,
            #id-{$id} .carousel-slider-nav-icon {
                height: {$_arrow_size}px;
                width: {$_arrow_size}px
            }
            #id-{$id}.arrows-outside .owl-prev {
                left: -{$_arrow_size}px
            }
            #id-{$id}.arrows-outside .owl-next {
                right: -{$_arrow_size}px
            }
        ";

		// Dots Nav
		echo "
		    #id-{$id} .owl-dots .owl-dot span {
                background-color: {$_nav_color};
                width: {$_bullet_size}px;
                height: {$_bullet_size}px;
            }
            #id-{$id} .owl-dots .owl-dot.active span,
            #id-{$id} .owl-dots .owl-dot:hover span {
                background-color: {$_nav_active_color}
            }
		";

		// Product Carousel Slider
		if ( $slide_type == 'product-carousel' ) {
			echo "
		        #id-{$id} .carousel-slider__product h3,
                #id-{$id} .carousel-slider__product .price {
                    color: {$_product_title_color};
                }

                #id-{$id} .carousel-slider__product a.add_to_cart_button,
                #id-{$id} .carousel-slider__product a.added_to_cart,
                #id-{$id} .carousel-slider__product a.quick_view,
                #id-{$id} .carousel-slider__product .onsale {
                    background-color: {$_product_btn_bg_color};
                    color: {$_product_btn_text_color};
                }

                #id-{$id} .carousel-slider__product .star-rating span:before {
                    color: {$_product_btn_bg_color};
                }
		    ";
		}

		echo "</style>";
	}
}
