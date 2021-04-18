<?php

namespace CarouselSlider\Modules\ProductCarousel;

use CarouselSlider\Helper;

defined( 'ABSPATH' ) || exit;

class CategoryCarouselView {
	/**
	 * Get view
	 *
	 * @param int $slider_id
	 * @param string $slider_type
	 *
	 * @return string
	 */
	public static function get_view( int $slider_id, string $slider_type ): string {
		$categories = ProductCarouselHelper::product_categories();

		$css_classes = [
			"carousel-slider-outer",
			"carousel-slider-outer-products",
			"carousel-slider-outer-{$slider_id}"
		];

		$attributes_array = Helper::get_slider_attributes( $slider_id, $slider_type );

		ob_start();
		echo '<div class="' . join( ' ', $css_classes ) . '">';
		echo "<div " . join( " ", $attributes_array ) . ">";
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
