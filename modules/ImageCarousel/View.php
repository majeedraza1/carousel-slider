<?php

namespace CarouselSlider\Modules\ImageCarousel;

use CarouselSlider\Abstracts\AbstractView;
use CarouselSlider\Abstracts\SliderSetting;
use CarouselSlider\TemplateParserBase;
use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * View class
 *
 * @package Modules/ImageCarousel
 */
class View extends AbstractView {

	/**
	 * Get slider setting
	 *
	 * @return SliderSetting|Setting
	 */
	public function get_slider_setting(): SliderSetting {
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
		$setting  = $this->get_slider_setting();
		$template = new TemplateParserBase( $setting );
		$template->set_template( 'loop/image-carousel.php' );

		$html = $this->start_wrapper_html();
		foreach ( $setting->get_image_ids() as $id ) {
			$_post = get_post( $id );
			if ( ! $_post instanceof WP_Post ) {
				continue;
			}

			$item = new Item( $_post );
			$template->set_object( $item );

			do_action( 'carousel_slider_image_gallery_loop', $_post );

			$html .= $this->start_item_wrapper_html();
			$html .= apply_filters( 'carousel_slider/loop/image-carousel', $template->render(), $item, $setting );
			$html .= $this->end_item_wrapper_html() . PHP_EOL;
		}
		$html .= $this->end_wrapper_html();

		return apply_filters( 'carousel_slider_gallery_images_carousel', $html );
	}
}
