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
	public static function carousel_options( $id ): array {
		$classes = Helper::get_css_classes( $id );

		$options_array = [
			'id'                        => 'id-' . $id,
			'class'                     => implode( ' ', $classes ),
			// General
			'data-slide-type'           => Helper::get_meta( $id, '_slide_type', 'image-carousel' ),
			'data-margin'               => Helper::get_meta( $id, '_margin_right', '10' ),
			'data-slide-by'             => Helper::get_meta( $id, '_slide_by', '1' ),
			'data-loop'                 => Helper::get_meta( $id, '_infinity_loop', 'true' ),
			'data-lazy-load'            => Helper::get_meta( $id, '_lazy_load_image', 'false' ),
			'data-stage-padding'        => Helper::get_meta( $id, '_stage_padding', '0' ),
			'data-auto-width'           => Helper::get_meta( $id, '_auto_width', 'false' ),
			// Navigation
			'data-dots'                 => get_post_meta( $id, '_dot_nav', true ) != 'off',
			'data-nav'                  => get_post_meta( $id, '_nav_button', true ) != 'off',
			// Autoplay
			'data-autoplay'             => Helper::get_meta( $id, '_autoplay', 'true' ),
			'data-autoplay-timeout'     => Helper::get_meta( $id, '_autoplay_timeout', '5000' ),
			'data-autoplay-speed'       => Helper::get_meta( $id, '_autoplay_speed', '500' ),
			'data-autoplay-hover-pause' => Helper::get_meta( $id, '_autoplay_pause', 'false' ),
			// Responsive
			'data-colums'               => Helper::get_meta( $id, '_items', '4' ),
			'data-colums-desktop'       => Helper::get_meta( $id, '_items_desktop', '4' ),
			'data-colums-small-desktop' => Helper::get_meta( $id, '_items_small_desktop', '4' ),
			'data-colums-tablet'        => Helper::get_meta( $id, '_items_portrait_tablet', '3' ),
			'data-colums-small-tablet'  => Helper::get_meta( $id, '_items_small_portrait_tablet', '2' ),
			'data-colums-mobile'        => Helper::get_meta( $id, '_items_portrait_mobile', '1' ),
		];

		return Helper::array_to_attribute( $options_array );
	}
}
