<?php

namespace CarouselSlider\Supports;

defined( 'ABSPATH' ) || exit;

class Sanitize {

	/**
	 * Sanitize number options.
	 *
	 * @param mixed $value The value to be sanitized.
	 *
	 * @return int|float
	 */
	public static function number( $value ) {
		if ( ! is_numeric( $value ) ) {
			return 0;
		}
		if ( is_int( $value ) || is_float( $value ) ) {
			return $value;
		}

		if ( preg_match( "/^\\d+\\.\\d+$/", $value ) === 1 ) {
			return floatval( $value );
		}

		return intval( $value );
	}

	/**
	 * Sanitize float number
	 *
	 * @param mixed $value
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
	 * @param mixed $value
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
	 * @param mixed $value
	 *
	 * @return string
	 */
	public static function email( $value ): string {
		return sanitize_email( $value );
	}

	/**
	 * Sanitize url
	 *
	 * @param mixed $value
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
	 * @param mixed $value
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
	 * @param mixed $value
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
	 * @param mixed $value
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
	 * @param mixed $value
	 *
	 * @return boolean
	 */
	public static function date( $value ) {
		$time = strtotime( $value );

		if ( $time ) {
			return date( 'Y-m-d', $time );
		}

		return '';
	}

	/**
	 * Sanitize short block html input
	 *
	 * @param mixed $value
	 *
	 * @return string
	 */
	public static function html( $value ): string {
		return wp_kses_post( $value );
	}

	/**
	 * Sanitize color
	 *
	 * @param mixed $value hex, rgb, rgba color or transparent
	 *
	 * @return string
	 */
	public static function color( $value ): string {
		// If the value is empty, then return empty.
		if ( '' === $value || ! is_string( $value ) ) {
			return '';
		}

		// If transparent, then return 'transparent'.
		if ( 'transparent' === trim( $value ) ) {
			return 'transparent';
		}

		// Trim unneeded whitespace
		$value = str_replace( ' ', '', $value );

		// If this is hex color, validate and return it
		if ( 1 === preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $value ) ) {
			return $value;
		}

		// If this is rgb, validate and return it
		if ( 'rgb(' === substr( $value, 0, 4 ) ) {
			return self::rgb_color( $value );
		}

		// If this is rgba, validate and return it
		if ( 'rgba(' === substr( $value, 0, 5 ) ) {
			return self::rgba_color( $value );
		}

		// Not valid color, return empty string
		return '';
	}

	/**
	 * Sanitize RGB Color
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	public static function rgb_color( string $value ): string {
		$value = str_replace( ' ', '', $value );
		if ( 'rgb(' === substr( $value, 0, 4 ) ) {
			list( $red, $green, $blue ) = sscanf( $value, 'rgb(%d,%d,%d)' );

			if ( self::is_validate_rgba( $red, $green, $blue ) ) {
				return "rgb($red,$green,$blue)";
			}
		}

		return '';
	}

	public static function rgba_color( string $value ): string {
		$value = str_replace( ' ', '', $value );
		if ( 'rgba(' === substr( $value, 0, 5 ) ) {
			list( $red, $green, $blue, $alpha ) = sscanf( $value, 'rgba(%d,%d,%d,%f)' );

			if ( self::is_validate_rgba( $red, $green, $blue, $alpha ) ) {
				return "rgba($red,$green,$blue,$alpha)";
			}
		}

		return '';
	}

	/**
	 * Validate if it is a valid rgba color
	 *
	 * @param int $red
	 * @param int $green
	 * @param int $blue
	 * @param float $alpha
	 *
	 * @return bool
	 */
	private static function is_validate_rgba( int $red, int $green, int $blue, float $alpha = 1 ): bool {
		return ( $red >= 0 && $red <= 255 ) &&
		       ( $green >= 0 && $green <= 255 ) &&
		       ( $blue >= 0 && $blue <= 255 ) &&
		       $alpha >= 0 && $alpha <= 1;
	}


	/**
	 * Sanitize meta value
	 *
	 * @param mixed $value
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
}
