<?php

namespace CarouselSlider\Modules\ProductCarousel;

use CarouselSlider\Frontend\Shortcode;
use CarouselSlider\Helper;

defined( 'ABSPATH' ) || exit;

class CategoryCarouselView {
	/**
	 * Get view
	 *
	 * @param int $slider_id
	 *
	 * @return string
	 */
	public static function get_view( int $slider_id ): string {
		$categories = ProductCarouselHelper::product_categories();
		$css_vars   = Helper::get_css_variable( $slider_id );
		$options    = ( new Shortcode )->carousel_options( $slider_id );

		$css_classes = [
			"carousel-slider-outer",
			"carousel-slider-outer-products",
			"carousel-slider-outer-{$slider_id}"
		];
		$styles      = [];
		foreach ( $css_vars as $key => $var ) {
			$styles[] = sprintf( "%s:%s", $key, $var );
		}

		ob_start();
		echo '<div class="' . join( ' ', $css_classes ) . '" style="' . implode( ';', $styles ) . '">';
		echo '<div ' . join( " ", $options ) . '>';
		foreach ( $categories as $category ) {
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
		$html = ob_get_clean();

		return apply_filters( 'carousel_slider_product_carousel', $html, $slider_id );
	}
}
