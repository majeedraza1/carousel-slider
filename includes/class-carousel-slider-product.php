<?php
if ( ! class_exists( 'Carousel_Slider_Product' ) ):

	class Carousel_Slider_Product {

		protected static $instance = null;

		/**
		 * Ensures only one instance of this class is loaded or can be loaded.
		 *
		 * @return Carousel_Slider_Product
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {
			add_action( 'wp_ajax_carousel_slider_quick_view', array( $this, 'quick_view' ) );
			add_action( 'wp_ajax_nopriv_carousel_slider_quick_view', array( $this, 'quick_view' ) );
		}

		public function quick_view() {
			if ( ! isset( $_GET['_wpnonce'], $_GET['product_id'], $_GET['slide_id'] ) ) {
				wp_die();
			}

			if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'carousel_slider_quick_view' ) ) {
				wp_die();
			}

			global $product;
			$product = wc_get_product( $_GET['product_id'] );

			?>
            <div id="pmid-<?php echo intval( $_GET['slide_id'] ); ?>" class="product carousel-slider__product-modal">

                <div class="images">
					<?php echo get_the_post_thumbnail( $product->get_id(), 'medium_large' ); ?>
					<?php if ( $product->is_on_sale() ) : ?>
						<?php echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . __( 'Sale!', 'carousel-slider' ) . '</span>', $product ); ?>
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
						} elseif ( $product->get_rating_html() ) {
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
						echo apply_filters( 'woocommerce_short_description', $product->post->post_excerpt );
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
		 * Get Recent Products
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
				'tax_query'           => WC()->query->get_tax_query(),
			);

			return get_posts( $query_args );
		}

		/**
		 * List best selling products on sale.
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
				'tax_query'           => WC()->query->get_tax_query(),
			);

			return get_posts( $query_args );
		}

		/**
		 * Get WooCommerce featured products
		 *
		 * Works with WooCommerce Version 3.0
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

			$meta_query  = WC()->query->get_meta_query();
			$tax_query   = WC()->query->get_tax_query();
			$tax_query[] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
				'operator' => 'IN',
			);

			$query_args = array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => $args['posts_per_page'],
				'orderby'             => $args['orderby'],
				'order'               => $args['order'],
				'meta_query'          => $meta_query,
				'tax_query'           => $tax_query,
			);

			return get_posts( $query_args );
		}


		/**
		 * List all (or limited) product categories.
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
	}


endif;

Carousel_Slider_Product::init();
