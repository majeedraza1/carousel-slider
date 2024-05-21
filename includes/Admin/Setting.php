<?php

namespace CarouselSlider\Admin;

use CarouselSlider\Helper;
use CarouselSlider\Supports\SettingApi\DefaultSettingApi;
use Exception;

defined( 'ABSPATH' ) || exit;

/**
 * Setting class to register global setting.
 *
 * @package CarouselSlider/Admin
 */
class Setting {
	/**
	 * Instance of current class
	 *
	 * @var self
	 */
	private static $instance;

	/**
	 * The only one instance of the class can be loaded
	 *
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
	 * @param  string $key  option key.
	 * @param  mixed  $default_value  default value.
	 *
	 * @return mixed
	 */
	public static function get_option( string $key, $default_value = '' ) {
		$default_args = array(
			'load_scripts'                        => 'optimized',
			'show_structured_data'                => 'on',
			'woocommerce_shop_loop_item_template' => 'v1-compatibility',
		);
		$options      = wp_parse_args( get_option( 'carousel_slider_settings', array() ), $default_args );

		return $options[ $key ] ?? $default_value;
	}

	/**
	 * Plugin setting fields
	 *
	 * @throws Exception It throws exception if you don't set name and id field.
	 */
	public function settings() {
		$settings = new DefaultSettingApi();
		$settings->add_menu(
			array(
				'page_title'  => __( 'Carousel Slider Settings', 'carousel-slider' ),
				'menu_title'  => __( 'Settings', 'carousel-slider' ),
				'about_text'  => __(
					'Thank you for choosing Carousel Slider. We hope you enjoy it!',
					'carousel-slider'
				),
				'menu_slug'   => 'settings',
				'parent_slug' => 'edit.php?post_type=carousels',
				'option_name' => 'carousel_slider_settings',
			)
		);

		// Add settings page tab.
		$settings->set_panel(
			array(
				'id'       => 'general',
				'title'    => __( 'General', 'carousel-slider' ),
				'priority' => 10,
			)
		);
		$settings->set_panel(
			array(
				'id'       => 'woocommerce',
				'title'    => __( 'WooCommerce', 'carousel-slider' ),
				'priority' => 20,
			)
		);
		$settings->set_panel(
			array(
				'id'                 => 'panel_extra',
				'title'              => __( 'Extra', 'carousel-slider' ),
				'priority'           => 20,
				'hide_submit_button' => true,
			)
		);

		$settings->add_field(
			array(
				'id'          => 'load_scripts',
				'type'        => 'radio',
				'default'     => 'optimized',
				'title'       => __( 'Style & Scrips', 'carousel-slider' ),
				'description' => __(
					'If you choose <strong>Optimized</strong>, then scrips and styles will be loaded only on page where
 				you are using shortcode. If <strong>Optimized</strong> is not working for you then choose
 				<strong>Optimized with style loader</strong>. Then it will add a small javascript at footer to load css
 				 file in header. If none of these is not working for you then choose <strong>Always</strong>',
					'carousel-slider'
				),
				'choices'     => array(
					'optimized'        => __( 'Optimized (recommended)', 'carousel-slider' ),
					'optimized-loader' => __( 'Optimized with style loader', 'carousel-slider' ),
					'always'           => __( 'Always', 'carousel-slider' ),
				),
				'panel'       => 'general',
				'priority'    => 10,
			)
		);
		$settings->add_field(
			array(
				'id'          => 'slider_js_package',
				'type'        => 'radio',
				'default'     => 'owl.carousel',
				'title'       => __( 'Slider JavaScript package', 'carousel-slider' ),
				'description' => __(
					'<strong>Swiper</strong>, is the most modern mobile touch slider without any third party dependencies.
 					<strong>Owl Carousel 2</strong> was great but now it is <strong>PRETTY MUCH DEAD</strong> as there is
 					no development after Nov 12, 2018',
					'carousel-slider'
				),
				'choices'     => array(
					array(
						'value' => 'owl.carousel',
						'label' => __( 'Owl Carousel 2 + Magnific Popup', 'carousel-slider' ),
					),
					array(
						'value' => 'swiper',
						'label' => __( 'Swiper (experimental)', 'carousel-slider' ),
					),
				),
				'panel'       => 'general',
				'priority'    => 20,
			)
		);
		$settings->add_field(
			array(
				'id'          => 'show_structured_data',
				'type'        => 'switch',
				'default'     => 'on',
				'title'       => __( 'Show Structured Data', 'carousel-slider' ),
				'description' => __(
					'If you enable to show, then it will generate structured data for every slider for better SEO.
					But if you are using some other SEO plugin to handle SEO, then you can disabled it.',
					'carousel-slider'
				),
				'panel'       => 'general',
				'priority'    => 30,
			)
		);

		$choices = array(
			array(
				'value' => 'wc-default',
				'label' => __( 'WooCommerce Default (recommended)', 'carousel-slider' ),
			),
			array(
				'value' => 'v1-compatibility',
				'label' => __( 'Compatibility mode (with version 1)', 'carousel-slider' ),
			),
		);
		if ( Helper::is_pro_active() ) {
			$choices[] = array(
				'value' => 'template-parser',
				'label' => __( 'Custom Template (pro)', 'carousel-slider' ),
			);
		}
		$settings->add_field(
			array(
				'id'          => 'woocommerce_shop_loop_item_template',
				'type'        => 'radio',
				'default'     => 'v1-compatibility',
				'title'       => __( 'Slider item template', 'carousel-slider' ),
				'description' => array(
					__(
						'<strong>WooCommerce Default</strong> use hook to load shop loop template and does not allow
						hiding/showing title, rating, price, card button, sale tag using slider settings.',
						'carousel-slider'
					),
					__(
						'<strong>Compatibility mode</strong> use custom template and allow hiding/showing title,
						rating, price, card button, sale tag.',
						'carousel-slider'
					),
				),
				'choices'     => $choices,
				'panel'       => 'woocommerce',
			)
		);
		$settings->add_field(
			array(
				'id'         => 'carousel_slider_allow_tracking',
				'type'       => 'data_sharing',
				'title'      => __( 'Data sharing', 'carousel-slider' ),
				'label'      => __(
					'Allow Carousel Slider to collect non-sensitive diagnostic data and usage information.',
					'carousel-slider'
				),
				'panel'      => 'panel_extra',
				'priority'   => 30,
				'standalone' => true,
			)
		);
	}

	/**
	 * Get modules choices
	 *
	 * @return array
	 */
	public function get_modules_choices(): array {
		$slider_types   = Helper::get_slider_types();
		$module_choices = array();
		foreach ( $slider_types as $value => $option ) {
			$choice = array(
				'value' => $value,
				'label' => isset( $option['pro'] ) && true === $option['pro'] ?
					sprintf( '%s - pro', $option['label'] ) : $option['label'],
			);
			if ( isset( $option['enabled'] ) && false === $option['enabled'] ) {
				$choice['readonly'] = true;
			}
			$module_choices[] = $choice;
		}

		return $module_choices;
	}
}
