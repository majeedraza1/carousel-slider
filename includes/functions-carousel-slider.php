<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

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

if ( ! function_exists( 'carousel_slider_sanitize_color' ) ) {
	/**
	 * Sanitizes a Hex, RGB or RGBA color
	 *
	 * @param $color
	 *
	 * @return mixed|string
	 */
	function carousel_slider_sanitize_color( $color ) {
		if ( '' === $color ) {
			return '';
		}

		// Trim unneeded whitespace
		$color = str_replace( ' ', '', $color );

		// If this is hex color, validate and return it
		if ( 1 === preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
			return $color;
		}

		// If this is rgb, validate and return it
		if ( 'rgb(' === substr( $color, 0, 4 ) ) {
			list( $red, $green, $blue ) = sscanf( $color, 'rgb(%d,%d,%d)' );

			if ( ( $red >= 0 && $red <= 255 ) &&
			     ( $green >= 0 && $green <= 255 ) &&
			     ( $blue >= 0 && $blue <= 255 )
			) {
				return "rgb({$red},{$green},{$blue})";
			}
		}

		// If this is rgba, validate and return it
		if ( 'rgba(' === substr( $color, 0, 5 ) ) {
			list( $red, $green, $blue, $alpha ) = sscanf( $color, 'rgba(%d,%d,%d,%f)' );

			if ( ( $red >= 0 && $red <= 255 ) &&
			     ( $green >= 0 && $green <= 255 ) &&
			     ( $blue >= 0 && $blue <= 255 ) &&
			     $alpha >= 0 && $alpha <= 1
			) {
				return "rgba({$red},{$green},{$blue},{$alpha})";
			}
		}

		return '';
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

		if ( defined( 'WC_VERSION' ) || defined( 'WOOCOMMERCE_VERSION' ) ) {
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
		$id         = $carousel_id;
		$per_page   = intval( get_post_meta( $id, '_products_per_page', true ) );
		$query_type = get_post_meta( $id, '_product_query_type', true );
		$query_type = empty( $query_type ) ? 'query_product' : $query_type;
		// Type mistake
		$query_type    = ( 'query_porduct' == $query_type ) ? 'query_product' : $query_type;
		$product_query = get_post_meta( $id, '_product_query', true );

		$product_carousel = new Carousel_Slider_Product();

		$args = array( 'posts_per_page' => $per_page );

		if ( $query_type == 'query_product' ) {

			// Get features products
			if ( $product_query == 'featured' ) {
				return $product_carousel->featured_products( $args );
			}

			// Get best_selling products
			if ( $product_query == 'best_selling' ) {
				return $product_carousel->best_selling_products( $args );
			}

			// Get recent products
			if ( $product_query == 'recent' ) {
				return $product_carousel->recent_products( $args );
			}

			// Get sale products
			if ( $product_query == 'sale' ) {
				return $product_carousel->sale_products( $args );
			}

			// Get top_rated products
			if ( $product_query == 'top_rated' ) {
				return $product_carousel->top_rated_products( $args );
			}
		}

		// Get products by product IDs
		if ( $query_type == 'specific_products' ) {
			$product_in = get_post_meta( $id, '_product_in', true );
			$product_in = array_map( 'intval', explode( ',', $product_in ) );

			return $product_carousel->products( array( 'post__in' => $product_in ) );
		}

		// Get posts by post categories IDs
		if ( $query_type == 'product_categories' ) {
			$product_cat_ids = get_post_meta( $id, '_product_categories', true );
			$product_cat_ids = array_map( 'intval', explode( ",", $product_cat_ids ) );

			return $product_carousel->products_by_categories( $product_cat_ids, $per_page );
		}

		// Get posts by post tags IDs
		if ( $query_type == 'product_tags' ) {
			$product_tags = get_post_meta( $id, '_product_tags', true );
			$product_tags = array_map( 'intval', explode( ',', $product_tags ) );

			return $product_carousel->products_by_tags( $product_tags, $per_page );
		}

		return array();
	}
}

if ( ! function_exists( 'carousel_slider_inline_style' ) ) {
	/**
	 * Get carousel slider inline style
	 *
	 * @param $carousel_id
	 */
	function carousel_slider_inline_style( $carousel_id ) {
		$id                      = $carousel_id;
		$_nav_color              = get_post_meta( $id, '_nav_color', true );
		$_nav_active_color       = get_post_meta( $id, '_nav_active_color', true );
		$_post_height            = get_post_meta( $id, '_post_height', true );
		$_product_title_color    = get_post_meta( $id, '_product_title_color', true );
		$_product_btn_bg_color   = get_post_meta( $id, '_product_button_bg_color', true );
		$_product_btn_text_color = get_post_meta( $id, '_product_button_text_color', true );
		$content_sliders         = get_post_meta( $id, '_content_slider', true );

		$slide_type = get_post_meta( $id, '_slide_type', true );
		$slide_type = in_array( $slide_type, carousel_slider_slide_type() ) ? $slide_type : 'image-carousel';

		$_arrow_size = get_post_meta( $id, '_arrow_size', true );
		$_arrow_size = empty( $_arrow_size ) ? 48 : absint( $_arrow_size );

		$_bullet_size = get_post_meta( $id, '_bullet_size', true );
		$_bullet_size = empty( $_bullet_size ) ? 10 : absint( $_bullet_size );

		echo "<style type=\"text/css\">";

		// Arrows Nav
		echo "
            #id-{$id} .carousel-slider-nav-icon {
                fill: {$_nav_color}
            }
            #id-{$id} .carousel-slider-nav-icon:hover {
                fill: {$_nav_active_color}
            }
            #id-{$id} .owl-prev,
            #id-{$id} .owl-next,
            #id-{$id} .carousel-slider-nav-icon {
                height: {$_arrow_size}px;
                width: {$_arrow_size}px
            }
            #id-{$id}.arrows-outside .owl-prev {
                left: -{$_arrow_size}px
            }
            #id-{$id}.arrows-outside .owl-next {
                right: -{$_arrow_size}px
            }
        ";

		// Dots Nav
		echo "
		    #id-{$id} .owl-dots .owl-dot span {
                background-color: {$_nav_color};
                width: {$_bullet_size}px;
                height: {$_bullet_size}px;
            }
            #id-{$id} .owl-dots .owl-dot.active span,
            #id-{$id} .owl-dots .owl-dot:hover span {
                background-color: {$_nav_active_color}
            }
		";

		// Product Carousel Slider
		if ( $slide_type == 'product-carousel' ) {
			echo "
		        #id-{$id} .carousel-slider__product h3,
                #id-{$id} .carousel-slider__product .price {
                    color: {$_product_title_color};
                }

                #id-{$id} .carousel-slider__product a.add_to_cart_button,
                #id-{$id} .carousel-slider__product a.added_to_cart,
                #id-{$id} .carousel-slider__product a.quick_view,
                #id-{$id} .carousel-slider__product .onsale {
                    background-color: {$_product_btn_bg_color};
                    color: {$_product_btn_text_color};
                }

                #id-{$id} .carousel-slider__product .star-rating span:before {
                    color: {$_product_btn_bg_color};
                }
		    ";
		}

		// Content Carousel
		if ( $slide_type == 'hero-banner-slider' && $content_sliders ) {
			foreach ( $content_sliders as $slide_id => $slide ) {
				// Title Style
				$heading_font_size = ! empty( $slide['heading_font_size'] ) ? esc_attr( $slide['heading_font_size'] ) : 40;
				$heading_gutter    = ! empty( $slide['heading_gutter'] ) ? esc_attr( $slide['heading_gutter'] ) : '30px';
				$heading_color     = ! empty( $slide['heading_color'] ) ? esc_attr( $slide['heading_color'] ) : '#ffffff';
				echo "#id-{$id} .hero__cell-{$slide_id} .carousel-slider-hero__cell__heading {
					font-size: {$heading_font_size}px;
					margin-bottom: {$heading_gutter};
					color: {$heading_color};
				}";
				// Description Style
				$description_font_size = ! empty( $slide['description_font_size'] ) ? esc_attr( $slide['description_font_size'] ) : 20;
				$description_gutter    = ! empty( $slide['description_gutter'] ) ? esc_attr( $slide['description_gutter'] ) : '30px';
				$description_color     = ! empty( $slide['description_color'] ) ? esc_attr( $slide['description_color'] ) : '#ffffff';
				echo "#id-{$id} .hero__cell-{$slide_id} .carousel-slider-hero__cell__description{
					font-size: {$description_font_size}px;
					margin-bottom: {$description_gutter};
					color: {$description_color};
				}";
				// Button Style
				if ( isset( $slide['link_type'] ) && ( $slide['link_type'] == 'button' ) ) {

					$_btn_1_type          = ! empty( $slide['button_one_type'] ) ? esc_attr( $slide['button_one_type'] ) : 'normal';
					$_btn_1_bg_color      = ! empty( $slide['button_one_bg_color'] ) ? carousel_slider_sanitize_color( $slide['button_one_bg_color'] ) : '#00d1b2';
					$_btn_1_color         = ! empty( $slide['button_one_color'] ) ? carousel_slider_sanitize_color( $slide['button_one_color'] ) : '#ffffff';
					$_btn_1_border_width  = ! empty( $slide['button_one_border_width'] ) ? esc_attr( $slide['button_one_border_width'] ) : '0px';
					$_btn_1_border_radius = ! empty( $slide['button_one_border_radius'] ) ? esc_attr( $slide['button_one_border_radius'] ) : '3px';

					if ( $_btn_1_type == 'stroke' ) {
						echo "
						#id-{$id} .cs-hero-button-{$slide_id}-1 {
							border: {$_btn_1_border_width} solid {$_btn_1_bg_color};
							border-radius: {$_btn_1_border_radius};
							background-color: transparent;
							color: {$_btn_1_bg_color};
						}
						#id-{$id} .cs-hero-button-{$slide_id}-1:hover {
							border: {$_btn_1_border_width} solid {$_btn_1_bg_color};
							background-color: {$_btn_1_bg_color};
							color: {$_btn_1_color};
						}
					";
					} else {
						echo "
						#id-{$id} .cs-hero-button-{$slide_id}-1 {
							background-color: {$_btn_1_bg_color};
							border: {$_btn_1_border_width} solid {$_btn_1_bg_color};
							border-radius: {$_btn_1_border_radius};
							color: {$_btn_1_color};
						}
					";
					}

					$_btn_2_type          = ! empty( $slide['button_two_type'] ) ? esc_attr( $slide['button_two_type'] ) : 'normal';
					$_btn_2_bg_color      = ! empty( $slide['button_two_bg_color'] ) ? carousel_slider_sanitize_color( $slide['button_two_bg_color'] ) : '#00d1b2';
					$_btn_2_color         = ! empty( $slide['button_two_color'] ) ? carousel_slider_sanitize_color( $slide['button_two_color'] ) : '#ffffff';
					$_btn_2_border_width  = ! empty( $slide['button_two_border_width'] ) ? esc_attr( $slide['button_two_border_width'] ) : '0px';
					$_btn_2_border_radius = ! empty( $slide['button_two_border_radius'] ) ? esc_attr( $slide['button_two_border_radius'] ) : '3px';
					if ( $_btn_2_type == 'stroke' ) {
						echo "
						#id-{$id} .cs-hero-button-{$slide_id}-2 {
							border: {$_btn_2_border_width} solid {$_btn_2_bg_color};
							border-radius: {$_btn_2_border_radius};
							background-color: transparent;
							color: {$_btn_2_bg_color};
						}
						#id-{$id} .cs-hero-button-{$slide_id}-2:hover {
							border: {$_btn_2_border_width} solid {$_btn_2_bg_color};
							background-color: {$_btn_2_bg_color};
							color: {$_btn_2_color};
						}
					";
					} else {
						echo "
						#id-{$id} .cs-hero-button-{$slide_id}-2 {
							background-color: {$_btn_2_bg_color};
							border: {$_btn_2_border_width} solid {$_btn_2_bg_color};
							border-radius: {$_btn_2_border_radius};
							color: {$_btn_2_color};
						}
					";
					}
				}
			}
		}

		echo "</style>";
	}
}

