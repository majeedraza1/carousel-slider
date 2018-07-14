<?php

namespace CarouselSlider\Abstracts;

use CarouselSlider\Supports\DynamicStyle;
use CarouselSlider\Supports\Utils;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

abstract class View {

	/**
	 * @var int
	 */
	protected $slider_id = 0;

	/**
	 * @var int
	 */
	protected $total_slides = 0;

	/**
	 * @return int
	 */
	public function get_total_slides() {
		return $this->total_slides;
	}

	/**
	 * @param int $total_slides
	 */
	public function set_total_slides( $total_slides ) {
		$this->total_slides = $total_slides;
	}

	/**
	 * Get slider id
	 *
	 * @return int
	 */
	public function get_slider_id() {
		return $this->slider_id;
	}

	/**
	 * Set current slider id
	 *
	 * @param int $slider_id
	 */
	public function set_slider_id( $slider_id ) {
		$this->slider_id = $slider_id;
	}

	/**
	 * Get owl carousel options
	 *
	 * @return array
	 */
	public function owl_options() {
		$owl_setting = array(
			'stagePadding'       => $this->stage_padding(),
			'nav'                => $this->is_nav_enabled(),
			'dots'               => $this->is_dot_enabled(),
			'margin'             => $this->gutter(),
			'loop'               => $this->infinity_loop(),
			'autoplay'           => $this->autoplay(),
			'autoplayTimeout'    => $this->autoplay_timeout(),
			'autoplaySpeed'      => $this->autoplay_speed(),
			'autoplayHoverPause' => $this->autoplay_hover_pause(),
			'slideBy'            => $this->slide_by(),
			'lazyLoad'           => $this->lazy_load_image(),
			'autoWidth'          => $this->auto_width(),
			'items'              => 1,
		);

		if ( $this->get_total_slides() <= 1 ) {
			$owl_setting['mouseDrag'] = false;
			$owl_setting['touchDrag'] = false;
			$owl_setting['nav']       = false;
			$owl_setting['dots']      = false;
			$owl_setting['autoplay']  = false;
		}

		$_responsive = array();
		foreach ( $this->responsive() as $item ) {
			$items   = intval( $item['items'] );
			$_config = array( 'items' => $items );
			if ( $this->get_total_slides() <= $items ) {
				$_config['mouseDrag'] = false;
				$_config['touchDrag'] = false;
				$_config['nav']       = false;
				$_config['dots']      = false;
				$_config['autoplay']  = false;
			}

			$_responsive[ $item['breakpoint'] ] = $_config;
		}
		$owl_setting['responsive'] = $_responsive;

		$owl_setting['navText'] = apply_filters( 'carousel_slider/nav_text', array(
			'<svg class="carousel-slider-nav-icon" viewBox="0 0 20 20"><path d="M14 5l-5 5 5 5-1 2-7-7 7-7z"></path></svg>',
			'<svg class="carousel-slider-nav-icon" viewBox="0 0 20 20"><path d="M6 15l5-5-5-5 1-2 7 7-7 7z"></path></svg>',
		) );

		return apply_filters( 'carousel_slider/owl_settings', $owl_setting );
	}

	/**
	 * Retrieve post meta field for a post.
	 *
	 * @param string $key The meta key to retrieve.
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	protected function get_meta( $key, $default = null ) {
		$meta = get_post_meta( $this->get_slider_id(), $key, true );

		if ( empty( $meta ) && func_num_args() > 1 ) {
			$meta = $default;
		}

		return $meta;
	}

	/**
	 * If a field has been 'checked' or not, meaning it contains
	 * one of the following values: 'yes', 'on', '1', 1, true, or 'true'.
	 * This can be used for determining if an HTML checkbox has been checked.
	 *
	 * @param  mixed $value
	 *
	 * @return boolean
	 */
	protected function checked( $value ) {
		return in_array( $value, array( 'yes', 'on', '1', 1, true, 'true' ), true );
	}

