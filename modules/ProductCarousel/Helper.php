<?php

namespace CarouselSlider\Modules\ProductCarousel;

use WC_Product;
use WP_Term;

defined( 'ABSPATH' ) || exit;

/**
 * Helper class
 *
 * @package Modules/ProductCarousel
 */
class Helper {

	/**
	 * List all (or limited) product categories.
	 *
	 * @param array $args Optional arguments.
	 *
	 * @return array|WP_Term[]
	 */
	public static function product_categories( array $args = [] ): array {
		$args = wp_parse_args(
			$args,
			[
				'hide_empty' => true,
				'orderby'    => 'name',
				'order'      => 'ASC',
			]
		);

		$args['taxonomy'] = 'product_cat';

		return get_terms( $args );
	}

	/**
	 * List all (or limited) product tags.
	 *
	 * @param array $args Optional arguments.
	 *
	 * @return array|WP_Term[]
	 */
	public static function product_tags( array $args = [] ): array {
		$args = wp_parse_args(
			$args,
			[
				'hide_empty' => true,
				'orderby'    => 'name',
				'order'      => 'ASC',
			]
		);

		$args['taxonomy'] = 'product_tag';

		return get_terms( $args );
	}

	/**
	 * Format term slug
	 *
	 * @param array  $tags List of term slug or term id.
	 * @param string $taxonomy Taxonomy slug.
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
			$terms = get_terms(
				[
					'taxonomy' => $taxonomy,
					'include'  => $ids,
				]
			);
			$slugs = is_array( $terms ) ? wp_list_pluck( $terms, 'slug' ) : [];
			$tags  = array_merge( $slugs, array_values( $tags ) );
		}

		return $tags;
	}

	/**
	 * Get product quick view url
	 *
	 * @param int $product_id The product id.
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
	 * @param array $args Arguments.
	 *
	 * @return array
	 */
	private static function parse_args( array $args = [] ): array {
		return wp_parse_args(
			$args,
			[
				'limit'      => 12,
				'order'      => 'DESC',
				'orderby'    => 'date',
				'visibility' => 'catalog',
				'paginate'   => false,
				'page'       => 1,
				'return'     => 'objects',
			]
		);
	}

	/**
	 * Get products
	 *
	 * @param int   $slider_id The slider id.
	 * @param array $args Arguments.
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
	 * Add top-rated query args.
	 *
	 * @param string $query_type Query type.
	 * @param array  $args Query arguments.
	 *
	 * @return void
	 */
	protected static function add_top_rated_query_args( string $query_type, array &$args ) {
		if ( 'top_rated' === $query_type ) {
			$args['order']    = 'DESC';
			$args['orderby']  = 'meta_value_num';
			$args['meta_key'] = '_wc_average_rating';
		}
	}

	/**
	 * Add on sale query args.
	 *
	 * @param string $query_type Query type.
	 * @param array  $args Query arguments.
	 *
	 * @return void
	 */
	protected static function add_on_sale_query_args( string $query_type, array &$args ) {
		if ( 'sale' === $query_type ) {
			$args['include'] = array_merge( [ 0 ], wc_get_product_ids_on_sale() );
		}
	}

	/**
	 * Add recent product query args.
	 *
	 * @param string $query_type Query type.
	 * @param array  $args Query arguments.
	 *
	 * @return void
	 */
	protected static function add_recent_product_query_args( string $query_type, array &$args ) {
		if ( 'recent' === $query_type ) {
			$args['order']   = 'DESC';
			$args['orderby'] = 'date';
		}
	}

	/**
	 * Add bestselling product query args.
	 *
	 * @param string $query_type Query type.
	 * @param array  $args Query arguments.
	 *
	 * @return void
	 */
	protected static function add_best_selling_query_args( string $query_type, array &$args ) {
		if ( 'best_selling' === $query_type ) {
			$args['order']    = 'DESC';
			$args['orderby']  = 'meta_value_num';
			$args['meta_key'] = 'total_sales';
		}
	}

	/**
	 * Add featured product query args.
	 *
	 * @param string $query_type Query type.
	 * @param array  $args Query arguments.
	 *
	 * @return void
	 */
	protected static function add_featured_product_query_args( string $query_type, array &$args ) {
		if ( 'featured' === $query_type ) {
			$args['featured'] = true;
		}
	}

	/**
	 * Add product tags query args.
	 *
	 * @param Setting $setting The Setting object.
	 * @param array   $args Query arguments.
	 *
	 * @return void
	 */
	protected static function add_product_tags_query_args( Setting $setting, array &$args ) {
		if ( 'product_tags' === $setting->get_query_type() ) {
			$args['tag'] = $setting->get_tags_slug();
		}
	}

	/**
	 * Add product categories query args.
	 *
	 * @param Setting $setting The Setting object.
	 * @param array   $args Query arguments.
	 *
	 * @return void
	 */
	protected static function add_product_categories_query_args( Setting $setting, array &$args ) {
		if ( 'product_categories' === $setting->get_query_type() ) {
			$args['category'] = $setting->get_categories_slug();
		}
	}

	/**
	 * Add specific product query args.
	 *
	 * @param Setting $setting The Setting object.
	 * @param array   $args Query arguments.
	 *
	 * @return void
	 */
	protected static function add_specific_products_query_args( Setting $setting, array &$args ) {
		if ( 'specific_products' === $setting->get_query_type() ) {
			$args['include'] = $setting->get_prop( 'product_in' );
		}
	}
}
