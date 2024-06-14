<?php

namespace CarouselSlider\Interfaces;

use CarouselSlider\Abstracts\SliderSetting;

defined( 'ABSPATH' ) || exit;

interface SliderViewInterface {
	/**
	 * Set slider id
	 *
	 * @param  int $slider_id  The slider id.
	 */
	public function set_slider_id( int $slider_id );

	/**
	 * Set slider type
	 *
	 * @param  string $slider_type  The slider type.
	 */
	public function set_slider_type( string $slider_type );

	/**
	 * Get slider setting
	 *
	 * @return SliderSettingInterface|SliderSetting
	 */
	public function get_slider_setting();

	/**
	 * Set slider setting class
	 *
	 * @param  SliderSettingInterface $slider_setting  The SliderSetting class.
	 */
	public function set_slider_setting( SliderSettingInterface $slider_setting );

	/**
	 * Render element.
	 * Generates the final HTML on the frontend.
	 *
	 * @return string
	 */
	public function render(): string;
}