	/**
	 * Get slide dynamic style
	 *
	 * @return string
	 */
	protected function dynamic_style() {
		return DynamicStyle::generate( $this->get_slider_id() );
	}

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
		$html .= $this->dynamic_style();
		$html .= "<div id='id-" . $id . "' class='" . $class . "' data-owl_carousel='" . $options . "'>";

		return $html;
	}

	protected function slider_wrapper_end() {
		return '</div></div>';
	}

	/**
	 * Get slider class
	 *
	 * @return string
	 */
	protected function get_slider_class() {
		$class = array( 'owl-carousel', 'carousel-slider' );

		// Arrows position
		if ( $this->arrow_position() == 'inside' ) {
			$class[] = 'arrows-inside';
		} else {
			$class[] = 'arrows-outside';
		}

		// Arrows visibility
		if ( $this->arrow_visibility() == 'always' ) {
			$class[] = 'arrows-visible-always';
		} elseif ( $this->arrow_visibility() == 'never' ) {
			$class[] = 'arrows-hidden';
		} else {
			$class[] = 'arrows-visible-hover';
		}

		// Dots position
		$class[] = 'dots-' . $this->dots_position();

		// Dots visibility
		if ( $this->dots_visibility() == 'always' ) {
			$class[] = 'dots-visible-always';
		} elseif ( $this->dots_visibility() == 'never' ) {
			$class[] = 'dots-hidden';
		} else {
			$class[] = 'dots-visible-hover';
		}

		// Dots shape
		$class[] = 'dots-' . $this->dots_shape();

		return implode( ' ', $class );
	}

	/********************************************************************************
	 * General Settings
	 *******************************************************************************/

	/**
	 * Get slider type
	 *
	 * @return mixed
	 */
	protected function slider_type() {
		return $this->get_meta( '_slide_type', 'image-carousel' );
	}

	/**
	 * Get slider image size
	 *
	 * @return string
	 */
	protected function image_size() {
		return $this->get_meta( '_image_size', 'full' );
	}

	/**
	 * Check if lazy load enabled
	 *
	 * @return bool
	 */
	protected function lazy_load_image() {
		$default = Utils::get_default_setting( 'lazy_load_image' );

		return $this->checked( $this->get_meta( '_lazy_load_image', $default ) );
	}

	/**
	 * Get space between two slide
	 *
	 * @return int
	 */
	protected function gutter() {
		$default = Utils::get_default_setting( 'margin_right' );

		$meta = $this->get_meta( '_margin_right', $default );
		if ( $meta == 'zero' ) {
			$meta = 0;
		}

		return intval( $meta );
	}

	/**
	 * Checked infinity loop is enabled or disabled
	 *
	 * @return bool
	 */
	protected function infinity_loop() {
		return $this->checked( $this->get_meta( '_inifnity_loop' ) );
	}

	/**
	 * Left and right padding on carousel slider stage wrapper.
	 *
	 * @return int
	 */
	protected function stage_padding() {
		$meta = $this->get_meta( '_stage_padding', 0 );
		if ( $meta == 'zero' ) {
			$meta = 0;
		}

		return intval( $meta );
	}

	/**
	 * Check auto width is enabled
	 *
	 * @return bool
	 */
	protected function auto_width() {
		return $this->checked( $this->get_meta( '_auto_width', 'off' ) );
	}

	/********************************************************************************
	 * Automatic Play Settings
	 *******************************************************************************/

	/**
	 * Check if autoplay is enabled
	 *
	 * @return bool
	 */
	protected function autoplay() {
		return $this->checked( $this->get_meta( '_autoplay', true ) );
	}

	/**
	 * Check autoplay hover pause is enabled
	 *
	 * @return bool
	 */
	protected function autoplay_hover_pause() {
		return $this->checked( $this->get_meta( '_autoplay_pause', true ) );
	}

	/**
	 * Get autoplay timeout
	 *
	 * @return int
	 */
	protected function autoplay_timeout() {
		return intval( $this->get_meta( '_autoplay_timeout', 5000 ) );
	}

	/**
	 * Get autoplay speed
	 *
	 * @return int
	 */
	protected function autoplay_speed() {
		return intval( $this->get_meta( '_autoplay_speed', 500 ) );
	}

	/********************************************************************************
	 * Navigation Settings
	 *******************************************************************************/

	/**
	 * Get number of steps to go for each navigation request
	 *
	 * @return int|string
	 */
	protected function slide_by() {
		$slide_by = $this->get_meta( '_slide_by' );

		if ( false !== strpos( 'page', $slide_by ) ) {
			return 'page';
		}

		return intval( $slide_by );
	}

	/**
	 * Get slider navigation color
	 *
	 * @return string
	 */
	protected function nav_color() {
		$default = Utils::get_default_setting( 'nav_color' );

		return Utils::sanitize_color( $this->get_meta( '_nav_color', $default ) );
	}

	/**
	 * Get slider navigation color for hover and active state
	 *
	 * @return string
	 */
	protected function nav_active_color() {
		$default = Utils::get_default_setting( 'nav_active_color' );

		return Utils::sanitize_color( $this->get_meta( '_nav_active_color', $default ) );
	}

	/**
	 * Check if navigation is enabled
	 *
	 * @return bool
	 */
	protected function is_nav_enabled() {
		return 'off' !== $this->get_meta( '_nav_button' );
	}

	/**
	 * Check if dot navigation is enabled
	 *
	 * @return bool
	 */
	protected function is_dot_enabled() {
		return 'off' !== $this->get_meta( '_dot_nav' );
	}

	/**
	 * Get arrow position
	 *
	 * @return string
	 */
	protected function arrow_position() {
		$arrow_position = $this->get_meta( '_arrow_position', 'outside' );

		return in_array( $arrow_position, array( 'inside', 'outside' ) ) ? $arrow_position : 'outside';
	}

	/**
	 * Get arrow visibility
	 *
	 * @return string
	 */
	protected function arrow_visibility() {
		$visibility = $this->get_meta( '_nav_button', 'on' );

		if ( 'always' == $visibility ) {
			return 'always';
		}

		if ( 'on' == $visibility ) {
			return 'hover';
		}

		return 'never';
	}

	/**
	 * Get dots position
	 *
	 * @return string
	 */
	protected function dots_position() {
		$arrow_position = $this->get_meta( '_bullet_position', 'center' );

		return in_array( $arrow_position, array( 'left', 'center', 'right' ) ) ? $arrow_position : 'center';
	}

	/**
	 * Get dots visibility
	 *
	 * @return string
	 */
	protected function dots_visibility() {
		$visibility = $this->get_meta( '_dot_nav', 'off' );

		if ( 'on' == $visibility ) {
			return 'always';
		}

		if ( 'hover' == $visibility ) {
			return 'hover';
		}

		return 'never';
	}

	/**
	 * Get dots shape
	 *
	 * @return string
	 */
	protected function dots_shape() {
		$arrow_position = $this->get_meta( '_bullet_shape', 'circle' );

		return in_array( $arrow_position, array( 'square', 'circle' ) ) ? $arrow_position : 'circle';
	}

	/********************************************************************************
	 * Responsive Settings
	 *******************************************************************************/

	/**
	 * Get responsive settings
	 *
	 * @return array
	 */
	protected function responsive() {
		$items_mobile        = intval( $this->get_meta( '_items_portrait_mobile', 1 ) );
		$items_small_tab     = intval( $this->get_meta( '_items_small_portrait_tablet', 2 ) );
		$items_tablet        = intval( $this->get_meta( '_items_portrait_tablet', 3 ) );
		$items_small_desktop = intval( $this->get_meta( '_items_small_desktop', 4 ) );
		$items_desktop       = intval( $this->get_meta( '_items_desktop', 4 ) );
		$items               = intval( $this->get_meta( '_items', 4 ) );

		return array(
			array( 'breakpoint' => 300, 'items' => $items_mobile ),
			array( 'breakpoint' => 600, 'items' => $items_small_tab ),
			array( 'breakpoint' => 768, 'items' => $items_tablet ),
			array( 'breakpoint' => 993, 'items' => $items_small_desktop ),
			array( 'breakpoint' => 1200, 'items' => $items_desktop ),
			array( 'breakpoint' => 1600, 'items' => $items ),
		);
	}
}
