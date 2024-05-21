<?php

namespace CarouselSlider\Admin;

use CarouselSlider\Helper;
use CarouselSlider\Supports\Sanitize;
use WP_Post;

/**
 * MetaBoxConfig class
 */
class MetaBoxConfig {

	/**
	 * Get hook args
	 *
	 * @return array
	 */
	public static function get_args(): array {
		global $post;
		$data = [
			'id'   => 0,
			'type' => '',
		];
		if ( $post instanceof WP_Post ) {
			$slide_type = get_post_meta( $post->ID, '_slide_type', true );

			$data['id']   = $post->ID;
			$data['type'] = array_key_exists( $slide_type, Helper::get_slide_types() ) ? $slide_type : '';
		}

		return $data;
	}

	/**
	 * Get sections
	 *
	 * @return array[]
	 */
	public static function get_sections_settings(): array {
		return [
			'section_general_settings'    => [
				'hook'  => 'metabox_general_settings',
				'id'    => 'section_general_settings',
				'label' => __( 'General Settings', 'carousel-slider' ),
			],
			'section_navigation_settings' => [
				'hook'  => 'metabox_navigation_settings',
				'id'    => 'section_navigation_settings',
				'label' => __( 'Navigation Settings', 'carousel-slider' ),
			],
			'section_pagination_settings' => [
				'hook'  => 'metabox_pagination_settings',
				'id'    => 'section_pagination_settings',
				'label' => __( 'Pagination Settings', 'carousel-slider' ),
			],
			'section_autoplay_settings'   => [
				'hook'  => 'metabox_autoplay_settings',
				'id'    => 'section_autoplay_settings',
				'label' => __( 'Autoplay Settings', 'carousel-slider' ),
			],
			'section_color_settings'      => [
				'hook'  => 'metabox_color_settings',
				'id'    => 'section_color_settings',
				'label' => __( 'Color Settings', 'carousel-slider' ),
			],
		];
	}

	/**
	 * Get all settings
	 *
	 * @return array
	 */
	public static function get_fields_settings(): array {
		$settings = array_merge(
			self::get_general_settings(),
			self::get_navigation_settings(),
			self::get_pagination_settings(),
			self::get_autoplay_settings(),
			self::get_color_settings()
		);

		return apply_filters( 'carousel_slider/admin/metabox_config', $settings, self::get_args() );
	}

