<?php

namespace CarouselSlider\Modules\PostCarousel;

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
		add_action( 'carousel_slider_view', array( $this, 'post_carousel_view' ), 10, 3 );
	}

	/**
	 * Hero carousel view
	 *
	 * @param $id
	 * @param string $slide_type
	 * @param array $slide_options
	 *
	 * @return void
	 */
	public function post_carousel_view( $id, $slide_type, $slide_options ) {

		if ( $slide_type == 'post-carousel' ) {

			ob_start();
			require CAROUSEL_SLIDER_MODULES . '/post-carousel/views/public/post-carousel.php';
			$html = ob_get_contents();
			ob_end_clean();

			echo apply_filters( 'carousel_slider_posts_carousel', $html, $id, $slide_options );
		}
	}
}

View::init();
