<?php

namespace CarouselSlider;

defined( 'ABSPATH' ) || exit;

class i18n {

	/**
	 * The instance of the class
	 *
	 * @var self
	 */
	protected static $instance;

	/**
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_action( 'init', [ self::$instance, 'load_plugin_textdomain' ] );
		}

		return self::$instance;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {
		$domain  = CAROUSEL_SLIDER;
		$lan_dir = CAROUSEL_SLIDER_PATH . '/languages/';

		load_plugin_textdomain( $domain, false, $lan_dir );
	}
}
