<?php

namespace CarouselSlider\Integration\Elementor;

use CarouselSlider\Frontend\Frontend;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

class ElementorWidget extends Widget_Base {

	/**
	 * @inheritDoc
	 */
	public function get_name(): string {
		return 'carousel-slider-elementor';
	}

	/**
	 * @inheritDoc
	 */
	public function get_title(): string {
		return esc_html__( 'Carousel Slider - Elementor', 'carousel-slider' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon(): string {
		return 'eicon-carousel';
	}

	/**
	 * @inheritDoc
	 */
	public function get_script_depends(): array {
		return [ 'carousel-slider-frontend' ];
	}

	/**
	 * @inheritDoc
	 */
	public function get_style_depends(): array {
		return [ 'carousel-slider-frontend' ];
	}

	/**
	 * @inheritDoc
	 */
	public function get_keywords(): array {
		return [ 'image', 'photo', 'carousel', 'slider' ];
	}

	/**
	 * @inheritDoc
	 */
	protected function register_controls() {
		$posts   = get_posts( [
			'post_type'   => CAROUSEL_SLIDER_POST_TYPE,
			'post_status' => 'publish',
			'numberposts' => - 1
		] );
		$options = [];
		foreach ( $posts as $post ) {
			$options[ $post->ID ] = $post->post_title;
		}

		$this->start_controls_section( 'content_section', [
			'label' => __( 'Slider Settings', 'carousel-slider' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'slider_id', [
			'label'      => __( 'Choose slider', 'carousel-slider' ),
			'type'       => Controls_Manager::SELECT,
			'input_type' => 'url',
			'options'    => $options
		] );

		$this->add_control( 'site_url', [
			'type'       => Controls_Manager::HIDDEN,
			'input_type' => 'hidden',
			'value'      => site_url()
		] );

		$this->end_controls_section();
	}

	/**
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
	 * @inheritDoc
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$html = '<div class="carousel-slider-elementor-widget">';
		$html .= Frontend::init()->carousel_slide( [ 'id' => intval( $settings['slider_id'] ) ] );
		$html .= '</div>';

		echo $html;
	}
}
