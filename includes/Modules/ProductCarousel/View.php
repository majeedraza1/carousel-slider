<?php

namespace CarouselSlider\Modules\ProductCarousel;

use CarouselSlider\Abstracts\AbstractView;
use CarouselSlider\Supports\Utils;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class View extends AbstractView {

	/**
	 * Render element.
	 * Generates the final HTML on the frontend.
	 */
	public function render() {

		// Check if WooCommerce is active
		if ( ! Utils::is_woocommerce_active() ) {
			if ( current_user_can( 'manage_options' ) ) {
				$html = '<div style="background-color:#ffdddd;border-left:5px solid #f44336;margin: 15px 0;padding: 15px;">';
				$html .= sprintf(
					esc_html__( 'Carousel Slider needs %s to work for products carousel.', 'carousel-slider' ),
					sprintf( '<a href="https://wordpress.org/plugins/woocommerce/" target="_blank" >%s</a>',
						__( 'WooCommerce', 'carousel-slider' )
					)
				);
				$html .= '</div>';

				return $html;
			}

			return '';
		}

		// Check if category list slider
		if ( $this->is_product_categories_list() ) {
			return $this->product_categories();
		}

		global $post;
		global $product;
		$posts = Utils::get_products( $this->get_slider_id() );

		$this->set_total_slides( count( $posts ) );

		$html = $this->slider_wrapper_start();

		foreach ( $posts as $post ) {
			setup_postdata( $post );
			$product = wc_get_product( $post );

			ob_start();
			echo '<div class="product carousel-slider__product">';

			do_action( 'carousel_slider_product_loop', $product, $post );
			do_action( 'carousel_slider_before_shop_loop_item', $product );

			do_action( 'woocommerce_before_shop_loop_item' );
			do_action( 'woocommerce_before_shop_loop_item_title' );
			do_action( 'woocommerce_shop_loop_item_title' );
			do_action( 'woocommerce_after_shop_loop_item_title' );
			do_action( 'woocommerce_after_shop_loop_item' );

			do_action( 'carousel_slider_after_shop_loop_item', $product, $post, $this->get_slider_id() );

			echo '</div>';

			$html .= ob_get_contents();
			ob_end_clean();

		}
		wp_reset_postdata();

		$html .= $this->slider_wrapper_end();

		return $html;
	}

	/**
	 * Get query type
	 *
	 * @return string
	 */
	protected function query_type() {
		$valid      = array( 'query_porduct', 'product_categories', 'product_tags', 'specific_products' );
		$query_type = $this->get_meta( '_product_query_type' );

		return in_array( $query_type, $valid ) ? $query_type : 'query_porduct';
	}

	/**
	 * Get product query
	 *
	 * @return string
	 */
	protected function query() {
		$valid = array( 'featured', 'recent', 'sale', 'best_selling', 'top_rated', 'product_categories_list' );
		$query = $this->get_meta( '_product_query' );

		return in_array( $query, $valid ) ? $query : 'recent';
	}

	/**
	 * Check if product categories list slider
	 *
	 * @return bool
	 */
	protected function is_product_categories_list() {
		return ( $this->query_type() == 'query_porduct' && $this->query() == 'product_categories_list' );
	}

	/**
	 * Get product categories list carousel
	 *
	 * @return string
	 */
	private function product_categories() {

		$product_categories = \CarouselSlider\Product::product_categories();
		$count              = count( $product_categories );

		if ( ! $count ) {
			return '';
		}

		$this->set_total_slides( $count );

		$html = $this->slider_wrapper_start();

		foreach ( $product_categories as $category ) {
			ob_start();

			echo '<div class="product carousel-slider__product">';
			do_action( 'woocommerce_before_subcategory', $category );
			do_action( 'woocommerce_before_subcategory_title', $category );
			do_action( 'woocommerce_shop_loop_subcategory_title', $category );
			do_action( 'woocommerce_after_subcategory_title', $category );
			do_action( 'woocommerce_after_subcategory', $category );
			echo '</div>';

			$html .= ob_get_contents();
			ob_end_clean();
		}

		$html .= $this->slider_wrapper_end();

		return $html;
	}
}
