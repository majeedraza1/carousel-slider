<?php

namespace CarouselSlider\Admin;

use CarouselSlider\Helper;

/**
 * MetaBoxConfig class
 */
class MetaBoxConfig {

	/**
	 * Get all settings
	 *
	 * @return array
	 */
	public static function get_settings(): array {
		return array_merge(
			self::get_general_settings(),
			self::get_navigation_settings(),
			self::get_pagination_settings(),
			self::get_autoplay_settings(),
			self::get_color_settings(),
			self::get_responsive_settings()
		);
	}

	/**
	 * Get general settings
	 *
	 * @return array
	 */
	public static function get_general_settings(): array {
		$settings = [
			[
				'id'          => '_image_size',
				'label'       => esc_html__( 'Carousel Image size', 'carousel-slider' ),
				'description' => sprintf(
				/* translators: 1: setting media page link start, 2: setting media page link end */
					esc_html__( 'Choose "original uploaded image" for full size image or your desired image size for carousel image. You can change the default size for thumbnail, medium and large from %1$s Settings >> Media %2$s.', 'carousel-slider' ),
					'<a target="_blank" href="' . admin_url( 'options-media.php' ) . '">',
					'</a>'
				),
				'type'        => 'image_sizes',
				'default'     => 'medium_large',
				'section'     => 'section_general_settings',
			],
			[
				'id'          => '_margin_right',
				'label'       => esc_html__( 'Item Spacing.', 'carousel-slider' ),
				'description' => esc_html__( 'Space between two slide. Enter 10 for 10px', 'carousel-slider' ),
				'default'     => Helper::get_default_setting( 'margin_right' ),
				'type'        => 'number',
				'section'     => 'section_general_settings',
			],
			[
				'id'          => '_stage_padding',
				'label'       => esc_html__( 'Stage Padding', 'carousel-slider' ),
				'description' => esc_html__( 'Add left and right padding on carousel slider stage wrapper.', 'carousel-slider' ),
				'default'     => 0,
				'type'        => 'number',
				'section'     => 'section_general_settings',
			],
			[
				'id'          => '_lazy_load_image',
				'label'       => esc_html__( 'Lazy Loading', 'carousel-slider' ),
				'description' => esc_html__( 'Enable image with lazy loading.', 'carousel-slider' ),
				'default'     => Helper::get_default_setting( 'lazy_load_image' ),
				'type'        => 'switch',
				'section'     => 'section_general_settings',
			],
			[
				'id'          => '_infinity_loop',
				'label'       => esc_html__( 'Infinity loop', 'carousel-slider' ),
				'description' => esc_html__( 'Enable or disable loop(circular) of carousel.', 'carousel-slider' ),
				'default'     => 'on',
				'type'        => 'switch',
				'section'     => 'section_general_settings',
			],
			[
				'id'          => '_auto_width',
				'label'       => esc_html__( 'Auto Width', 'carousel-slider' ),
				'description' => esc_html__( 'Set item width according to its content width. Use width style on item to get the result you want. ', 'carousel-slider' ),
				'default'     => 'off',
				'type'        => 'switch',
				'section'     => 'section_general_settings',
			],
		];

		return apply_filters( 'carousel_slider/admin/metabox_general_config', $settings );
	}

