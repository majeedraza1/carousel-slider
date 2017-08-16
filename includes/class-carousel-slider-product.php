<?php
if ( ! class_exists( 'CarouselSliderProduct' ) ):

	class CarouselSliderProduct {

		protected static $instance = null;

		/**
		 * Ensures only one instance of this class is loaded or can be loaded.
		 *
		 * @return CarouselSliderProduct
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
						<?php echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . __( 'Sale!', 'carousel-slider' ) . '</span>', $post, $product ); ?>
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
	}


endif;

CarouselSliderProduct::init();
