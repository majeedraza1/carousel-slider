<?php

namespace CarouselSlider\Modules\ProductCarousel;

use WC_Product;
use WP_Term;

defined( 'ABSPATH' ) || exit;

class ProductUtils {

	/**
	 * Parse arguments
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	private static function parse_args( array $args = [] ): array {
		$args = wp_parse_args( $args, [
			'limit'      => 12,
			'order'      => 'DESC',
			'orderby'    => 'date',
			'visibility' => 'catalog',
			'paginate'   => false,
			'page'       => 1,
			'return'     => 'objects',
		] );

		return $args;
	}

	/**
	 * Get products
	 *
	 * @param int $slider_id
	 *
	 * @return array|WC_Product[]
	 */
	public static function get_products( int $slider_id ): array {
		$per_page   = (int) get_post_meta( $slider_id, '_products_per_page', true );
		$per_page   = $per_page ? $per_page : 12;
		$query_type = get_post_meta( $slider_id, '_product_query_type', true );
		$query_type = empty( $query_type ) ? 'query_product' : $query_type;
		// Type mistake
		$query_type = ( 'query_porduct' == $query_type ) ? 'query_product' : $query_type;
		$query      = get_post_meta( $slider_id, '_product_query', true );

		$args = static::parse_args( [ 'limit' => $per_page ] );

		if ( $query_type == 'specific_products' ) {
			$product_in = get_post_meta( $slider_id, '_product_in', true );
			$product_in = is_string( $product_in ) ? explode( ',', $product_in ) : $product_in;
			$product_in = array_map( 'intval', (array) $product_in );

			$args['include'] = $product_in;
		}

		if ( $query_type == 'product_categories' ) {
			$categories = get_post_meta( $slider_id, '_product_categories', true );
			$categories = is_string( $categories ) ? explode( ',', $categories ) : $categories;
			$categories = array_map( 'intval', $categories );

			$args['category'] = static::format_term_for_query( $categories, 'product_cat' );
		}

		if ( $query_type == 'product_tags' ) {
			$tags = get_post_meta( $slider_id, '_product_tags', true );
			$tags = is_string( $tags ) ? explode( ',', $tags ) : $tags;
			$tags = array_map( 'intval', $tags );

			$args['tag'] = static::format_term_for_query( $tags, 'product_tag' );
		}

		if ( $query_type == 'query_product' ) {
			// Featured
			if ( $query == 'featured' ) {
				$args['featured'] = true;
			}

			// Best selling
			if ( $query == 'best_selling' ) {
				$args['order']    = 'DESC';
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = 'total_sales';
			}

			// Recent products
			if ( $query == 'recent' ) {
				$args['order']   = 'DESC';
				$args['orderby'] = 'date';
			}

			if ( $query == 'sale' ) {
				$args['include'] = array_merge( [ 0 ], wc_get_product_ids_on_sale() );
			}

			if ( $query == 'top_rated' ) {
				$args['order']    = 'DESC';
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = '_wc_average_rating';
			}

		}

		return wc_get_products( $args );
	}

	/**
	 * List all (or limited) product categories.
	 *
	 * @param array $args
	 *
	 * @return array|WP_Term[]
	 */
	public static function product_categories( $args = array() ): array {
		$args = wp_parse_args( $args, array(
			'hide_empty' => true,
			'orderby'    => 'name',
			'order'      => 'ASC',
		) );

		$args['taxonomy'] = 'product_cat';

		return get_terms( $args );
	}

	/**
	 * Format term slug
	 *
	 * @param array $tags
	 * @param string $taxonomy
	 *
	 * @return array
	 */
	protected static function format_term_for_query( array $tags, string $taxonomy ): array {
		$ids = [];
		foreach ( $tags as $index => $tag ) {
			if ( is_numeric( $tag ) ) {
				$ids[] = intval( $tag );
				unset( $tags[ $index ] );
			}
		}
		if ( count( $ids ) ) {
			$terms = get_terms( [ 'taxonomy' => $taxonomy, 'include' => $ids ] );
			$slugs = wp_list_pluck( $terms, 'slug' );
			$tags  = array_merge( $slugs, array_values( $tags ) );
		}

		return $tags;
	}

	/**
	 * Get product quick view url
	 *
	 * @param int $product_id
	 *
	 * @return string
	 */
	public static function get_product_quick_view_url( int $product_id ): string {
		$args = array(
			'action'     => 'carousel_slider_quick_view',
			'ajax'       => 'true',
			'product_id' => $product_id,
		);
		$url  = add_query_arg( $args, admin_url( 'admin-ajax.php' ) );

		return wp_nonce_url( $url, 'carousel_slider_quick_view' );
	}
}
