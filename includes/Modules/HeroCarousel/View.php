<?php

namespace CarouselSlider\Modules\HeroCarousel;

use CarouselSlider\Abstracts\AbstractView;
use CarouselSlider\Supports\Utils;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class View extends AbstractView {

	/**
	 * @var array
	 */
	protected $style = array();

	/**
	 * Render element.
	 * Generates the final HTML on the frontend.
	 */
	public function render() {

		$slides       = $this->content_slider();
		$slides_count = count( $slides );

		if ( $slides_count < 1 ) {
			return '';
		}

		$this->set_total_slides( $slides_count );

		$id    = $this->get_slider_id();
		$_html = '';

		$element                   = "#id-{$id} .carousel-slider-hero__cell";
		$this->style[ $element ][] = array( 'property' => 'height', 'value' => $this->slide_height() );

		$element                   = "#id-{$id} .cell_content";
		$this->style[ $element ][] = array( 'property' => 'max-width', 'value' => $this->content_width() );

		foreach ( $slides as $slide_id => $slide ) {

			$html = '';

			$_link_type  = $this->link_type( $slide );
			$_slide_link = $this->slide_link( $slide );
			$class       = "carousel-slider-cell-{$id}-{$slide_id}";

			$cell_class = "carousel-slider-hero__cell {$class}";
			if ( 'full' == $this->link_type( $slide ) && $_slide_link ) {
				$html .= '<a class="' . $cell_class . '" href="' . $_slide_link . '" target="' . $this->link_target( $slide ) . '">';
			} else {
				$html .= '<div class="' . $cell_class . '">';
			}

			// Slide Background
			$_img_bg_position  = ! empty( $slide['img_bg_position'] ) ? esc_attr( $slide['img_bg_position'] ) : 'center center';
			$_img_bg_size      = ! empty( $slide['img_bg_size'] ) ? esc_attr( $slide['img_bg_size'] ) : 'contain';
			$_bg_color         = ! empty( $slide['bg_color'] ) ? esc_attr( $slide['bg_color'] ) : '';
			$_bg_overlay       = ! empty( $slide['bg_overlay'] ) ? esc_attr( $slide['bg_overlay'] ) : '';
			$_ken_burns_effect = ! empty( $slide['ken_burns_effect'] ) ? esc_attr( $slide['ken_burns_effect'] ) : '';
			$_img_id           = ! empty( $slide['img_id'] ) ? absint( $slide['img_id'] ) : 0;
			$_img_src          = wp_get_attachment_image_src( $_img_id, 'full' );
			$_have_img         = is_array( $_img_src );

			// Slide background
			$element                   = "#id-{$id} .{$class} .cell_background";
			$this->style[ $element ][] = array( 'property' => 'background-position', 'value' => $_img_bg_position );
			$this->style[ $element ][] = array( 'property' => 'background-size', 'value' => $_img_bg_size );

			if ( $_have_img && ! $this->lazy_load_image() ) {
				$this->style[ $element ][] = array( 'property' => 'background-image', 'value' => $_img_src[0], );
			}
			if ( ! empty( $_bg_color ) ) {
				$this->style[ $element ][] = array( 'property' => 'background-color', 'value' => $_bg_color, );
			}

			// Background class
			$_slide_bg_class = 'carousel-slider-hero__cell__background cell_background';

			if ( 'zoom-in' == $_ken_burns_effect ) {
				$_slide_bg_class .= ' carousel-slider-hero-ken-in';
			} elseif ( 'zoom-out' == $_ken_burns_effect ) {
				$_slide_bg_class .= ' carousel-slider-hero-ken-out';
			}

			if ( $this->lazy_load_image() ) {
				$html .= '<div class="' . $_slide_bg_class . ' owl-lazy" data-src="' . $_img_src[0] . '" id="slide-item-' . $id . '-' . $slide_id . '"></div>';
			} else {
				$html .= '<div class="' . $_slide_bg_class . '" id="slide-item-' . $id . '-' . $slide_id . '"></div>';
			}

			// Cell Inner
			$_content_alignment = ! empty( $slide['content_alignment'] ) ? esc_attr( $slide['content_alignment'] ) : 'left';
			$_cell_inner_class  = 'carousel-slider-hero__cell__inner cell_inner carousel-slider--h-position-center';
			if ( $_content_alignment == 'left' ) {
				$_cell_inner_class .= ' carousel-slider--v-position-middle carousel-slider--text-left';
			} elseif ( $_content_alignment == 'right' ) {
				$_cell_inner_class .= ' carousel-slider--v-position-middle carousel-slider--text-right';
			} else {
				$_cell_inner_class .= ' carousel-slider--v-position-middle carousel-slider--text-center';
			}

			$slide_padding   = $this->slide_padding();
			$_padding_top    = isset( $slide_padding['top'] ) ? esc_attr( $slide_padding['top'] ) : '1rem';
			$_padding_right  = isset( $slide_padding['right'] ) ? esc_attr( $slide_padding['right'] ) : '3rem';
			$_padding_bottom = isset( $slide_padding['bottom'] ) ? esc_attr( $slide_padding['bottom'] ) : '1rem';
			$_padding_left   = isset( $slide_padding['left'] ) ? esc_attr( $slide_padding['left'] ) : '3rem';

			$element                 = "#id-{$id} .{$class} .cell_inner";
			$this->style[ $element ] = array(
				array( 'property' => 'padding-top', 'value' => $_padding_top ),
				array( 'property' => 'padding-right', 'value' => $_padding_right ),
				array( 'property' => 'padding-bottom', 'value' => $_padding_bottom ),
				array( 'property' => 'padding-left', 'value' => $_padding_left ),
			);

			$html .= '<div class="' . $_cell_inner_class . '">';

			// Background Overlay
			if ( ! empty( $_bg_overlay ) ) {
				$element                   = "#id-{$id} .{$class} .cell_background_overlay";
				$this->style[ $element ][] = array( 'property' => 'background-color', 'value' => $_bg_overlay );

				$html .= '<div class="carousel-slider-hero__cell__background_overlay cell_background_overlay"></div>';
			}

			$html .= '<div class="carousel-slider-hero__cell__content cell_content">';

			// Slide Heading
			$_slide_heading = isset( $slide['slide_heading'] ) ? $slide['slide_heading'] : '';

			$html .= '<div class="carousel-slider-hero__cell__heading">';
			$html .= wp_kses_post( $_slide_heading );
			$html .= '</div>'; // .carousel-slider-hero__cell__heading

			$_slide_description = isset( $slide['slide_description'] ) ? $slide['slide_description'] : '';

			$html .= '<div class="carousel-slider-hero__cell__description">';
			$html .= wp_kses_post( $_slide_description );
			$html .= '</div>'; // .carousel-slider-hero__cell__content

			// Buttons
			if ( $_link_type == 'button' ) {
				$html .= '<div class="carousel-slider-hero__cell__buttons">';

				// Slide Button #1
				$_btn_1_text   = ! empty( $slide['button_one_text'] ) ? esc_attr( $slide['button_one_text'] ) : '';
				$_btn_1_url    = ! empty( $slide['button_one_url'] ) ? esc_url( $slide['button_one_url'] ) : '';
				$_btn_1_target = ! empty( $slide['button_one_target'] ) ? esc_attr( $slide['button_one_target'] ) : '_self';
				$_btn_1_type   = ! empty( $slide['button_one_type'] ) ? esc_attr( $slide['button_one_type'] ) : 'normal';
				$_btn_1_size   = ! empty( $slide['button_one_size'] ) ? esc_attr( $slide['button_one_size'] ) : 'medium';
				if ( Utils::is_url( $_btn_1_url ) ) {
					$_btn_1_class = 'button cs-hero-button';
					$_btn_1_class .= ' cs-hero-button-' . $slide_id . '-1';
					$_btn_1_class .= ' cs-hero-button-' . $_btn_1_type;
					$_btn_1_class .= ' cs-hero-button-' . $_btn_1_size;

					$html .= '<span class="carousel-slider-hero__cell__button__one">';
					$html .= '<a class="' . $_btn_1_class . '" href="' .
					         $_btn_1_url . '" target="' . $_btn_1_target . '">' . esc_attr( $_btn_1_text ) . "</a>";
					$html .= '</span>';
				}

				// Slide Button #2
				$_btn_2_text   = ! empty( $slide['button_two_text'] ) ? esc_attr( $slide['button_two_text'] ) : '';
				$_btn_2_url    = ! empty( $slide['button_two_url'] ) ? esc_url( $slide['button_two_url'] ) : '';
				$_btn_2_target = ! empty( $slide['button_two_target'] ) ? esc_attr( $slide['button_two_target'] ) : '_self';
				$_btn_2_size   = ! empty( $slide['button_two_size'] ) ? esc_attr( $slide['button_two_size'] ) : 'medium';
				$_btn_2_type   = ! empty( $slide['button_two_type'] ) ? esc_attr( $slide['button_two_type'] ) : 'normal';
				if ( Utils::is_url( $_btn_2_url ) ) {
					$_btn_2_class = 'button cs-hero-button';
					$_btn_2_class .= ' cs-hero-button-' . $slide_id . '-2';
					$_btn_2_class .= ' cs-hero-button-' . $_btn_2_type;
					$_btn_2_class .= ' cs-hero-button-' . $_btn_2_size;

					$html .= '<span class="carousel-slider-hero__cell__button__two">';
					$html .= '<a class="' . $_btn_2_class . '" href="' . $_btn_2_url . '" target="' . $_btn_2_target . '">' . esc_attr( $_btn_2_text ) . "</a>";
					$html .= '</span>';
				}

				$html .= '</div>'; // .carousel-slider-hero__cell__button
			}

			$html .= '</div>'; // .carousel-slider-hero__cell__content
			$html .= '</div>'; // .carousel-slider-hero__cell__inner

			if ( $_link_type == 'full' && Utils::is_url( $_slide_link ) ) {
				$html .= '</a>'; // .carousel-slider-hero__cell
			} else {
				$html .= '</div>'; // .carousel-slider-hero__cell
			}

			$_html .= apply_filters( 'carousel_slider_content', $html, $slide_id, $slide );
		}

		$__html = $this->slider_wrapper_start();
		$__html .= $_html;
		$__html .= $this->slider_wrapper_end();

		return $__html;
	}

	/**
	 * Slider wrapper start
	 *
	 * @return string
	 */
	protected function slider_wrapper_start() {
		$id      = $this->get_slider_id();
		$class   = $this->get_slider_class();
		$options = wp_json_encode( $this->owl_options() );

		$outer_classes = array(
			'carousel-slider-outer',
			'carousel-slider-' . $this->slider_type(),
			'carousel-slider-' . $id
		);

		$html = '<div class="' . implode( ' ', $outer_classes ) . '">';
		$html .= '<style type="text/css">' . PHP_EOL;
		$html .= $this->dynamic_style( true ) . PHP_EOL;
		$html .= $this->get_style();
		$html .= '</style>' . PHP_EOL;
		$html .= "<div id='id-" . $id . "' class='" . $class . "' data-owl_options='" . $options . "'
		data-animation='" . $this->content_animation() . "' data-slide-type='" . $this->slider_type() . "'>";

		return $html;
	}

	/**
	 * Get slider content
	 *
	 * @return array
	 */
	protected function content_slider() {
		return $this->get_meta( '_content_slider' );
	}

	/**
	 * Get slider settings
	 *
	 * @param string $key
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	protected function slider_settings( $key = null, $default = null ) {
		$settings = $this->get_meta( '_content_slider_settings' );

		if ( empty( $key ) ) {
			return $settings;
		}

		return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;
	}

	/**
	 * Get slider content animation
	 *
	 * @param string $default
	 *
	 * @return string
	 */
	protected function content_animation( $default = null ) {
		return $this->slider_settings( 'content_animation', $default );
	}

	/**
	 * Get content max width
	 *
	 * @param string $default
	 *
	 * @return string
	 */
	protected function content_width( $default = null ) {
		return $this->slider_settings( 'content_width', $default );
	}

	/**
	 * Get slider height
	 *
	 * @param string $default
	 *
	 * @return string
	 */
	protected function slide_height( $default = null ) {
		return $this->slider_settings( 'slide_height', $default );
	}

	/**
	 * Get slide padding
	 *
	 * @param string $key
	 * @param array|string $default
	 *
	 * @return array|string
	 */
	protected function slide_padding( $key = null, $default = null ) {
		$padding = (array) $this->slider_settings( 'slide_padding', $default );

		if ( empty( $key ) ) {
			return $padding;
		}

		if ( ! in_array( $key, array( 'top', 'right', 'bottom', 'left' ) ) ) {
			return '';
		}

		return isset( $padding[ $key ] ) ? $padding[ $key ] : $default;
	}

	/**
	 * Get content setting
	 *
	 * @param array $slide
	 * @param string $key
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	protected function get_content_setting( $slide, $key, $default = null ) {
		return isset( $slide[ $key ] ) ? $slide[ $key ] : $default;
	}

	/**
	 * Get link type
	 *
	 * @param array $slide
	 *
	 * @return null|string
	 */
	private function link_type( $slide ) {
		$valid     = array( 'full', 'button' );
		$link_type = $this->get_content_setting( $slide, 'link_type', 'full' );

		return in_array( $link_type, $valid ) ? $link_type : 'full';
	}

	/**
	 * Get slide target
	 *
	 * @param array $slide
	 *
	 * @return null|string
	 */
	private function link_target( $slide ) {
		$valid       = array( '_self', '_blank' );
		$link_target = $this->get_content_setting( $slide, 'link_target', '_self' );

		return in_array( $link_target, $valid ) ? $link_target : '_self';
	}

	/**
	 * Get slide link
	 *
	 * @param array $slide
	 *
	 * @return string
	 */
	private function slide_link( $slide ) {
		$slide_link = $this->get_content_setting( $slide, 'slide_link' );

		return Utils::is_url( $slide_link ) ? esc_url( $slide_link ) : '';
	}

	/**
	 * Get slider style
	 *
	 * @return string
	 */
	protected function get_style() {
		$styles    = $this->style;
		$final_css = '';

		foreach ( $styles as $selector => $style_array ) {
			$final_css .= $selector . '{';
			foreach ( $style_array as $style ) {

				$property = $style['property'];
				$value    = (string) $style['value'];

				if ( empty( $value ) ) {
					continue;
				}

				// Make sure background-images are properly formatted
				if ( 'background-image' == $property ) {
					if ( false === strrpos( $value, 'url(' ) ) {
						$value = 'url("' . esc_url_raw( $value ) . '")';
					}
				}

				$final_css .= $property . ':' . $value . ';';
			}
			$final_css .= '}' . PHP_EOL;
		}

		return empty( $final_css ) ? '' : $final_css;
	}
}
