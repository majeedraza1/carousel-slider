<?php
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

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

if ( ! class_exists( 'Carousel_Slider_Product' ) ) {

	class Carousel_Slider_Product {

		/**
		 * The instance of the class
		 *
		 * @var self
		 */
		private static $instance;

		/**
		 * Product carousel quick view
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();

				add_action( 'carousel_slider_after_shop_loop_item', [ self::$instance, 'quick_view_button' ], 10, 3 );
				add_action( 'carousel_slider_after_shop_loop_item', [ self::$instance, 'wish_list_button' ], 12, 3 );

				add_action( 'wp_ajax_carousel_slider_quick_view', array( self::$instance, 'quick_view' ) );
				add_action( 'wp_ajax_nopriv_carousel_slider_quick_view', array( self::$instance, 'quick_view' ) );
			}

			return self::$instance;
		}

		/**
		 * Show YITH Wishlist button on product slider
		 *
		 * @param WC_Product $product
		 * @param WP_Post $post
		 * @param $slider_id
		 */
		public static function wish_list_button( $product, $post, $slider_id ) {
			$_product_wish_list = get_post_meta( $slider_id, '_product_wishlist', true );

			if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.7.0', '>=' ) ) {
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
		 * @param WC_Product $product
		 * @param WP_Post $post
		 * @param $slider_id
		 */
		public static function quick_view_button( $product, $post, $slider_id ) {
			$_show_btn = get_post_meta( $slider_id, '_product_quick_view', true );

			if ( $_show_btn == 'on' ) {

				if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.7.0', '>=' ) ) {
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
		 * Display quick view popup content
		 */
		public static function quick_view() {
			if ( ! isset( $_GET['_wpnonce'], $_GET['product_id'], $_GET['slide_id'] ) ) {
				wp_die();
			}

			if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'carousel_slider_quick_view' ) ) {
				wp_die();
			}

			global $product;
			$product = wc_get_product( intval( $_GET['product_id'] ) );

			?>
			<div id="pmid-<?php echo intval( $_GET['slide_id'] ); ?>" class="product carousel-slider__product-modal">

				<div class="images">
					<?php echo get_the_post_thumbnail( $product->get_id(), 'medium_large' ); ?>
					<?php if ( $product->is_on_sale() ) : ?>
						<?php echo apply_filters( 'woocommerce_sale_flash',
							'<span class="onsale">' . __( 'Sale!', 'carousel-slider' ) . '</span>', $product ); ?>
					<?php endif; ?>
				</div>

				<div class="summary entry-summary">

					<h1 class="product_title entry-title">
						<?php echo esc_attr( $product->get_title() ); ?>
					</h1>

					<div class="woocommerce-product-rating">
						<?php
						// Check if WooCommerce Version 3.0.0 or higher
						if ( function_exists( 'wc_get_rating_html' ) ) {
							echo wc_get_rating_html( $product->get_average_rating() );
						} elseif ( method_exists( $product, 'get_rating_html' ) ) {
							echo $product->get_rating_html();
						}
						?>
					</div>

					<div class="price">
						<?php
						if ( $product->get_price_html() ) {
							echo $product->get_price_html();
						}
						?>
					</div>

					<div class="description">
						<?php
						echo '<div style="clear: both;"></div>';
						echo apply_filters( 'woocommerce_short_description', $product->get_description() );
						?>
					</div>

					<div>
						<?php
						// Show button
						echo '<div style="clear: both;"></div>';
						if ( function_exists( 'woocommerce_template_loop_add_to_cart' ) ) {
							woocommerce_template_loop_add_to_cart();
						}
						?>
					</div>

				</div>
			</div>
			<?php
			wp_die();
		}

		/**
		 * Get WooCommerce version
		 *
		 * @return string
		 */
		private function wc_version() {
			if ( defined( 'WC_VERSION' ) ) {
				return WC_VERSION;
			}

			return '0.0.0';
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
		public function products( $args = array() ) {
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

			if ( version_compare( $this->wc_version(), '2.7.0', '>=' ) ) {
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
		public function recent_products( $args = array() ) {

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

			if ( version_compare( $this->wc_version(), '2.7.0', '>=' ) ) {
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

			if ( version_compare( $this->wc_version(), '2.7.0', '>=' ) ) {
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

			if ( version_compare( $this->wc_version(), '2.7.0', '<' ) ) {
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

			if ( version_compare( $this->wc_version(), '2.7.0', '>=' ) ) {
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

			if ( version_compare( $this->wc_version(), '2.7.0', '>=' ) ) {
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

			if ( version_compare( $this->wc_version(), '2.7.0', '>=' ) ) {
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
		 * @return array|int|WP_Error
		 */
		public function product_categories( $args = array() ) {

			$default = array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => 1,
				'orderby'    => 'name',
				'order'      => 'ASC',
			);

			$args = wp_parse_args( $args, $default );

			return get_terms( $args );
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
}

Carousel_Slider_Product::init();
