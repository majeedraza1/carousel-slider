<?php

namespace CarouselSlider\Abstracts;

use CarouselSlider\Helper;
use CarouselSlider\Interfaces\SliderSettingInterface;
use CarouselSlider\Interfaces\SliderViewInterface;
use CarouselSlider\Supports\Validate;

defined( 'ABSPATH' ) || exit;

/**
 * AbstractView class
 * The base view class for any slider type
 *
 * @package CarouselSlider/Abstracts
 */
abstract class AbstractView implements SliderViewInterface {
	/**
	 * Slider id
	 *
	 * @var int
	 */
	protected $slider_id = 0;

	/**
	 * Slider type
	 *
	 * @var string
	 */
	protected $slider_type = '';

	/**
	 * The slider setting class
	 *
	 * @var SliderSetting
	 */
	protected $slider_setting;

	/**
	 * Render element.
	 * Generates the final HTML on the frontend.
	 *
	 * @return string
	 */
	abstract public function render(): string;

	/**
	 * Get slider id
	 *
	 * @return int
	 */
	public function get_slider_id(): int {
		return $this->slider_id;
	}

	/**
	 * Set slider id
	 *
	 * @param  int $slider_id  The slider id.
	 */
	public function set_slider_id( int $slider_id ) {
		$this->slider_id = $slider_id;
	}

	/**
	 * Get slider type
	 *
	 * @return string
	 */
	public function get_slider_type(): string {
		return $this->slider_type;
	}

	/**
	 * Set slider type
	 *
	 * @param  string $slider_type  The slider type.
	 */
	public function set_slider_type( string $slider_type ) {
		$this->slider_type = $slider_type;
	}

	/**
	 * Get slider setting
	 *
	 * @return SliderSetting|SliderSettingInterface|Data
	 */
	public function get_slider_setting() {
		if ( ! $this->slider_setting instanceof SliderSettingInterface ) {
			$this->slider_setting = new SliderSetting( $this->get_slider_id() );
		}

		return $this->slider_setting;
	}

	/**
	 * Set slider setting class
	 *
	 * @param  SliderSettingInterface $slider_setting  The SliderSetting class.
	 */
	public function set_slider_setting( SliderSettingInterface $slider_setting ) {
		$this->slider_setting = $slider_setting;
	}

	/**
	 * Get slider javaScript package name
	 *
	 * @return string
	 */
	protected function get_slider_js_package(): string {
		$package = Helper::get_setting( 'slider_js_package' );

		return 'owl.carousel' === $package ? 'owl.carousel' : 'swiper';
	}

	/**
	 * Check if we are using swiper
	 *
	 * @return bool
	 */
	protected function is_using_swiper(): bool {
		return Helper::is_using_swiper();
	}

	/**
	 * Get slider start wrapper html
	 *
	 * @param  array $args  The additional arguments.
	 *
	 * @return string
	 */
	public function start_wrapper_html( array $args = [] ): string {
		$setting     = $this->get_slider_setting();
		$css_classes = [
			'carousel-slider-outer',
			'carousel-slider-outer-' . $this->get_slider_type(),
			'carousel-slider-outer-' . $this->get_slider_id(),
		];
		if ( $this->is_using_swiper() ) {
			$css_classes[] = 'swiper';
			$css_classes[] = sprintf( 'navigation-visibility-%s', $setting->get_nav_visibility() );
			$css_classes[] = sprintf( 'navigation-position-%s', $setting->get_option( 'nav_position' ) );
			$css_classes[] = sprintf( 'pagination-visibility-%s', $setting->get_pagination_visibility() );
			$css_classes[] = sprintf( 'pagination-shape-%s', $setting->get_option( 'pagination_shape' ) );
			$css_classes[] = sprintf( 'pagination-align-%s', $setting->get_option( 'pagination_alignment' ) );
		}

		$outer_attributes_array = [
			'class' => implode( ' ', $css_classes ),
			'style' => Helper::array_to_style( $this->get_css_variable() ),
		];

		$attributes_array = $this->get_slider_attributes( $args );

		$html  = '<div ' . join( ' ', Helper::array_to_attribute( $outer_attributes_array ) ) . '>' . PHP_EOL;
		$html .= '<div ' . join( ' ', $attributes_array ) . '>' . PHP_EOL;

		return $html;
	}

