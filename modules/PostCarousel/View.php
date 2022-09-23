<?php

namespace CarouselSlider\Modules\PostCarousel;

use CarouselSlider\Abstracts\AbstractView;
use CarouselSlider\Modules\PostCarousel\Helper as PostCarouselHelper;
use CarouselSlider\TemplateParserBase;

defined( 'ABSPATH' ) || exit;

/**
 * View class
 *
 * @package Modules/PostCarousel
 */
class View extends AbstractView {

	/**
	 * Render html view
	 *
	 * @inheritDoc
	 */
	public function render(): string {
		$posts = PostCarouselHelper::get_posts( $this->get_slider_id() );

		$content_html = $this->start_wrapper_html();

		$template = new TemplateParserBase( $this->get_slider_setting() );
		$template->set_template( 'loop/post-carousel.php' );

		foreach ( $posts as $post ) {
			setup_postdata( $post );
			$category = get_the_category( $post->ID );

			$template->set_object( new Item( $post ) );

			do_action( 'carousel_slider_post_loop', $post, $category );

			$content_html .= $this->start_item_wrapper_html();
			$content_html .= apply_filters( 'carousel_slider/loop/post-carousel', $template->render(), $post, $this->get_slider_setting() );
			$content_html .= $this->end_item_wrapper_html();
		}
		wp_reset_postdata();

		$content_html .= $this->end_wrapper_html();

		return apply_filters( 'carousel_slider_posts_carousel', $content_html, $this->get_slider_id(), $posts );
	}
}
