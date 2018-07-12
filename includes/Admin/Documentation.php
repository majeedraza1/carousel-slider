<?php

namespace CarouselSlider\Admin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Documentation {

	/**
	 * @var self
	 */
	private static $instance = null;

	/**
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @return Documentation
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_action( 'admin_menu', array( self::$instance, 'admin_menu' ) );
		}

		return self::$instance;
	}

	public function admin_menu() {
		add_submenu_page(
			'edit.php?post_type=carousels',
			'Documentation',
			'Documentation',
			'manage_options',
			'carousel-slider-documentation',
			array( $this, 'submenu_page_callback' )
		);
	}

	public function submenu_page_callback() {
		include_once CAROUSEL_SLIDER_TEMPLATES . '/admin/documentation.php';
	}
}
