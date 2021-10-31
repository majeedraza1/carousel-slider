<?php

namespace CarouselSlider\Modules\HeroCarousel;

use CarouselSlider\Abstracts\AbstractView;
use CarouselSlider\Helper;

defined( 'ABSPATH' ) || exit;

class View extends AbstractView {

	/**
	 * @inheritDoc
	 */
	public function render(): string {
		$slider_id         = $this->get_slider_id();
		$slider_type       = $this->get_slider_type();
		$items             = get_post_meta( $slider_id, '_content_slider', true );
		$lazy_load_image   = get_post_meta( $slider_id, '_lazy_load_image', true );
		$be_lazy           = in_array( $lazy_load_image, array( 'on', 'off' ) ) ? $lazy_load_image : 'on';
		$settings          = get_post_meta( $slider_id, '_content_slider_settings', true );
		$content_animation = ! empty( $settings['content_animation'] ) ? esc_attr( $settings['content_animation'] ) : '';

		$css_classes = [
			"carousel-slider-outer",
			"carousel-slider-outer-contents",
			"carousel-slider-outer-$slider_id"
		];

		$attributes_array = Helper::get_slider_attributes( $slider_id, $slider_type, [
			'data-animation' => $content_animation
		] );

		$html = '<div class="' . join( ' ', $css_classes ) . '">' . PHP_EOL;
		$html .= "<div " . join( " ", $attributes_array ) . ">" . PHP_EOL;
		foreach ( $items as $slide_id => $slide ) {
			$item = new Item( $slide, array_merge( $settings, [
				'item_id'         => $slide_id,
				'slider_id'       => $slider_id,
				'lazy_load_image' => $be_lazy
			] ) );
			$html .= $item->get_view() . PHP_EOL;
		}

		$html .= '</div><!-- .carousel-slider-' . $slider_id . ' -->' . PHP_EOL;
		$html .= '</div><!-- .carousel-slider-outer-' . $slider_id . ' -->' . PHP_EOL;

		return apply_filters( 'carousel_slider_hero_banner_carousel', $html, $slider_id );
	}
}
