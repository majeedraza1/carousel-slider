<?php

namespace CarouselSlider\Modules\ProductCarousel;

use CarouselSlider\Abstracts\AbstractTemplate;
use CarouselSlider\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Template class
 *
 * @package Modules/ProductCarousel
 */
class Template extends AbstractTemplate {

	/**
	 * Get default image carousel settings
	 *
	 * @return array
	 */
	public static function get_default_settings(): array {
		return wp_parse_args(
			[
				'_slide_type'                => 'product-carousel',
				// Product Carousel Settings.
				'_product_query_type'        => 'query_product',
				'_product_query'             => 'recent',
				'_product_categories'        => '',
				'_product_tags'              => '',
				'_product_in'                => '',
				'_products_per_page'         => '12',
				'_product_title'             => 'on',
				'_product_rating'            => 'on',
				'_product_price'             => 'on',
				'_product_cart_button'       => 'on',
				'_product_onsale'            => 'on',
				'_product_wishlist'          => 'off',
				'_product_quick_view'        => 'off',
				'_product_title_color'       => Helper::get_default_setting( 'product_title_color' ),
				'_product_button_bg_color'   => Helper::get_default_setting( 'product_button_bg_color' ),
				'_product_button_text_color' => Helper::get_default_setting( 'product_button_text_color' ),
			],
			parent::get_default_settings()
		);
	}

	/**
	 * Create gallery image carousel with random images
	 *
	 * @param string $slider_title The slider title.
	 * @param array  $args Arguments.
	 *
	 * @return int The post ID on success. The value 0 on failure.
	 */
	public static function create( string $slider_title = null, array $args = [] ): int {
		if ( empty( $slider_title ) ) {
			$slider_title = 'Product Carousel';
		}

		$post_id = self::create_slider( $slider_title );

		if ( ! $post_id ) {
			return 0;
		}

		$data       = wp_parse_args( $args, self::get_default_settings() );
		$query_type = $data['_product_query_type'];

		$query_types  = [
			'specific_products'  => [
				'_product_in' => implode( ',', self::get_random_products_ids() ),
			],
			'product_categories' => [
				'_product_categories' => implode( ',', self::get_product_categories_ids() ),
			],
			'product_tags'       => [
				'_product_tags' => implode( ',', self::get_product_tags_ids() ),
			],
			'query_product'      => [
				'_product_query' => 'recent',
			],
		];
		$default_args = $query_types[ $query_type ] ?? [];

		foreach ( $default_args as $meta_key => $default_value ) {
			if ( empty( $data[ $meta_key ] ) ) {
				$data[ $meta_key ] = $default_value;
			}
		}

		foreach ( $data as $meta_key => $meta_value ) {
			update_post_meta( $post_id, $meta_key, $meta_value );
		}

		return $post_id;
	}

	/**
	 * Get random products ID
	 *
	 * @return array List of products ID.
	 */
	private static function get_random_products_ids(): array {
		$args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'orderby'        => 'rand',
			'posts_per_page' => 10,
		);

		$_posts = get_posts( $args );

		return wp_list_pluck( $_posts, 'ID' );
	}

	/**
	 * Get random product categories id
	 *
	 * @return array List of product categories id.
	 */
	private static function get_product_categories_ids(): array {
		$terms = get_terms(
			[
				'taxonomy'   => 'product_cat',
				'hide_empty' => true,
				'number'     => 5,
			]
		);

		return wp_list_pluck( $terms, 'term_id' );
	}

	/**
	 * Get random product tags id
	 *
	 * @return array List of product tags id.
	 */
	private static function get_product_tags_ids(): array {
		$terms = get_terms(
			array(
				'taxonomy'   => 'product_tag',
				'hide_empty' => true,
				'number'     => 5,
			)
		);

		return wp_list_pluck( $terms, 'term_id' );
	}
}
