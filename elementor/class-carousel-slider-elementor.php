<?php

class Carousel_Slider_Elementor {

	private static $instance;

	/**
	 * @return Carousel_Slider_Elementor
	 */
	public static function init() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'init_widgets' ) );
		add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_frontend_styles' ) );
	}

	public function init_widgets() {
		\Elementor\Plugin::instance()->elements_manager->add_category(
			'carousel-slider-elements',
			array(
				'title' => 'Carousel Slider Elements',
				'icon'  => 'fa fa-plug'
			),
			1
		);

		require_once 'modules/hero-carousel/hero-carousel.php';
		require_once 'modules/testimonial-carousel/testimonial-carousel.php';
		require_once 'modules/media-carousel/media-carousel.php';
	}

	public function frontend_scripts() {
		$suffix = ( defined( "SCRIPT_DEBUG" ) && SCRIPT_DEBUG ) ? '' : '.min';
		wp_enqueue_script(
			'elementor-carousel-slider',
			CAROUSEL_SLIDER_ASSETS . '/js/elementor.js',
			[ 'jquery' ],
			CAROUSEL_SLIDER_VERSION,
			true
		);
	}

	public function enqueue_frontend_styles() {
		wp_enqueue_style(
			'elementor-carousel-slider',
			CAROUSEL_SLIDER_ASSETS . '/css/elementor.css',
			[],
			CAROUSEL_SLIDER_VERSION,
			'all'
		);
	}
}

return Carousel_Slider_Elementor::init();
