<?php

namespace CarouselSlider\Modules\ProductCarousel;

class View {

	protected static $instance = null;

	/**
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @return View
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action( 'carousel_slider_view', array( $this, 'product_carousel_view' ), 10, 3 );
	}

	/**
	 * Hero carousel view
	 *
	 * @param $id
	 * @param string $slide_type
	 * @param array $slide_options
	 *
	 * @return void
	 */
	public function product_carousel_view( $id, $slide_type, $slide_options ) {

		if ( 'product-carousel' == $slide_type ) {
			ob_start();


			$query_type    = get_post_meta( $id, '_product_query_type', true );
			$query_type    = empty( $query_type ) ? 'query_porduct' : $query_type;
			$product_query = get_post_meta( $id, '_product_query', true );

			if ( $query_type == 'query_porduct' && $product_query == 'product_categories_list' ) {
				echo $this->product_categories( $id, $slide_options );
			} else {
				require CAROUSEL_SLIDER_MODULES . '/product-carousel/views/public/product-carousel.php';
			}


			$html = ob_get_contents();
			ob_end_clean();

			echo apply_filters( 'carousel_slider_product_carousel', $html, $id, $slide_options );
		}
	}

	/**
	 * Get product categories list carousel
	 *
	 * @param int $id
	 *
	 * @param array $slide_options
	 *
	 * @return string
	 */
	private function product_categories( $id, $slide_options ) {

		$product_carousel   = new \Carousel_Slider_Product();
		$product_categories = $product_carousel->product_categories();

		$options = join( " ", carousel_slider_array_to_attribute( $slide_options ) );

		ob_start();
		if ( $product_categories ) {
			echo '<div class="carousel-slider-outer carousel-slider-outer-products carousel-slider-outer-' . $id . '">';
			carousel_slider_inline_style( $id );
			echo '<div ' . $options . '>';


			foreach ( $product_categories as $category ) {
				echo '<div class="product carousel-slider__product">';
				do_action( 'woocommerce_before_subcategory', $category );
				do_action( 'woocommerce_before_subcategory_title', $category );
				do_action( 'woocommerce_shop_loop_subcategory_title', $category );
				do_action( 'woocommerce_after_subcategory_title', $category );
				do_action( 'woocommerce_after_subcategory', $category );
				echo '</div>';
			}

			echo '</div>';
			echo '</div>';
		}

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}
}

View::init();
