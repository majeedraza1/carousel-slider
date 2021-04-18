<?php

namespace CarouselSlider\Frontend;

use CarouselSlider\Helper;

defined( 'ABSPATH' ) || exit;

class Shortcode {

	/**
	 * Generate carousel options for slider
	 *
	 * @param $id
	 *
	 * @return array
	 */
	public function carousel_options( $id ) {
		$_dot_nav    = ( get_post_meta( $id, '_dot_nav', true ) != 'off' );
		$_nav_button = ( get_post_meta( $id, '_nav_button', true ) != 'off' );

		$classes = Helper::get_css_classes( $id );

		$options_array = [
			'id'                        => 'id-' . $id,
			'class'                     => implode( ' ', $classes ),
			// General
			'data-slide-type'           => $this->get_meta( $id, '_slide_type', 'image-carousel' ),
			'data-margin'               => $this->get_meta( $id, '_margin_right', '10' ),
			'data-slide-by'             => $this->get_meta( $id, '_slide_by', '1' ),
			'data-loop'                 => $this->get_meta( $id, '_infinity_loop', 'true' ),
			'data-lazy-load'            => $this->get_meta( $id, '_lazy_load_image', 'false' ),
			'data-stage-padding'        => $this->get_meta( $id, '_stage_padding', '0' ),
			'data-auto-width'           => $this->get_meta( $id, '_auto_width', 'false' ),
			// Navigation
			'data-dots'                 => $_dot_nav,
			'data-nav'                  => $_nav_button,
			// Autoplay
			'data-autoplay'             => $this->get_meta( $id, '_autoplay', 'true' ),
			'data-autoplay-timeout'     => $this->get_meta( $id, '_autoplay_timeout', '5000' ),
			'data-autoplay-speed'       => $this->get_meta( $id, '_autoplay_speed', '500' ),
			'data-autoplay-hover-pause' => $this->get_meta( $id, '_autoplay_pause', 'false' ),
			// Responsive
			'data-colums'               => $this->get_meta( $id, '_items', '4' ),
			'data-colums-desktop'       => $this->get_meta( $id, '_items_desktop', '4' ),
			'data-colums-small-desktop' => $this->get_meta( $id, '_items_small_desktop', '4' ),
			'data-colums-tablet'        => $this->get_meta( $id, '_items_portrait_tablet', '3' ),
			'data-colums-small-tablet'  => $this->get_meta( $id, '_items_small_portrait_tablet', '2' ),
			'data-colums-mobile'        => $this->get_meta( $id, '_items_portrait_mobile', '1' ),
		];

		return Helper::array_to_attribute( $options_array );
	}

	/**
	 * Get post meta by id and key
	 *
	 * @param $id
	 * @param $key
	 * @param null $default
	 *
	 * @return string
	 */
	public function get_meta( $id, $key, $default = null ): string {
		return Helper::get_meta( $id, $key, $default );
	}
}
