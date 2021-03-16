<?php

namespace CarouselSlider\Admin;

defined( 'ABSPATH' ) || exit;

class Admin {

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

			add_action( 'admin_enqueue_scripts', [ self::$instance, 'admin_scripts' ], 10 );
			add_action( 'admin_menu', [ self::$instance, 'documentation_menu' ] );
			add_filter( 'admin_footer_text', [ self::$instance, 'admin_footer_text' ] );
		}

		return self::$instance;
	}

	/**
	 * Load admin scripts
	 *
	 * @param $hook
	 */
	public function admin_scripts( $hook ) {
		global $post;

		$_is_carousel = is_a( $post, 'WP_Post' ) && ( 'carousels' == $post->post_type );
		$_is_doc      = ( 'carousels_page_carousel-slider-documentation' == $hook );

		if ( ! $_is_carousel && ! $_is_doc ) {
			return;
		}

		wp_enqueue_media();
		wp_enqueue_style( 'carousel-slider-admin' );
		wp_enqueue_script( 'carousel-slider-admin' );
	}

	/**
	 * Add documentation menu
	 */
	public function documentation_menu() {
		add_submenu_page(
			'edit.php?post_type=carousels',
			__( 'Documentation', 'carousel-slider' ),
			__( 'Documentation', 'carousel-slider' ),
			'manage_options',
			'carousel-slider-documentation',
			[ $this, 'documentation_page_callback' ]
		);
	}

	/**
	 * Documentation page callback
	 */
	public function documentation_page_callback() {
		include_once CAROUSEL_SLIDER_TEMPLATES . '/admin/documentation.php';
	}

	/**
	 * Add custom footer text on plugins page.
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	public function admin_footer_text( $text ) {
		global $post_type, $hook_suffix;

		$footer_text = sprintf(
			__( 'If you like %1$s Carousel Slider %2$s please leave us a %3$s rating. A huge thanks in advance!', 'carousel-slider' ),
			'<strong>',
			'</strong>',
			'<a href="https://wordpress.org/support/view/plugin-reviews/carousel-slider?filter=5#postform" target="_blank" data-rated="Thanks :)">&starf;&starf;&starf;&starf;&starf;</a>'
		);

		if ( $post_type == 'carousels' || $hook_suffix == 'carousels_page_carousel-slider-documentation' ) {
			return $footer_text;
		}

		return $text;
	}
}
