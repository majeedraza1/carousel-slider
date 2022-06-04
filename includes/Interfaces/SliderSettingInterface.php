<?php

namespace CarouselSlider\Interfaces;

/**
 * SliderSettingInterface class
 */
interface SliderSettingInterface {
	/**
	 * Get slider Id
	 *
	 * @return int
	 */
	public function get_slider_id(): int;

	/**
	 * Get slider type
	 *
	 * @return string
	 */
	public function get_slider_type(): string;

	/**
	 * Get global option for key
	 *
	 * @param string $key option key.
	 * @param mixed  $default default value to return if data key does not exist.
	 *
	 * @return mixed The key's value, or the default value
	 */
	public function get_global_option( string $key, $default = '' );

	/**
	 * Get option for key
	 * If there is no option for key, return from global option.
	 *
	 * @param string $key option key.
	 * @param mixed  $default default value to return if data key does not exist.
	 *
	 * @return mixed The key's value, or the default value
	 */
	public function get_option( string $key, $default = '' );
}
