<?php

namespace CarouselSlider\Modules\PostCarousel;

use CarouselSlider\Abstracts\View;
use CarouselSlider\Supports\Utils;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class CarouselView extends View {

	/**
	 * Render element.
	 * Generates the final HTML on the frontend.
	 */
	public function render() {
		$posts = Utils::get_posts( $this->get_slider_id() );

		$this->set_total_slides( count( $posts ) );

		echo $this->slider_wrapper_start();

		foreach ( $posts as $_post ):
			global $post;
			$post = $_post;
			setup_postdata( $post );

			$category   = get_the_category( $post->ID );
			$_permalink = esc_url( get_permalink( $post ) );
			$_thumb_id  = get_post_thumbnail_id( $post );
			$_excerpt   = wp_trim_words( wp_strip_all_tags( $post->post_content ), '20', ' ...' );
			$image_src  = wp_get_attachment_image_src( $_thumb_id, $this->image_size() );

			do_action( 'carousel_slider_post_loop', $post, $category );

			$html = '<div class="carousel-slider__post">';
			$html .= '<div class="carousel-slider__post-content">';
			$html .= '<div class="carousel-slider__post-header">';
			// Post Thumbnail

			if ( has_post_thumbnail( $post ) ) {

				if ( $this->lazy_load_image() ) {

					$html .= sprintf( '<a href="%s" class="carousel-slider__post-image owl-lazy" data-src="%s"></a>',
						$_permalink, $image_src[0] );
				} else {

					$html .= sprintf( '<a href="%s" class="carousel-slider__post-image" style="background-image: url(%s)"></a>',
						$_permalink, $image_src[0] );
				}

			} else {

				$html .= sprintf( '<a href="%s" class="carousel-slider__post-image"></a>', $_permalink );
			}

			// Post Title
			$html .= sprintf( '<a class="carousel-slider__post-title" href="%s"><h1>%s</h1></a>', $_permalink,
				$post->post_title );
			$html .= '</div>'; // End Post Header
			$html .= '<div class="carousel-slider__post-excerpt">' . $_excerpt . '</div>';
			$html .= '<footer class="carousel-slider__post-meta">';
			$html .= '<div class="carousel-slider__post-excerpt-overlay"></div>';
			$html .= '<div class="carousel-slider__post-publication-meta">';
			$html .= '<div class="carousel-slider__post-details-info">';

			// Post author
			$_author_url  = esc_url( get_author_posts_url( intval( $post->post_author ) ) );
			$_author_name = esc_html( get_the_author_meta( 'display_name', intval( $post->post_author ) ) );

			$html .= sprintf( '<div class="carousel-slider__post-author"><a class="carousel-slider__post-author-link" href="%s">%s</a></div>',
				$_author_url,
				$_author_name
			);
			// Post date
			$_created  = strtotime( $post->post_date );
			$_modified = strtotime( $post->post_modified );

			if ( $_created !== $_modified ) {

				$html .= sprintf( '<time class="carousel-slider__post-publication-date" datetime="%s">%s</time>',
					date_i18n( 'c', $_modified ),
					date_i18n( get_option( 'date_format' ), $_modified )
				);

			} else {

				$html .= sprintf( '<time class="carousel-slider__post-publication-date" datetime="%s">%s</time>',
					date_i18n( 'c', $_created ),
					date_i18n( get_option( 'date_format' ), $_created )
				);
			}
			$html .= '</div>';
			$html .= '</div>';

			// Post catagory
			$cat_link  = isset( $category[0]->term_id ) ? esc_url( get_category_link( $category[0]->term_id ) ) : '';
			$cat_title = isset( $category[0]->name ) ? esc_html( $category[0]->name ) : '';
			$html      .= '<div class="carousel-slider__post-category">';
			if ( isset( $cat_link ) ) {
				$html .= sprintf( '<a class="carousel-slider__post-category-link" href="%s">%s</a>',
					$cat_link,
					$cat_title
				);
			}
			$html .= '</div>';
			$html .= '</footer>';
			$html .= '</div>';
			$html .= '</div>';

			echo apply_filters( 'carousel_slider_post', $html, $post, $category );
		endforeach;
		wp_reset_postdata();

		echo $this->slider_wrapper_end();
	}
}
