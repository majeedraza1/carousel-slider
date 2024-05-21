<?php

namespace CarouselSlider\Modules\HeroCarousel;

use CarouselSlider\Abstracts\AbstractTemplate;

defined( 'ABSPATH' ) || exit;

/**
 * Template class
 *
 * @package Modules/HeroCarousel
 */
class Template extends AbstractTemplate {

	/**
	 * Get default hero carousel settings
	 *
	 * @return array
	 */
	public static function get_default_settings(): array {
		return wp_parse_args(
			[
				'_slide_type'                  => 'hero-banner-slider',
				'_type_of_slider'              => 'slider',
				// Responsive Settings.
				'_items_portrait_mobile'       => '1',
				'_items_small_portrait_tablet' => '1',
				'_items_portrait_tablet'       => '1',
				'_items_small_desktop'         => '1',
				'_items_desktop'               => '1',
				'_items'                       => '1',
			],
			parent::get_default_settings()
		);
	}

	/**
	 * Get default hero carousel content settings
	 *
	 * @return array
	 */
	protected static function get_content_settings(): array {
		return [
			'slide_height'      => '300px',
			'content_width'     => '80%',
			'content_animation' => 'zoomIn',
			'slide_padding'     => [
				'top'    => '3rem',
				'right'  => '3rem',
				'bottom' => '3rem',
				'left'   => '3rem',
			],
		];
	}

	/**
	 * Hero Slider Content Settings
	 *
	 * @param  int   $index  The index number.
	 * @param  int   $image_id  Image id.
	 * @param  array $args  Additional arguments.
	 *
	 * @return array
	 */
	protected static function get_content( int $index = 0, int $image_id = 0, array $args = array() ): array {
		++$index;
		$settings = [
			// Slide Content.
			'slide_heading'            => 'Slide Heading ' . $index,
			'slide_description'        => 'Slide Description form slide ' . $index,
			// Slide Background.
			'img_id'                   => $image_id,
			'img_bg_position'          => 'center center',
			'img_bg_size'              => 'cover',
			'ken_burns_effect'         => ( $index % 2 ) ? 'zoom-in' : 'zoom-out',
			'bg_color'                 => 'rgba(255,255,255,0.5)',
			'bg_overlay'               => 'rgba(0,0,0,0.5)',
			// Slide Style.
			'content_alignment'        => 'left',
			'heading_font_size'        => 40,
			'heading_gutter'           => '30px',
			'heading_color'            => '#ffffff',
			'description_font_size'    => '20',
			'description_gutter'       => '30px',
			'description_color'        => '#ffffff',
			// Slide Link.
			'link_type'                => 'button',
			'slide_link'               => '',
			'link_target'              => '_self',
			// Slide Button #1.
			'button_one_text'          => 'Button ' . $index,
			'button_one_url'           => 'https://sayfulislam.com',
			'button_one_target'        => '_self',
			'button_one_type'          => 'stroke',
			'button_one_size'          => 'medium',
			'button_one_border_width'  => '2px',
			'button_one_border_radius' => '3px',
			'button_one_bg_color'      => '#00d1b2',
			'button_one_color'         => '#ffffff',
			// Slide Button #2.
			'button_two_text'          => '',
			'button_two_url'           => '',
			'button_two_target'        => '_self',
			'button_two_type'          => 'stroke',
			'button_two_size'          => 'medium',
			'button_two_border_width'  => '2px',
			'button_two_border_radius' => '3px',
			'button_two_bg_color'      => '#00d1b2',
			'button_two_color'         => '#ffffff',
		];

		return wp_parse_args( $args, $settings );
	}

	/**
	 * Create hero carousel with random images
	 *
	 * @param  string $slider_title  The slider title.
	 * @param  array  $args  Additional arguments.
	 *
	 * @return int The post ID on success. The value 0 on failure.
	 */
	public static function create( string $slider_title = '', array $args = [] ): int {
		if ( empty( $slider_title ) ) {
			$slider_title = 'Hero Carousel';
		}

		$post_id = self::create_slider( $slider_title );

		if ( ! $post_id ) {
			return 0;
		}

		// Update General Settings.
		$data = wp_parse_args( $args, self::get_default_settings() );
		foreach ( $data as $meta_key => $meta_value ) {
			update_post_meta( $post_id, $meta_key, $meta_value );
		}

		// Update Content settings.
		update_post_meta( $post_id, '_content_slider_settings', self::get_content_settings() );

		// Update Content.
		$images     = self::get_images();
		$images_ids = wp_list_pluck( $images, 'id' );
		$images_ids = array_splice( $images_ids, 0, 5 );

		$content = array();
		foreach ( $images_ids as $index => $images_id ) {
			$content[] = self::get_content( $index, $images_id );
		}

		$content = array_values( $content );

		update_post_meta( $post_id, '_content_slider', $content );

		return $post_id;
	}
}
