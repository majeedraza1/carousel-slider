<?php

namespace CarouselSlider\Abstracts;

use CarouselSlider\Helper;

/**
 * OwlSetting class
 * The owl carousel javascript option generator.
 *
 * @package CarouselSlider/Abstracts
 */
class OwlSetting {

	/**
	 * Owl settings
	 *
	 * @var array
	 */
	protected $settings = [];

	/**
	 * Get slider settings
	 *
	 * @var SliderSetting|null
	 */
	protected $slider_setting = null;

	/**
	 * Class constructor
	 *
	 * @param  SliderSetting $slider_setting  slider setting class.
	 */
	public function __construct( SliderSetting $slider_setting ) {
		$this->slider_setting = $slider_setting;
		$this->read( $slider_setting );
	}

	/**
	 * Read settings
	 *
	 * @param  SliderSetting $setting  slider setting class.
	 *
	 * @return void
	 */
	public function read( SliderSetting $setting ) {
		$this->settings = [
			'nav'                => $setting->get_nav_visibility() !== 'never',
			'dots'               => $setting->get_pagination_visibility() !== 'never',
			'slideBy'            => $setting->get_nav_steps(),
			'stagePadding'       => $setting->get_stage_padding(),
			'margin'             => $setting->get_space_between(),
			'loop'               => $setting->is_loop(),
			'lazyLoad'           => $setting->lazy_load_image(),
			'autoplay'           => $setting->is_autoplay(),
			'autoplayTimeout'    => $setting->get_autoplay_delay(),
			'autoplaySpeed'      => $setting->get_autoplay_speed(),
			'autoplayHoverPause' => $setting->has_autoplay_hover_pause(),
			'autoWidth'          => $setting->is_auto_width(),
		];

		if ( $this->slider_setting->is_slider() ) {
			$this->settings['items'] = 1;
		} else {
			foreach ( $this->slider_setting->get_slides_per_view() as $prefix => $item ) {
				$this->settings['responsive'][ Helper::get_breakpoint_width( $prefix ) ] = [ 'items' => $item ];
			}
		}
	}

	/**
	 * Get all settings
	 *
	 * @return array
	 */
	public function all(): array {
		return apply_filters( 'carousel_slider/settings/owl_settings', $this->settings, $this->slider_setting );
	}
}
