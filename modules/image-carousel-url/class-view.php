<?php

namespace CarouselSlider\Modules\ImageCarouselURL;

class View {

	protected static $instance = null;

	/**
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @return View
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action( 'carousel_slider_view', array( $this, 'carousel_view' ), 10, 3 );
	}

	/**
	 * Image URL carousel view
	 *
	 * @param $id
	 * @param string $slide_type
	 * @param array $slide_options
	 *
	 * @return void
	 */
	public function carousel_view( $id, $slide_type, $slide_options ) {

		if ( $slide_type == 'image-carousel-url' ) {
			ob_start();
			require CAROUSEL_SLIDER_MODULES . '/image-carousel-url/views/public/images-carousel-url.php';
			$html = ob_get_contents();
			ob_end_clean();

			echo apply_filters( 'carousel_slider_link_images_carousel', $html, $id );
		}
	}
}

View::init();
