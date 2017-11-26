<?php

namespace CarouselSlider\Modules\VideoCarousel;

class VideoCarousel {

	protected static $instance;

	/**
	 * @return VideoCarousel
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_filter( 'carousel_slider_slide_type', array( $this, 'add_video_slide_type' ), 40 );
		add_action( 'carousel_slider_save_meta_box', array( $this, 'save_meta_box' ) );
	}

	/**
	 * Add video carousel as slide type
	 *
	 * @param array $slide_type
	 *
	 * @return mixed
	 */
	public function add_video_slide_type( $slide_type ) {
		$slide_type['video-carousel'] = __( 'Video Carousel', 'carousel-slider' );

		return $slide_type;
	}

	/**
	 * Save video carousel custom meta box
	 *
	 * @param int $post_id Post ID.
	 */
	public function save_meta_box( $post_id ) {

	}
}

VideoCarousel::init();
