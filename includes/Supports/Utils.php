<?php

namespace CarouselSlider\Supports;

use CarouselSlider\Product;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Utils {

	/**
	 * Check if url is valid as per RFC 2396 Generic Syntax
	 *
	 * @param  string $url
	 *
	 * @return boolean
	 */
	public static function is_url( $url ) {
		return filter_var( $url, FILTER_VALIDATE_URL );
	}

	/**
	 * Sanitizes a Hex, RGB or RGBA color
	 *
	 * @param $color
	 *
	 * @return mixed|string
	 */
	public static function sanitize_color( $color ) {
		if ( '' === $color ) {
			return '';
		}

		// Trim unneeded whitespace
		$color = str_replace( ' ', '', $color );

		// If this is hex color, validate and return it
		if ( 1 === preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
			return $color;
		}

		// If this is rgb, validate and return it
		if ( 'rgb(' === substr( $color, 0, 4 ) ) {
			list( $red, $green, $blue ) = sscanf( $color, 'rgb(%d,%d,%d)' );

			if ( ( $red >= 0 && $red <= 255 ) &&
			     ( $green >= 0 && $green <= 255 ) &&
			     ( $blue >= 0 && $blue <= 255 )
			) {
				return "rgb({$red},{$green},{$blue})";
			}
		}

		// If this is rgba, validate and return it
		if ( 'rgba(' === substr( $color, 0, 5 ) ) {
			list( $red, $green, $blue, $alpha ) = sscanf( $color, 'rgba(%d,%d,%d,%f)' );

			if ( ( $red >= 0 && $red <= 255 ) &&
			     ( $green >= 0 && $green <= 255 ) &&
			     ( $blue >= 0 && $blue <= 255 ) &&
			     $alpha >= 0 && $alpha <= 1
			) {
				return "rgba({$red},{$green},{$blue},{$alpha})";
			}
		}

		return '';
	}

	/**
	 * Check if WooCommerce is active
	 *
	 * @return bool
	 */
	public static function is_woocommerce_active() {

		if ( in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ) ) ) {
			return true;
		}

		if ( defined( 'WC_VERSION' ) || defined( 'WOOCOMMERCE_VERSION' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get products by carousel slider ID
	 *
	 * @param $carousel_id
	 *
	 * @return array
	 */
	public static function get_products( $carousel_id ) {
		$id            = $carousel_id;
		$per_page      = intval( get_post_meta( $id, '_products_per_page', true ) );
		$query_type    = get_post_meta( $id, '_product_query_type', true );
		$query_type    = empty( $query_type ) ? 'query_porduct' : $query_type;
		$product_query = get_post_meta( $id, '_product_query', true );

		$product_carousel = new Product();

		$args = array( 'posts_per_page' => $per_page );

		if ( $query_type == 'query_porduct' ) {

			// Get features products
			if ( $product_query == 'featured' ) {
				return $product_carousel->featured_products( $args );
			}

			// Get best_selling products
			if ( $product_query == 'best_selling' ) {
				return $product_carousel->best_selling_products( $args );
			}

			// Get recent products
			if ( $product_query == 'recent' ) {
				return Product::recent_products( $args );
			}

			// Get sale products
			if ( $product_query == 'sale' ) {
				return $product_carousel->sale_products( $args );
			}

			// Get top_rated products
			if ( $product_query == 'top_rated' ) {
				return $product_carousel->top_rated_products( $args );
			}
		}

		// Get products by product IDs
		if ( $query_type == 'specific_products' ) {
			$product_in = get_post_meta( $id, '_product_in', true );
			$product_in = array_map( 'intval', explode( ',', $product_in ) );

			return Product::find_by_ids( array( 'post__in' => $product_in ) );
		}

		// Get posts by post categories IDs
		if ( $query_type == 'product_categories' ) {
			$product_cat_ids = get_post_meta( $id, '_product_categories', true );
			$product_cat_ids = array_map( 'intval', explode( ",", $product_cat_ids ) );

			return $product_carousel->products_by_categories( $product_cat_ids, $per_page );
		}

		// Get posts by post tags IDs
		if ( $query_type == 'product_tags' ) {
			$product_tags = get_post_meta( $id, '_product_tags', true );
			$product_tags = array_map( 'intval', explode( ',', $product_tags ) );

			return $product_carousel->products_by_tags( $product_tags, $per_page );
		}

		return array();
	}

	/**
	 * Get default setting
	 *
	 * @param null $key
	 *
	 * @return mixed
	 */
	public static function get_default_setting( $key = null ) {
		$options = array(
			'product_title_color'       => '#323232',
			'product_button_bg_color'   => '#00d1b2',
			'product_button_text_color' => '#f1f1f1',
			'nav_color'                 => '#f1f1f1',
			'nav_active_color'          => '#00d1b2',
			'margin_right'              => 10,
			'lazy_load_image'           => 'on',
		);

		$options = apply_filters( 'carousel_slider_default_settings', $options );

		if ( is_null( $key ) ) {
			return $options;
		}

		return isset( $options[ $key ] ) ? $options[ $key ] : null;
	}

	/**
	 * Get carousel slider available slide type
	 *
	 * @param bool $key_only
	 *
	 * @return array
	 */
	public static function get_slide_types( $key_only = true ) {
		$types = apply_filters( 'carousel_slider_slide_types', array(
			'image-carousel'     => __( 'Image Carousel', 'carousel-slider' ),
			'image-carousel-url' => __( 'Image Carousel (URL)', 'carousel-slider' ),
			'post-carousel'      => __( 'Post Carousel', 'carousel-slider' ),
			'product-carousel'   => __( 'Product Carousel', 'carousel-slider' ),
			'video-carousel'     => __( 'Video Carousel', 'carousel-slider' ),
			'hero-banner-slider' => __( 'Hero Carousel', 'carousel-slider' ),
		) );

		if ( $key_only ) {
			return array_keys( $types );
		}

		return $types;
	}
}
