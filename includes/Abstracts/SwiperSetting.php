<?php

namespace CarouselSlider\Abstracts;

use CarouselSlider\Supports\Validate;

/**
 * SwiperSettings class
 */
class SwiperSetting {
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
		// 'slideBy'   => $setting->get_prop( 'nav_steps' ),

		$show_navigation = $setting->get_nav_visibility() !== 'never';
		$show_pagination = $setting->get_pagination_visibility() !== 'never';

		$this->settings = [
			'navigation'         => $show_navigation,
			'pagination'         => $show_pagination,
			'direction'          => $setting->get_slider_direction(),
			'loop'               => $setting->get_prop( 'loop' ),
			'slidesOffsetBefore' => $setting->get_prop( 'stage_padding', 0 ),
			'slidesOffsetAfter'  => $setting->get_prop( 'stage_padding', 0 ),
			'speed'              => $setting->get_prop( 'autoplay_speed', 300 ),
			'spaceBetween'       => $setting->get_prop( 'space_between' ),
			'breakpoints'        => $this->get_breakpoints(),
		];

		if ( $setting->is_auto_width() ) {
			unset( $this->settings['breakpoints'] );
			$this->settings['slidesPerView'] = 'auto';
		}

		if ( $show_navigation ) {
			$this->settings['navigation'] = [
				'nextEl' => '.swiper-button-next',
				'prevEl' => '.swiper-button-prev',
			];
		}

		if ( $show_pagination ) {
			$this->settings['pagination'] = [
				'el'             => '.swiper-pagination',
				'dynamicBullets' => true,
				'type'           => $setting->get_pagination_type(),
			];
		}

		if ( Validate::checked( $setting->get_option( 'scrollbar' ) ) ) {
			$this->settings['scrollbar'] = [
				'el' => '.swiper-scrollbar',
			];
		}

		$lazy = $setting->get_prop( 'lazy_load' );
		if ( Validate::checked( $lazy ) ) {
			$this->settings['lazy']          = true;
			$this->settings['preloadImages'] = false;
		}

		$autoplay = $setting->get_prop( 'autoplay' );
		if ( Validate::checked( $autoplay ) ) {
			$this->settings['autoplay'] = [
				'delay'             => $setting->get_prop( 'autoplay_delay' ),
				'pauseOnMouseEnter' => $setting->get_prop( 'autoplay_hover_pause' ),
			];
		}
	}

	/**
	 * Get breakpoints
	 *
	 * @return array
	 */
	public function get_breakpoints(): array {
		$slider_breakpoint = [
			300  => [ 'slidesPerView' => $this->slider_setting->get_prop( 'items_on_mobile' ) ],
			600  => [ 'slidesPerView' => $this->slider_setting->get_prop( 'items_on_small_tablet' ) ],
			768  => [ 'slidesPerView' => $this->slider_setting->get_prop( 'items_on_tablet' ) ],
			1024 => [ 'slidesPerView' => $this->slider_setting->get_prop( 'items_on_desktop' ) ],
			1200 => [ 'slidesPerView' => $this->slider_setting->get_prop( 'items_on_widescreen' ) ],
			1921 => [ 'slidesPerView' => $this->slider_setting->get_prop( 'items_on_fullhd' ) ],
		];

		return $slider_breakpoint;
	}

	/**
	 * Get all settings
	 *
	 * @return array
	 */
	public function all(): array {
		return apply_filters( 'carousel_slider/settings/swiper_settings', $this->settings, $this->slider_setting );
	}
}
