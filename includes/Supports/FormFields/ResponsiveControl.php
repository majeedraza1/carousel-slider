<?php

namespace CarouselSlider\Supports\FormFields;

use CarouselSlider\Helper;

/**
 * Columns class
 */
class ResponsiveControl extends BaseField {
	/**
	 * Render field html
	 *
	 * @inheritDoc
	 */
	public function render(): string {
		$devices = (array) $this->get_setting( 'device_choices', [] );
		$default = (array) $this->get_setting( 'default', [] );
		$value   = (array) $this->get_value();
		$name    = $this->get_name();

		$html = '<div>';
		foreach ( $devices as $key ) {
			$attr_name  = $name . '[' . $key . ']';
			$attr_value = $value[ $key ] ?? $default[ $key ];

			$html .= '<div class="shapla-dimension">';
			$html .= '<span class="add-on cs-tooltip" title="' . $this->get_breakpoints_label( $key ) . '">' . $this->get_breakpoints_icon( $key ) . '</span>';
			$html .= '<input type="text" name="' . esc_attr( $attr_name ) . '" value="' . esc_attr( $attr_value ) . '">';
			$html .= '</div>';
		}
		$html .= '</div>';

		return $html;
	}

	/**
	 * Get breakpoint info
	 *
	 * @param string $prefix The breakpoint prefix.
	 *
	 * @return string
	 */
	public function get_breakpoints_label( string $prefix ): string {
		$labels = [
			'xs'  => sprintf( 'Extra small device (Mobile): <%spx', Helper::get_breakpoint_width( 'sm' ) ),
			'sm'  => sprintf( 'Small device (Small Tablet): ≥%spx', Helper::get_breakpoint_width( 'sm' ) ),
			'md'  => sprintf( 'Medium device (Tablet): ≥%spx', Helper::get_breakpoint_width( 'md' ) ),
			'lg'  => sprintf( 'Large device (Desktop): ≥%spx', Helper::get_breakpoint_width( 'lg' ) ),
			'xl'  => sprintf( 'Extra large device (Widescreen): ≥%spx', Helper::get_breakpoint_width( 'xl' ) ),
			'2xl' => sprintf( '2X large device (Full HD): ≥%spx', Helper::get_breakpoint_width( '2xl' ) ),
		];

		return $labels[ $prefix ] ?? '';
	}

