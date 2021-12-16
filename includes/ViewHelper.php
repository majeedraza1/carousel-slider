<?php

namespace CarouselSlider;

use CarouselSlider\Supports\Validate;

defined( 'ABSPATH' ) || exit;

/**
 * ViewHelper class
 */
class ViewHelper {


	/**
	 * Array to style
	 *
	 * @param array $styles The styles array.
	 *
	 * @return string
	 */
	public static function array_to_style( array $styles ): string {
		$_styles = [];
		foreach ( $styles as $key => $value ) {
			if ( ! is_string( $key ) || empty( $value ) ) {
				continue;
			}
			$_styles[] = sprintf( '%s:%s', $key, $value );
		}

		return implode( ';', $_styles );
	}

	/**
	 * Convert array to html data attribute
	 *
	 * @param array $array The attributes list.
	 *
	 * @return array
	 */
	public static function array_to_attribute( array $array ): array {
		return array_map(
			function ( $key, $value ) {
				// If boolean value.
				if ( is_bool( $value ) ) {
					return sprintf( '%s="%s"', $key, ( $value ? 'true' : 'false' ) );
				}
				// If array value.
				if ( is_array( $value ) ) {
					return sprintf( '%s="%s"', $key, implode( ' ', $value ) );
				}

				if ( is_string( $value ) && Validate::json( $value ) ) {
					return sprintf( "%s='%s'", $key, $value );
				}

				// If string value.
				return sprintf( '%s="%s"', $key, esc_attr( $value ) );

			},
			array_keys( $array ),
			array_values( $array )
		);
	}
}
