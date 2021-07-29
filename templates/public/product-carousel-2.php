<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! carousel_slider_is_woocommerce_active() ) {
	if ( current_user_can( 'manage_options' ) ) {
		printf(
			esc_html__( 'Carousel Slider needs %s to work for products carousel.', 'carousel-slider' ),
			sprintf( '<a href="https://wordpress.org/plugins/woocommerce/" target="_blank" >%s</a>',
				__( 'WooCommerce', 'carousel-slider' )
			)
		);
	}

	return;
}
?>
<div class="products carousel-slider-outer carousel-slider-outer-products carousel-slider-outer-<?php echo $id; ?>">
	<?php carousel_slider_inline_style( $id ); ?>
    <div <?php echo join( " ", $this->carousel_options( $id ) ); ?>>
		<?php
		global $post;
		global $product;
		$posts = carousel_slider_products( $id );

		foreach ( $posts as $post ):
			setup_postdata( $post );
			$product = wc_get_product( $post );

			if ( ! $product->is_visible() ) {
				continue;
			}

			echo '<div class="product carousel-slider__product">';

			do_action( 'carousel_slider_product_loop', $product );
			do_action( 'carousel_slider_before_shop_loop_item', $product );

			do_action( 'woocommerce_before_shop_loop_item' );
			do_action( 'woocommerce_before_shop_loop_item_title' );
			do_action( 'woocommerce_shop_loop_item_title' );
			do_action( 'woocommerce_after_shop_loop_item_title' );
			do_action( 'woocommerce_after_shop_loop_item' );

			do_action( 'carousel_slider_after_shop_loop_item', $product, $post, $id );

			echo '</div>';

		endforeach;
		wp_reset_postdata();

		?>
    </div>
</div>
