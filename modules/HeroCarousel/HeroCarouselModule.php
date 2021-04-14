<?php

namespace CarouselSlider\Modules\HeroCarousel;

defined( 'ABSPATH' ) || exit;

class HeroCarouselModule {
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

			add_action( 'carousel_slider/save_slider', [ self::$instance, 'save_slider' ] );

			Ajax::init();
		}

		return self::$instance;
	}

	/**
	 * Save slider content and settings
	 *
	 * @param int $slider_id
	 */
	public function save_slider( int $slider_id ) {
		if ( isset( $_POST['carousel_slider_content'] ) ) {
			$_content_slides = is_array( $_POST['carousel_slider_content'] ) ? $_POST['carousel_slider_content'] : [];
			$_slides         = array_map( function ( $slide ) {
				return Item::sanitize( $slide );
			}, $_content_slides );

			update_post_meta( $slider_id, '_content_slider', $_slides );
		}

		if ( isset( $_POST['content_settings'] ) ) {
			$this->update_content_settings( $slider_id );
		}
	}

	/**
	 * Update hero carousel settings
	 *
	 * @param int $post_id post id
	 */
	private function update_content_settings( int $post_id ) {
		$setting   = $_POST['content_settings'];
		$_settings = [
			'slide_height'      => sanitize_text_field( $setting['slide_height'] ),
			'content_width'     => sanitize_text_field( $setting['content_width'] ),
			'content_animation' => sanitize_text_field( $setting['content_animation'] ),
			'slide_padding'     => [
				'top'    => sanitize_text_field( $setting['slide_padding']['top'] ),
				'right'  => sanitize_text_field( $setting['slide_padding']['right'] ),
				'bottom' => sanitize_text_field( $setting['slide_padding']['bottom'] ),
				'left'   => sanitize_text_field( $setting['slide_padding']['left'] ),
			],
		];
		update_post_meta( $post_id, '_content_slider_settings', $_settings );
	}
}