	/**
	 * Get general settings
	 *
	 * @return array
	 */
	public static function get_general_settings(): array {
		$settings = [
			'image_size'       => [
				'section'     => 'section_general_settings',
				'default'     => 'medium_large',
				'id'          => '_image_size',
				'label'       => esc_html__( 'Carousel Image size', 'carousel-slider' ),
				'description' => sprintf(
				/* translators: 1: setting media page link start, 2: setting media page link end */
					esc_html__( 'Choose "original uploaded image" for full size image or your desired image size for carousel image. You can change the default size for thumbnail, medium and large from %1$s Settings >> Media %2$s.', 'carousel-slider' ),
					'<a target="_blank" href="' . admin_url( 'options-media.php' ) . '">',
					'</a>'
				),
				'type'        => 'image_sizes',
			],
			'space_between'    => [
				'section'     => 'section_general_settings',
				'type'        => 'number',
				'id'          => '_margin_right',
				'label'       => esc_html__( 'Item Spacing.', 'carousel-slider' ),
				'description' => esc_html__( 'Space between two slide. Enter 10 for 10px', 'carousel-slider' ),
				'default'     => Helper::get_default_setting( 'margin_right' ),
			],
			'stage_padding'    => [
				'section'     => 'section_general_settings',
				'type'        => 'number',
				'id'          => '_stage_padding',
				'label'       => esc_html__( 'Stage Padding', 'carousel-slider' ),
				'description' => esc_html__( 'Add left and right padding on carousel slider stage wrapper.', 'carousel-slider' ),
				'default'     => 0,
			],
			'lazy_load'        => [
				'section'     => 'section_general_settings',
				'type'        => 'switch',
				'id'          => '_lazy_load_image',
				'label'       => esc_html__( 'Lazy Loading', 'carousel-slider' ),
				'description' => esc_html__( 'Enable image with lazy loading.', 'carousel-slider' ),
				'default'     => Helper::get_default_setting( 'lazy_load_image' ),
			],
			'loop'             => [
				'section'     => 'section_general_settings',
				'type'        => 'switch',
				'id'          => '_infinity_loop',
				'label'       => esc_html__( 'Infinity loop', 'carousel-slider' ),
				'description' => esc_html__( 'Enable or disable loop(circular) of carousel.', 'carousel-slider' ),
				'default'     => 'on',
			],
			'type_of_slider'   => [
				'section'     => 'section_general_settings',
				'type'        => 'button_group',
				'id'          => '_type_of_slider',
				'label'       => esc_html__( 'Slider Type', 'carousel-slider' ),
				'description' => esc_html__( 'Choose slider if you want to display one image/slide at a time. Choose carousel if you want to display multiple images/slides at a time.', 'carousel-slider' ),
				'default'     => 'carousel',
				'choices'     => [
					[
						'value' => 'carousel',
						'label' => esc_html__( 'Carousel', 'carousel-slider' ),
					],
					[
						'value' => 'slider',
						'label' => esc_html__( 'Slider', 'carousel-slider' ),
					],
				],
			],
			'auto_width'       => [
				'section'     => 'section_general_settings',
				'type'        => 'switch',
				'id'          => '_auto_width',
				'label'       => esc_html__( 'Auto Width', 'carousel-slider' ),
				'description' => esc_html__( 'Set item width according to its content width. Use width style on item to get the result you want. ', 'carousel-slider' ),
				'default'     => 'off',
				'condition'   => [
					'_type_of_slider' => 'carousel',
				],
			],
			'slides_per_view'  => [
				'section'           => 'section_general_settings',
				'type'              => 'responsive_control',
				'id'                => '_slides_per_view',
				'label'             => esc_html__( 'Slides Per View', 'carousel-slider' ),
				'description'       => esc_html__( 'Set number of slides to show per view. If you enable "Auto Width", this option will be disabled.', 'carousel-slider' ),
				'device_choices'    => [ 'xs', 'sm', 'md', 'lg', 'xl', '2xl' ],
				'default'           => [
					'xs'  => 1,
					'sm'  => 2,
					'md'  => 2,
					'lg'  => 3,
					'xl'  => 4,
					'2xl' => 5,
				],
				'condition'         => [
					'_type_of_slider' => 'carousel',
				],
				'sanitize_callback' => [ Sanitize::class, 'deep_int' ],
			],
			'slider_direction' => [
				'section'     => 'section_general_settings',
				'type'        => 'button_group',
				'id'          => '_slider_direction',
				'label'       => esc_html__( 'Slider Direction', 'carousel-slider' ),
				'description' => esc_html__( 'Choose slider direction.', 'carousel-slider' ),
				'default'     => 'horizontal',
				'pro_only'    => true,
				'choices'     => [
					[
						'value' => 'horizontal',
						'label' => esc_html__( 'Horizontal', 'carousel-slider' ),
					],
					[
						'value'    => 'vertical',
						'label'    => esc_html__( 'Vertical', 'carousel-slider' ),
						'pro_only' => true,
					],
				],
			],
			'slider_effect'    => [
				'section'     => 'section_general_settings',
				'type'        => 'button_group',
				'id'          => '_slider_effect',
				'label'       => esc_html__( 'Slider Effect', 'carousel-slider' ),
				'description' => esc_html__( 'Choose slider effect.', 'carousel-slider' ),
				'default'     => 'slide',
				'pro_only'    => true,
				'choices'     => [
					[
						'value' => 'slide',
						'label' => esc_html__( 'Slide', 'carousel-slider' ),
					],
					[
						'value'    => 'fade',
						'label'    => esc_html__( 'Fade', 'carousel-slider' ),
						'pro_only' => true,
					],
					[
						'value'    => 'cube',
						'label'    => esc_html__( 'Cube', 'carousel-slider' ),
						'pro_only' => true,
					],
					[
						'value'    => 'coverflow',
						'label'    => esc_html__( 'Coverflow', 'carousel-slider' ),
						'pro_only' => true,
					],
					[
						'value'    => 'flip',
						'label'    => esc_html__( 'Flip', 'carousel-slider' ),
						'pro_only' => true,
					],
					[
						'value'    => 'creative',
						'label'    => esc_html__( 'Creative', 'carousel-slider' ),
						'pro_only' => true,
					],
					[
						'value'    => 'cards',
						'label'    => esc_html__( 'Cards', 'carousel-slider' ),
						'pro_only' => true,
					],
				],
			],
		];

		return apply_filters( 'carousel_slider/admin/metabox_general_config', $settings, self::get_args() );
	}

