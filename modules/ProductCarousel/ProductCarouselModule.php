<?php

namespace CarouselSlider\Modules\ProductCarousel;

use WC_Product;

defined( 'ABSPATH' ) || exit;

class ProductCarouselModule {
	/**
	 * The instance of the class
	 *
	 * @var self
	 */
	protected static $instance;

	/**
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_filter( 'carousel_slider/view', [ self::$instance, 'view' ], 10, 3 );

			add_action( 'carousel_slider_after_shop_loop_item', [ self::$instance, 'quick_view_button' ], 10, 2 );
			add_action( 'carousel_slider_after_shop_loop_item', [ self::$instance, 'wish_list_button' ], 12, 2 );

			add_action( 'wp_ajax_carousel_slider_quick_view', [ self::$instance, 'quick_view' ] );
			add_action( 'wp_ajax_nopriv_carousel_slider_quick_view', [ self::$instance, 'quick_view' ] );

			ProductCarouselAdmin::init();
		}

		return self::$instance;
	}

	public function view( string $html, int $slider_id, string $slider_type ): string {
		if ( 'product-carousel' != $slider_type ) {
			return $html;
		}

		$query_type    = get_post_meta( $slider_id, '_product_query_type', true );
		$query_type    = empty( $query_type ) ? 'query_product' : $query_type;
		$query_type    = ( 'query_porduct' == $query_type ) ? 'query_product' : $query_type; // Type mistake
		$product_query = get_post_meta( $slider_id, '_product_query', true );

		if ( $query_type == 'query_product' && $product_query == 'product_categories_list' ) {
			return ProductCarouselView::get_category_view( $slider_id, $slider_type );
		}

		return ProductCarouselView::get_view( $slider_id, $slider_type );
	}

	/**
	 * Show quick view button on product slider
	 *
	 * @param WC_Product $product
	 * @param int $slider_id
	 */
	public static function quick_view_button( $product, $slider_id ) {
		$_show_btn = get_post_meta( $slider_id, '_product_quick_view', true );

		if ( $_show_btn == 'on' ) {
			wp_enqueue_script( 'magnific-popup' );

			$quick_view_html = '<div style="clear: both;"></div>';
			$quick_view_html .= sprintf(
				'<a class="magnific-popup button quick_view" href="%1$s" data-product-id="%2$s">%3$s</a>',
				ProductCarouselHelper::get_product_quick_view_url( $product->get_id() ),
				$product->get_id(),
				__( 'Quick View', 'carousel-slider' )
			);
			echo apply_filters( 'carousel_slider_product_quick_view', $quick_view_html, $product );
		}
	}

	/**
	 * Show YITH Wishlist button on product slider
	 *
	 * @param WC_Product $product
	 * @param $slider_id
	 */
	public static function wish_list_button( $product, $slider_id ) {
		$_product_wish_list = get_post_meta( $slider_id, '_product_wishlist', true );
		if ( class_exists( 'YITH_WCWL' ) && $_product_wish_list == 'on' ) {
			echo do_shortcode( '[yith_wcwl_add_to_wishlist product_id="' . $product->get_id() . '"]' );
		}
	}

	/**
	 * Display quick view popup content
	 */
	public static function quick_view() {
		if ( ! isset( $_GET['_wpnonce'], $_GET['product_id'] ) ) {
			wp_die();
		}

		if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'carousel_slider_quick_view' ) ) {
			wp_die();
		}

		global $product;
		$product = wc_get_product( intval( $_GET['product_id'] ) );
		$html    = static::get_quick_view_html( $product );
		echo apply_filters( 'carousel_slider/product_quick_view_html', $html, $product );
		wp_die();
	}

	/**
	 * Get quick view html
	 *
	 * @param WC_Product $product
	 *
	 * @return string
	 */
	public static function get_quick_view_html( WC_Product $product ): string {
		ob_start();
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
					<?php echo esc_html( $product->get_title() ); ?>
				</h1>

				<div class="woocommerce-product-rating">
					<?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
				</div>

				<div class="price">
					<?php echo $product->get_price_html(); ?>
				</div>

				<div class="description">
					<div style="clear: both;"></div>
					<?php echo apply_filters( 'woocommerce_short_description', $product->get_description() ); ?>
				</div>

				<div>
					<div style="clear: both;"></div>
					<?php woocommerce_template_loop_add_to_cart(); ?>
				</div>

			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
