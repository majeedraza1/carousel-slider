<?php

namespace CarouselSlider\Integration\Elementor;

use CarouselSlider\Frontend\Frontend;
use CarouselSlider\Helper;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

/**
 * ElementorWidget class
 */
class ElementorWidget extends Widget_Base {

	/**
	 * Get element name.
	 *
	 * @inheritDoc
	 */
	public function get_name(): string {
		return 'carousel-slider-elementor';
	}

	/**
	 * Get element title.
	 *
	 * @inheritDoc
	 */
	public function get_title(): string {
		return esc_html__( 'Carousel Slider - Elementor', 'carousel-slider' );
	}

	/**
	 * Get widget icon.
	 *
	 * @inheritDoc
	 */
	public function get_icon(): string {
		return 'eicon-carousel';
	}

	/**
	 * Get script dependencies.
	 *
	 * @inheritDoc
	 */
	public function get_script_depends(): array {
		return [ 'carousel-slider-frontend' ];
	}

	/**
	 * Get style dependencies.
	 *
	 * @inheritDoc
	 */
	public function get_style_depends(): array {
		return [ 'carousel-slider-frontend' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * @inheritDoc
	 */
	public function get_keywords(): array {
		return [ 'image', 'photo', 'carousel', 'slider' ];
	}

	/**
	 * Register controls.
	 *
	 * @inheritDoc
	 */
	protected function register_controls() {
		$posts   = Helper::get_sliders();
		$options = [];
		foreach ( $posts as $post ) {
			$options[ $post->ID ] = $post->post_title;
		}

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Slider Settings', 'carousel-slider' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'slider_id',
			[
				'label'      => __( 'Choose slider', 'carousel-slider' ),
				'type'       => Controls_Manager::SELECT,
				'input_type' => 'url',
				'options'    => $options,
			]
		);

		$this->add_control(
			'site_url',
			[
				'type'       => Controls_Manager::HIDDEN,
				'input_type' => 'hidden',
				'value'      => site_url(),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render element output in the editor.
	 *
	 * @inheritDoc
	 */
	public function content_template() {
		?>
		<div class="carousel-slider-iframe-container">
			<div class="carousel-slider-iframe-overlay"></div>
			<iframe class="carousel-slider-iframe"
					src="{{settings.site_url}}?carousel_slider_preview=1&carousel_slider_iframe=1&slider_id={{settings.slider_id}}"
					height="0" width="500"></iframe>
		</div>
		<?php
	}

	/**
	 * Render element.
	 *
	 * @inheritDoc
	 */
	protected function render() {
		$settings  = $this->get_settings_for_display();
		$slider_id = intval( $settings['slider_id'] );

		if ( 'elementor' === ( $_GET['action'] ?? '' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$args = add_query_arg(
				[
					'carousel_slider_preview' => true,
					'carousel_slider_iframe'  => true,
					'slider_id'               => $slider_id,
				],
				site_url()
			);

			$html  = '<div class="carousel-slider-iframe-container">';
			$html .= '<div class="carousel-slider-iframe-overlay"></div>';
			$html .= '<iframe class="carousel-slider-iframe" src="' . $args . '" height="0" width="500"></iframe>';
			$html .= '</div>';
			echo wp_kses_post( $html );

			return;
		}

		$html  = '<div class="carousel-slider-elementor-widget">';
		$html .= Frontend::init()->carousel_slide( [ 'id' => $slider_id ] );
		$html .= '</div>';

		echo wp_kses_post( $html );
	}
}
