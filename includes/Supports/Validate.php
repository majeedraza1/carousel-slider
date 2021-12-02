<?php

namespace CarouselSlider\Supports;

defined( 'ABSPATH' ) || exit;

class Validate {
	/**
	 * Check if url is valid as per RFC 2396 Generic Syntax
	 *
	 * @param mixed $url
	 *
	 * @return bool
	 */
	public static function url( $url ): bool {
		return (bool) filter_var( $url, FILTER_VALIDATE_URL );
	}

	/**
	 * If a field has been 'checked' or not, meaning it contains
	 * one of the following values: 'yes', 'on', '1', 1, true, or 'true'.
	 * This can be used for determining if an HTML checkbox has been checked.
	 *
	 * @param mixed $value
	 *
	 * @return boolean
	 */
	public static function checked( $value ): bool {
		return in_array( $value, [ 'yes', 'on', '1', 1, true, 'true' ], true );
	}

	/**
	 * Check if value is json
	 *
	 * @param mixed $string
	 *
	 * @return bool
	 */
	public static function json( $string ): bool {
		if ( ! is_string( $string ) ) {
			return false;
		}
		json_decode( $string );

		return ( json_last_error() == JSON_ERROR_NONE );
	}
}
