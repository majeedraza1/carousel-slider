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
		return in_array( $value, array( 'yes', 'on', '1', 1, true, 'true' ), true );
	}
}