	/**
	 * Get navigation settings
	 *
	 * @return array
	 */
	public static function get_navigation_settings(): array {
		$settings = [
			[
				'section'     => 'section_navigation_settings',
				'type'        => 'select',
				'id'          => '_nav_button',
				'class'       => 'small-text',
				'label'       => esc_html__( 'Show Arrow Nav', 'carousel-slider' ),
				'description' => esc_html__( 'Choose when to show arrow navigator.', 'carousel-slider' ),
				'default'     => 'on',
				'choices'     => [
					'off'    => esc_html__( 'Never', 'carousel-slider' ),
					'on'     => esc_html__( 'Mouse Over', 'carousel-slider' ),
					'always' => esc_html__( 'Always', 'carousel-slider' ),
				],
			],
			[
				'section'     => 'section_navigation_settings',
				'type'        => 'text',
				'id'          => '_slide_by',
				'label'       => esc_html__( 'Arrow Steps', 'carousel-slider' ),
				'description' => esc_html__( 'Steps to go for each navigation request. Write -1 to slide by page.', 'carousel-slider' ),
				'default'     => 1,
			],
			[
				'section'     => 'section_navigation_settings',
				'type'        => 'select',
				'id'          => '_arrow_position',
				'label'       => esc_html__( 'Arrow Position', 'carousel-slider' ),
				'description' => esc_html__( 'Choose where to show arrow. Inside slider or outside slider.', 'carousel-slider' ),
				'default'     => 'outside',
				'choices'     => [
					'outside' => esc_html__( 'Outside', 'carousel-slider' ),
					'inside'  => esc_html__( 'Inside', 'carousel-slider' ),
				],
			],
			[
				'section'     => 'section_navigation_settings',
				'type'        => 'number',
				'id'          => '_arrow_size',
				'label'       => esc_html__( 'Arrow Size', 'carousel-slider' ),
				'description' => esc_html__( 'Enter arrow size in pixels.', 'carousel-slider' ),
				'default'     => 48,
			],
		];

		return apply_filters( 'carousel_slider/admin/metabox_navigation_config', $settings );
	}

	/**
	 * Get pagination settings
	 *
	 * @return array
	 */
	public static function get_pagination_settings(): array {
		$settings = [
			[
				'section'     => 'section_pagination_settings',
				'type'        => 'select',
				'id'          => '_dot_nav',
				'label'       => esc_html__( 'Show Bullet Nav', 'carousel-slider' ),
				'description' => esc_html__( 'Choose when to show bullet navigator.', 'carousel-slider' ),
				'default'     => 'off',
				'choices'     => [
					'off'   => esc_html__( 'Never', 'carousel-slider' ),
					'on'    => esc_html__( 'Always', 'carousel-slider' ),
					'hover' => esc_html__( 'Mouse Over', 'carousel-slider' ),
				],
			],
			[
				'section'     => 'section_pagination_settings',
				'type'        => 'select',
				'id'          => '_bullet_position',
				'label'       => esc_html__( 'Bullet Position', 'carousel-slider' ),
				'description' => esc_html__( 'Choose where to show bullets.', 'carousel-slider' ),
				'default'     => 'center',
				'choices'     => [
					'left'   => esc_html__( 'Left', 'carousel-slider' ),
					'center' => esc_html__( 'Center', 'carousel-slider' ),
					'right'  => esc_html__( 'Right', 'carousel-slider' ),
				],
			],
			[
				'section'     => 'section_pagination_settings',
				'type'        => 'number',
				'id'          => '_bullet_size',
				'label'       => esc_html__( 'Bullet Size', 'carousel-slider' ),
				'description' => esc_html__( 'Enter bullet size in pixels.', 'carousel-slider' ),
				'default'     => 10,
			],
			[
				'section'     => 'section_pagination_settings',
				'type'        => 'select',
				'id'          => '_bullet_shape',
				'label'       => esc_html__( 'Bullet Shape', 'carousel-slider' ),
				'description' => esc_html__( 'Choose bullet nav shape.', 'carousel-slider' ),
				'default'     => 'circle',
				'choices'     => [
					'square' => esc_html__( 'Square', 'carousel-slider' ),
					'circle' => esc_html__( 'Circle', 'carousel-slider' ),
				],
			],
		];

		return apply_filters( 'carousel_slider/admin/metabox_pagination_config', $settings );
	}

	/**
	 * Get autoplay settings
	 *
	 * @return array
	 */
	public static function get_autoplay_settings(): array {
		$settings = [
			[
				'section'     => 'section_autoplay_settings',
				'type'        => 'switch',
				'id'          => '_autoplay',
				'label'       => esc_html__( 'AutoPlay', 'carousel-slider' ),
				'description' => esc_html__( 'Choose whether slideshow should play automatically.', 'carousel-slider' ),
				'default'     => 'on',
			],
			[
				'section'     => 'section_autoplay_settings',
				'type'        => 'switch',
				'id'          => '_autoplay_pause',
				'label'       => esc_html__( 'Pause On Hover', 'carousel-slider' ),
				'description' => esc_html__( 'Pause automatic play on mouse hover.', 'carousel-slider' ),
				'default'     => 'on',
			],
			[
				'section'     => 'section_autoplay_settings',
				'type'        => 'number',
				'id'          => '_autoplay_timeout',
				'label'       => esc_html__( 'Autoplay Timeout', 'carousel-slider' ),
				'description' => esc_html__( 'Automatic play interval timeout in millisecond.', 'carousel-slider' ),
				'default'     => 5000,
			],
			[
				'section'     => 'section_autoplay_settings',
				'type'        => 'number',
				'id'          => '_autoplay_speed',
				'label'       => esc_html__( 'Autoplay Speed', 'carousel-slider' ),
				'description' => esc_html__( 'Automatic play speed in millisecond.', 'carousel-slider' ),
				'default'     => 500,
			],
		];

		return apply_filters( 'carousel_slider/admin/metabox_autoplay_config', $settings );
	}

