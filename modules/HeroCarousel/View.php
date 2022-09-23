<?php

namespace CarouselSlider\Modules\HeroCarousel;

use CarouselSlider\Abstracts\AbstractView;
use CarouselSlider\Abstracts\SliderSetting;
use CarouselSlider\TemplateParserBase;

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

		$template = new TemplateParserBase( $this->get_slider_setting() );
		$template->set_template( 'loop/hero-banner-slider.php' );

		$html = $this->start_wrapper_html( [ 'data-animation' => $content_animation ] );
		foreach ( $items as $slide_id => $slide ) {
			$item = new Item( $slide );
			$item->set_prop( 'id', $slide_id + 1 );
			$item->set_setting( $this->get_slider_setting() );

			$template->set_object( $item );

			$item_html  = $this->start_item_wrapper_html();
			$item_html .= $template->render();
			$item_html .= $this->end_item_wrapper_html();

			$html .= apply_filters( 'carousel_slider/loop/hero-banner-slider', $item_html, $item, $this->get_slider_setting() ) . PHP_EOL;
		}

		$html .= $this->end_wrapper_html();

		return apply_filters( 'carousel_slider_hero_banner_carousel', $html, $slider_id );
	}
}
