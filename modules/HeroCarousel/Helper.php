<?php

namespace CarouselSlider\Modules\HeroCarousel;

defined( 'ABSPATH' ) || exit;

/**
 * Helper class
 *
 * @package Modules/HeroCarousel
 */
class Helper {
	/**
	 * Get background size
	 *
	 * @return array
	 */
	public static function background_size(): array {
		return [
			'auto'      => 'auto',
			'contain'   => 'contain',
			'cover'     => 'cover',
			'100% 100%' => '100%',
			'100% auto' => '100% width',
			'auto 100%' => '100% height',
		];
	}

	/**
	 * Get background positions
	 *
	 * @return array
	 */
	public static function background_position(): array {
		return [
			'left top'      => 'left top',
			'left center'   => 'left center',
			'left bottom'   => 'left bottom',
			'center top'    => 'center top',
			'center center' => 'center',
			'center bottom' => 'center bottom',
			'right top'     => 'right top',
			'right center'  => 'right center',
			'right bottom'  => 'right bottom',
		];
	}

	/**
	 * Get animations
	 *
	 * @return array
	 */
	public static function animations(): array {
		return [
			''            => esc_html__( 'None', 'carousel-slider' ),
			'fadeInDown'  => esc_html__( 'Fade In Down', 'carousel-slider' ),
			'fadeInUp'    => esc_html__( 'Fade In Up', 'carousel-slider' ),
			'fadeInRight' => esc_html__( 'Fade In Right', 'carousel-slider' ),
			'fadeInLeft'  => esc_html__( 'Fade In Left', 'carousel-slider' ),
			'zoomIn'      => esc_html__( 'Zoom In', 'carousel-slider' ),
		];
	}

	/**
	 * Get text alignment
	 *
	 * @return string[]
	 */
	public static function text_alignment(): array {
		return [
			'left'   => 'left',
			'center' => 'center',
			'right'  => 'right',
		];
	}

	/**
	 * Get link type
	 *
	 * @return array
	 */
	public static function link_type(): array {
		return [
			'none'   => esc_html__( 'No Link', 'carousel-slider' ),
			'full'   => esc_html__( 'Full Slide', 'carousel-slider' ),
			'button' => esc_html__( 'Button', 'carousel-slider' ),
		];
	}

	/**
	 * Link target
	 *
	 * @return string[]
	 */
	public static function link_target(): array {
		return [ '_blank', '_self' ];
	}

	/**
	 * Ken burns effects
	 *
	 * @return array
	 */
	public static function ken_burns_effects(): array {
		return [
			''         => __( 'None', 'carousel-slider' ),
			'zoom-in'  => __( 'Zoom In', 'carousel-slider' ),
			'zoom-out' => __( 'Zoom Out', 'carousel-slider' ),
		];
	}
}
