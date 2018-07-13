<?php

namespace CarouselSlider\Display;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Preview {

	public static $instance = null;

	/**
	 * @return Preview
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_filter( 'template_include', array( self::$instance, 'template_include' ) );
			add_filter( 'preview_post_link', array( self::$instance, 'preview_post_link' ), 10, 2 );
		}

		return self::$instance;
	}

	/**
	 * Generate slider preview link
	 *
	 * @param string $preview_link
	 * @param \WP_Post $post
	 *
	 * @return string
	 */
	public static function preview_post_link( $preview_link, $post ) {
		if ( 'carousels' == get_post_type( $post ) ) {
			$preview_link = add_query_arg( array(
				'carousel_slider' => true,
				'slider_id'       => $post->ID,
				'preview'         => true,
			), site_url( '/' ) );
		}

		return $preview_link;
	}

	/**
	 * Include form preview template
	 *
	 * @param string $template
	 *
	 * @return string
	 */
	public static function template_include( $template ) {

		if ( isset( $_GET['carousel_slider'], $_GET['slider_id'], $_GET['preview'] ) ) {
			if ( current_user_can( 'manage_options' ) ) {
				$template = CAROUSEL_SLIDER_TEMPLATES . '/public/preview-slider.php';
			}
		}

		return $template;
	}
}