	/**
	 * Get slider end wrapper html
	 *
	 * @return string
	 */
	public function end_wrapper_html(): string {
		$html = '</div><!-- .carousel-slider-' . $this->get_slider_id() . ' -->' . PHP_EOL;
		if ( $this->is_using_swiper() ) {
			// If we need pagination.
			if ( $this->get_slider_setting()->get_pagination_visibility() !== 'never' ) {
				$html .= '<div class="swiper-pagination"></div>';
			}

			// If we need navigation.
			if ( $this->get_slider_setting()->get_nav_visibility() !== 'never' ) {
				$html .= '<div class="swiper-button-prev"></div><div class="swiper-button-next"></div>';
			}

			// If we need scrollbar.
			if ( Validate::checked( $this->get_slider_setting()->get_option( 'scrollbar' ) ) ) {
				$html .= '<div class="swiper-scrollbar"></div>';
			}
		}
		$html .= '</div><!-- .carousel-slider-outer-' . $this->get_slider_id() . ' -->' . PHP_EOL;

		return $html;
	}

	/**
	 * Get item wrapper start html
	 *
	 * @return string
	 */
	public function start_item_wrapper_html(): string {
		if ( $this->is_using_swiper() ) {
			return '<div class="swiper-slide">';
		}

		return '';
	}

	/**
	 * Get item wrapper end html
	 *
	 * @return string
	 */
	public function end_item_wrapper_html(): string {
		if ( $this->is_using_swiper() ) {
			return '</div>';
		}

		return '';
	}

	/**
	 * Get slider default attributes
	 *
	 * @param  array $args  The additional arguments.
	 *
	 * @return array
	 */
	protected function get_slider_attributes( array $args = [] ): array {
		$default_attributes = [
			'id'              => sprintf( "'id-%s", $this->get_slider_id() ),
			'class'           => implode( ' ', $this->get_css_classes() ),
			'data-slide-type' => $this->get_slider_type(),
		];

		if ( $this->is_using_swiper() ) {
			$swiper_settings = new SwiperSetting( $this->get_slider_setting() );

			$default_attributes['data-swiper'] = wp_json_encode( $swiper_settings->all() );
		} else {
			$owl_settings = new OwlSetting( $this->get_slider_setting() );

			$default_attributes['data-owl-settings'] = wp_json_encode( $owl_settings->all() );
		}

		$attributes = array_merge( $default_attributes, $this->extra_slider_attributes(), $args );

		return Helper::array_to_attribute( $attributes );
	}

	/**
	 * Get extra slider attributes
	 *
	 * @return array
	 */
	protected function extra_slider_attributes(): array {
		return [];
	}

	/**
	 * Get slider CSS style variables
	 *
	 * @return array
	 */
	public function get_css_variable(): array {
		$setting = $this->get_slider_setting();
		$css_var = [
			'--carousel-slider-nav-color'        => $setting->get_prop( 'nav_color' ),
			'--carousel-slider-active-nav-color' => $setting->get_prop( 'nav_active_color' ),
			'--carousel-slider-arrow-size'       => $setting->get_prop( 'nav_size' ) . 'px',
			'--carousel-slider-bullet-size'      => $setting->get_prop( 'pagination_size' ) . 'px',
		];

		if ( $this->is_using_swiper() ) {
			$css_var['--swiper-theme-color']            = $setting->get_prop( 'nav_color' );
			$css_var['--swiper-navigation-size']        = $setting->get_prop( 'nav_size' ) . 'px';
			$css_var['--swiper-pagination-bullet-size'] = $setting->get_prop( 'pagination_size' ) . 'px';
		}

		return apply_filters( 'carousel_slider/css_var', $css_var, $setting );
	}

	/**
	 * Get slider css classes
	 *
	 * @return string[]
	 */
	public function get_css_classes(): array {
		$setting = $this->get_slider_setting();

		$css_classes = [
			'carousel-slider',
			'carousel-slider-' . $this->get_slider_id(),
			'arrows-visibility-' . $setting->get_nav_visibility(),
			'dots-visibility-' . $setting->get_pagination_visibility(),
			'arrows-' . $setting->get_option( 'nav_position' ),
			'dots-' . $setting->get_option( 'pagination_alignment' ),
			'dots-' . $setting->get_option( 'pagination_shape' ),
		];

		if ( $this->is_using_swiper() ) {
			$css_classes[] = 'swiper-wrapper';
		} else {
			$css_classes[] = 'owl-carousel';
		}

		return apply_filters( 'carousel_slider/css_classes', $css_classes, $setting );
	}
}
