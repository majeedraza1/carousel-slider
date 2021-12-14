<?php
/**
 * The ajax-specific functionality of the plugin.
 *
 * @package CarouselSlider
 */

namespace CarouselSlider;

defined( 'ABSPATH' ) || exit;

/**
 * Ajax class
 */
class Ajax {

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

			add_action( 'wp_ajax_carousel_slider_test', [ self::$instance, 'test' ] );
		}

		return self::$instance;
	}

	/**
	 * A AJAX method just to test some data
	 */
	public function test() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Sorry. This link only for developer to do some testing.' );
		}

		var_dump( 'Testing some data on AJAX' ); // phpcs:ignore
		die();
	}
}
