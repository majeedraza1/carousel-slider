<?php

namespace CarouselSlider\Modules\ProductCarousel;

use Automattic\WooCommerce\Utilities\FeaturesUtil;
use WC_Product;

defined( 'ABSPATH' ) || exit;

/**
 * Module class
 *
 * @package Modules/ProductCarousel
 */
class Module {
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

			add_filter( 'carousel_slider/register_view', [ self::$instance, 'view' ] );

			add_action( 'carousel_slider_after_shop_loop_item', [ self::$instance, 'quick_view_button' ], 10, 2 );
			add_action( 'carousel_slider_after_shop_loop_item', [ self::$instance, 'wish_list_button' ], 12, 2 );

			add_action( 'wp_ajax_carousel_slider_quick_view', [ self::$instance, 'quick_view' ] );
			add_action( 'wp_ajax_nopriv_carousel_slider_quick_view', [ self::$instance, 'quick_view' ] );

			add_action( 'before_woocommerce_init', [ self::$instance, 'declaring_extension_compatibility' ] );

			Admin::init();
		}

		return self::$instance;
	}

	/**
	 * Declaring extension compatibility
	 */
	public function declaring_extension_compatibility() {
		if ( class_exists( FeaturesUtil::class ) ) {
			FeaturesUtil::declare_compatibility( 'custom_order_tables', CAROUSEL_SLIDER_FILE, true );
		}
	}

	/**
	 * Register view
	 *
	 * @param  array $views  Registered views.
	 *
	 * @return array
	 */
	public function view( array $views ): array {
		$views['product-carousel'] = new View();

		return $views;
	}

	/**
	 * Show quick view button on product slider
	 *
	 * @param  WC_Product $product  The WC_Product object.
	 * @param  int        $slider_id  The slider id.
	 */
	public static function quick_view_button( $product, $slider_id ) {
		$_show_btn = get_post_meta( $slider_id, '_product_quick_view', true );

		if ( 'on' === $_show_btn ) {
			wp_enqueue_script( 'magnific-popup' );

			$quick_view_html  = '<div style="clear: both;"></div>';
			$quick_view_html .= sprintf(
				'<a class="magnific-popup button quick_view" href="%1$s" data-product-id="%2$s">%3$s</a>',
				Helper::get_product_quick_view_url( $product->get_id() ),
				$product->get_id(),
				__( 'Quick View', 'carousel-slider' )
			);
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo apply_filters( 'carousel_slider_product_quick_view', $quick_view_html, $product );
		}
	}

	/**
	 * Show YITH Wishlist button on product slider
	 *
	 * @param  WC_Product $product  The WC_Product object.
	 * @param  int        $slider_id  The slider id.
	 */
	public static function wish_list_button( $product, $slider_id ) {
		$_product_wish_list = get_post_meta( $slider_id, '_product_wishlist', true );
		if ( class_exists( 'YITH_WCWL' ) && 'on' === $_product_wish_list ) {
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
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo apply_filters( 'carousel_slider/product_quick_view_html', $html, $product );
		wp_die();
	}

	/**
	 * Get quick view html
	 *
	 * @param  WC_Product $product  The WC_Product object.
	 *
	 * @return string
	 */
	public static function get_quick_view_html( WC_Product $product ): string {
		ob_start();
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$slider_id = isset( $_GET['slide_id'] ) ? intval( $_GET['slide_id'] ) : 0;
		?>
		<div id="pmid-<?php echo esc_attr( $slider_id ); ?>" class="product carousel-slider__product-modal">

			<div class="images">
				<?php echo get_the_post_thumbnail( $product->get_id(), 'medium_large' ); ?>
				<?php if ( $product->is_on_sale() ) : ?>
					<?php
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo apply_filters(
						'woocommerce_sale_flash',
						'<span class="onsale">' . __( 'Sale!', 'carousel-slider' ) . '</span>',
						$product
					);
					?>
				<?php endif; ?>
			</div>

			<div class="summary entry-summary">

				<h1 class="product_title entry-title">
					<?php echo esc_html( $product->get_title() ); ?>
				</h1>

				<div class="woocommerce-product-rating">
					<?php
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo wc_get_rating_html( $product->get_average_rating() );
					?>
				</div>

				<div class="price">
					<?php
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo $product->get_price_html();
					?>
				</div>

				<div class="description">
					<div style="clear: both;"></div>
					<?php
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo apply_filters( 'woocommerce_short_description', $product->get_description() );
					?>
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