	/**
	 * Get navigation settings
	 *
	 * @return array
	 */
	public static function get_navigation_settings(): array {
		$settings = [
			'nav_visibility' => [
				'section'     => 'section_navigation_settings',
				'type'        => 'button_group',
				'id'          => '_nav_button',
				'label'       => esc_html__( 'Show Navigation', 'carousel-slider' ),
				'description' => esc_html__( 'Choose when to show arrow navigator.', 'carousel-slider' ),
				'default'     => 'on',
				'choices'     => [
					'off'    => esc_html__( 'Never', 'carousel-slider' ),
					'on'     => esc_html__( 'Mouse Over', 'carousel-slider' ),
					'always' => esc_html__( 'Always', 'carousel-slider' ),
				],
			],
			'nav_steps'      => [
				'section'     => 'section_navigation_settings',
				'type'        => 'text',
				'id'          => '_slide_by',
				'label'       => esc_html__( 'Navigation Steps', 'carousel-slider' ),
				'description' => esc_html__( 'Steps to go for each navigation request. Write -1 to slide by page.', 'carousel-slider' ),
				'default'     => 1,
			],
			'nav_size'       => [
				'section'     => 'section_navigation_settings',
				'type'        => 'number',
				'id'          => '_arrow_size',
				'label'       => esc_html__( 'Navigation Size', 'carousel-slider' ),
				'description' => esc_html__( 'Enter arrow size in pixels.', 'carousel-slider' ),
				'default'     => 48,
			],
			'nav_position'   => [
				'section'     => 'section_navigation_settings',
				'type'        => 'button_group',
				'id'          => '_arrow_position',
				'label'       => esc_html__( 'Navigation Position', 'carousel-slider' ),
				'description' => esc_html__( 'Choose where to show arrow. Inside slider or outside slider.', 'carousel-slider' ),
				'default'     => Helper::is_using_swiper() ? 'inside' : 'outside',
				'choices'     => [
					[
						'value'    => 'outside',
						'label'    => esc_html__( 'Outside', 'carousel-slider' ),
						'disabled' => Helper::is_using_swiper(),
					],
					[
						'value' => 'inside',
						'label' => esc_html__( 'Inside', 'carousel-slider' ),
					],
				],
			],
		];

		return apply_filters( 'carousel_slider/admin/metabox_navigation_config', $settings, self::get_args() );
	}

