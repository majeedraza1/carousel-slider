<?php

namespace CarouselSlider\Modules\HeroCarousel;

defined( 'ABSPATH' ) || exit;

class HeroCarouselHelper {
	/**
	 * @param bool $key_only
	 *
	 * @return array
	 */
	public static function background_size( $key_only = false ): array {
		$sizes = [
			'auto'      => 'auto',
			'contain'   => 'contain',
			'cover'     => 'cover', // Default
			'100% 100%' => '100%',
			'100% auto' => '100% width',
			'auto 100%' => '100% height',
		];
		if ( $key_only ) {
			return array_keys( $sizes );
		}

		return $sizes;
	}

	/**
	 * @param bool $key_only
	 *
	 * @return array
	 */
	public static function background_position( $key_only = false ): array {
		$positions = [
			'left top'      => 'left top',
			'left center'   => 'left center',
			'left bottom'   => 'left bottom',
			'center top'    => 'center top',
			'center center' => 'center', // Default
			'center bottom' => 'center bottom',
			'right top'     => 'right top',
			'right center'  => 'right center',
			'right bottom'  => 'right bottom',
		];
		if ( $key_only ) {
			return array_keys( $positions );
		}

		return $positions;
	}
}
