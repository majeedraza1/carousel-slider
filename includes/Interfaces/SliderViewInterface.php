<?php

namespace CarouselSlider\Interfaces;

defined( 'ABSPATH' ) || exit;

interface SliderViewInterface {
	/**
	 * Set slider id
	 *
	 * @param int $slider_id The slider id.
	 */
	public function set_slider_id( int $slider_id );

	/**
	 * Set slider type
	 *
	 * @param string $slider_type The slider type.
	 */
	public function set_slider_type( string $slider_type );

	/**
	 * Render element.
	 * Generates the final HTML on the frontend.
	 *
	 * @return string
	 */
	public function render(): string;
}
