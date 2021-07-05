<?php

namespace CarouselSlider\Admin;

use CarouselSlider\Supports\SettingAPI;
use Exception;

defined( 'ABSPATH' ) || exit;

class Setting {
	/**
	 * Instance of current class
	 *
	 * @var self
	 */
	private static $instance;

	/**
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_action( 'init', array( self::$instance, 'settings' ) );
		}

		return self::$instance;
	}

	/**
	 * Plugin setting fields
	 *
	 * @throws Exception
	 */
	public function settings() {
		$settings = new SettingAPI;
		$settings->add_menu( [
			'page_title'  => __( 'Carousel Slider Settings', 'carousel-slider' ),
			'menu_title'  => __( 'Settings', 'carousel-slider' ),
			'about_text'  => __( 'Thank you for choosing Carousel Slider. We hope you enjoy it!', 'carousel-slider' ),
			'menu_slug'   => 'settings',
			'parent_slug' => 'edit.php?post_type=carousels',
			'option_name' => 'carousel_slider_settings',
		] );

		// Add settings page tab
		$settings->add_tab( [
			'id'    => 'general',
			'title' => __( 'General', 'carousel-slider' ),
		] );

		$settings->add_field( [
			'id'      => 'load_scripts',
			'type'    => 'radio',
			'std'     => 'optimized',
			'name'    => __( 'Style & Scrips', 'carousel-slider' ),
			'desc'    => __( 'If you choose Optimized, then scrips and styles will be loaded only on page where you are using shortcode. If Optimized is not working for you then choose Always.', 'carousel-slider' ),
			'options' => [
				'always'    => __( 'Always', 'carousel-slider' ),
				'optimized' => __( 'Optimized (recommended)', 'carousel-slider' ),
			],
			'tab'     => 'general',
		] );
		$settings->add_field( [
			'id'   => 'show_structured_data',
			'type' => 'checkbox',
			'std'  => '1',
			'name' => __( 'Show Structured Data', 'carousel-slider' ),
			'desc' => __( 'If you enable to show, then it will generate structured data for every slider for better SEO. But if you are using some other SEO plugin to handle SEO, then you can disabled it.', 'carousel-slider' ),
			'tab'  => 'general',
		] );
	}
}
