<?php

namespace CarouselSlider;

/**!
 * @package Carousel_Slider
 * @subpackage Carousel_Slider_Product
 *
 * @method init()
 * @method wish_list_button()
 * @method quick_view_button()
 * @method quick_view()
 * @method products()
 * @method recent_products()
 * @method best_selling_products()
 * @method featured_products()
 * @method sale_products()
 * @method top_rated_products()
 * @method product_categories()
 * @method products_by_categories()
 * @method products_by_tags()
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Product {

	private static $instance;

	/**
	 * Product carousel quick view
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_action( 'carousel_slider_after_shop_loop_item', array(
				self::$instance,
				'quick_view_button'
			), 10, 3 );
			add_action( 'carousel_slider_after_shop_loop_item', array(
				self::$instance,
				'wish_list_button'
			), 12, 3 );
		}

		return self::$instance;
	}

	/**
	 * Show YITH Wishlist button on product slider
	 *
	 * @param \WC_Product $product
	 * @param \WP_Post $post
	 * @param $slider_id
	 */
	public static function wish_list_button( $product, $post, $slider_id ) {
		$_product_wish_list = get_post_meta( $slider_id, '_product_wishlist', true );

		if ( self::is_version3() ) {
			$product_id = $product->get_id();
		} else {
			$product_id = $post->ID;
		}

		if ( class_exists( 'YITH_WCWL' ) && $_product_wish_list == 'on' ) {
			echo do_shortcode( '[yith_wcwl_add_to_wishlist product_id="' . $product_id . '"]' );
		}
	}

	/**
	 * Show quick view button on product slider
	 *
	 * @param \WC_Product $product
	 * @param \WP_Post $post
	 * @param $slider_id
	 */
	public static function quick_view_button( $product, $post, $slider_id ) {
		$_show_btn = get_post_meta( $slider_id, '_product_quick_view', true );

		if ( $_show_btn == 'on' ) {

			if ( self::is_version3() ) {
				$product_id = $product->get_id();
			} else {
				$product_id = $post->ID;
			}

			wp_enqueue_script( 'magnific-popup' );

			$ajax_url = wp_nonce_url( add_query_arg( array(
				'ajax'       => 'true',
				'action'     => 'carousel_slider_quick_view',
				'product_id' => $product_id,
				'slide_id'   => $slider_id
			), admin_url( 'admin-ajax.php' ) ), 'carousel_slider_quick_view' );

			$quick_view_html = '<div style="clear: both;"></div>';
			$quick_view_html .= sprintf(
				'<a class="magnific-popup button quick_view" href="%1$s" data-product-id="%2$s">%3$s</a>',
				$ajax_url,
				$product_id,
				__( 'Quick View', 'carousel-slider' )
			);
			echo apply_filters( 'carousel_slider_product_quick_view', $quick_view_html, $product );
		}
	}

	/**
	 * Get WooCommerce version
	 *
	 * @return string
	 */
	private static function wc_version() {
		if ( defined( 'WC_VERSION' ) ) {
			return WC_VERSION;
		}

		return '0.0.0';
	}

	/**
	 * Check if it WooCommerce version 3.0 or higher
	 *
	 * @return bool
	 */
	private static function is_version3() {
		return defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0', '>=' );
	}

	/**
	 * List multiple products by product ids
	 *
	 * Works with WooCommerce Version 2.5.*, 2.6.*, 3.0.*, 3.1.*
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public static function find_by_ids( $args = array() ) {

		if ( self::is_version3() ) {
			$arguments = array( 'status' => 'publish', 'include' => $args['post__in'] );
			$products  = wc_get_products( $arguments );

			return $products;
		}

		$defaults = array(
			'orderby'        => 'title',
			'order'          => 'asc',
			'posts_per_page' => - 1,
			'post__in'       => array(),
		);
		$args     = wp_parse_args( $args, $defaults );

		$query_args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => $args['posts_per_page'],
			'orderby'             => $args['orderby'],
			'order'               => $args['order'],
			'post__in'            => $args['post__in'],
			'meta_query'          => WC()->query->get_meta_query(),
		);

		if ( version_compare( self::wc_version(), '2.7.0', '>=' ) ) {
			$query_args['tax_query'] = WC()->query->get_tax_query();
		}

		return get_posts( $query_args );
	}

	/**
	 * Get Recent Products
	 *
	 * Works with WooCommerce Version 2.5.*, 2.6.*, 3.0.*, 3.1.*
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public static function recent_products( $args = array() ) {

		if ( self::is_version3() ) {
			$arguments = array(
				'status'  => 'publish',
				'order'   => 'DESC',
				'orderby' => 'date',
				'limit'   => $args['posts_per_page'],
			);
			$products  = wc_get_products( $arguments );

			return $products;
		}

		$defaults = array(
			'posts_per_page' => 12,
			'orderby'        => 'date',
			'order'          => 'desc',
		);

		$args = wp_parse_args( $args, $defaults );

		$query_args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => $args['posts_per_page'],
			'orderby'             => $args['orderby'],
			'order'               => $args['order'],
			'meta_query'          => WC()->query->get_meta_query(),
		);

		if ( version_compare( self::wc_version(), '2.7.0', '>=' ) ) {
			$query_args['tax_query'] = WC()->query->get_tax_query();
		}

		return get_posts( $query_args );
	}

	/**
	 * List best selling products on sale.
	 *
	 * Works with WooCommerce Version 2.5.*, 2.6.*, 3.0.*, 3.1.*
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public function best_selling_products( $args = array() ) {

		$defaults = array(
			'posts_per_page' => 12,
		);

		$args = wp_parse_args( $args, $defaults );

		$query_args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => $args['posts_per_page'],
			'meta_key'            => 'total_sales',
			'orderby'             => 'meta_value_num',
			'meta_query'          => WC()->query->get_meta_query(),
		);

		if ( version_compare( Product::wc_version(), '2.7.0', '>=' ) ) {
			$query_args['tax_query'] = WC()->query->get_tax_query();
		}

		return get_posts( $query_args );
	}

	/**
	 * Get WooCommerce featured products
	 *
	 * Works with WooCommerce Version 2.5.*, 2.6.*, 3.0.*, 3.1.*
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public function featured_products( $args = array() ) {

		$defaults = array(
			'posts_per_page' => 12,
			'orderby'        => 'date',
			'order'          => 'desc',
		);

		$args = wp_parse_args( $args, $defaults );

		$meta_query = WC()->query->get_meta_query();

		if ( version_compare( Product::wc_version(), '2.7.0', '<' ) ) {
			$meta_query[] = array(
				'key'   => '_featured',
				'value' => 'yes'
			);
		}

		$query_args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => $args['posts_per_page'],
			'orderby'             => $args['orderby'],
			'order'               => $args['order'],
			'meta_query'          => $meta_query,
		);

		if ( version_compare( Product::wc_version(), '2.7.0', '>=' ) ) {
			$tax_query               = WC()->query->get_tax_query();
			$tax_query[]             = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
				'operator' => 'IN',
			);
			$query_args['tax_query'] = $tax_query;
		}

		return get_posts( $query_args );
	}

	/**
	 * List all products on sale.
	 *
	 * Works with WooCommerce Version 2.5.*, 2.6.*, 3.0.*, 3.1.*
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public function sale_products( $args = array() ) {
		$defaults = array(
			'posts_per_page' => 12,
			'orderby'        => 'title',
			'order'          => 'asc',
		);

		$args = wp_parse_args( $args, $defaults );

		$query_args = array(
			'posts_per_page' => $args['posts_per_page'],
			'orderby'        => $args['orderby'],
			'order'          => $args['order'],
			'no_found_rows'  => 1,
			'post_status'    => 'publish',
			'post_type'      => 'product',
			'meta_query'     => WC()->query->get_meta_query(),
			'post__in'       => array_merge( array( 0 ), wc_get_product_ids_on_sale() ),
		);

		if ( version_compare( Product::wc_version(), '2.7.0', '>=' ) ) {
			$query_args['tax_query'] = WC()->query->get_tax_query();
		}

		return get_posts( $query_args );
	}

	/**
	 * Get top rated products
	 *
	 * Works with WooCommerce Version 2.5.*, 2.6.*, 3.0.*, 3.1.*
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public function top_rated_products( $args = array() ) {
		$defaults = array(
			'posts_per_page' => 12,
			'orderby'        => 'title',
			'order'          => 'asc',
		);

		$args = wp_parse_args( $args, $defaults );

		if ( version_compare( Product::wc_version(), '2.7.0', '>=' ) ) {
			$query_args = array(
				'posts_per_page' => $args['posts_per_page'],
				'no_found_rows'  => 1,
				'post_status'    => 'publish',
				'post_type'      => 'product',
				'meta_key'       => '_wc_average_rating',
				'orderby'        => 'meta_value_num',
				'order'          => 'DESC',
				'meta_query'     => WC()->query->get_meta_query(),
				'tax_query'      => WC()->query->get_tax_query(),
			);
			$_posts     = new WP_Query( $query_args );

			return $_posts->posts;
		}

		// For WooCommerce version is less than 2.7.0
		add_filter( 'posts_clauses', array( WC()->query, 'order_by_rating_post_clauses' ) );

		$query_args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'orderby'             => $args['orderby'],
			'order'               => $args['order'],
			'posts_per_page'      => $args['posts_per_page'],
			'meta_query'          => WC()->query->get_meta_query(),
		);


		$_posts = new WP_Query( $query_args );

		remove_filter( 'posts_clauses', array( WC()->query, 'order_by_rating_post_clauses' ) );

		return $_posts->posts;
	}


	/**
	 * List all (or limited) product categories.
	 *
	 * Works with WooCommerce Version 2.5.*, 2.6.*, 3.0.*, 3.1.*
	 *
	 * @param array $args
	 *
	 * @return array|\WP_Error
	 */
	public function product_categories( $args = array() ) {

		$default = array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => 1,
			'orderby'    => 'name',
			'order'      => 'ASC',
		);

		$args = wp_parse_args( $args, $default );

		$terms = get_terms( $args );

		if ( is_wp_error( $terms ) ) {
			return array();
		}

		return $terms;
	}

	/**
	 * Get products by categories ids
	 *
	 * Works with WooCommerce Version 2.5.*, 2.6.*, 3.0.*, 3.1.*
	 *
	 * @param array $cat_ids
	 * @param int $per_page
	 *
	 * @return array
	 */
	public function products_by_categories( $cat_ids = array(), $per_page = 12 ) {
		$args = array(
			'post_type'          => 'product',
			'post_status'        => 'publish',
			'ignore_sticky_post' => 1,
			'posts_per_page'     => $per_page,
			'tax_query'          => array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $cat_ids,
					'operator' => 'IN',
				),
			),
		);

		return get_posts( $args );
	}

	/**
	 * Get products by tags ids
	 *
	 * Works with WooCommerce Version 2.5.*, 2.6.*, 3.0.*, 3.1.*
	 *
	 * @param array $cat_ids
	 * @param int $per_page
	 *
	 * @return array
	 */
	public function products_by_tags( $cat_ids = array(), $per_page = 12 ) {
		$args = array(
			'post_type'          => 'product',
			'post_status'        => 'publish',
			'ignore_sticky_post' => 1,
			'posts_per_page'     => $per_page,
			'tax_query'          => array(
				array(
					'taxonomy' => 'product_tag',
					'field'    => 'term_id',
					'terms'    => $cat_ids,
					'operator' => 'IN',
				),
			),
		);

		return get_posts( $args );
	}
}
