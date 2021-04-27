<?php

namespace CarouselSlider\Modules\HeroCarousel;

use CarouselSlider\Supports\MetaBoxForm;

class HeroCarouselAdmin {

	/**
	 * @var self
	 */
	private static $instance;

	/**
	 * Only one instance of the class can be loaded
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;

			add_action( 'carousel_slider/meta_box_content', [ self::$instance, 'meta_box_content' ], 10, 2 );
		}

		return self::$instance;
	}

	/**
	 * Load meta box content
	 *
	 * @param int $slider_id
	 * @param string $slide_type
	 */
	public function meta_box_content( int $slider_id, string $slide_type ) {
		global $post;
		require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/hero-banner/hero-banner-slider.php';
	}

	/**
	 * Get content settings
	 *
	 * @param int $slider_id
	 */
	public static function content_meta_box_settings( int $slider_id ) {
		$content_settings   = get_post_meta( $slider_id, '_content_slider_settings', true );
		$_slide_height      = $content_settings['slide_height'] ?? '400px';
		$_content_width     = $content_settings['content_width'] ?? '850px';
		$_content_animation = $content_settings['content_animation'] ?? '';
		$form               = new MetaBoxForm;

		echo '<div class="content_settings">';
		$form->text( [
			'group'            => 'content_settings',
			'id'               => 'slide_height',
			'name'             => esc_html__( 'Slide Height', 'carousel-slider' ),
			'desc'             => esc_html__( 'Enter a px, em, rem or vh value for slide height. ex: 100vh', 'carousel-slider' ),
			'std'              => '400px',
			'input_attributes' => [
				'name'  => "content_settings[slide_height]",
				'value' => $_slide_height,
			],
		] );
		$form->text( [
			'group'            => 'content_settings',
			'id'               => 'content_width',
			'name'             => esc_html__( 'Slider Content Max Width', 'carousel-slider' ),
			'desc'             => esc_html__( 'Enter a px, em, rem or % value for slide height. ex: 960px', 'carousel-slider' ),
			'std'              => '850px',
			'input_attributes' => [
				'name'  => "content_settings[content_width]",
				'value' => $_content_width,
			],
		] );
		$form->select( [
			'group'            => 'content_settings',
			'id'               => 'content_animation',
			'name'             => esc_html__( 'Content Animation', 'carousel-slider' ),
			'desc'             => esc_html__( 'Select slide content animation.', 'carousel-slider' ),
			'std'              => 'fadeOut',
			'options'          => HeroCarouselHelper::animations(),
			'input_attributes' => [
				'name'  => "content_settings[content_animation]",
				'value' => $_content_animation,
			],
		] );
		$form->spacing( [
			'meta_key' => '_content_slider_settings',
			'group'    => 'content_settings',
			'id'       => 'slide_padding',
			'name'     => esc_html__( 'Slider Padding', 'carousel-slider' ),
			'desc'     => esc_html__( 'Enter padding around slide in px, em or rem.', 'carousel-slider' ),
			'default'  => [ 'top' => '1rem', 'right' => '1rem', 'bottom' => '1rem', 'left' => '1rem' ],
		] );
		echo '</div>';
	}
}
