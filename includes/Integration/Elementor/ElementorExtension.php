<?php

namespace CarouselSlider\Integration\Elementor;

use CarouselSlider\Assets;
use Elementor\Plugin;

defined( 'ABSPATH' ) || exit;

/**
 * ElementorExtension class
 */
class ElementorExtension {

	/**
	 * The instance of the class.
	 *
	 * @var self
	 */
	private static $instance = null;

	/**
	 * The instance of the class
	 *
	 * @return ElementorExtension|null
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_action( 'elementor/frontend/after_register_scripts', [ self::$instance, 'widget_scripts' ] );
			add_action( 'elementor/widgets/widgets_registered', [ self::$instance, 'register_widgets' ] );
		}

		return self::$instance;
	}

	/**
	 * Widget scrips
	 */
	public function widget_scripts() {
		wp_register_script(
			'carousel-slider-elementor',
			Assets::get_assets_url( 'js/frontend.js' ),
			[ 'elementor-frontend', 'jquery' ],
			'1.0.0',
			true
		);
		wp_register_style(
			'carousel-slider-elementor',
			Assets::get_assets_url( 'css/frontend.css' ),
			[],
			CAROUSEL_SLIDER_VERSION
		);
	}

	/**
	 * Register Elementor widgets
	 */
	public function register_widgets() {
		Plugin::instance()->widgets_manager->register_widget_type( new ElementorWidget() );
	}
}
