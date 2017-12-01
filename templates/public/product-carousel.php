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

$posts = carousel_slider_products( $id );


$_image_size       = get_post_meta( $id, '_image_size', true );
$_nav_color        = get_post_meta( $id, '_nav_color', true );
$_nav_active_color = get_post_meta( $id, '_nav_active_color', true );
$_lazy_load_image  = get_post_meta( $id, '_lazy_load_image', true );

$_product_title      = get_post_meta( $id, '_product_title', true );
$_product_rating     = get_post_meta( $id, '_product_rating', true );
$_product_price      = get_post_meta( $id, '_product_price', true );
$_product_cart_btn   = get_post_meta( $id, '_product_cart_button', true );
$_product_onsale     = get_post_meta( $id, '_product_onsale', true );
$_product_wishlist   = get_post_meta( $id, '_product_wishlist', true );
$_product_quick_view = get_post_meta( $id, '_product_quick_view', true );
?>
<div class="carousel-slider-outer carousel-slider-outer-products carousel-slider-outer-<?php echo $id; ?>">
	<?php carousel_slider_inline_style( $id ); ?>
    <div <?php echo join( " ", $this->carousel_options( $id ) ); ?>>
		<?php foreach ( $posts as $post ): setup_postdata( $post ); ?>
			<?php
			$product = wc_get_product( $post->ID );
			do_action( 'carousel_slider_product_loop', $product, $post );
			?>
            <div class="product carousel-slider__product">
				<?php
				echo sprintf( '<a class="woocommerce-LoopProduct-link" href="%s">', get_the_permalink( $post->ID ) );
				// Post Thumbnail
				if ( has_post_thumbnail( $post->ID ) ) {
					if ( $_lazy_load_image == 'on' ) {
						$image_src = get_the_post_thumbnail_url( $post->ID, $_image_size );
						echo sprintf( '<img class="owl-lazy" data-src="%1$s" />', $image_src );
					} else {
						$image_src = get_the_post_thumbnail_url( $post->ID, $_image_size );
						echo sprintf( '<img src="%1$s" />', $image_src );
					}
				}
				echo "</a>";

				// Show title
				if ( $_product_title == 'on' ) {
					echo sprintf( '<a href="%1$s"><h3>%2$s</h3></a>', get_the_permalink( $post->ID ), get_the_title( $post->ID ) );
				}

				// Show Rating
				if ( $_product_rating == 'on' ) {
					if ( version_compare( WC_VERSION, "3.0.0", ">=" ) ) {
						echo wc_get_rating_html( $product->get_average_rating() );
					} else {
						echo $product->get_rating_html();
					}

				}
				// Sale Product batch
				if ( $product->is_on_sale() && $_product_onsale == 'on' ) {
					echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . __( 'Sale!', 'carousel-slider' ) . '</span>', $product );
				}
				// Show Price
				if ( $_product_price == 'on' ) {
					$price_html = '<span class="price">' . $product->get_price_html() . '</span>';
					echo apply_filters( 'carousel_slider_product_price', $price_html, $product );
				}
				// Show button
				if ( $_product_cart_btn == 'on' ) {
					$button_html = '<div style="clear: both;"></div>';
					ob_start();
					if ( function_exists( 'woocommerce_template_loop_add_to_cart' ) ) {
						woocommerce_template_loop_add_to_cart();
					}
					$button_html .= ob_get_contents();
					ob_end_clean();
					echo apply_filters( 'carousel_slider_product_add_to_cart', $button_html, $product );
				}

				if ( $_product_quick_view == 'on' ) {
					wp_enqueue_script( 'magnific-popup' );
					$ajax_url        = wp_nonce_url( add_query_arg( array(
						'ajax'       => 'true',
						'action'     => 'carousel_slider_quick_view',
						'product_id' => $post->ID,
						'slide_id'   => $id
					), admin_url( 'admin-ajax.php' ) ), 'carousel_slider_quick_view' );
					$quick_view_html = '<div style="clear: both;"></div>';
					$quick_view_html .= sprintf(
						'<a class="magnific-popup button quick_view" href="%1$s" data-product-id="%2$s">%3$s</a>',
						$ajax_url,
						$post->ID,
						__( 'Quick View', 'carousel-slider' )
					);
					echo apply_filters( 'carousel_slider_product_quick_view', $quick_view_html, $product );
				}

				// WooCommerce Wishlist
				if ( class_exists( 'YITH_WCWL' ) && $_product_wishlist == 'on' ) {
					echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
				}
				?>
            </div>
		<?php endforeach;
		wp_reset_postdata(); ?>
    </div>
</div>
