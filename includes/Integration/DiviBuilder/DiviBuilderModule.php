<?php

namespace CarouselSlider\Integration\DiviBuilder;

use ET_Builder_Module;

defined( 'ABSPATH' ) || exit;

/**
 * DiviBuilderModule class
 */
class DiviBuilderModule {

	/**
	 * The instance of the class
	 *
	 * @var self
	 */
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

	/**
	 * Load module script
	 */
	public function load_scripts() {
		if ( empty( $_GET['et_fb'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}
		wp_enqueue_style(
			'carousel-slider-divi-modules',
			CAROUSEL_SLIDER_ASSETS . '/css/admin-divi-modules.css',
			[],
			CAROUSEL_SLIDER_VERSION
		);
		wp_enqueue_script(
			'carousel-slider-divi-modules',
			CAROUSEL_SLIDER_ASSETS . '/js/admin-divi-modules.js',
			[ 'react', 'react-dom' ],
			CAROUSEL_SLIDER_VERSION,
			true
		);
		wp_localize_script( 'carousel-slider-divi-modules', 'csDivi', [ 'site_url' => site_url() ] );
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
