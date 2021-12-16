<?php

namespace CarouselSlider\Modules\VideoCarousel;

use CarouselSlider\Abstracts\AbstractTemplate;

defined( 'ABSPATH' ) || exit;

/**
 * Template class
 *
 * @package Modules/VideoCarousel
 */
class Template extends AbstractTemplate {

	/**
	 * Create gallery image carousel with random images
	 *
	 * @param string $slider_title The slider title.
	 * @param array  $args Additional arguments.
	 *
	 * @return int The post ID on success. The value 0 on failure.
	 */
	public static function create( $slider_title = null, $args = [] ): int {
		if ( empty( $slider_title ) ) {
			$slider_title = 'Image Carousel with Dummy Data';
		}

		$default = self::get_default_settings();
		$urls    = self::get_video_urls();
		$urls    = implode( ',', $urls );

		$default['_slide_type'] = 'video-carousel';
		$default['_video_url']  = $urls;

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

	/**
	 * Get video url
	 *
	 * @return array
	 */
	private static function get_video_urls(): array {
		return [
			'https://www.youtube.com/watch?v=_hVsamgr1k4',
			'https://www.youtube.com/watch?v=ZzI1JhElrxc',
			'https://www.youtube.com/watch?v=ImJB946azy0',
			'https://www.youtube.com/watch?v=a7hqn1yNzwM',
			'https://www.youtube.com/watch?v=OaYQZfr1RM',
			'https://www.youtube.com/watch?v=kYgp6wp27lM',
			'https://www.youtube.com/watch?v=4LhDXH81whk',
			'https://www.youtube.com/watch?v=yiAkvXyfakg',
		];
	}
}
