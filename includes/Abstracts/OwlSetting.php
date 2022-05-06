<?php

namespace CarouselSlider\Abstracts;

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
	 * @param SliderSetting $slider_setting slider setting class.
	 */
	public function __construct( SliderSetting $slider_setting ) {
		$this->slider_setting = $slider_setting;
		$this->read( $slider_setting );
	}

	/**
	 * Read settings
	 *
	 * @param SliderSetting $setting slider setting class.
	 *
	 * @return void
	 */
	public function read( SliderSetting $setting ) {
		$this->settings = [
			'nav'                => $setting->get_prop( 'nav_visibility' ) !== 'never',
			'dots'               => $setting->get_prop( 'pagination_visibility' ) !== 'never',
			'slideBy'            => $setting->get_prop( 'nav_steps' ),
			'stagePadding'       => $setting->get_prop( 'stage_padding' ),
			'margin'             => $setting->get_prop( 'space_between' ),
			'loop'               => $setting->get_prop( 'loop' ),
			'lazyLoad'           => $setting->get_prop( 'lazy_load' ),
			'autoWidth'          => $setting->get_prop( 'auto_width' ),
			'autoplay'           => $setting->get_prop( 'autoplay' ),
			'autoplayTimeout'    => $setting->get_prop( 'autoplay_delay' ),
			'autoplaySpeed'      => $setting->get_prop( 'autoplay_speed' ),
			'autoplayHoverPause' => $setting->get_prop( 'autoplay_hover_pause' ),
			'responsive'         => [
				300  => [ 'items' => $setting->get_prop( 'items_on_mobile' ) ],
				600  => [ 'items' => $setting->get_prop( 'items_on_small_tablet' ) ],
				768  => [ 'items' => $setting->get_prop( 'items_on_tablet' ) ],
				1024 => [ 'items' => $setting->get_prop( 'items_on_desktop' ) ],
				1200 => [ 'items' => $setting->get_prop( 'items_on_widescreen' ) ],
				1921 => [ 'items' => $setting->get_prop( 'items_on_fullhd' ) ],
			],
		];
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
