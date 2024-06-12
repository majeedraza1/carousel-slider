<?php

namespace CarouselSlider\Admin;

use WP_Post;

/**
 * Admin live preview
 */
class PreviewMetaBox {

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

			add_action( 'add_meta_boxes', array( self::$instance, 'add_meta_boxes' ), 10, 2 );
			add_action( 'wp_ajax_carousel_slider_preview_meta_box', array( self::$instance, 'preview_meta_box' ) );
		}

		return self::$instance;
	}

	/**
	 * Add carousel slider meta box
	 *
	 * @param  string  $post_type  The post type.
	 * @param  WP_Post $post  The post object.
	 */
	public function add_meta_boxes( $post_type, $post ) {
		if ( CAROUSEL_SLIDER_POST_TYPE !== $post_type ) {
			return;
		}
		add_meta_box(
			'carousel-slider-live-preview',
			__( 'Live Preview', 'carousel-slider' ),
			[ $this, 'carousel_slider_live_preview' ],
			CAROUSEL_SLIDER_POST_TYPE,
			'normal',
			'high'
		);
	}

	/**
	 * Carousel slider live preview
	 *
	 * @param  WP_Post $post  The post object.
	 *
	 * @return void
	 */
	public function carousel_slider_live_preview( $post ) {
		?>
		<div id="carousel_slider_preview_meta_box">
		</div>
		<?php
	}

	/**
	 * Send preview meta box
	 *
	 * @return void
	 */
	public function preview_meta_box() {
		$nonce = isset( $_POST['cs_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['cs_nonce'] ) ) : ''; // phpcs:ignore
		if ( ! wp_verify_nonce( $nonce, 'carousel_slider_ajax_nonce' ) ) {
			return;
		}
	}
}