	/**
	 * Get pagination settings
	 *
	 * @return array
	 */
	public static function get_pagination_settings(): array {
		$settings = [
			'pagination_visibility' => [
				'section'     => 'section_pagination_settings',
				'type'        => 'button_group',
				'id'          => '_dot_nav',
				'label'       => esc_html__( 'Show Pagination', 'carousel-slider' ),
				'description' => esc_html__( 'Choose when to show pagination.', 'carousel-slider' ),
				'default'     => 'off',
				'choices'     => [
					'off'   => esc_html__( 'Never', 'carousel-slider' ),
					'on'    => esc_html__( 'Always', 'carousel-slider' ),
					'hover' => esc_html__( 'Mouse Over', 'carousel-slider' ),
				],
			],
			'pagination_alignment'  => [
				'section'     => 'section_pagination_settings',
				'type'        => 'button_group',
				'id'          => '_bullet_position',
				'label'       => esc_html__( 'Pagination Alignment', 'carousel-slider' ),
				'description' => esc_html__( 'Choose pagination alignment.', 'carousel-slider' ),
				'default'     => 'center',
				'choices'     => [
					'left'   => esc_html__( 'Left', 'carousel-slider' ),
					'center' => esc_html__( 'Center', 'carousel-slider' ),
					'right'  => esc_html__( 'Right', 'carousel-slider' ),
				],
			],
			'pagination_size'       => [
				'section'     => 'section_pagination_settings',
				'type'        => 'number',
				'id'          => '_bullet_size',
				'label'       => esc_html__( 'Pagination Size', 'carousel-slider' ),
				'description' => esc_html__( 'Enter pagination size in pixels.', 'carousel-slider' ),
				'default'     => 10,
			],
			'pagination_shape'      => [
				'section'     => 'section_pagination_settings',
				'type'        => 'button_group',
				'id'          => '_bullet_shape',
				'label'       => esc_html__( 'Pagination Shape', 'carousel-slider' ),
				'description' => esc_html__( 'Choose pagination shape.', 'carousel-slider' ),
				'default'     => 'circle',
				'choices'     => [
					'square' => esc_html__( 'Square', 'carousel-slider' ),
					'circle' => esc_html__( 'Circle', 'carousel-slider' ),
				],
			],
		];

		$settings['pagination_position'] = [
			'section'     => 'section_pagination_settings',
			'type'        => 'button_group',
			'id'          => '_pagination_position',
			'label'       => esc_html__( 'Pagination Position', 'carousel-slider' ),
			'description' => esc_html__( 'Choose where to show pagination. Inside slider or outside slider.', 'carousel-slider' ),
			'default'     => Helper::is_using_swiper() ? 'inside' : 'outside',
			'pro_only'    => true,
			'choices'     => [
				[
					'value'    => 'outside',
					'label'    => esc_html__( 'Outside', 'carousel-slider' ),
					'disabled' => Helper::is_using_swiper(),
				],
				[
					'value' => 'inside',
					'label' => esc_html__( 'Inside', 'carousel-slider' ),
				],
			],
		];
		$settings['pagination_type']     = [
			'section'     => 'section_pagination_settings',
			'type'        => 'button_group',
			'id'          => '_pagination_type',
			'label'       => esc_html__( 'Pagination Type', 'carousel-slider' ),
			'description' => esc_html__( 'Choose pagination type.', 'carousel-slider' ),
			'default'     => 'bullets',
			'pro_only'    => true,
			'choices'     => [
				[
					'value' => 'bullets',
					'label' => esc_html__( 'Bullets', 'carousel-slider' ),
				],
				[
					'value'    => 'fraction',
					'label'    => esc_html__( 'Fraction', 'carousel-slider' ),
					'pro_only' => true,
				],
				[
					'value'    => 'progressbar',
					'label'    => esc_html__( 'progressbar', 'carousel-slider' ),
					'pro_only' => true,
				],
				[
					'value'    => 'custom',
					'label'    => esc_html__( 'custom', 'carousel-slider' ),
					'pro_only' => true,
				],
			],
		];

		return apply_filters( 'carousel_slider/admin/metabox_pagination_config', $settings, self::get_args() );
	}

	/**
	 * Get autoplay settings
	 *
	 * @return array
	 */
	public static function get_autoplay_settings(): array {
		$settings = [
			'autoplay'             => [
				'section'     => 'section_autoplay_settings',
				'type'        => 'switch',
				'id'          => '_autoplay',
				'label'       => esc_html__( 'AutoPlay', 'carousel-slider' ),
				'description' => esc_html__( 'Choose whether slideshow should play automatically.', 'carousel-slider' ),
				'default'     => 'on',
			],
			'autoplay_hover_pause' => [
				'section'     => 'section_autoplay_settings',
				'type'        => 'switch',
				'id'          => '_autoplay_pause',
				'label'       => esc_html__( 'Pause On Hover', 'carousel-slider' ),
				'description' => esc_html__( 'Pause automatic play on mouse hover.', 'carousel-slider' ),
				'default'     => 'on',
			],
			'autoplay_delay'       => [
				'section'     => 'section_autoplay_settings',
				'type'        => 'number',
				'id'          => '_autoplay_timeout',
				'label'       => esc_html__( 'Autoplay Timeout', 'carousel-slider' ),
				'description' => esc_html__( 'Automatic play interval timeout in millisecond.', 'carousel-slider' ),
				'default'     => 5000,
			],
			'autoplay_speed'       => [
				'section'     => 'section_autoplay_settings',
				'type'        => 'number',
				'id'          => '_autoplay_speed',
				'label'       => esc_html__( 'Autoplay Speed', 'carousel-slider' ),
				'description' => esc_html__( 'Automatic play speed in millisecond.', 'carousel-slider' ),
				'default'     => 500,
			],
		];

		return apply_filters( 'carousel_slider/admin/metabox_autoplay_config', $settings, self::get_args() );
	}

