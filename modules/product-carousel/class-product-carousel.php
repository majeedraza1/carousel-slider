<?php

namespace CarouselSlider\Modules\ProductCarousel;

class ProductCarousel {


	protected static $instance;

	/**
	 * @return ProductCarousel
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_filter( 'carousel_slider_slide_type', array( $this, 'add_post_slide_type' ), 60 );
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
		$slide_type['product-carousel'] = __( 'WooCommerce Product Carousel', 'carousel-slider' );

		return $slide_type;
	}

	/**
	 * Save product carousel custom meta box
	 *
	 * @param int $post_id Post ID.
	 */
	public function save_meta_box( $post_id ) {

	}
}

ProductCarousel::init();
