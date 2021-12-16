<?php

namespace CarouselSlider\Integration\DiviBuilder;

use CarouselSlider\Frontend\Frontend;
use CarouselSlider\Helper;
use ET_Builder_Module;

defined( 'ABSPATH' ) || exit;

/**
 * Module class
 */
class Module extends ET_Builder_Module {
	/**
	 * Module slug
	 *
	 * @var string
	 */
	public $slug = 'carousel_slider_divi_module';

	/**
	 * Enable support visual builder
	 *
	 * @var string
	 */
	public $vb_support = 'on';

	/**
	 * Credits of our custom modules.
	 *
	 * @var string[]
	 */
	protected $module_credits = [
		'module_uri' => 'https://wordpress.org/plugins/carousel-slider',
		'author'     => 'Sayful Islam',
		'author_uri' => 'https://sayfulislam.com',
	];

	/**
	 * Init module
	 *
	 * @return void
	 */
	public function init() {
		$this->name = esc_html__( 'Carousel Slider', 'carousel-slider' );
	}

	/**
	 * Get the settings fields data for this element.
	 *
	 * @inheritDoc
	 */
	public function get_fields(): array {
		$posts   = Helper::get_sliders();
		$options = [];
		foreach ( $posts as $post ) {
			$options[ $post->ID ] = $post->post_title;
		}

		return [
			'slider_id' => [
				'label'           => esc_html__( 'Slider', 'carousel-slider' ),
				'description'     => esc_html__( 'Select a slider.', 'carousel-slider' ),
				'type'            => 'select',
				'option_category' => 'basic_option',
				'options'         => $options,
			],
			'site_url'  => [
				'type'  => 'hidden',
				'value' => site_url(),
			],
		];
	}

	/**
	 * Generates the module's HTML output based on {@see self::$props}. This method should be
	 * overridden in module classes.
	 *
	 * @param array  $unprocessed_props List of unprocessed attributes.
	 * @param string $content Content being processed.
	 * @param string $render_slug Slug of module that is used for rendering output.
	 *
	 * @return string The module's HTML output.
	 */
	public function render( $unprocessed_props, $content = null, $render_slug ) {
		$slider_id = intval( $this->props['slider_id'] );

		return Frontend::init()->carousel_slide( [ 'id' => $slider_id ] );
	}
}
