<?php

namespace CarouselSlider\Integration\DiviBuilder;

use ET_Builder_Module;

defined( 'ABSPATH' ) || exit;

class DiviBuilderModule {
	private static $instance = null;

	/**
	 * The instance of the class
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_action( 'carousel_slider/activation', [ self::$instance, 'activation' ] );
			add_action( 'wp_enqueue_scripts', [ self::$instance, 'load_scripts' ] );
			add_action( 'et_builder_ready', [ self::$instance, 'load_modules' ] );
		}

		return self::$instance;
	}

	/**
	 * Force the legacy backend builder to reload its template cache.
	 * This ensures that custom modules are available for use right away.
	 */
	public function activation() {
		if ( function_exists( 'et_pb_force_regenerate_templates' ) ) {
			et_pb_force_regenerate_templates();
		}
	}

	public function load_scripts() {
		// @todo load only when builder is active
		wp_enqueue_style( 'carousel-slider-divi-modules', CAROUSEL_SLIDER_ASSETS . '/css/divi-modules.css', [] );
		wp_enqueue_script( 'carousel-slider-divi-modules', CAROUSEL_SLIDER_ASSETS . '/js/divi-modules.js',
			[ 'react', 'react-dom' ], '', true );
		wp_localize_script( 'carousel-slider-divi-modules', 'csDivi', [
			'site_url' => site_url(),
		] );
	}

	/**
	 * Load modules
	 */
	public function load_modules() {
		if ( ! self::should_load_modules() ) {
			return;
		}
		new Module();
	}

	/**
	 * Check if we should load modules
	 *
	 * @return bool
	 */
	public static function should_load_modules(): bool {
		return class_exists( ET_Builder_Module::class );
	}
}
