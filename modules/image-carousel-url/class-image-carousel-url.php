<?php

namespace CarouselSlider\Modules\ImageCarouselURL;

class ImageCarouselURL {

	protected static $instance;

	/**
	 * @return ImageCarouselURL
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
		$slide_type['image-carousel-url'] = __( 'Image Carousel - from URL', 'carousel-slider' );

		return $slide_type;
	}

	/**
	 * Save post carousel custom meta box
	 *
	 * @param int $post_id Post ID.
	 */
	public function save_meta_box( $post_id ) {

		if ( ! isset( $_POST['_images_urls'] ) ) {
			return;
		}

		$url      = $_POST['_images_urls']['url'];
		$title    = $_POST['_images_urls']['title'];
		$caption  = $_POST['_images_urls']['caption'];
		$alt      = $_POST['_images_urls']['alt'];
		$link_url = $_POST['_images_urls']['link_url'];

		$urls = array();

		for ( $i = 0; $i < count( $url ); $i ++ ) {
			$urls[] = array(
				'url'      => esc_url_raw( $url[ $i ] ),
				'title'    => sanitize_text_field( $title[ $i ] ),
				'caption'  => sanitize_text_field( $caption[ $i ] ),
				'alt'      => sanitize_text_field( $alt[ $i ] ),
				'link_url' => esc_url_raw( $link_url[ $i ] ),
			);
		}
		update_post_meta( $post_id, '_images_urls', $urls );
	}
}

ImageCarouselURL::init();
