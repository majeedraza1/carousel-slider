<?php

namespace CarouselSlider\Admin;

use CarouselSlider\Supports\SettingApi\DefaultSettingApi;
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
	 * Get option
	 *
	 * @param string $key
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public static function get_option( string $key, $default = '' ) {
		$default_args = [
			'load_scripts'                        => 'optimized',
			'show_structured_data'                => '1',
			'woocommerce_shop_loop_item_template' => 'v1-compatibility',
		];
		$options      = wp_parse_args( get_option( 'carousel_slider_settings', [] ), $default_args );

		return $options[ $key ] ?? $default;
	}

	/**
	 * Plugin setting fields
	 *
	 * @throws Exception
	 */
	public function settings() {
		$settings = new DefaultSettingApi();
		$settings->add_menu( [
			'page_title'  => __( 'Carousel Slider Settings', 'carousel-slider' ),
			'menu_title'  => __( 'Settings', 'carousel-slider' ),
			'about_text'  => __( 'Thank you for choosing Carousel Slider. We hope you enjoy it!', 'carousel-slider' ),
			'menu_slug'   => 'settings',
			'parent_slug' => 'edit.php?post_type=carousels',
			'option_name' => 'carousel_slider_settings',
		] );

		// Add settings page tab
		$settings->set_panel( [
			'id'    => 'general',
			'title' => __( 'General', 'carousel-slider' ),
		] );
		$settings->set_panel( [
			'id'    => 'woocommerce',
			'title' => __( 'WooCommerce', 'carousel-slider' ),
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
			'panel'   => 'general',
		] );
		$settings->add_field( [
			'id'    => 'show_structured_data',
			'type'  => 'checkbox',
			'std'   => '1',
			'name'  => __( 'Show Structured Data', 'carousel-slider' ),
			'desc'  => __( 'If you enable to show, then it will generate structured data for every slider for better SEO. But if you are using some other SEO plugin to handle SEO, then you can disabled it.', 'carousel-slider' ),
			'panel' => 'general',
		] );
		$settings->add_field( [
			'id'      => 'woocommerce_shop_loop_item_template',
			'type'    => 'radio',
			'std'     => 'v1-compatibility',
			'name'    => __( 'Slider item template', 'carousel-slider' ),
			'desc'    => [
				__( '<strong>WooCommerce Default</strong> use hook to load shop loop template and does not allow hiding/showing title, rating, price, card button, sale tag using slider settings.', 'carousel-slider' ),
				__( '<strong>Compatibility mode</strong> use custom template and allow hiding/showing title, rating, price, card button, sale tag.', 'carousel-slider' ),
			],
			'options' => [
				'wc-default'       => __( 'WooCommerce Default (recommended)', 'carousel-slider' ),
				'v1-compatibility' => __( 'Compatibility mode (with version 1)', 'carousel-slider' ),
			],
			'panel'   => 'woocommerce',
		] );
	}
}