	/**
	 * Get color settings
	 *
	 * @return array
	 */
	public static function get_color_settings(): array {
		$settings = [
			'nav_color'        => [
				'section'     => 'section_color_settings',
				'type'        => 'color',
				'id'          => '_nav_color',
				'label'       => esc_html__( 'Navigation & Pagination Color', 'carousel-slider' ),
				'description' => esc_html__( 'Set theme color to use with navigation and pagination.', 'carousel-slider' ),
				'default'     => Helper::get_default_setting( 'nav_color' ),
			],
			'nav_active_color' => [
				'section'     => 'section_color_settings',
				'type'        => 'color',
				'id'          => '_nav_active_color',
				'label'       => esc_html__( 'Navigation & Pagination Hover Color', 'carousel-slider' ),
				'description' => esc_html__( 'Set theme color for hover and active state to use with navigation and pagination.', 'carousel-slider' ),
				'default'     => Helper::get_default_setting( 'nav_active_color' ),
			],
		];

		return apply_filters( 'carousel_slider/admin/metabox_color_config', $settings, self::get_args() );
	}

	/**
	 * Get responsive settings
	 *
	 * @return array
	 */
	public static function get_responsive_settings(): array {
		$settings = [
			'items_on_fullhd'       => [
				'section'     => 'section_responsive_settings',
				'type'        => 'number',
				'id'          => '_items',
				'label'       => esc_html__( 'Columns: Full HD', 'carousel-slider' ),
				'description' => esc_html__( 'The number of items you want to see on the Extra Large Desktop Layout (Screens size greater than 1921 pixels DP)', 'carousel-slider' ),
				'default'     => 4,
			],
			'items_on_widescreen'   => [
				'section'     => 'section_responsive_settings',
				'type'        => 'number',
				'id'          => '_items_desktop',
				'label'       => esc_html__( 'Columns : Desktop', 'carousel-slider' ),
				'description' => esc_html__( 'The number of items you want to see on the Desktop Layout (Screens size from 1200 pixels DP to 1920 pixels DP)', 'carousel-slider' ),
				'default'     => 4,
			],
			'items_on_desktop'      => [
				'section'     => 'section_responsive_settings',
				'type'        => 'number',
				'id'          => '_items_small_desktop',
				'label'       => esc_html__( 'Columns : Small Desktop', 'carousel-slider' ),
				'description' => esc_html__( 'The number of items you want to see on the Small Desktop Layout (Screens size from 993 pixels DP to 1199 pixels DP)', 'carousel-slider' ),
				'default'     => 3,
			],
			'items_on_tablet'       => [
				'section'     => 'section_responsive_settings',
				'type'        => 'number',
				'id'          => '_items_portrait_tablet',
				'label'       => esc_html__( 'Columns : Tablet', 'carousel-slider' ),
				'description' => esc_html__( 'The number of items you want to see on the Tablet Layout (Screens size from 768 pixels DP to 992 pixels DP)', 'carousel-slider' ),
				'default'     => 2,
			],
			'items_on_small_tablet' => [
				'section'     => 'section_responsive_settings',
				'type'        => 'number',
				'id'          => '_items_small_portrait_tablet',
				'label'       => esc_html__( 'Columns : Small Tablet', 'carousel-slider' ),
				'description' => esc_html__( 'The number of items you want to see on the Small Tablet Layout(Screens size from 600 pixels DP to 767 pixels DP)', 'carousel-slider' ),
				'default'     => 2,
			],
			'items_on_mobile'       => [
				'section'     => 'section_responsive_settings',
				'type'        => 'number',
				'id'          => '_items_portrait_mobile',
				'label'       => esc_html__( 'Columns : Mobile', 'carousel-slider' ),
				'description' => esc_html__( 'The number of items you want to see on the Mobile Layout (Screens size from 320 pixels DP to 599 pixels DP)', 'carousel-slider' ),
				'default'     => 1,
			],
		];

		return apply_filters( 'carousel_slider/admin/metabox_responsive_config', $settings, self::get_args() );
	}
}
