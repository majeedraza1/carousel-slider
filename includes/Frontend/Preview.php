<?php

namespace CarouselSlider\Frontend;

defined( 'ABSPATH' ) || exit;

class Preview {
	/**
	 * The instance of the class
	 *
	 * @var self
	 */
	protected static $instance;

	/**
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_filter( 'template_include', array( self::$instance, 'template_include' ) );
		}

		return self::$instance;
	}

	/**
	 * Include custom template
	 *
	 * @param $template
	 *
	 * @return mixed|string
	 */
	public function template_include( $template ) {
		if ( isset( $_GET['carousel_slider_preview'], $_GET['carousel_slider_iframe'], $_GET['slider_id'] ) ) {
			if ( current_user_can( 'edit_pages' ) ) {
				add_filter( 'carousel_slider_load_scripts', '__return_true' );
				$template = CAROUSEL_SLIDER_TEMPLATES . '/preview-slider.php';
			}
		}

		return $template;
	}
}
