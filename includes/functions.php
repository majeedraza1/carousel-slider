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

		if ( defined( 'WC_VERSION' ) ) {
			return true;
		}

		return false;
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

		// Post Carousel Slider
		if ( $slide_type == 'post-carousel' ) {

			echo "
                #id-{$id} .carousel-slider__post {
                    height: {$_post_height}px
                }
            ";
		}

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

                #id-{$id} .carousel-slider__product .star-rating {
                    color: {$_product_btn_bg_color};
                }
		    ";
		}

		// Content Carousel
		if ( $slide_type == 'hero-banner-slider' && $content_sliders ) {
			foreach ( $content_sliders as $slide_id => $slide ) {
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
		$types = apply_filters( 'carousel_slider_slide_type', array() );

		if ( $key_only ) {
			return array_keys( $types );
		}

		return $types;
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
