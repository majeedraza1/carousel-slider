<?php

namespace CarouselSlider\Modules\VideoCarousel;

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
		add_action( 'carousel_slider_view', array( $this, 'video_carousel_view' ), 10, 3 );
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
	public function video_carousel_view( $id, $slide_type, $slide_options ) {

		if ( $slide_type == 'video-carousel' ) {
			ob_start();
			require CAROUSEL_SLIDER_MODULES . '/video-carousel/views/public/video-carousel.php';
			$html = ob_get_contents();
			ob_end_clean();

			echo apply_filters( 'carousel_slider_videos_carousel', $html, $id, $slide_options );
		}

	}

	/**
	 * Convert url to youtube and vimeo video link
	 *
	 * @param $url
	 *
	 * @return string
	 */
	public function video_url( $url ) {
		if ( ! carousel_slider_is_url( $url ) ) {
			return;
		}

		$url = esc_url( $url );

		if ( strpos( $url, 'youtube.com' ) > 0 ) {
			return '<div class="item-video"><a class="owl-video" href="' . $url . '"></a></div>';
		}

		if ( strpos( $url, 'vimeo.com' ) > 0 ) {
			return '<div class="item-video"><a class="owl-video" href="' . $url . '"></a></div>';
		}

		return;
	}
}

View::init();
