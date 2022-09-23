<?php
defined( 'ABSPATH' ) || die;

/**
 * The global variable that are available to use here
 *
 * @global \CarouselSlider\Modules\ProductCarousel\Setting $setting Slider setting object.
 * @global \WC_Product $object The WooCommerce product object.
 */

echo '<div class="product carousel-slider__product">';

do_action( 'carousel_slider_before_shop_loop_item', $object );

// Show product image.
if ( $object->get_image_id() ) {
	echo '<a class="woocommerce-LoopProduct-link" href="' . esc_url( $object->get_permalink() ) . '">';
	if ( $setting->lazy_load_image() ) {
		$image      = wp_get_attachment_image_src( $object->get_image_id(), $setting->get_image_size() );
		$lazy_class = $setting->is_using_swiper() ? 'swiper-lazy' : 'owl-lazy';
		echo '<img class="' . esc_attr( $lazy_class ) . '" data-src="' . esc_url( $image[0] ) . '" />';
	} else {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $object->get_image( $setting->get_image_size() );
	}
	echo '</a>';
}

// Show title.
if ( $setting->get_prop( 'show_title' ) ) {
	echo '<a href="' . esc_attr( $object->get_permalink() ) . '">';
	echo '<h3 class="woocommerce-loop-product__title">' . esc_html( $object->get_title() ) . '</h3>';
	echo '</a>';
}

// Show Rating.
if ( $setting->get_prop( 'show_rating' ) ) {
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo wc_get_rating_html( $object->get_average_rating() );
}

// Sale Product batch.
if ( $object->is_on_sale() && $setting->get_prop( 'show_onsale_tag' ) ) {
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo apply_filters(
		'woocommerce_sale_flash',
		'<span class="onsale">' . __( 'Sale!', 'carousel-slider' ) . '</span>',
		get_post( $object->get_id() ),
		$object
	);
}

// Show Price.
if ( $setting->get_prop( 'show_price' ) ) {
	$price_html = '<span class="price">' . $object->get_price_html() . '</span>';
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo apply_filters( 'carousel_slider_product_price', $price_html, $object );
}

// Show button.
if ( $setting->get_prop( 'show_cart_button' ) ) {
	echo '<div style="clear: both;"></div>';
	woocommerce_template_loop_add_to_cart();
}

do_action( 'carousel_slider_after_shop_loop_item', $object, $setting->get_slider_id(), $setting );

echo '</div>';
