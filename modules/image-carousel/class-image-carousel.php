<?php

namespace CarouselSlider\Modules\ImageCarousel;

class ImageCarousel {

	protected static $instance;

	/**
	 * @return ImageCarousel
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_filter( 'carousel_slider_slide_type', array( $this, 'add_post_slide_type' ), 20 );
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
		$slide_type['image-carousel'] = __( 'Image Carousel - from Media Library', 'carousel-slider' );

		return $slide_type;
	}

	/**
	 * Save post carousel custom meta box
	 *
	 * @param int $post_id Post ID.
	 */
	public function save_meta_box( $post_id ) {

	}
}

ImageCarousel::init();