	/**
	 * Get breakpoint info
	 *
	 * @param string $prefix The breakpoint prefix.
	 *
	 * @return string
	 */
	public function get_breakpoints_icon( string $prefix ): string {
		$icons = [
			'xs'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><path d="M13 46Q11.8 46 10.9 45.1Q10 44.2 10 43V5Q10 3.8 10.9 2.9Q11.8 2 13 2H35Q36.2 2 37.1 2.9Q38 3.8 38 5V43Q38 44.2 37.1 45.1Q36.2 46 35 46ZM13 38.5H35V9.5H13ZM13 41.5V43Q13 43 13 43Q13 43 13 43H35Q35 43 35 43Q35 43 35 43V41.5ZM13 6.5H35V5Q35 5 35 5Q35 5 35 5H13Q13 5 13 5Q13 5 13 5ZM13 5Q13 5 13 5Q13 5 13 5V6.5V5Q13 5 13 5Q13 5 13 5ZM13 43Q13 43 13 43Q13 43 13 43V41.5V43Q13 43 13 43Q13 43 13 43Z"/></svg>',
			'sm'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><path d="M5 38Q3.8 38 2.9 37.1Q2 36.2 2 35V13Q2 11.8 2.9 10.9Q3.8 10 5 10H43Q44.2 10 45.1 10.9Q46 11.8 46 13V35Q46 36.2 45.1 37.1Q44.2 38 43 38ZM9.5 35H38.5V13H9.5ZM6.5 35V13H5Q5 13 5 13Q5 13 5 13V35Q5 35 5 35Q5 35 5 35ZM41.5 35H43Q43 35 43 35Q43 35 43 35V13Q43 13 43 13Q43 13 43 13H41.5ZM43 13Q43 13 43 13Q43 13 43 13H41.5H43Q43 13 43 13Q43 13 43 13ZM5 13Q5 13 5 13Q5 13 5 13H6.5H5Q5 13 5 13Q5 13 5 13Z"/></svg>',
			'md'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><path d="M9 46Q7.75 46 6.875 45.125Q6 44.25 6 43V5Q6 3.75 6.875 2.875Q7.75 2 9 2H39Q40.25 2 41.125 2.875Q42 3.75 42 5V43Q42 44.25 41.125 45.125Q40.25 46 39 46ZM9 35.5H39V9.5H9ZM9 38.5V43Q9 43 9 43Q9 43 9 43H39Q39 43 39 43Q39 43 39 43V38.5ZM9 6.5H39V5Q39 5 39 5Q39 5 39 5H9Q9 5 9 5Q9 5 9 5ZM9 5Q9 5 9 5Q9 5 9 5V6.5V5Q9 5 9 5Q9 5 9 5ZM9 43Q9 43 9 43Q9 43 9 43V38.5V43Q9 43 9 43Q9 43 9 43ZM24 42.25Q24.65 42.25 25.075 41.825Q25.5 41.4 25.5 40.75Q25.5 40.1 25.075 39.675Q24.65 39.25 24 39.25Q23.35 39.25 22.925 39.675Q22.5 40.1 22.5 40.75Q22.5 41.4 22.925 41.825Q23.35 42.25 24 42.25Z"/></svg>',
			'lg'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><path d="M2.85 40Q1.65 40 0.825 39.125Q0 38.25 0 37H7.05Q5.85 37 4.95 36.1Q4.05 35.2 4.05 34V9Q4.05 7.8 4.95 6.9Q5.85 6 7.05 6H40.95Q42.15 6 43.05 6.9Q43.95 7.8 43.95 9V34Q43.95 35.2 43.05 36.1Q42.15 37 40.95 37H48Q48 38.25 47.125 39.125Q46.25 40 45 40ZM40.95 34Q40.95 34 40.95 34Q40.95 34 40.95 34V9Q40.95 9 40.95 9Q40.95 9 40.95 9H7.05Q7.05 9 7.05 9Q7.05 9 7.05 9V34Q7.05 34 7.05 34Q7.05 34 7.05 34ZM24 38.9Q24.7 38.9 25.2 38.4Q25.7 37.9 25.7 37.2Q25.7 36.5 25.2 36Q24.7 35.5 24 35.5Q23.3 35.5 22.8 36Q22.3 36.5 22.3 37.2Q22.3 37.9 22.8 38.4Q23.3 38.9 24 38.9ZM7.05 34Q7.05 34 7.05 34Q7.05 34 7.05 34V9Q7.05 9 7.05 9Q7.05 9 7.05 9Q7.05 9 7.05 9Q7.05 9 7.05 9V34Q7.05 34 7.05 34Q7.05 34 7.05 34Z"/></svg>',
			'xl'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><path d="M16.75 44V41H21.05V36H7.05Q5.85 36 4.95 35.1Q4.05 34.2 4.05 33V9Q4.05 7.8 4.95 6.9Q5.85 6 7.05 6H41.05Q42.25 6 43.15 6.9Q44.05 7.8 44.05 9V33Q44.05 34.2 43.15 35.1Q42.25 36 41.05 36H27.05V41H31.35V44ZM7.05 33H41.05Q41.05 33 41.05 33Q41.05 33 41.05 33V9Q41.05 9 41.05 9Q41.05 9 41.05 9H7.05Q7.05 9 7.05 9Q7.05 9 7.05 9V33Q7.05 33 7.05 33Q7.05 33 7.05 33ZM7.05 33Q7.05 33 7.05 33Q7.05 33 7.05 33V9Q7.05 9 7.05 9Q7.05 9 7.05 9Q7.05 9 7.05 9Q7.05 9 7.05 9V33Q7.05 33 7.05 33Q7.05 33 7.05 33Z"/></svg>',
			'2xl' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><path d="M16.8 44V42.25L21 38H7Q5.8 38 4.9 37.1Q4 36.2 4 35V9Q4 7.8 4.9 6.9Q5.8 6 7 6H41Q42.2 6 43.1 6.9Q44 7.8 44 9V35Q44 36.2 43.1 37.1Q42.2 38 41 38H27L31.2 42.25V44ZM7 30.2H41V9Q41 9 41 9Q41 9 41 9H7Q7 9 7 9Q7 9 7 9ZM7 30.2V9Q7 9 7 9Q7 9 7 9Q7 9 7 9Q7 9 7 9V30.2Z"/></svg>',
		];

		return $icons[ $prefix ] ?? '';
	}
}
