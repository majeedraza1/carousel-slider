<?php

namespace CarouselSlider\Supports;

defined( 'ABSPATH' ) || exit;

/**
 * Sanitize class
 */
class Sanitize {

	/**
	 * Sanitize number options.
	 *
	 * @param  mixed $value  The value to be sanitized.
	 *
	 * @return int|float
	 */
	public static function number( $value ) {
		if ( ! is_numeric( $value ) ) {
			return 0;
		}

		if ( preg_match( '/^\\d+\\.\\d+$/', $value ) === 1 ) {
			return floatval( $value );
		}

		return intval( $value );
	}

	/**
	 * Sanitize float number
	 *
	 * @param  mixed $value  The value to be sanitized.
	 *
	 * @return float
	 */
	public static function float( $value ): float {
		if ( ! is_numeric( $value ) ) {
			return 0;
		}

		return floatval( $value );
	}

	/**
	 * Sanitize integer number
	 *
	 * @param  mixed $value  The value to be sanitized.
	 *
	 * @return int
	 */
	public static function int( $value ): int {
		if ( ! is_numeric( $value ) ) {
			return 0;
		}

		return intval( $value );
	}

	/**
	 * Sanitize email
	 *
	 * @param  mixed $value  The value to be sanitized.
	 *
	 * @return string
	 */
	public static function email( $value ): string {
		return sanitize_email( $value );
	}

	/**
	 * Sanitize url
	 *
	 * @param  mixed $value  The value to be sanitized.
	 *
	 * @return string
	 */
	public static function url( $value ): string {
		return esc_url_raw( trim( $value ) );
	}

	/**
	 * Sanitizes a string
	 *
	 * - Checks for invalid UTF-8,
	 * - Converts single `<` characters to entities
	 * - Strips all tags
	 * - Removes line breaks, tabs, and extra whitespace
	 * - Strips octets
	 *
	 * @param  mixed $value  The value to be sanitized.
	 *
	 * @return string
	 */
	public static function text( $value ): string {
		return sanitize_text_field( $value );
	}

	/**
	 * Sanitizes a multiline string
	 *
	 * The function is like sanitize_text_field(), but preserves
	 * new lines (\n) and other whitespace, which are legitimate
	 * input in textarea elements.
	 *
	 * @param  mixed $value  The value to be sanitized.
	 *
	 * @return string
	 */
	public static function textarea( $value ): string {
		return sanitize_textarea_field( $value );
	}

	/**
	 * If a field has been 'checked' or not, meaning it contains
	 * one of the following values: 'yes', 'on', '1', 1, true, or 'true'.
	 * This can be used for determining if an HTML checkbox has been checked.
	 *
	 * @param  mixed $value  The value to be sanitized.
	 *
	 * @return mixed|boolean|string
	 */
	public static function checked( $value ) {
		$true_values  = [ 'yes', 'on', '1', 1, true, 'true' ];
		$false_values = [ 'no', 'off', '0', 0, false, 'false' ];

		return in_array( $value, array_merge( $true_values, $false_values ), true ) ? $value : '';
	}

	/**
	 * Check if the given input is a valid date.
	 *
	 * @param  mixed $value  The value to be sanitized.
	 *
	 * @return boolean
	 */
	public static function date( $value ) {
		$time = strtotime( $value );

		if ( $time ) {
			return gmdate( 'Y-m-d', $time );
		}

		return '';
	}

	/**
	 * Sanitize short block html input
	 *
	 * @param  mixed $value  The value to be sanitized.
	 *
	 * @return string
	 */
	public static function html( $value ): string {
		return wp_kses_post( $value );
	}

	/**
	 * Sanitize colors.
	 *
	 * @param  mixed $value  The color.
	 *
	 * @return string
	 */
	public static function color( $value ): string {
		// If the value is empty, then return empty.
		if ( '' === $value || ! is_string( $value ) ) {
			return '';
		}

		// Trim unneeded whitespace.
		$value = str_replace( ' ', '', $value );

		// This pattern will check and match 3/6/8-character hex, rgb, rgba, hsl, & hsla colors.
		$pattern  = '/^(\#[\da-f]{3}|\#[\da-f]{6}|\#[\da-f]{8}|';
		$pattern .= 'rgba\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)(,\s*(0\.\d+|1))\)|';
		$pattern .= 'hsla\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)(,\s*(0\.\d+|1))\)|';
		$pattern .= 'rgb\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)\)|';
		$pattern .= 'hsl\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)\))$/';

		// Return the 1st match found.
		if ( 1 === preg_match( $pattern, $value ) ) {
			return $value;
		}

		// If no match was found, return an empty string.
		return '';
	}


	/**
	 * Sanitize meta value
	 *
	 * @param  mixed $value  The value to be sanitized.
	 *
	 * @return mixed
	 */
	public static function deep( $value ) {
		if ( empty( $value ) ) {
			return $value;
		}
		if ( is_scalar( $value ) ) {
			if ( is_numeric( $value ) ) {
				return self::number( $value );
			}

			return sanitize_text_field( $value );
		}

		$sanitized_value = [];
		if ( is_array( $value ) ) {
			foreach ( $value as $index => $item ) {
				$sanitized_value[ $index ] = self::deep( $item );
			}
		}

		return $sanitized_value;
	}

	/**
	 * Sanitize array of integer
	 *
	 * @param  mixed $value  The value to be sanitized.
	 *
	 * @return array
	 */
	public static function deep_int( $value ): array {
		if ( ! is_array( $value ) ) {
			return [];
		}

		return map_deep( $value, 'intval' );
	}

	/**
	 * Sanitizes css dimensions.
	 *
	 * @param  mixed $value  The value to be sanitized.
	 *
	 * @return string
	 */
	public static function css_dimension( $value ): string {
		if ( ! ( is_string( $value ) || is_numeric( $value ) ) ) {
			return '';
		}
		// Trim it.
		$value = trim( $value );

		// If the value is round, then return 50%.
		if ( 'round' === $value ) {
			$value = '50%';
		}

		// If the value is empty, return empty.
		if ( '' === $value ) {
			return '';
		}

		// If auto, inherit or initial, return the value.
		if ( 'auto' === $value || 'initial' === $value || 'inherit' === $value ) {
			return $value;
		}

		// Return empty if there are no numbers in the value.
		if ( ! preg_match( '#[0-9]#', $value ) ) {
			return '';
		}

		// The raw value without the units.
		$raw_value = filter_var( $value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
		$unit_used = '';

		// An array of all valid CSS units. Their order was carefully chosen for this evaluation, don't mix it up!!!
		$units = array(
			'rem',
			'em',
			'ex',
			'%',
			'px',
			'cm',
			'mm',
			'in',
			'pt',
			'pc',
			'ch',
			'vh',
			'vw',
			'vmin',
			'vmax',
		);
		foreach ( $units as $unit ) {
			if ( false !== strpos( $value, $unit ) ) {
				$unit_used = $unit;
			}
		}

		// Hack for rem values.
		if ( 'em' === $unit_used && false !== strpos( $value, 'rem' ) ) {
			$unit_used = 'rem';
		}

		return $raw_value . $unit_used;
	}
}
