<?php

namespace CarouselSlider\Modules\HeroCarousel;

use CarouselSlider\Abstracts\AbstractView;

defined( 'ABSPATH' ) || exit;

class View extends AbstractView {

	/**
	 * @inheritDoc
	 */
	public function render(): string {
		$slider_id         = $this->get_slider_id();
		$items             = get_post_meta( $slider_id, '_content_slider', true );
		$lazy_load_image   = get_post_meta( $slider_id, '_lazy_load_image', true );
		$be_lazy           = in_array( $lazy_load_image, array( 'on', 'off' ) ) ? $lazy_load_image : 'on';
		$settings          = get_post_meta( $slider_id, '_content_slider_settings', true );
		$content_animation = ! empty( $settings['content_animation'] ) ? esc_attr( $settings['content_animation'] ) : '';

		$html = $this->start_wrapper_html( [ 'data-animation' => $content_animation ] );
		foreach ( $items as $slide_id => $slide ) {
			$item  = new Item(
				$slide,
				array_merge(
					$settings,
					[
						'item_id'         => $slide_id,
						'slider_id'       => $slider_id,
						'lazy_load_image' => $be_lazy,
					]
				)
			);
			$html .= $item->get_view() . PHP_EOL;
		}

		$html .= $this->end_wrapper_html();

		return apply_filters( 'carousel_slider_hero_banner_carousel', $html, $slider_id );
	}
}