	/**
	 * Get color settings
	 *
	 * @return array
	 */
	public static function get_color_settings(): array {
		$settings = [
			[
				'section' => 'section_color_settings',
				'type'    => 'color',
				'id'      => '_nav_color',
				'label'   => esc_html__( 'Arrows & Dots Color', 'carousel-slider' ),
				'default' => Helper::get_default_setting( 'nav_color' ),
			],
			[
				'section' => 'section_color_settings',
				'type'    => 'color',
				'id'      => '_nav_active_color',
				'label'   => esc_html__( 'Arrows & Dots Hover Color', 'carousel-slider' ),
				'default' => Helper::get_default_setting( 'nav_active_color' ),
			],
		];

		return apply_filters( 'carousel_slider/admin/metabox_color_config', $settings );
	}

	/**
	 * Get responsive settings
	 *
	 * @return array
	 */
	public static function get_responsive_settings(): array {
		$settings = [
			[
				'section'     => 'section_responsive_settings',
				'type'        => 'number',
				'id'          => '_items',
				'label'       => esc_html__( 'Columns', 'carousel-slider' ),
				'description' => esc_html__( 'The number of items you want to see on the Extra Large Desktop Layout (Screens size greater than 1921 pixels DP)', 'carousel-slider' ),
				'default'     => 4,
			],
			[
				'section'     => 'section_responsive_settings',
				'type'        => 'number',
				'id'          => '_items_desktop',
				'label'       => esc_html__( 'Columns : Desktop', 'carousel-slider' ),
				'description' => esc_html__( 'The number of items you want to see on the Desktop Layout (Screens size from 1200 pixels DP to 1920 pixels DP)', 'carousel-slider' ),
				'default'     => 4,
			],
			[
				'section'     => 'section_responsive_settings',
				'type'        => 'number',
				'id'          => '_items_small_desktop',
				'label'       => esc_html__( 'Columns : Small Desktop', 'carousel-slider' ),
				'description' => esc_html__( 'The number of items you want to see on the Small Desktop Layout (Screens size from 993 pixels DP to 1199 pixels DP)', 'carousel-slider' ),
				'default'     => 3,
			],
			[
				'section'     => 'section_responsive_settings',
				'type'        => 'number',
				'id'          => '_items_portrait_tablet',
				'label'       => esc_html__( 'Columns : Tablet', 'carousel-slider' ),
				'description' => esc_html__( 'The number of items you want to see on the Tablet Layout (Screens size from 768 pixels DP to 992 pixels DP)', 'carousel-slider' ),
				'default'     => 2,
			],
			[
				'section'     => 'section_responsive_settings',
				'type'        => 'number',
				'id'          => '_items_small_portrait_tablet',
				'label'       => esc_html__( 'Columns : Small Tablet', 'carousel-slider' ),
				'description' => esc_html__( 'The number of items you want to see on the Small Tablet Layout(Screens size from 600 pixels DP to 767 pixels DP)', 'carousel-slider' ),
				'default'     => 2,
			],
			[
				'section'     => 'section_responsive_settings',
				'type'        => 'number',
				'id'          => '_items_portrait_mobile',
				'label'       => esc_html__( 'Columns : Mobile', 'carousel-slider' ),
				'description' => esc_html__( 'The number of items you want to see on the Mobile Layout (Screens size from 320 pixels DP to 599 pixels DP)', 'carousel-slider' ),
				'default'     => 1,
			],
		];

		return apply_filters( 'carousel_slider/admin/metabox_responsive_config', $settings );
	}
}
