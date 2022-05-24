<?php

namespace CarouselSlider\Abstracts;

use CarouselSlider\Helper;
use CarouselSlider\Interfaces\SliderViewInterface;

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
	 * @param int $slider_id The slider id.
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
	 * @param string $slider_type The slider type.
	 */
	public function set_slider_type( string $slider_type ) {
		$this->slider_type = $slider_type;
	}

	/**
	 * Get slider setting
	 *
	 * @return SliderSetting|Data
	 */
	public function get_slider_setting() {
		if ( ! $this->slider_setting instanceof SliderSetting ) {
			$this->slider_setting = new SliderSetting( $this->get_slider_id() );
		}

		return $this->slider_setting;
	}

	/**
	 * Set slider setting class
	 *
	 * @param SliderSetting $slider_setting The SliderSetting class.
	 */
	public function set_slider_setting( SliderSetting $slider_setting ) {
		$this->slider_setting = $slider_setting;
	}

	/**
	 * Get slider start wrapper html
	 *
	 * @param array $args The additional arguments.
	 *
	 * @return string
	 */
	public function start_wrapper_html( array $args = [] ): string {
		$css_classes = [
			'carousel-slider-outer',
			'carousel-slider-outer-' . $this->get_slider_type(),
			'carousel-slider-outer-' . $this->get_slider_id(),
		];

		$attributes_array = $this->get_slider_attributes( $args );

		$html  = '<div class="' . join( ' ', $css_classes ) . '">' . PHP_EOL;
		$html .= '<div ' . join( ' ', $attributes_array ) . '>' . PHP_EOL;

		return $html;
	}

	/**
	 * Get slider end wrapper html
	 *
	 * @return string
	 */
	public function end_wrapper_html(): string {
		$html  = '</div><!-- .carousel-slider-' . $this->get_slider_id() . ' -->' . PHP_EOL;
		$html .= '</div><!-- .carousel-slider-outer-' . $this->get_slider_id() . ' -->' . PHP_EOL;

		return $html;
	}

	/**
	 * Get slider default attributes
	 *
	 * @param array $args The additional arguments.
	 *
	 * @return array
	 */
	protected function get_slider_attributes( array $args = [] ): array {
		$owl_settings       = ( new OwlSetting( $this->get_slider_setting() ) )->all();
		$default_attributes = [
			'id'                => sprintf( "'id-%s", $this->get_slider_id() ),
			'class'             => implode( ' ', $this->get_css_classes() ),
			'style'             => Helper::array_to_style( $this->get_css_variable() ),
			'data-slide-type'   => $this->get_slider_type(),
			'data-owl-settings' => wp_json_encode( $owl_settings ),
		];
		$attributes         = array_merge( $default_attributes, $this->extra_slider_attributes(), $args );

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
			'owl-carousel',
			'carousel-slider',
			'carousel-slider-' . $this->get_slider_id(),
			'arrows-visibility-' . $setting->get_prop( 'nav_visibility' ),
			'arrows-' . $setting->get_prop( 'nav_position' ),
			'dots-visibility-' . $setting->get_prop( 'pagination_visibility' ),
			'dots-' . $setting->get_prop( 'pagination_position' ),
			'dots-' . $setting->get_prop( 'pagination_shape' ),
		];

		return apply_filters( 'carousel_slider/css_classes', $css_classes, $setting );
	}
}
