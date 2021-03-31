<?php

namespace CarouselSlider\Modules\HeroCarousel;

use CarouselSlider\Supports\Sanitize;

defined( 'ABSPATH' ) || exit;

class Item {
	/**
	 * Default content
	 *
	 * @var string[]
	 */
	protected static $default = [
		// Slide Content
		'slide_heading'            => '',
		'slide_description'        => '',
		// Slide Background
		'img_id'                   => '',
		'img_bg_position'          => 'center center',
		'img_bg_size'              => 'cover',
		'bg_color'                 => 'rgba(0,0,0,0.6)',
		'ken_burns_effect'         => '',
		'bg_overlay'               => '',
		// Slide Style
		'content_alignment'        => 'center',
		'heading_font_size'        => '40',
		'heading_gutter'           => '30px',
		'heading_color'            => '#ffffff',
		'description_font_size'    => '20',
		'description_gutter'       => '30px',
		'description_color'        => '#ffffff',
		// Slide Link
		'link_type'                => 'none',
		'slide_link'               => '',
		'link_target'              => '_self',
		// Slide Button #1
		'button_one_text'          => '',
		'button_one_url'           => '',
		'button_one_target'        => '_self',
		'button_one_type'          => 'stroke',
		'button_one_size'          => 'medium',
		'button_one_border_width'  => '3px',
		'button_one_border_radius' => '0px',
		'button_one_bg_color'      => '#ffffff',
		'button_one_color'         => '#323232',
		// Slide Button #2
		'button_two_text'          => '',
		'button_two_url'           => '',
		'button_two_target'        => '_self',
		'button_two_type'          => 'stroke',
		'button_two_size'          => 'medium',
		'button_two_border_width'  => '3px',
		'button_two_border_radius' => '0px',
		'button_two_bg_color'      => '#ffffff',
		'button_two_color'         => '#323232',
	];

	/**
	 * Get default data
	 *
	 * @return string[]
	 */
	public static function get_default(): array {
		return self::$default;
	}

	/**
	 * Sanitize item data
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public static function sanitize( array $data ): array {
		$color_fields  = [
			'bg_color',
			'bg_overlay',
			'heading_color',
			'description_color',
			'button_one_bg_color',
			'button_one_color',
			'button_two_bg_color',
			'button_two_color',
		];
		$data          = wp_parse_args( $data, self::get_default() );
		$sanitize_data = [];
		foreach ( $data as $key => $value ) {
			if ( in_array( $key, [ 'slide_heading', 'slide_description' ] ) ) {
				$sanitize_data[ $key ] = Sanitize::html( $value );
			} elseif ( in_array( $key, [ 'img_id', 'heading_font_size', 'description_font_size' ] ) ) {
				$sanitize_data[ $key ] = Sanitize::int( $value );
			} elseif ( in_array( $key, [ 'slide_link', 'button_one_url', 'button_two_url' ] ) ) {
				$sanitize_data[ $key ] = Sanitize::url( $value );
			} elseif ( in_array( $key, $color_fields ) ) {
				$sanitize_data[ $key ] = Sanitize::color( $value );
			} else {
				$sanitize_data[ $key ] = Sanitize::text( $value );
			}
		}

		return $sanitize_data;
	}
}
