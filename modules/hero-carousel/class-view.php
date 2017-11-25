<?php

namespace CarouselSlider\Modules\HeroCarousel;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

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
		add_action( 'carousel_slider_view', array( $this, 'hero_carousel_view' ), 10, 3 );
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
	public function hero_carousel_view( $id, $slide_type, $slide_options ) {
		if ( 'hero-banner-slider' == $slide_type ) {

			ob_start();
			require CAROUSEL_SLIDER_TEMPLATES . '/public/hero-slider.php';
			$html = ob_get_contents();
			ob_end_clean();

			echo apply_filters( 'carousel_slider_content_carousel', $html, $id, $slide_options );
		}
	}
}

View::init();
