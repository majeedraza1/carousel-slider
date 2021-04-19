<?php

use CarouselSlider\Modules\HeroCarousel\HeroCarouselHelper;
use CarouselSlider\Modules\PostCarousel\PostCarouselHelper;
use CarouselSlider\Modules\ProductCarousel\ProductCarouselHelper;
use CarouselSlider\Supports\Sanitize;
use CarouselSlider\Supports\Validate;
use CarouselSlider\Helper;

defined( 'ABSPATH' ) || exit;

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

/**
 * Check if WooCommerce is active
 *
 * @return bool
 */
function carousel_slider_is_woocommerce_active(): bool {
	return Helper::is_woocommerce_active();
}

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

/**
 * @param bool $key_only
 *
 * @return array
 */
function carousel_slider_background_position( $key_only = false ): array {
	return HeroCarouselHelper::background_position( $key_only );
}

/**
 * @param bool $key_only
 *
 * @return array
 */
function carousel_slider_background_size( $key_only = false ): array {
	return HeroCarouselHelper::background_size( $key_only );
}

function carousel_slider_default_settings() {
	$options = Helper::get_default_settings();

	return json_decode( json_encode( $options ) );
}

/**
 * Get carousel slider inline style
 *
 * @param $carousel_id
 */
function carousel_slider_inline_style( $carousel_id ) {
	return;
}
