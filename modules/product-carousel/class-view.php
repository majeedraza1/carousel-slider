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
			$posts         = array();

			if ( $query_type == 'query_porduct' && $product_query == 'product_categories_list' ) {
				echo $this->product_categories( $id, $slide_options );
			} else {
				$posts = $this->carousel_slider_products( $id );
				require CAROUSEL_SLIDER_MODULES . '/product-carousel/views/public/product-carousel.php';
			}


			$html = ob_get_contents();
			ob_end_clean();

			echo apply_filters( 'carousel_slider_product_carousel', $html, $id, $slide_options, $posts );
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

		$product_carousel   = new Product();
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

	/**
	 * Get products by carousel slider ID
	 *
	 * @param $carousel_id
	 *
	 * @return array
	 */
	public function carousel_slider_products( $carousel_id ) {
		$id            = $carousel_id;
		$per_page      = intval( get_post_meta( $id, '_products_per_page', true ) );
		$query_type    = get_post_meta( $id, '_product_query_type', true );
		$query_type    = empty( $query_type ) ? 'query_porduct' : $query_type;
		$product_query = get_post_meta( $id, '_product_query', true );

		$product_carousel = new Product();

		$args = array( 'posts_per_page' => $per_page );

		if ( $query_type == 'query_porduct' ) {

			// Get features products
			if ( $product_query == 'featured' ) {
				return $product_carousel->featured_products( $args );
			}

			// Get best_selling products
			if ( $product_query == 'best_selling' ) {
				return $product_carousel->best_selling_products( $args );
			}

			// Get recent products
			if ( $product_query == 'recent' ) {
				return $product_carousel->recent_products( $args );
			}

			// Get sale products
			if ( $product_query == 'sale' ) {
				return $product_carousel->sale_products( $args );
			}

			// Get top_rated products
			if ( $product_query == 'top_rated' ) {
				return $product_carousel->top_rated_products( $args );
			}
		}

		// Get products by product IDs
		if ( $query_type == 'specific_products' ) {
			$product_in = get_post_meta( $id, '_product_in', true );
			$product_in = array_map( 'intval', explode( ',', $product_in ) );

			return $product_carousel->products( array( 'post__in' => $product_in ) );
		}

		// Get posts by post categories IDs
		if ( $query_type == 'product_categories' ) {
			$product_cat_ids = get_post_meta( $id, '_product_categories', true );
			$product_cat_ids = array_map( 'intval', explode( ",", $product_cat_ids ) );

			return $product_carousel->products_by_categories( $product_cat_ids, $per_page );
		}

		// Get posts by post tags IDs
		if ( $query_type == 'product_tags' ) {
			$product_tags = get_post_meta( $id, '_product_tags', true );
			$product_tags = array_map( 'intval', explode( ',', $product_tags ) );

			return $product_carousel->products_by_tags( $product_tags, $per_page );
		}

		return array();
	}
}

View::init();
