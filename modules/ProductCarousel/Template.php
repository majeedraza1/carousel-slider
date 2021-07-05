<?php

namespace CarouselSlider\Modules\ProductCarousel;

use CarouselSlider\Abstracts\AbstractTemplate;
use CarouselSlider\Helper;

defined( 'ABSPATH' ) || exit;

class Template extends AbstractTemplate {

	/**
	 * Get default image carousel settings
	 *
	 * @return array
	 */
	public static function get_default_settings(): array {
		return wp_parse_args( [
			'_slide_type'                => 'product-carousel',
			// Product Carousel Settings
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
		], parent::get_default_settings() );
	}

	/**
	 * Create gallery image carousel with random images
	 *
	 * @param string $slider_title
	 * @param array $args
	 *
	 * @return int The post ID on success. The value 0 on failure.
	 */
	public static function create( $slider_title = null, $args = [] ): int {
		if ( empty( $slider_title ) ) {
			$slider_title = 'Product Carousel';
		}

		$post_id = self::create_slider( $slider_title );

		if ( ! $post_id ) {
			return 0;
		}

		$data       = wp_parse_args( $args, self::get_default_settings() );
		$query_type = $data['_product_query_type'];

		if ( 'specific_products' == $query_type ) {
			if ( empty( $data['_product_in'] ) ) {
				$posts_ids           = self::get_random_products_ids();
				$posts_ids           = is_array( $posts_ids ) ? implode( ',', $posts_ids ) : $posts_ids;
				$data['_product_in'] = $posts_ids;
			}
		}

		if ( 'product_categories' == $query_type ) {
			if ( empty( $data['_product_categories'] ) ) {
				$categories_ids              = self::get_product_categories_ids();
				$categories_ids              = is_array( $categories_ids ) ? implode( ',', $categories_ids ) : $categories_ids;
				$data['_product_categories'] = $categories_ids;
			}
		}

		if ( 'product_tags' == $query_type ) {
			if ( empty( $data['_product_tags'] ) ) {
				$tags_ids              = self::get_product_tags_ids();
				$tags_ids              = is_array( $tags_ids ) ? implode( ',', $tags_ids ) : $tags_ids;
				$data['_product_tags'] = $tags_ids;
			}
		}

		if ( 'query_product' == $query_type ) {
			if ( empty( $data['_product_query'] ) ) {
				$data['_product_query'] = 'recent';
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
	 *
	 * @return array List of product categories id.
	 */
	private static function get_product_categories_ids(): array {
		$terms = get_terms( [
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'number'     => 5,
		] );

		return wp_list_pluck( $terms, 'term_id' );
	}

	/**
	 * Get random product tags id
	 *
	 *
	 * @return array List of product tags id.
	 */
	private static function get_product_tags_ids(): array {
		$terms = get_terms( array(
			'taxonomy'   => 'product_tag',
			'hide_empty' => true,
			'number'     => 5,
		) );

		return wp_list_pluck( $terms, 'term_id' );
	}
}
