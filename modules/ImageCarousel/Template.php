<?php

namespace CarouselSlider\Modules\ImageCarousel;

use CarouselSlider\Abstracts\AbstractTemplate;

defined( 'ABSPATH' ) || exit;

/**
 * Template class
 *
 * @package Modules/ImageCarousel
 */
class Template extends AbstractTemplate {

	/**
	 * Get default image carousel settings
	 *
	 * @return array
	 */
	public static function get_default_settings(): array {
		return wp_parse_args(
			[
				'_slide_type'              => 'image-carousel',
				// Image Carousel Settings.
				'_show_attachment_title'   => 'off',
				'_show_attachment_caption' => 'off',
				'_image_lightbox'          => 'on',
				'_image_target'            => '_self',
			],
			parent::get_default_settings()
		);
	}

	/**
	 * Create gallery image carousel with random images
	 *
	 * @param string $slider_title The slider title.
	 * @param array  $args Arguments.
	 *
	 * @return int The post ID on success. The value 0 on failure.
	 */
	public static function create( $slider_title = '', $args = [] ): int {
		$images = self::get_images();
		$images = array_slice( $images, 0, 10 );
		$ids    = wp_list_pluck( $images, 'id' );
		$ids    = is_array( $ids ) ? implode( ',', $ids ) : $ids;

		if ( empty( $slider_title ) ) {
			$slider_title = 'Image Carousel with Dummy Data';
		}

		$default = self::get_default_settings();

		$default['_wpdh_image_ids'] = $ids;

		$data = wp_parse_args( $args, $default );

		$post_id = self::create_slider( $slider_title );

		if ( ! $post_id ) {
			return 0;
		}

		foreach ( $data as $meta_key => $meta_value ) {
			update_post_meta( $post_id, $meta_key, $meta_value );
		}

		return $post_id;
	}
}
