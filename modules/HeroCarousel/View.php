<?php

namespace CarouselSlider\Modules\HeroCarousel;

use CarouselSlider\Abstracts\AbstractView;
use CarouselSlider\Abstracts\SliderSetting;

defined( 'ABSPATH' ) || exit;

/**
 * View class
 *
 * @package Modules/HeroCarousel
 */
class View extends AbstractView {

	/**
	 * Get slider setting
	 *
	 * @return Setting
	 */
	public function get_slider_setting(): Setting {
		if ( ! $this->slider_setting instanceof SliderSetting ) {
			$this->slider_setting = new Setting( $this->get_slider_id() );
		}

		return $this->slider_setting;
	}

	/**
	 * Render html content
	 *
	 * @inheritDoc
	 */
	public function render(): string {
		$slider_id         = $this->get_slider_id();
		$items             = get_post_meta( $slider_id, '_content_slider', true );
		$items             = is_array( $items ) ? array_values( $items ) : [];
		$settings          = get_post_meta( $slider_id, '_content_slider_settings', true );
		$content_animation = ! empty( $settings['content_animation'] ) ? esc_attr( $settings['content_animation'] ) : '';

		$html = $this->start_wrapper_html( [ 'data-animation' => $content_animation ] );
		foreach ( $items as $slide_id => $slide ) {
			$item = new Item( $slide );
			$item->set_prop( 'id', $slide_id + 1 );
			$item->set_setting( $this->get_slider_setting() );

			$html .= apply_filters( 'carousel_slider/loop/hero-banner-slider', $item->get_view(), $item, $this->get_slider_setting() ) . PHP_EOL;
		}

		$html .= $this->end_wrapper_html();

		return apply_filters( 'carousel_slider_hero_banner_carousel', $html, $slider_id );
	}
}
