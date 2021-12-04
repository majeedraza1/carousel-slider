<?php

namespace CarouselSlider\Modules\PostCarousel;

use CarouselSlider\Abstracts\AbstractView;
use CarouselSlider\Supports\Validate;
use CarouselSlider\Modules\PostCarousel\Helper as PostCarouselHelper;
use WP_Post;
use WP_Term;

defined( 'ABSPATH' ) || exit;

class View extends AbstractView {

	/**
	 * @inheritDoc
	 */
	public function render(): string {
		$_image_size      = get_post_meta( $this->get_slider_id(), '_image_size', true );
		$_lazy_load_image = Validate::checked( get_post_meta( $this->get_slider_id(), '_lazy_load_image', true ) );

		$posts = PostCarouselHelper::get_posts( $this->get_slider_id() );

		$html = $this->start_wrapper_html();

		foreach ( $posts as $post ) {
			setup_postdata( $post );
			$category = get_the_category( $post->ID );

			do_action( 'carousel_slider_post_loop', $post, $category );

			$html .= '<div class="carousel-slider__post">';
			$html .= '<div class="carousel-slider__post-content">';
			$html .= '<div class="carousel-slider__post-header">';

			$_permalink = esc_url( get_permalink( $post->ID ) );

			// Post Thumbnail
			$html .= $this->get_thumbnail_html( $post, $_image_size, $_lazy_load_image, $_permalink );

			// Post Title
			$html .= sprintf( '<a class="carousel-slider__post-title" href="%s"><h2>%s</h2></a>', $_permalink, $post->post_title );
			$html .= '</div>'; // End Post Header

			// Post summery
			$html .= $this->get_summery_html( $post );

			// Footer
			$html .= '<footer class="carousel-slider__post-meta">';
			// $html .= '<div class="carousel-slider__post-excerpt-overlay"></div>';
			$html .= '<div class="carousel-slider__post-publication-meta">';
			$html .= '<div class="carousel-slider__post-details-info">';

			// Post author
			$html .= $this->get_author_html( $post );

			// Post date
			$html .= $this->get_date_html( $post );
			$html .= '</div>';
			$html .= '</div>';

			// Post category
			$html .= $this->get_category_html( count( $category ) ? $category[0] : [] );

			$html .= '</footer>';
			$html .= '</div>';
			$html .= '</div>' . PHP_EOL;
		}
		wp_reset_postdata();

		$html .= $this->end_wrapper_html();

		return apply_filters( 'carousel_slider_posts_carousel', $html, $this->get_slider_id(), $posts );
	}

	/**
	 * @param WP_Post $post
	 * @param string $image_size
	 * @param bool $lazy_load
	 * @param string $permalink
	 *
	 * @return string
	 */
	protected function get_thumbnail_html( WP_Post $post, string $image_size, bool $lazy_load, string $permalink ): string {
		$thumbnail_id = get_post_thumbnail_id( $post->ID );
		if ( ! $thumbnail_id ) {
			return '<a href="' . esc_url( $permalink ) . '" class="carousel-slider__post-image"></a>';
		}
		$image_src = wp_get_attachment_image_src( $thumbnail_id, $image_size );
		$url       = is_array( $image_src ) ? $image_src[0] : '';

		if ( $lazy_load ) {
			return '<a class="carousel-slider__post-image owl-lazy" href="' . esc_url( $permalink ) . '" data-src="' . esc_url( $url ) . '"></a>';
		}

		return '<a href="' . esc_url( $permalink ) . '" class="carousel-slider__post-image" style="background-image: url(' . esc_url( $url ) . ')"></a>';
	}

	/**
	 * @param WP_Post $post
	 *
	 * @return string
	 */
	protected function get_date_html( WP_Post $post ): string {
		$created = strtotime( $post->post_date );
		$updated = strtotime( $post->post_modified );

		if ( $created !== $updated ) {
			return sprintf( '<time class="carousel-slider__post-publication-date" datetime="%s">%s</time>',
				date_i18n( 'c', $updated ),
				date_i18n( get_option( 'date_format' ), $updated )
			);
		}

		return sprintf( '<time class="carousel-slider__post-publication-date" datetime="%s">%s</time>',
			date_i18n( 'c', $created ),
			date_i18n( get_option( 'date_format' ), $created )
		);
	}

	/**
	 * @param WP_Term|mixed $category
	 *
	 * @return string
	 */
	protected function get_category_html( $category ): string {
		if ( ! $category instanceof WP_Term ) {
			return '';
		}
		$html = '<div class="carousel-slider__post-category">';
		$html .= sprintf( '<a class="carousel-slider__post-category-link" href="%s">%s</a>',
			esc_url( get_category_link( $category->term_id ) ),
			esc_html( $category->name )
		);
		$html .= '</div>';

		return $html;
	}

	/**
	 * @param WP_Post $post
	 *
	 * @return string
	 */
	protected function get_author_html( WP_Post $post ): string {
		$author_url  = esc_url( get_author_posts_url( intval( $post->post_author ) ) );
		$author_name = esc_html( get_the_author_meta( 'display_name', intval( $post->post_author ) ) );

		$html = '<div class="carousel-slider__post-author">';
		$html .= '<a class="carousel-slider__post-author-link" href="' . $author_url . '">' . $author_name . '</a>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * @param WP_Post $post
	 *
	 * @return string
	 */
	protected function get_summery_html( WP_Post $post ): string {
		$num_words = apply_filters( 'carousel_slider_post_excerpt_length', 20 );
		$more_text = apply_filters( 'carousel_slider_post_read_more', ' ...', $post );
		$content   = apply_filters( 'the_content', $post->post_content );
		$excerpt   = wp_trim_words( $content, $num_words, $more_text );

		return '<div class="carousel-slider__post-excerpt">' . $excerpt . '</div>';
	}
}
