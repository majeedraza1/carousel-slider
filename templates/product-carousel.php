<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'WC_VERSION' ) ) {
	if ( current_user_can( 'manage_options' ) ){
		echo sprintf( esc_html__( 'Carousel Slider needs %s to work for products carousel.', 'carousel-slider' ), sprintf('<a href="https://wordpress.org/plugins/woocommerce/" target="_blank" >%s</a>', __('WooCommerce', 'carousel-slider')) );
	}
	return;
}

$args = array(
	'post_type' 			=> 'product',
	'post_status' 			=> 'publish',
	'ignore_sticky_post' 	=> 1,
	'posts_per_page'    	=> intval(get_post_meta( $id, '_products_per_page', true ))
);


$order   		= get_post_meta( $id, '_product_order', true );
$orderby 		= get_post_meta( $id, '_product_orderby', true );
$posts_per_page = intval(get_post_meta( $id, '_products_per_page', true ));

$query_type 	= get_post_meta( $id, '_product_query_type', true );
$query_type 	= empty($query_type) ? 'query_porduct' : $query_type;
$product_query 	= get_post_meta( $id, '_product_query', true );
$products 		= array();

// Get products by product IDs
if ( $query_type == 'specific_products' ) {
	$products_ids = explode(',', get_post_meta( $id, '_product_in', true ));

	$posts = array_map(function($value){
		return get_post( intval( $value ) );
	}, $products_ids);
}

if ( $query_type == 'query_porduct' ) {

	// Get features products
	if ( $product_query == 'featured' ) {
		$args = array_merge($args, array(
			'meta_key' 			=> '_featured',
			'meta_value' 		=> 'yes',
			'orderby'  			=> 'date',
			'order'  			=> 'desc'
		));
		$posts = get_posts( $args );
	}

	// Get recent products
	if ( $product_query == 'recent' ) {
		$args = array_merge($args, array(
			'ignore_sticky_posts' => 1,
			'orderby'             => 'date',
			'order'               => 'desc',
			'meta_query'          => WC()->query->get_meta_query()
		));
		$posts = get_posts( $args );
	}

	// Get sale products
	if ( $product_query == 'sale' ) {

		$args = array_merge($args, array(
			'orderby'  		=> 'title',
			'order'    		=> 'asc',
			'no_found_rows' => 1,
			'meta_query' 	=> WC()->query->get_meta_query(),
			'post__in'      => array_merge( array( 0 ), wc_get_product_ids_on_sale() )
		));
		$posts = get_posts( $args );
	}

	// Get best_selling products
	if ( $product_query == 'best_selling' ) {
		$args = array(
			'ignore_sticky_posts' => 1,
			'meta_key'            => 'total_sales',
			'orderby'             => 'meta_value_num',
			'meta_query'          => WC()->query->get_meta_query()
		);
		$posts = get_posts( $args );
	}

	// Get top_rated products
	if ( $product_query == 'top_rated' ) {

		add_filter( 'posts_clauses',  array( WC()->query, 'order_by_rating_post_clauses' ) );
		$args = array_merge($args, array(
			'no_found_rows' => 1,
			'meta_query' 	=> WC()->query->get_meta_query(),
		));
		$posts = get_posts( $args );
	}

}

// Get posts by post catagories IDs
if ( $query_type == 'product_categories' ) {
	$product_categories 	= get_post_meta( $id, '_product_categories', true );
	$args = array_merge($args, array(
		'tax_query' => array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => explode(",", $product_categories),
				'operator' => 'IN',
			),
		),
	));
	$posts = get_posts( $args );
}

// Get posts by post tags IDs
if ( $query_type == 'product_tags' ) {
	$product_tags 	= get_post_meta( $id, '_product_tags', true );
	$product_tags 	= array_map('intval', explode(',', $product_tags));
	$args 		= array_merge( $args, array(
		'tax_query' => array(
			array(
				'taxonomy' => 'product_tag',
				'field'    => 'term_id',
				'terms'    => $product_tags,
				'operator' => 'IN',
			),
		),
	));
	$posts = get_posts( $args );
}


$_image_size 	    = get_post_meta( $id, '_image_size', true );
$_nav_color         = get_post_meta( $id, '_nav_color', true );
$_nav_active_color  = get_post_meta( $id, '_nav_active_color', true );
$_lazy_load_image  	= get_post_meta( $id, '_lazy_load_image', true );

$_product_title  	= get_post_meta( $id, '_product_title', true );
$_product_rating  	= get_post_meta( $id, '_product_rating', true );
$_product_price  	= get_post_meta( $id, '_product_price', true );
$_product_cart_btn  = get_post_meta( $id, '_product_cart_button', true );
$_product_onsale  	= get_post_meta( $id, '_product_onsale', true );
$_product_wishlist  = get_post_meta( $id, '_product_wishlist', true );
$_product_quick_view  = get_post_meta( $id, '_product_quick_view', true );

