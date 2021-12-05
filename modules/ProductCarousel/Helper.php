<?php

namespace CarouselSlider\Modules\ProductCarousel;

use WC_Product;
use WP_Term;

defined( 'ABSPATH' ) || exit;

class Helper {

	/**
	 * List all (or limited) product categories.
	 *
	 * @param array $args
	 *
	 * @return array|WP_Term[]
	 */
	public static function product_categories( array $args = [] ): array {
		$args = wp_parse_args( $args, [
			'hide_empty' => true,
			'orderby'    => 'name',
			'order'      => 'ASC',
		] );

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
	public static function format_term_slug( array $tags, string $taxonomy ): array {
		$ids = [];
		foreach ( $tags as $index => $tag ) {
			if ( is_numeric( $tag ) ) {
				$ids[] = intval( $tag );
				unset( $tags[ $index ] );
			}
		}
		if ( count( $ids ) ) {
			$terms = get_terms( [ 'taxonomy' => $taxonomy, 'include' => $ids ] );
			$slugs = is_array( $terms ) ? wp_list_pluck( $terms, 'slug' ) : [];
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

	/**
	 * Parse arguments
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	private static function parse_args( array $args = [] ): array {
		return wp_parse_args( $args, [
			'limit'      => 12,
			'order'      => 'DESC',
			'orderby'    => 'date',
			'visibility' => 'catalog',
			'paginate'   => false,
			'page'       => 1,
			'return'     => 'objects',
		] );
	}

	/**
	 * Get products
	 *
	 * @param int $slider_id
	 * @param array $args
	 *
	 * @return array|WC_Product[]
	 */
	public static function get_products( int $slider_id, array $args = [] ): array {
		$setting = new Setting( $slider_id );

		$args       = static::parse_args( array_merge( $args, [ 'limit' => $setting->get_prop( 'per_page' ) ] ) );
		$query_type = $setting->get_query_type();

		self::add_specific_products_query_args( $setting, $args );
		self::add_product_categories_query_args( $setting, $args );
		self::add_product_tags_query_args( $setting, $args );
		self::add_featured_product_query_args( $query_type, $args );
		self::add_best_selling_query_args( $query_type, $args );
		self::add_recent_product_query_args( $query_type, $args );
		self::add_on_sale_query_args( $query_type, $args );
		self::add_top_rated_query_args( $query_type, $args );

		return wc_get_products( $args );
	}

	/**
	 * @param string $query_type
	 * @param array $args
	 *
	 * @return void
	 */
	protected static function add_top_rated_query_args( string $query_type, array &$args ) {
		if ( $query_type == 'top_rated' ) {
			$args['order']    = 'DESC';
			$args['orderby']  = 'meta_value_num';
			$args['meta_key'] = '_wc_average_rating';
		}
	}

	/**
	 * @param string $query_type
	 * @param array $args
	 *
	 * @return void
	 */
	protected static function add_on_sale_query_args( string $query_type, array &$args ) {
		if ( $query_type == 'sale' ) {
			$args['include'] = array_merge( [ 0 ], wc_get_product_ids_on_sale() );
		}
	}

	/**
	 * @param string $query_type
	 * @param array $args
	 *
	 * @return void
	 */
	protected static function add_recent_product_query_args( string $query_type, array &$args ) {
		if ( $query_type == 'recent' ) {
			$args['order']   = 'DESC';
			$args['orderby'] = 'date';
		}
	}

	/**
	 * @param string $query_type
	 * @param array $args
	 *
	 * @return void
	 */
	protected static function add_best_selling_query_args( string $query_type, array &$args ) {
		if ( $query_type == 'best_selling' ) {
			$args['order']    = 'DESC';
			$args['orderby']  = 'meta_value_num';
			$args['meta_key'] = 'total_sales';
		}
	}

	/**
	 * @param string $query_type
	 * @param array $args
	 *
	 * @return void
	 */
	protected static function add_featured_product_query_args( string $query_type, array &$args ) {
		if ( $query_type == 'featured' ) {
			$args['featured'] = true;
		}
	}

	/**
	 * @param Setting $setting
	 * @param array $args
	 *
	 * @return void
	 */
	protected static function add_product_tags_query_args( Setting $setting, array &$args ) {
		if ( $setting->get_query_type() == 'product_tags' ) {
			$args['tag'] = $setting->get_tags_slug();
		}
	}

	/**
	 * @param Setting $setting
	 * @param array $args
	 *
	 * @return void
	 */
	protected static function add_product_categories_query_args( Setting $setting, array &$args ) {
		if ( $setting->get_query_type() == 'product_categories' ) {
			$args['category'] = $setting->get_categories_slug();
		}
	}

	/**
	 * @param Setting $setting
	 * @param array $args
	 *
	 * @return void
	 */
	protected static function add_specific_products_query_args( Setting $setting, array &$args ) {
		if ( $setting->get_query_type() == 'specific_products' ) {
			$args['include'] = $setting->get_prop( 'product_in' );
		}
	}
}