if ( ! function_exists( 'carousel_slider_slide_type' ) ) {
	/**
	 * Get carousel slider available slide type
	 *
	 * @param bool $key_only
	 *
	 * @return array
	 */
	function carousel_slider_slide_type( $key_only = true ) {
		$types = apply_filters( 'carousel_slider_slide_type', array(
			'image-carousel'     => __( 'Image Carousel', 'carousel-slider' ),
			'image-carousel-url' => __( 'Image Carousel (URL)', 'carousel-slider' ),
			'post-carousel'      => __( 'Post Carousel', 'carousel-slider' ),
			'product-carousel'   => __( 'Product Carousel', 'carousel-slider' ),
			'video-carousel'     => __( 'Video Carousel', 'carousel-slider' ),
			'hero-banner-slider' => __( 'Hero Carousel', 'carousel-slider' ),
		) );

		if ( $key_only ) {
			return array_keys( $types );
		}

		return $types;
	}
}

if ( ! function_exists( 'carousel_slider_background_position' ) ) {
	/**
	 * @param bool $key_only
	 *
	 * @return array
	 */
	function carousel_slider_background_position( $key_only = false ) {
		$positions = array(
			'left top'      => 'left top',
			'left center'   => 'left center',
			'left bottom'   => 'left bottom',
			'center top'    => 'center top',
			'center center' => 'center', // Default
			'center bottom' => 'center bottom',
			'right top'     => 'right top',
			'right center'  => 'right center',
			'right bottom'  => 'right bottom',
		);
		if ( $key_only ) {
			return array_keys( $positions );
		}

		return $positions;
	}
}

if ( ! function_exists( 'carousel_slider_background_size' ) ) {
	/**
	 * @param bool $key_only
	 *
	 * @return array
	 */
	function carousel_slider_background_size( $key_only = false ) {
		$sizes = array(
			'auto'      => 'auto',
			'contain'   => 'contain',
			'cover'     => 'cover', // Default
			'100% 100%' => '100%',
			'100% auto' => '100% width',
			'auto 100%' => '100% height',
		);
		if ( $key_only ) {
			return array_keys( $sizes );
		}

		return $sizes;
	}
}

if ( ! function_exists( 'carousel_slider_default_settings' ) ) {
	function carousel_slider_default_settings() {
		$options = array(
			'product_title_color'       => '#323232',
			'product_button_bg_color'   => '#00d1b2',
			'product_button_text_color' => '#f1f1f1',
			'nav_color'                 => '#f1f1f1',
			'nav_active_color'          => '#00d1b2',
			'margin_right'              => 10,
			'lazy_load_image'           => 'off',
		);

		$options = apply_filters( 'carousel_slider_default_settings', $options );
		$options = json_decode( json_encode( $options ), false );

		return $options;
	}
}