$_product_title_color  		= get_post_meta( $id, '_product_title_color', true );
$_product_btn_bg_color  	= get_post_meta( $id, '_product_button_bg_color', true );
$_product_btn_text_color 	= get_post_meta( $id, '_product_button_text_color', true );
?>
<style>
    #id-<?php echo $id; ?> .owl-dots .owl-dot span {
        background-color: <?php echo esc_attr($_nav_color); ?>
    }
    #id-<?php echo $id; ?> .owl-dots .owl-dot.active span,
    #id-<?php echo $id; ?> .owl-dots .owl-dot:hover span {
        background-color: <?php echo esc_attr($_nav_active_color); ?>
    }
    #id-<?php echo $id; ?> .carousel-slider-nav-icon {
        fill: <?php echo esc_attr($_nav_color); ?>;
    }
    #id-<?php echo $id; ?> .carousel-slider-nav-icon:hover {
        fill: <?php echo esc_attr($_nav_active_color); ?>;
    }
    #id-<?php echo $id; ?> .carousel-slider__product h3,
    #id-<?php echo $id; ?> .carousel-slider__product .price {
		color: <?php echo esc_attr($_product_title_color); ?>;
	}
	#id-<?php echo $id; ?> .carousel-slider__product a.add_to_cart_button,
	#id-<?php echo $id; ?> .carousel-slider__product a.added_to_cart,
	#id-<?php echo $id; ?> .carousel-slider__product a.quick_view,
    #id-<?php echo $id; ?> .carousel-slider__product .onsale  {
		background-color: <?php echo esc_attr($_product_btn_bg_color); ?>;
		color: <?php echo esc_attr($_product_btn_text_color); ?>;
	}
	#id-<?php echo $id; ?> .carousel-slider__product .star-rating {
		color: <?php echo esc_attr($_product_btn_bg_color); ?>;
	}
</style>
<div <?php echo join(" ", $this->carousel_options($id)); ?>>
	<?php foreach ( $posts as $post ): setup_postdata( $post );?>
		<?php
			$product = wc_get_product( $post->ID );
			do_action( 'carousel_slider_product_loop' );
		?>
		<div class="product carousel-slider__product">
			<?php
				echo sprintf('<a class="woocommerce-LoopProduct-link" href="%s">', get_the_permalink( $post->ID ));
				// Post Thumbnail
	            if( has_post_thumbnail($post->ID) ) {
					if ( $_lazy_load_image == 'on' ) {
		                $image_src = get_the_post_thumbnail_url( $post->ID, $_image_size );
		                echo sprintf( '<img class="owl-lazy" data-src="%1$s" />', $image_src );
		            }
		            else {
		                $image_src = get_the_post_thumbnail_url( $post->ID, $_image_size );
		                echo sprintf( '<img src="%1$s" />', $image_src );
		            }
	            }
	            echo "</a>";

	            // Show title
	            if ($_product_title == 'on') {
		            echo sprintf('<a href="%1$s"><h3>%2$s</h3></a>', get_the_permalink( $post->ID ), get_the_title( $post->ID ));
	            }
			
	            // Show Rating
				if($_product_rating == 'on'){
					if ( version_compare( WC_VERSION, "3.0.0", ">=" )) {
						echo wc_get_rating_html( $product->get_average_rating() );
					} else {
						echo $product->get_rating_html();
					}

				}
				// Sale Product batch
				if ( $product->is_on_sale() && $_product_onsale == 'on' ){
					echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . __( 'Sale!', 'carousel-slider' ) . '</span>',  $product );
				}
	            // Show Price
				if($_product_price == 'on'){
					echo '<span class="price">' . $product->get_price_html() . '</span>';
				}
	            // Show button
				if($_product_cart_btn == 'on'){
					echo '<div style="clear: both;"></div>';
					if ( function_exists('woocommerce_template_loop_add_to_cart')) {
						woocommerce_template_loop_add_to_cart();
					}
				}

				if ( $_product_quick_view == 'on' ) {
	                wp_enqueue_script( 'magnific-popup' );
					$ajax_url = wp_nonce_url( add_query_arg( array( 'ajax' => 'true', 'action' => 'carousel_slider_quick_view', 'product_id' => $post->ID, 'slide_id' => $id ), admin_url( 'admin-ajax.php' ) ), 'carousel_slider_quick_view');
					echo sprintf('<a class="magnific-popup button quick_view" href="%1$s" data-product-id="%2$s">%3$s</a>', $ajax_url, $post->ID, __('Quick View', 'carousel-slider'));
				}

				// WooCommerce Wishlist
				if ( class_exists( 'YITH_WCWL' ) && $_product_wishlist == 'on') {
					echo do_shortcode('[yith_wcwl_add_to_wishlist]');
				}
			?>
		</div>
	<?php endforeach; wp_reset_postdata();?>
</div>