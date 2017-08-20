<?php

if ( ! function_exists( 'carousel_slider_is_url' ) ) {
	/**
	 * Check if url is valid as per RFC 2396 Generic Syntax
	 *
	 * @param  string $url
	 *
	 * @return boolean
	 */
	function carousel_slider_is_url( $url ) {
		if ( filter_var( $url, FILTER_VALIDATE_URL ) ) {

			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'carousel_slider_get_meta' ) ) {
	/**
	 * Get post meta by id and key
	 *
	 * @param $id
	 * @param $key
	 * @param null $default
	 *
	 * @return string
	 */
	function carousel_slider_get_meta( $id, $key, $default = null ) {
		$meta = get_post_meta( $id, $key, true );

		if ( empty( $meta ) && $default ) {
			$meta = $default;
		}

		if ( $meta == 'zero' ) {
			$meta = '0';
		}
		if ( $meta == 'on' ) {
			$meta = 'true';
		}
		if ( $meta == 'off' ) {
			$meta = 'false';
		}
		if ( $key == '_margin_right' && $meta == 0 ) {
			$meta = '0';
		}

		return esc_attr( $meta );
	}
}

if ( ! function_exists( 'carousel_slider_array_to_attribute' ) ) {
	/**
	 * Convert array to html data attribute
	 *
	 * @param $array
	 *
	 * @return array|string
	 */
	function carousel_slider_array_to_attribute( $array ) {
		if ( ! is_array( $array ) ) {
			return '';
		}

		$attribute = array_map( function ( $key, $value ) {
			// If boolean value
			if ( is_bool( $value ) ) {
				if ( $value ) {

					return sprintf( '%s="%s"', $key, 'true' );
				} else {

					return sprintf( '%s="%s"', $key, 'false' );
				}
			}
			// If array value
			if ( is_array( $value ) ) {

				return sprintf( '%s="%s"', $key, implode( " ", $value ) );
			}

			// If string value
			return sprintf( '%s="%s"', $key, esc_attr( $value ) );

		}, array_keys( $array ), array_values( $array ) );

		return $attribute;
	}
}

if ( ! function_exists( 'carousel_slider_is_woocommerce_active' ) ) {
	/**
	 * Check if WooCommerce is active
	 *
	 * @return bool
	 */
	function carousel_slider_is_woocommerce_active() {

		if ( in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ) ) ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'carousel_slider_posts' ) ) {
	/**
	 * Get posts by carousel slider ID
	 *
	 * @param $carousel_id
	 *
	 * @return array
	 */
	function carousel_slider_posts( $carousel_id ) {
		$id = $carousel_id;
		// Get settings from carousel slider
		$order      = get_post_meta( $id, '_post_order', true );
		$orderby    = get_post_meta( $id, '_post_orderby', true );
		$per_page   = intval( get_post_meta( $id, '_posts_per_page', true ) );
		$query_type = get_post_meta( $id, '_post_query_type', true );
		$query_type = empty( $query_type ) ? 'latest_posts' : $query_type;

		$args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'order'          => $order,
			'orderby'        => $orderby,
			'posts_per_page' => $per_page
		);

		// Get posts by post IDs
		if ( $query_type == 'specific_posts' ) {
			$post_in = explode( ',', get_post_meta( $id, '_post_in', true ) );
			$post_in = array_map( 'intval', $post_in );
			unset( $args['posts_per_page'] );
			$args = array_merge( $args, array( 'post__in' => $post_in ) );
		}

		// Get posts by post catagories IDs
		if ( $query_type == 'post_categories' ) {
			$post_categories = get_post_meta( $id, '_post_categories', true );
			$args            = array_merge( $args, array( 'cat' => $post_categories ) );
		}

		// Get posts by post tags IDs
		if ( $query_type == 'post_tags' ) {
			$post_tags = get_post_meta( $id, '_post_tags', true );
			$post_tags = array_map( 'intval', explode( ',', $post_tags ) );
			$args      = array_merge( $args, array( 'tag__in' => $post_tags ) );
		}

		// Get posts by date range
		if ( $query_type == 'date_range' ) {

			$post_date_after  = get_post_meta( $id, '_post_date_after', true );
			$post_date_before = get_post_meta( $id, '_post_date_before', true );

			if ( $post_date_after && $post_date_before ) {
				$args = array_merge( $args, array(
					'date_query' => array(
						array(
							'after'     => $post_date_after,
							'before'    => $post_date_before,
							'inclusive' => true,
						),
					),
				) );
			} elseif ( $post_date_after ) {
				$args = array_merge( $args, array(
					'date_query' => array(
						array(
							'before'    => $post_date_before,
							'inclusive' => true,
						),
					),
				) );
			} elseif ( $post_date_before ) {
				$args = array_merge( $args, array(
					'date_query' => array(
						array(
							'before'    => $post_date_before,
							'inclusive' => true,
						),
					),
				) );
			}
		}

		$posts = get_posts( $args );

		return $posts;
	}
}

