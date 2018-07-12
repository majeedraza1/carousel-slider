<?php

namespace CarouselSlider\Supports;

class DynamicStyle {
	/**
	 * Generate dynamic style for slider
	 *
	 * @param int $id
	 * @param bool $echo
	 */
	public static function generate( $id = 0, $echo = true ) {
		$_nav_color              = get_post_meta( $id, '_nav_color', true );
		$_nav_active_color       = get_post_meta( $id, '_nav_active_color', true );
		$_post_height            = get_post_meta( $id, '_post_height', true );
		$_product_title_color    = get_post_meta( $id, '_product_title_color', true );
		$_product_btn_bg_color   = get_post_meta( $id, '_product_button_bg_color', true );
		$_product_btn_text_color = get_post_meta( $id, '_product_button_text_color', true );
		$content_sliders         = get_post_meta( $id, '_content_slider', true );

		$slide_type = get_post_meta( $id, '_slide_type', true );
		$slide_type = in_array( $slide_type, Utils::get_slide_types() ) ? $slide_type : 'image-carousel';

		$_arrow_size = get_post_meta( $id, '_arrow_size', true );
		$_arrow_size = empty( $_arrow_size ) ? 48 : absint( $_arrow_size );

		$_bullet_size = get_post_meta( $id, '_bullet_size', true );
		$_bullet_size = empty( $_bullet_size ) ? 10 : absint( $_bullet_size );

		ob_start();
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
					$_btn_1_bg_color      = ! empty( $slide['button_one_bg_color'] ) ? Utils::sanitize_color( $slide['button_one_bg_color'] ) : '#00d1b2';
					$_btn_1_color         = ! empty( $slide['button_one_color'] ) ? Utils::sanitize_color( $slide['button_one_color'] ) : '#ffffff';
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
					$_btn_2_bg_color      = ! empty( $slide['button_two_bg_color'] ) ? Utils::sanitize_color( $slide['button_two_bg_color'] ) : '#00d1b2';
					$_btn_2_color         = ! empty( $slide['button_two_color'] ) ? Utils::sanitize_color( $slide['button_two_color'] ) : '#ffffff';
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

		$styles = ob_get_clean();

		$style = "<style type=\"text/css\">";
		$style .= Utils::minify_css( $styles, false );
		$style .= "</style>" . PHP_EOL;

		if ( ! $echo ) {
			return $style;
		}

		echo $style;
	}
}