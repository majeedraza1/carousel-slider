<?php

namespace CarouselSlider\Modules\ProductCarousel;

class MetaBox {

	protected static $instance;
	protected $form;

	/**
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
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
	 * Add Hero slider meta box content
	 *
	 * @param \WP_Post $post
	 * @param string $slide_type
	 */
	public function meta_box_content( $post, $slide_type ) {
		$this->form = new \Carousel_Slider_Form();
		require_once CAROUSEL_SLIDER_MODULES . '/product-carousel/views/admin/product-carousel.php';
	}
}

MetaBox::init();