if ( ! function_exists( 'carousel_slider_products' ) ) {
	/**
	 * Get products by carousel slider ID
	 *
	 * @param $carousel_id
	 *
	 * @return array
	 */
	function carousel_slider_products( $carousel_id ) {
		$id            = $carousel_id;
		$per_page      = intval( get_post_meta( $id, '_products_per_page', true ) );
		$query_type    = get_post_meta( $id, '_product_query_type', true );
		$query_type    = empty( $query_type ) ? 'query_porduct' : $query_type;
		$product_query = get_post_meta( $id, '_product_query', true );

		$args = array(
			'post_type'          => 'product',
			'post_status'        => 'publish',
			'ignore_sticky_post' => 1,
			'posts_per_page'     => $per_page
		);

		// Get products by product IDs
		if ( $query_type == 'specific_products' ) {
			$product_in = explode( ',', get_post_meta( $id, '_product_in', true ) );
			$product_in = array_map( 'intval', $product_in );
			$args       = array_merge( $args, array( 'post__in' => $product_in ) );
			unset( $args['posts_per_page'] );

			return get_posts( $args );
		}

		if ( $query_type == 'query_porduct' ) {

			// Get features products
			if ( $product_query == 'featured' ) {
				$args = array_merge( $args, array(
					'meta_key'   => '_featured',
					'meta_value' => 'yes',
					'orderby'    => 'date',
					'order'      => 'desc'
				) );

				return get_posts( $args );
			}

			// Get recent products
			if ( $product_query == 'recent' ) {
				$args = array_merge( $args, array(
					'ignore_sticky_posts' => 1,
					'orderby'             => 'date',
					'order'               => 'desc',
					'meta_query'          => WC()->query->get_meta_query()
				) );

				return get_posts( $args );
			}

			// Get sale products
			if ( $product_query == 'sale' ) {

				$args = array_merge( $args, array(
					'orderby'       => 'title',
					'order'         => 'asc',
					'no_found_rows' => 1,
					'meta_query'    => WC()->query->get_meta_query(),
					'post__in'      => array_merge( array( 0 ), wc_get_product_ids_on_sale() )
				) );

				return get_posts( $args );
			}

			// Get best_selling products
			if ( $product_query == 'best_selling' ) {
				$args = array(
					'ignore_sticky_posts' => 1,
					'meta_key'            => 'total_sales',
					'orderby'             => 'meta_value_num',
					'meta_query'          => WC()->query->get_meta_query()
				);

				return get_posts( $args );
			}

			// Get top_rated products
			if ( $product_query == 'top_rated' ) {

				add_filter( 'posts_clauses', array( WC()->query, 'order_by_rating_post_clauses' ) );
				$args = array_merge( $args, array(
					'no_found_rows' => 1,
					'meta_query'    => WC()->query->get_meta_query(),
				) );

				return get_posts( $args );
			}

		}

		// Get posts by post catagories IDs
		if ( $query_type == 'product_categories' ) {
			$product_categories = get_post_meta( $id, '_product_categories', true );
			$args               = array_merge( $args, array(
				'tax_query' => array(
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'term_id',
						'terms'    => explode( ",", $product_categories ),
						'operator' => 'IN',
					),
				),
			) );

			return get_posts( $args );
		}

		// Get posts by post tags IDs
		if ( $query_type == 'product_tags' ) {
			$product_tags = get_post_meta( $id, '_product_tags', true );
			$product_tags = array_map( 'intval', explode( ',', $product_tags ) );
			$args         = array_merge( $args, array(
				'tax_query' => array(
					array(
						'taxonomy' => 'product_tag',
						'field'    => 'term_id',
						'terms'    => $product_tags,
						'operator' => 'IN',
					),
				),
			) );

			return get_posts( $args );
		}

		return array();
	}
}

if ( ! function_exists( 'carousel_slider_inline_style' ) ) {
	function carousel_slider_inline_style( $carousel_id ) {
		$id                      = $carousel_id;
		$_nav_color              = get_post_meta( $id, '_nav_color', true );
		$_nav_active_color       = get_post_meta( $id, '_nav_active_color', true );
		$_post_height            = get_post_meta( $id, '_post_height', true );
		$_product_title_color    = get_post_meta( $id, '_product_title_color', true );
		$_product_btn_bg_color   = get_post_meta( $id, '_product_button_bg_color', true );
		$_product_btn_text_color = get_post_meta( $id, '_product_button_text_color', true );

		$slide_type = get_post_meta( $id, '_slide_type', true );
		$slide_type = in_array( $slide_type, array(
			'image-carousel',
			'post-carousel',
			'image-carousel-url',
			'video-carousel',
			'product-carousel'
		) ) ? $slide_type : 'image-carousel';

		?>
        <style>
            #id-<?php echo $id; ?> .owl-dots .owl-dot span {
                background-color: <?php echo $_nav_color; ?>
            }

            #id-<?php echo $id; ?> .owl-dots .owl-dot.active span,
            #id-<?php echo $id; ?> .owl-dots .owl-dot:hover span {
                background-color: <?php echo $_nav_active_color; ?>
            }

            #id-<?php echo $id; ?> .carousel-slider-nav-icon {
                fill: <?php echo $_nav_color; ?>;
            }

            #id-<?php echo $id; ?> .carousel-slider-nav-icon:hover {
                fill: <?php echo $_nav_active_color; ?>;
            }

            <?php if ( $slide_type == 'post-carousel'): ?>

            #id-<?php echo $id; ?> .carousel-slider__post {
                height: <?php echo $_post_height; ?>px;
            }

            <?php elseif ( $slide_type == 'product-carousel'): ?>

            #id-<?php echo $id; ?> .carousel-slider__product h3,
            #id-<?php echo $id; ?> .carousel-slider__product .price {
                color: <?php echo esc_attr($_product_title_color); ?>;
            }

            #id-<?php echo $id; ?> .carousel-slider__product a.add_to_cart_button,
            #id-<?php echo $id; ?> .carousel-slider__product a.added_to_cart,
            #id-<?php echo $id; ?> .carousel-slider__product a.quick_view,
            #id-<?php echo $id; ?> .carousel-slider__product .onsale {
                background-color: <?php echo esc_attr($_product_btn_bg_color); ?>;
                color: <?php echo esc_attr($_product_btn_text_color); ?>;
            }

            #id-<?php echo $id; ?> .carousel-slider__product .star-rating {
                color: <?php echo esc_attr($_product_btn_bg_color); ?>;
            }

            <?php endif; ?>
        </style>
		<?php
	}
}