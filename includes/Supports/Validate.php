<?php

namespace CarouselSlider\Supports;

defined( 'ABSPATH' ) || exit;

/**
 * Validate class
 */
class Validate {
	/**
	 * Check if url is valid as per RFC 2396 Generic Syntax
	 *
	 * @param mixed $url The URL string.
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
	 * @param mixed $value The value to be checked.
	 *
	 * @return boolean
	 */
	public static function checked( $value ): bool {
		return in_array( $value, [ 'yes', 'on', '1', 1, true, 'true' ], true );
	}

	/**
	 * Check if value is json
	 *
	 * @param mixed $value The value to be checked.
	 *
	 * @return bool
	 */
	public static function json( $value ): bool {
		if ( ! is_string( $value ) ) {
			return false;
		}
		json_decode( $value );

		return ( json_last_error() === JSON_ERROR_NONE );
	}
}
