<?php

namespace CarouselSlider\Display;

use CarouselSlider\Abstracts\AbstractView;
use CarouselSlider\ModuleManager;
use CarouselSlider\Supports\Utils;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Shortcode {

	private static $instance = null;

	/**
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @return Shortcode
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_shortcode( 'carousel_slide', array( self::$instance, 'carousel_slide' ) );
		}

		return self::$instance;
	}

	/**
	 * A shortcode for rendering the carousel slide.
	 *
	 * @param  array $attributes Shortcode attributes.
	 *
	 * @return string  The shortcode output
	 */
	public function carousel_slide( $attributes ) {
		if ( empty( $attributes['id'] ) ) {
			return '';
		}

		$id         = intval( $attributes['id'] );
		$slide_type = get_post_meta( $id, '_slide_type', true );
		$slide_type = in_array( $slide_type, Utils::get_slide_types() ) ? $slide_type : 'image-carousel';

		$moduleManager = ModuleManager::init();
		if ( ! $moduleManager->has( $slide_type ) ) {
			return '';
		}
		$className = $moduleManager->get( $slide_type );
		$view      = new $className;
		if ( ! $view instanceof AbstractView ) {
			return '';
		}

		$view->set_slider_id( $id );
		$html      = $view->render();
		$hook_name = 'carousel_slider/view/' . str_replace( '-', '_', $slide_type );

		return apply_filters( $hook_name, $html, $view );
	}
}
