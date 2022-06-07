<?php

namespace CarouselSlider\Modules\ImageCarousel;

use CarouselSlider\Supports\Validate;
use WP_Post;

/**
 * Item class
 */
class Item {

	/**
	 * The WP_Post object.
	 *
	 * @var WP_Post
	 */
	protected $post;

	/**
	 * Class constructor
	 *
	 * @param WP_Post $post The WP_Post object.
	 */
	public function __construct( WP_Post $post ) {
		$this->post = $post;
	}

	/**
	 * Get post id
	 *
	 * @return int
	 */
	public function get_id(): int {
		return $this->get_post()->ID;
	}

	/**
	 * Get title
	 *
	 * @return string
	 */
	public function get_title(): string {
		return $this->get_post()->post_title;
	}

	/**
	 * Get title
	 *
	 * @return string
	 */
	public function get_caption(): string {
		return $this->get_post()->post_excerpt;
	}

	/**
	 * Get image alt text
	 *
	 * @return string
	 */
	public function get_alt_text(): string {
		$image_alt = get_post_meta( $this->get_post()->ID, '_wp_attachment_image_alt', true );

		return trim( wp_strip_all_tags( $image_alt ) );
	}

	/**
	 * Get link url
	 *
	 * @return string
	 */
	public function get_link_url(): string {
		$image_link_url = get_post_meta( $this->post->ID, '_carousel_slider_link_url', true );

		return Validate::url( $image_link_url ) ? $image_link_url : '';
	}

	/**
	 * Get image
	 *
	 * @param string|int[] $size Registered image size name, or an array of width and height.
	 *
	 * @return string
	 */
	public function get_image( $size = 'medium_large' ): string {
		return wp_get_attachment_image( $this->get_id(), $size, false, [ 'alt' => $this->get_alt_text() ] );
	}

	/**
	 * Get image src
	 *
	 * @param string|int[] $size Registered image size name, or an array of width and height.
	 *
	 * @return array
	 */
	public function get_image_src( $size = 'medium_large' ): array {
		return wp_get_attachment_image_src( $this->get_id(), $size );
	}

	/**
	 * Get post object
	 *
	 * @return WP_Post
	 */
	public function get_post(): WP_Post {
		return $this->post;
	}
}
