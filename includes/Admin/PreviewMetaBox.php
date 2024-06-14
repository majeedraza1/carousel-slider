<?php

namespace CarouselSlider\Admin;

use CarouselSlider\Helper;
use CarouselSlider\Interfaces\SliderViewInterface;
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

			add_action( 'add_meta_boxes', array( self::$instance, 'add_meta_boxes' ) );
			add_action( 'wp_ajax_carousel_slider_preview_meta_box', array( self::$instance, 'preview_meta_box' ) );
		}

		return self::$instance;
	}

	/**
	 * Add carousel slider meta box
	 *
	 * @param  string $post_type  The post type.
	 */
	public function add_meta_boxes( $post_type ) {
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
	 * @return void
	 */
	public function carousel_slider_live_preview() {
		?>
		<div id="carousel_slider_preview_iframe_container" class="carousel_slider_preview_iframe_container"></div>
		<div id="carousel_slider_preview_meta_box"></div>
		<?php
	}

	/**
	 * Send preview meta box
	 *
	 * @return void
	 */
	public function preview_meta_box() {
		$nonce = isset( $_REQUEST['cs_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['cs_nonce'] ) ) : ''; // phpcs:ignore
		if ( ! wp_verify_nonce( $nonce, 'carousel_slider_ajax_nonce' ) ) {
			wp_send_json_error( __( 'Sorry, you are not allowed to access this resource.', 'carousel-slider' ), 403 );
		}
		$post_id = isset( $_REQUEST['post_ID'] ) ? absint( $_REQUEST['post_ID'] ) : 0;
		$post    = get_post( $post_id );
		if ( ! $post instanceof WP_Post ) {
			wp_send_json_error( __( 'Sorry, no item found for your request.', 'carousel-slider' ), 404 );
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			wp_send_json_error( __( 'Sorry, you are not allowed to access this resource.', 'carousel-slider' ), 403 );
		}

		if ( CAROUSEL_SLIDER_POST_TYPE !== $post->post_type ) {
			wp_send_json_error( __( 'Sorry, you are not allowed to access this resource.', 'carousel-slider' ), 401 );
		}

		$slider_type = get_post_meta( $post_id, '_slide_type', true );
		$view        = Helper::get_slider_view( $slider_type );
		if ( ! $view instanceof SliderViewInterface ) {
			wp_send_json_error( __( 'Sorry, no item found for your request.', 'carousel-slider' ), 422 );
		}

		$view->set_slider_id( $post_id );
		$view->set_slider_type( $slider_type );

		// Modify setting.
		$setting         = $view->get_slider_setting();
		$common_settings = $_POST['carousel_slider'] ?? [];
		$setting->read_http_post_variables( $common_settings );

		if ( 'image-carousel' === $slider_type ) {
			$image_carousel = $_POST['image_carousel'] ?? [];
			$setting->read_extra_http_post_variables( $image_carousel );
		}

		$view->set_slider_setting( $setting );

		// @TODO remove save option with temp
		MetaBox::init()->save_meta_box( $post_id );

		$response = [
			'slider_type' => $slider_type,
			'html'        => $view->render(),
		];

		wp_send_json_success( $response, 200 );
	}
}
