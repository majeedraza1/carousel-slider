<?php

namespace CarouselSlider\Modules\PostCarousel;

class PostCarousel {

	protected static $instance;

	/**
	 * @return PostCarousel
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_filter( 'carousel_slider_slide_type', array( $this, 'add_post_slide_type' ), 50 );
		add_action( 'carousel_slider_save_meta_box', array( $this, 'save_meta_box' ) );
	}

	/**
	 * Add post carousel as slide type
	 *
	 * @param array $slide_type
	 *
	 * @return mixed
	 */
	public function add_post_slide_type( $slide_type ) {
		$slide_type['post-carousel'] = __( 'Post Carousel', 'carousel-slider' );

		return $slide_type;
	}

	/**
	 * Save post carousel custom meta box
	 *
	 * @param int $post_id Post ID.
	 */
	public function save_meta_box( $post_id ) {
		if ( ! isset( $_POST['carousel_slider']['_post_categories'] ) ) {
			update_post_meta( $post_id, '_post_categories', '' );
		}

		if ( ! isset( $_POST['carousel_slider']['_post_tags'] ) ) {
			update_post_meta( $post_id, '_post_tags', '' );
		}

		if ( ! isset( $_POST['carousel_slider']['_post_in'] ) ) {
			update_post_meta( $post_id, '_post_in', '' );
		}
	}
}

PostCarousel::init();
