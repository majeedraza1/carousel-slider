<?php

namespace CarouselSlider\Modules\ImageCarousel;

class MetaBox {

	protected static $instance;
	private $form;

	/**
	 * @return MetaBox
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * MetaBox constructor.
	 */
	public function __construct() {
		add_action( 'carousel_slider_meta_box', array( $this, 'meta_box_content' ), 10, 2 );
	}

	/**
	 * Add Image carousel from URL meta box content
	 *
	 * @param \WP_Post $post
	 * @param string $slide_type
	 */
	public function meta_box_content( $post, $slide_type ) {
		$this->form = new \Carousel_Slider_Form();
		require_once CAROUSEL_SLIDER_MODULES . '/image-carousel/views/admin/images-media.php';
	}
}

MetaBox::init();
