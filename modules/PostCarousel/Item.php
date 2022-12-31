<?php

namespace CarouselSlider\Modules\PostCarousel;

use WP_Post;
use WP_Term;

/**
 * Item class
 *
 * @package CarouselSlider\Modules\PostCarousel
 */
class Item {
	/**
	 * Post object
	 *
	 * @var WP_Post
	 */
	protected $post;

	/**
	 * Item constructor.
	 *
	 * @param WP_Post $post Post object.
	 */
	public function __construct( $post = null ) {
		if ( $post instanceof WP_Post ) {
			$this->post = $post;
		}
	}

	/**
	 * Get thumbnail html
	 *
	 * @param string $image_size Image size slug.
	 * @param bool   $lazy_load Lazy load images.
	 *
	 * @return string
	 */
	public function get_thumbnail_html( string $image_size, bool $lazy_load ): string {
		$permalink    = get_permalink( $this->post );
		$thumbnail_id = (int) get_post_thumbnail_id( $this->post->ID );
		if ( ! $thumbnail_id ) {
			return '<a href="' . esc_url( $permalink ) . '" class="carousel-slider__post-image"></a>';
		}
		$image_src = wp_get_attachment_image_src( $thumbnail_id, $image_size );
		$url       = is_array( $image_src ) ? $image_src[0] : '';

		$attrs = [
			'class' => 'carousel-slider__post-image',
			'href'  => esc_url( $permalink ),
		];

		if ( ! $lazy_load ) {
			if ( \CarouselSlider\Helper::is_using_swiper() ) {
				$attrs['data-background'] = esc_url( $url );
				$attrs['class']           = 'carousel-slider__post-image swiper-lazy';
			} else {
				$attrs['data-src'] = esc_url( $url );
				$attrs['class']    = 'carousel-slider__post-image owl-lazy';
			}
		} else {
			$attrs['style'] = 'background-image: url(' . esc_url( $url ) . ')';
		}

		return '<a ' . join( ' ', \CarouselSlider\Helper::array_to_attribute( $attrs ) ) . '></a>';
	}

	/**
	 * Get post object
	 *
	 * @return WP_Post
	 */
	public function get_post(): WP_Post {
		return $this->post;
	}

	/**
	 * Get post id
	 *
	 * @return int
	 */
	public function get_id(): int {
		return $this->post->ID;
	}

	/**
	 * Get permalink
	 *
	 * @return false|string
	 */
	public function get_permalink() {
		return get_permalink( $this->get_post() );
	}

	/**
	 * Get post title
	 *
	 * @return string
	 */
	public function get_title(): string {
		return get_the_title( $this->get_post() );
	}

	/**
	 * Get post content
	 *
	 * @return string
	 */
	public function get_content(): string {
		return apply_filters( 'the_content', $this->get_post()->post_content );
	}

	/**
	 * Get summery html
	 *
	 * @param int $excerpt_length Excerpt length.
	 *
	 * @return string
	 */
	public function get_summery( int $excerpt_length = 20 ): string {
		return wp_trim_words(
			apply_filters( 'the_content', $this->get_post()->post_content ),
			apply_filters( 'carousel_slider_post_excerpt_length', $excerpt_length ),
			apply_filters( 'carousel_slider_post_read_more', ' ...', $this->get_post() )
		);
	}

	/**
	 *  Get author posts url
	 *
	 * @return string
	 */
	public function get_author_posts_url(): string {
		return get_author_posts_url( intval( $this->get_post()->post_author ) );
	}

	/**
	 * Get author display name
	 *
	 * @return string
	 */
	public function get_author_display_name(): string {
		return get_the_author_meta( 'display_name', intval( $this->get_post()->post_author ) );
	}

	/**
	 * Get formatted date post modified data
	 *
	 * @param string $format Date format.
	 *
	 * @return string
	 */
	public function get_formatted_modified_date( string $format = '' ): string {
		if ( empty( $format ) ) {
			$format = get_option( 'date_format' );
		}

		return date_i18n( $format, $this->get_post()->post_modified );
	}

	/**
	 * Get categories related to post
	 *
	 * @return array|WP_Term[] Array of terms.
	 */
	public function get_categories(): array {
		return get_the_category( $this->get_post() );
	}

	/**
	 * Check if it has category
	 *
	 * @return bool
	 */
	public function has_category(): bool {
		return count( $this->get_categories() ) > 0;
	}

	/**
	 * Get primary category related to post
	 *
	 * @TODO: Check Yost SEO plugin to get primary category.
	 *
	 * @return null|WP_Term
	 */
	public function get_primary_category() {
		if ( ! $this->has_category() ) {
			return null;
		}
		$categories          = $this->get_categories();
		$primary_category    = $categories[0];
		$primary_category_id = $this->get_primary_category_id();
		if ( ! $primary_category_id ) {
			return $primary_category;
		}
		foreach ( $categories as $category ) {
			if ( $category->term_id === $this->get_primary_category_id() ) {
				$primary_category = $category;
				break;
			}
		}

		return $primary_category;
	}

	/**
	 * Get primary category id
	 *
	 * @return int
	 */
	public function get_primary_category_id(): int {
		$meta = get_post_meta( $this->get_id(), '_yoast_wpseo_primary_category', true );

		return is_numeric( $meta ) ? intval( $meta ) : 0;
	}
}
