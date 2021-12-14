<?php

namespace CarouselSlider\Integration\DiviBuilder;

use CarouselSlider\Frontend\Frontend;
use CarouselSlider\Helper;
use ET_Builder_Module;

defined( 'ABSPATH' ) || exit;

class Module extends ET_Builder_Module {

	public $slug       = 'carousel_slider_divi_module';
	public $vb_support = 'on';

	protected $module_credits = [
		'module_uri' => 'https://wordpress.org/plugins/carousel-slider',
		'author'     => 'Sayful Islam',
		'author_uri' => 'https://sayfulislam.com',
	];

	public function init() {
		$this->name = esc_html__( 'Carousel Slider', 'carousel-slider' );
	}

	/**
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

	public function render( $unprocessed_props, $content = null, $render_slug ) {
		$slider_id = intval( $this->props['slider_id'] );

		return Frontend::init()->carousel_slide( [ 'id' => $slider_id ] );
	}
}
