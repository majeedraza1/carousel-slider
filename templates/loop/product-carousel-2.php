<?php

defined( 'ABSPATH' ) || die;

/**
 * The global variable that are available to use here
 *
 * @global \CarouselSlider\Modules\ProductCarousel\Setting $setting Slider setting object.
 * @global \WC_Product $product The WooCommerce product object.
 * @global \WP_Post $post The post object.
 */


echo '<div class="product carousel-slider__product">';

do_action( 'carousel_slider_before_shop_loop_item', $product, $setting->get_slider_id() );

do_action( 'woocommerce_before_shop_loop_item' );
do_action( 'woocommerce_before_shop_loop_item_title' );
do_action( 'woocommerce_shop_loop_item_title' );
do_action( 'woocommerce_after_shop_loop_item_title' );
do_action( 'woocommerce_after_shop_loop_item' );

do_action( 'carousel_slider_after_shop_loop_item', $product, $setting->get_slider_id() );

echo '</div>';
