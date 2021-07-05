<?php

namespace CarouselSlider\Modules\PostCarousel;

use CarouselSlider\Abstracts\AbstractView;
use CarouselSlider\Helper;
use CarouselSlider\Supports\Validate;
use CarouselSlider\Modules\PostCarousel\Helper as PostCarouselHelper;

defined( 'ABSPATH' ) || exit;

class View extends AbstractView {

	/**
	 * @inheritDoc
	 */
	public function render(): string {
		$slider_id        = $this->slider_id;
		$slider_type      = $this->slider_type;
		$_image_size      = get_post_meta( $slider_id, '_image_size', true );
		$_lazy_load_image = get_post_meta( $slider_id, '_lazy_load_image', true );

		$posts = PostCarouselHelper::get_posts( $slider_id );

		$css_classes = [
			"carousel-slider-outer",
			"carousel-slider-outer-posts",
			"carousel-slider-outer-{$slider_id}"
		];

		$attributes_array = Helper::array_to_attribute( [
			'id'                => 'id-' . $slider_id,
			'class'             => implode( ' ', Helper::get_css_classes( $slider_id ) ),
			'style'             => Helper::array_to_style( Helper::get_css_variable( $slider_id ) ),
			'data-slide-type'   => $slider_type,
			'data-owl-settings' => wp_json_encode( Helper::get_owl_carousel_settings( $slider_id ) ),
		] );

		$html = '<div class="' . join( ' ', $css_classes ) . '">';
		$html .= "<div " . join( " ", $attributes_array ) . ">";

		foreach ( $posts as $post ) {
			setup_postdata( $post );
			$category = get_the_category( $post->ID );

			do_action( 'carousel_slider_post_loop', $post, $category );

			$html .= '<div class="carousel-slider__post">';
			$html .= '<div class="carousel-slider__post-content">';
			$html .= '<div class="carousel-slider__post-header">';
			// Post Thumbnail
			$_permalink = esc_url( get_permalink( $post->ID ) );
			$_thumb_id  = get_post_thumbnail_id( $post->ID );
			$num_words  = apply_filters( 'carousel_slider_post_excerpt_length', 20 );
			$more_text  = apply_filters( 'carousel_slider_post_read_more', ' ...', $post );
			$_content   = apply_filters( 'the_content', $post->post_content );
			$_excerpt   = wp_trim_words( $_content, $num_words, $more_text );

			if ( has_post_thumbnail( $post ) ) {
				$image_src = wp_get_attachment_image_src( $_thumb_id, $_image_size );

				if ( Validate::checked( $_lazy_load_image ) ) {
					$html .= sprintf( '<a href="%s" class="carousel-slider__post-image owl-lazy" data-src="%s"></a>', $_permalink, $image_src[0] );
				} else {
					$html .= sprintf( '<a href="%s" class="carousel-slider__post-image" style="background-image: url(%s)"></a>', $_permalink, $image_src[0] );
				}
			} else {
				$html .= sprintf( '<a href="%s" class="carousel-slider__post-image"></a>', $_permalink );
			}

			// Post Title
			$html .= sprintf( '<a class="carousel-slider__post-title" href="%s"><h2>%s</h2></a>', $_permalink, $post->post_title );
			$html .= '</div>'; // End Post Header
			$html .= '<div class="carousel-slider__post-excerpt">' . $_excerpt . '</div>';

			// Footer
			$html .= '<footer class="carousel-slider__post-meta">';
			// $html .= '<div class="carousel-slider__post-excerpt-overlay"></div>';
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
		}
		wp_reset_postdata();

		$html .= '</div>';
		$html .= '</div>';

		return apply_filters( 'carousel_slider_posts_carousel', $html, $slider_id, $posts );
	}
}
