<?php
defined( 'ABSPATH' ) || die;

/**
 * The global variable that are available to use here
 *
 * This template can be overridden by copying it to yourtheme/carousel-slider/loop/product-categories-list.php.
 *
 * @global \CarouselSlider\Modules\ProductCarousel\Setting $setting Slider setting object.
 * @global \WP_Term $object The WP_Term object.
 */

echo '<div class="product carousel-slider__product">';
do_action( 'woocommerce_before_subcategory', $object );
do_action( 'woocommerce_before_subcategory_title', $object );
do_action( 'woocommerce_shop_loop_subcategory_title', $object );
do_action( 'woocommerce_after_subcategory_title', $object );
do_action( 'woocommerce_after_subcategory', $object );
echo '</div>' . PHP_EOL;
