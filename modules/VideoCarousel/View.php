<?php

namespace CarouselSlider\Modules\VideoCarousel;

use CarouselSlider\Abstracts\AbstractView;
use CarouselSlider\Modules\VideoCarousel\Helper as VideoCarouselHelper;
use CarouselSlider\TemplateParserBase;

defined( 'ABSPATH' ) || exit;

/**
 * View class
 *
 * @package Modules/VideoCarousel
 */
class View extends AbstractView {
	/**
	 * Render html view
	 *
	 * @inheritDoc
	 */
	public function render(): string {
		$setting   = $this->get_slider_setting();
		$slider_id = $this->get_slider_id();
		$urls      = get_post_meta( $slider_id, '_video_url', true );
		if ( is_string( $urls ) ) {
			$urls = array_filter( explode( ',', $urls ) );
		}
		$urls = VideoCarouselHelper::get_video_url( $urls );

		$template = new TemplateParserBase( $setting );
		$template->set_template( 'loop/video-carousel.php' );

		$html = $this->start_wrapper_html();
		foreach ( $urls as $url ) {
			$item = new Item( $url );
			$template->set_object( $item );

			$html .= $this->start_item_wrapper_html();
			$html .= apply_filters(
				'carousel_slider/loop/video-carousel',
				$template->render(),
				$item,
				$this->get_slider_setting()
			);
			$html .= $this->end_item_wrapper_html();
		}

		$html .= $this->end_wrapper_html();

		return apply_filters( 'carousel_slider_videos_carousel', $html, $slider_id );
	}
}
