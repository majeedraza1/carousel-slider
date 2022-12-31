<?php

namespace CarouselSlider\Abstracts;

use CarouselSlider\Helper;
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
			'slidesPerView'      => 1,
		];

		if ( ! $setting->is_slider() ) {
			$this->settings['breakpoints'] = $this->get_breakpoints();
		}

		if ( $setting->is_auto_width() ) {
			if ( isset( $this->settings['breakpoints'] ) ) {
				unset( $this->settings['breakpoints'] );
			}
			$this->settings['slidesPerView'] = 'auto';
		}

		if ( 'vertical' === $setting->get_slider_direction() || $setting->is_slider() ) {
			if ( isset( $this->settings['breakpoints'] ) ) {
				unset( $this->settings['breakpoints'] );
			}
		}

		if ( $show_navigation ) {
			$this->settings['navigation'] = [
				'nextEl' => '.swiper-button-next',
				'prevEl' => '.swiper-button-prev',
			];
		}

		if ( $show_pagination ) {
			$this->settings['pagination'] = [
				'el'        => '.swiper-pagination',
				'type'      => $setting->get_pagination_type(),
				'clickable' => true,
			];
		}

		if ( Validate::checked( $setting->get_option( 'scrollbar' ) ) ) {
			$this->settings['scrollbar'] = [
				'el' => '.swiper-scrollbar',
			];
		}

		$lazy = $setting->get_prop( 'lazy_load' );
		if ( Validate::checked( $lazy ) ) {
			$this->settings['lazy']          = [
				'loadPrevNext' => true,
			];
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
		$slider_breakpoint = [];

		foreach ( $this->slider_setting->get_slides_per_view() as $prefix => $item ) {
			$slider_breakpoint[ Helper::get_breakpoint_width( $prefix ) ] = [ 'slidesPerView' => $item ];
		}

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
