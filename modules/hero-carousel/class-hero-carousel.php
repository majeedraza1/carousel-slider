<?php

namespace CarouselSlider\Modules\HeroCarousel;

class HeroCarousel {

	protected static $instance = null;

	/**
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @return HeroCarousel
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action( 'carousel_slider_save_meta_box', array( $this, 'save_meta_box' ), 10, 3 );
	}

	/**
	 * Save hero carousel custom meta box
	 *
	 * @param int $post_id Post ID.
	 * @param \WP_Post $post Post object.
	 * @param bool $update Whether this is an existing post being updated or not.
	 */
	public function save_meta_box( $post_id, $post, $update ) {

		if ( isset( $_POST['carousel_slider_content'] ) ) {
			$this->update_content_slider( $post_id );
		}

		if ( isset( $_POST['content_settings'] ) ) {
			$this->update_content_settings( $post_id );
		}
	}

	/**
	 * Update content slider
	 *
	 * @param int $post_id
	 */
	private function update_content_slider( $post_id ) {
		$_content_slides = $_POST['carousel_slider_content'];
		$_slides         = array_map( function ( $slide ) {
			$_slide = array(
				// Slide Content
				'slide_heading'            => wp_kses_post( $slide['slide_heading'] ),
				'slide_description'        => wp_kses_post( $slide['slide_description'] ),
				// Slide Background
				'img_id'                   => intval( $slide['img_id'] ),
				'img_bg_position'          => sanitize_text_field( $slide['img_bg_position'] ),
				'img_bg_size'              => sanitize_text_field( $slide['img_bg_size'] ),
				'ken_burns_effect'         => sanitize_text_field( $slide['ken_burns_effect'] ),
				'bg_color'                 => carousel_slider_sanitize_color( $slide['bg_color'] ),
				'bg_overlay'               => carousel_slider_sanitize_color( $slide['bg_overlay'] ),
				// Slide Style
				'content_alignment'        => sanitize_text_field( $slide['content_alignment'] ),
				'heading_font_size'        => intval( $slide['heading_font_size'] ),
				'heading_gutter'           => sanitize_text_field( $slide['heading_gutter'] ),
				'heading_color'            => carousel_slider_sanitize_color( $slide['heading_color'] ),
				'description_font_size'    => intval( $slide['description_font_size'] ),
				'description_gutter'       => sanitize_text_field( $slide['description_gutter'] ),
				'description_color'        => carousel_slider_sanitize_color( $slide['description_color'] ),
				// Slide Link
				'link_type'                => sanitize_text_field( $slide['link_type'] ),
				'slide_link'               => esc_url_raw( $slide['slide_link'] ),
				'link_target'              => sanitize_text_field( $slide['link_target'] ),
				// Slide Button #1
				'button_one_text'          => sanitize_text_field( $slide['button_one_text'] ),
				'button_one_url'           => esc_url_raw( $slide['button_one_url'] ),
				'button_one_target'        => sanitize_text_field( $slide['button_one_target'] ),
				'button_one_type'          => sanitize_text_field( $slide['button_one_type'] ),
				'button_one_size'          => sanitize_text_field( $slide['button_one_size'] ),
				'button_one_border_width'  => sanitize_text_field( $slide['button_one_border_width'] ),
				'button_one_border_radius' => sanitize_text_field( $slide['button_one_border_radius'] ),
				'button_one_bg_color'      => carousel_slider_sanitize_color( $slide['button_one_bg_color'] ),
				'button_one_color'         => carousel_slider_sanitize_color( $slide['button_one_color'] ),
				// Slide Button #2
				'button_two_text'          => sanitize_text_field( $slide['button_two_text'] ),
				'button_two_url'           => esc_url_raw( $slide['button_two_url'] ),
				'button_two_target'        => sanitize_text_field( $slide['button_two_target'] ),
				'button_two_type'          => sanitize_text_field( $slide['button_two_type'] ),
				'button_two_size'          => sanitize_text_field( $slide['button_two_size'] ),
				'button_two_border_width'  => sanitize_text_field( $slide['button_two_border_width'] ),
				'button_two_border_radius' => sanitize_text_field( $slide['button_two_border_radius'] ),
				'button_two_bg_color'      => carousel_slider_sanitize_color( $slide['button_two_bg_color'] ),
				'button_two_color'         => carousel_slider_sanitize_color( $slide['button_two_color'] ),
			);

			return $_slide;
		}, $_content_slides );

		update_post_meta( $post_id, '_content_slider', $_slides );
	}

	/**
	 * Update hero carousel general settings
	 *
	 * @param int $post_id
	 */
	private function update_content_settings( $post_id ) {
		$setting   = $_POST['content_settings'];
		$_settings = array(
			'slide_height'  => sanitize_text_field( $setting['slide_height'] ),
			'content_width' => sanitize_text_field( $setting['content_width'] ),
			'slide_padding' => array(
				'top'    => sanitize_text_field( $setting['slide_padding']['top'] ),
				'right'  => sanitize_text_field( $setting['slide_padding']['right'] ),
				'bottom' => sanitize_text_field( $setting['slide_padding']['bottom'] ),
				'left'   => sanitize_text_field( $setting['slide_padding']['left'] ),
			),
		);
		update_post_meta( $post_id, '_content_slider_settings', $_settings );
	}
}

HeroCarousel::init();
