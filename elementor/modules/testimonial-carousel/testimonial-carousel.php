<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Carousel_Slider_Testimonial_Slider extends Widget_Base {

	private $slide_prints_count = 0;

	/**
	 * Retrieve the name.
	 *
	 * @return string The name.
	 */
	public function get_name() {
		return 'carousel-slider-testimonial-carousel';
	}

	/**
	 * Retrieve element title.
	 *
	 * @return string Element title.
	 */
	public function get_title() {
		return __( 'Testimonial Carousel', 'carousel-slider' );
	}

	/**
	 * Retrieve element icon.
	 *
	 * @return string Element icon.
	 */
	public function get_icon() {
		return 'eicon-testimonial-carousel';
	}

	/**
	 * Retrieve widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'carousel-slider-elements' );
	}

	/**
	 * Retrieve script dependencies.
	 * Get the list of script dependencies the element requires.
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'imagesloaded', 'jquery-swiper' ];
	}

	/**
	 * Register controls.
	 *
	 * Used to add new controls to any element type. For example, external
	 * developers use this method to register controls in a widget.
	 *
	 * Should be inherited and register new controls using `add_control()`,
	 * `add_responsive_control()` and `add_group_control()`, inside control
	 * wrappers like `start_controls_section()`, `start_controls_tabs()` and
	 * `start_controls_tab()`.
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_slides',
			[
				'label' => __( 'Slides', 'elementor-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$this->add_repeater_controls( $repeater );

		$this->add_control(
			'slides',
			[
				'label'   => __( 'Slides', 'elementor-pro' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_fields(),
				'default' => $this->get_repeater_defaults(),
			]
		);

		$this->add_control(
			'skin',
			[
				'label'        => __( 'Skin', 'elementor-pro' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'default',
				'options'      => [
					'default' => __( 'Default', 'elementor-pro' ),
					'bubble'  => __( 'Bubble', 'elementor-pro' ),
				],
				'prefix_class' => 'elementor-testimonial--skin-',
				'render_type'  => 'template',
			]
		);

		$this->add_control(
			'layout',
			[
				'label'        => __( 'Layout', 'elementor-pro' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'image_inline',
				'options'      => [
					'image_inline'  => __( 'Image Inline', 'elementor-pro' ),
					'image_stacked' => __( 'Image Stacked', 'elementor-pro' ),
					'image_above'   => __( 'Image Above', 'elementor-pro' ),
					'image_left'    => __( 'Image Left', 'elementor-pro' ),
					'image_right'   => __( 'Image Right', 'elementor-pro' ),
				],
				'prefix_class' => 'elementor-testimonial--layout-',
				'render_type'  => 'template',
			]
		);

		$this->add_control(
			'alignment',
			[
				'label'        => __( 'Alignment', 'elementor-pro' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'default'      => 'center',
				'options'      => [
					'left'   => [
						'title' => __( 'Left', 'elementor-pro' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor-pro' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'elementor-pro' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'prefix_class' => 'elementor-testimonial--align-',
			]
		);

		$slides_per_view = range( 1, 10 );
		$slides_per_view = array_combine( $slides_per_view, $slides_per_view );

		$this->add_responsive_control(
			'slides_per_view',
			[
				'type'               => Controls_Manager::SELECT,
				'label'              => __( 'Slides Per View', 'elementor-pro' ),
				'options'            => [ '' => __( 'Default', 'elementor-pro' ) ] + $slides_per_view,
				'condition'          => null,
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'type'       => Controls_Manager::SLIDER,
				'label'      => __( 'Width', 'elementor-pro' ),
				'range'      => [
					'px' => [
						'min' => 100,
						'max' => 1140,
					],
					'%'  => [
						'min' => 50,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default'    => [
					'unit' => '%',
				],
				'selectors'  => [
					'{{WRAPPER}}.elementor-arrows-yes .elementor-main-swiper' => 'width: calc( {{SIZE}}{{UNIT}} - 40px )',
					'{{WRAPPER}} .elementor-main-swiper'                      => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_options',
			[
				'label' => __( 'Additional Options', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'show_arrows',
			[
				'type'               => Controls_Manager::SWITCHER,
				'label'              => __( 'Arrows', 'elementor-pro' ),
				'default'            => 'yes',
				'label_off'          => __( 'Hide', 'elementor-pro' ),
				'label_on'           => __( 'Show', 'elementor-pro' ),
				'frontend_available' => true,
				'prefix_class'       => 'elementor-arrows-',
				'render_type'        => 'template',
			]
		);

		$this->add_control(
			'pagination',
			[
				'label'              => __( 'Pagination', 'elementor-pro' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'bullets',
				'options'            => [
					''         => __( 'None', 'elementor-pro' ),
					'bullets'  => __( 'Dots', 'elementor-pro' ),
					'fraction' => __( 'Fraction', 'elementor-pro' ),
					'progress' => __( 'Progress', 'elementor-pro' ),
				],
				'prefix_class'       => 'elementor-pagination-type-',
				'render_type'        => 'template',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'speed',
			[
				'label'              => __( 'Transition Duration', 'elementor-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 500,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'              => __( 'Autoplay', 'elementor-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'separator'          => 'before',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label'              => __( 'Autoplay Speed', 'elementor-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 5000,
				'condition'          => [
					'autoplay' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'pause_on_interaction',
			[
				'label'              => __( 'Pause on Interaction', 'elementor-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'condition'          => [
					'autoplay' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image_size',
				'default'   => 'full',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slides_style',
			[
				'label' => __( 'Slides', 'elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label'              => __( 'Space Between', 'elementor-pro' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => [
					'px' => [
						'max' => 50,
					],
				],
				'desktop_default'    => [
					'size' => 10,
				],
				'tablet_default'     => [
					'size' => 10,
				],
				'mobile_default'     => [
					'size' => 10,
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'slide_background_color',
			[
				'label'     => __( 'Background Color', 'elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-main-swiper .swiper-slide' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'slide_border_size',
			[
				'label'     => __( 'Border Size', 'elementor-pro' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .elementor-main-swiper .swiper-slide' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'slide_border_color',
			[
				'label'     => __( 'Border Color', 'elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-main-swiper .swiper-slide' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'slide_padding',
			[
				'label'     => __( 'Padding', 'elementor-pro' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .elementor-main-swiper .swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'slide_border_radius',
			[
				'label'      => __( 'Border Radius', 'elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'%' => [
						'max' => 50,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .elementor-main-swiper .swiper-slide' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			[
				'label' => __( 'Content', 'elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_gap',
			[
				'label'     => __( 'Gap', 'elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-testimonial--layout-image_inline .elementor-testimonial__footer,
					{{WRAPPER}}.elementor-testimonial--layout-image_stacked .elementor-testimonial__footer' => 'margin-top: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-testimonial--layout-image_above .elementor-testimonial__footer'  => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-testimonial--layout-image_left .elementor-testimonial__footer'   => 'padding-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-testimonial--layout-image_right .elementor-testimonial__footer'  => 'padding-left: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'     => __( 'Text Color', 'elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-testimonial__text' => 'color: {{VALUE}}',
				],
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'label'    => __( 'Typography', 'elementor-pro' ),
				'selector' => '{{WRAPPER}} .elementor-testimonial__text',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control(
			'name_title_style',
			[
				'label'     => __( 'Name', 'elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'name_color',
			[
				'label'     => __( 'Text Color', 'elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-testimonial__name' => 'color: {{VALUE}}',
				],
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'name_typography',
				'label'    => __( 'Typography', 'elementor-pro' ),
				'selector' => '{{WRAPPER}} .elementor-testimonial__name',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'heading_title_style',
			[
				'label'     => __( 'Title', 'elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Text Color', 'elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-testimonial__title' => 'color: {{VALUE}}',
				],
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'elementor-pro' ),
				'selector' => '{{WRAPPER}} .elementor-testimonial__title',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_2,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			[
				'label' => __( 'Image', 'elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_size',
			[
				'label'     => __( 'Image Size', 'elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-testimonial__image img'                                                                   => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-testimonial--layout-image_left .elementor-testimonial__content:after,
					 {{WRAPPER}}.elementor-testimonial--layout-image_right .elementor-testimonial__content:after' => 'top: calc( {{text_padding.TOP}}{{text_padding.UNIT}} + ({{SIZE}}{{UNIT}} / 2) - 8px );',

					'body:not(.rtl) {{WRAPPER}}.elementor-testimonial--layout-image_stacked:not(.elementor-testimonial--align-center):not(.elementor-testimonial--align-right) .elementor-testimonial__content:after,
					 body:not(.rtl) {{WRAPPER}}.elementor-testimonial--layout-image_inline:not(.elementor-testimonial--align-center):not(.elementor-testimonial--align-right) .elementor-testimonial__content:after,
					 {{WRAPPER}}.elementor-testimonial--layout-image_stacked.elementor-testimonial--align-left .elementor-testimonial__content:after,
					 {{WRAPPER}}.elementor-testimonial--layout-image_inline.elementor-testimonial--align-left .elementor-testimonial__content:after' => 'left: calc( {{text_padding.LEFT}}{{text_padding.UNIT}} + ({{SIZE}}{{UNIT}} / 2) - 8px ); right:auto;',

					'body.rtl {{WRAPPER}}.elementor-testimonial--layout-image_stacked:not(.elementor-testimonial--align-center):not(.elementor-testimonial--align-left) .elementor-testimonial__content:after,
					 body.rtl {{WRAPPER}}.elementor-testimonial--layout-image_inline:not(.elementor-testimonial--align-center):not(.elementor-testimonial--align-left) .elementor-testimonial__content:after,
					 {{WRAPPER}}.elementor-testimonial--layout-image_stacked.elementor-testimonial--align-right .elementor-testimonial__content:after,
					 {{WRAPPER}}.elementor-testimonial--layout-image_inline.elementor-testimonial--align-right .elementor-testimonial__content:after' => 'right: calc( {{text_padding.RIGHT}}{{text_padding.UNIT}} + ({{SIZE}}{{UNIT}} / 2) - 8px ); left:auto;',

					'body:not(.rtl) {{WRAPPER}}.elementor-testimonial--layout-image_above:not(.elementor-testimonial--align-center):not(.elementor-testimonial--align-right) .elementor-testimonial__content:after,
					 {{WRAPPER}}.elementor-testimonial--layout-image_above.elementor-testimonial--align-left .elementor-testimonial__content:after' => 'left: calc( {{text_padding.LEFT}}{{text_padding.UNIT}} + ({{SIZE}}{{UNIT}} / 2) - 8px ); right:auto;',

					'body.rtl {{WRAPPER}}.elementor-testimonial--layout-image_above:not(.elementor-testimonial--align-center):not(.elementor-testimonial--align-left) .elementor-testimonial__content:after,
					 {{WRAPPER}}.elementor-testimonial--layout-image_above.elementor-testimonial--align-right .elementor-testimonial__content:after' => 'right: calc( {{text_padding.RIGHT}}{{text_padding.UNIT}} + ({{SIZE}}{{UNIT}} / 2) - 8px ); left:auto;',
				],
			]
		);

		$this->add_responsive_control(
			'image_gap',
			[
				'label'     => __( 'Image Gap', 'elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'body.rtl {{WRAPPER}}.elementor-testimonial--layout-image_inline.elementor-testimonial--align-left .elementor-testimonial__image + cite, 
					 body.rtl {{WRAPPER}}.elementor-testimonial--layout-image_above.elementor-testimonial--align-left .elementor-testimonial__image + cite,
					 body:not(.rtl) {{WRAPPER}}.elementor-testimonial--layout-image_inline .elementor-testimonial__image + cite, 
					 body:not(.rtl) {{WRAPPER}}.elementor-testimonial--layout-image_above .elementor-testimonial__image + cite' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0;',

					'body:not(.rtl) {{WRAPPER}}.elementor-testimonial--layout-image_inline.elementor-testimonial--align-right .elementor-testimonial__image + cite, 
					 body:not(.rtl) {{WRAPPER}}.elementor-testimonial--layout-image_above.elementor-testimonial--align-right .elementor-testimonial__image + cite,
					 body.rtl {{WRAPPER}}.elementor-testimonial--layout-image_inline .elementor-testimonial__image + cite,
					 body.rtl {{WRAPPER}}.elementor-testimonial--layout-image_above .elementor-testimonial__image + cite' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left:0;',

					'{{WRAPPER}}.elementor-testimonial--layout-image_stacked .elementor-testimonial__image + cite, 
					 {{WRAPPER}}.elementor-testimonial--layout-image_left .elementor-testimonial__image + cite,
					 {{WRAPPER}}.elementor-testimonial--layout-image_right .elementor-testimonial__image + cite' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'image_border',
			[
				'label'        => __( 'Border', 'elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'selectors'    => [
					'{{WRAPPER}} .elementor-testimonial__image img' => 'border-style: solid',
				],
			]
		);

		$this->add_control(
			'image_border_color',
			[
				'label'     => __( 'Border Color', 'elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => [
					'{{WRAPPER}} .elementor-testimonial__image img' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'image_border' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_border_width',
			[
				'label'     => __( 'Border Width', 'elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-testimonial__image img' => 'border-width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'image_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label'     => __( 'Border Radius', 'elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .elementor-testimonial__image img' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_navigation',
			[
				'label' => __( 'Navigation', 'elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_arrows',
			[
				'label'     => __( 'Arrows', 'elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'none',
			]
		);

		$this->add_control(
			'arrows_size',
			[
				'label'     => __( 'Size', 'elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 20,
				],
				'range'     => [
					'px' => [
						'min' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'arrows_color',
			[
				'label'     => __( 'Color', 'elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_pagination',
			[
				'label'     => __( 'Pagination', 'elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'pagination!' => '',
				],
			]
		);

		$this->add_control(
			'pagination_size',
			[
				'label'     => __( 'Size', 'elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet'                                => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .swiper-container-horizontal .swiper-pagination-progress' => 'height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .swiper-pagination-fraction'                              => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'pagination!' => '',
				],
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label'     => __( 'Color', 'elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet-active, {{WRAPPER}} .swiper-pagination-progressbar' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .swiper-pagination-fraction'                                                  => 'color: {{VALUE}}',
				],
				'condition' => [
					'pagination!' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render element.
	 * Generates the final HTML on the frontend.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_active_settings();

		$_desktop              = ! empty( $settings['slides_per_view'] ) ? intval( $settings['slides_per_view'] ) : 1;
		$_tablet               = ! empty( $settings['slides_per_view_tablet'] ) ? intval( $settings['slides_per_view_tablet'] ) : $_desktop;
		$_mobile               = ! empty( $settings['slides_per_view_mobile'] ) ? intval( $settings['slides_per_view_mobile'] ) : $_tablet;
		$_show_arrows          = ( isset( $settings['show_arrows'] ) && 'yes' == $settings['show_arrows'] ) ? true : false;
		$_speed                = ! empty( $settings['speed'] ) ? intval( $settings['speed'] ) : 500;
		$_autoplay             = ( isset( $settings['autoplay'] ) && 'yes' == $settings['autoplay'] ) ? true : false;
		$_autoplay_speed       = ! empty( $settings['autoplay_speed'] ) ? intval( $settings['autoplay_speed'] ) : 5000;
		$_pause_on_interaction = ( isset( $settings['pause_on_interaction'] ) && 'yes' == $settings['pause_on_interaction'] ) ? true : false;


		$slides_per_view = [
			'desktop'              => $_desktop,
			'tablet'               => $_tablet,
			'mobile'               => $_mobile,
			'show_arrows'          => $_show_arrows,
			'speed'                => $_speed,
			'autoplay'             => $_autoplay,
			'autoplay_speed'       => $_autoplay_speed,
			'pause_on_interaction' => $_pause_on_interaction,
		];

		$default_settings = [
			'container_class' => 'elementor-main-swiper',
			'video_play_icon' => true,
		];

		$settings = array_merge( $default_settings, $settings );

		$slides_count = count( $settings['slides'] );
		?>
        <div class="elementor-swiper">
            <div class="<?php echo esc_attr( $settings['container_class'] ); ?> swiper-container">
                <div class="swiper-wrapper">
					<?php
					foreach ( $settings['slides'] as $index => $slide ) :
						$this->slide_prints_count ++;
						?>
                        <div class="swiper-slide">
							<?php $this->print_slide( $slide, $settings,
								'slide-' . $index . '-' . $this->slide_prints_count ); ?>
                        </div>
					<?php endforeach; ?>
                </div>
				<?php if ( 1 < $slides_count ) : ?>
					<?php if ( $settings['pagination'] ) : ?>
                        <div class="swiper-pagination"></div>
					<?php endif; ?>
					<?php if ( $settings['show_arrows'] ) : ?>
                        <div class="elementor-swiper-button elementor-swiper-button-prev">
                            <i class="eicon-chevron-left"></i>
                        </div>
                        <div class="elementor-swiper-button elementor-swiper-button-next">
                            <i class="eicon-chevron-right"></i>
                        </div>
					<?php endif; ?>
				<?php endif; ?>
            </div>
        </div>
		<?php
	}

	/**
	 * Render element output in the editor.
	 * Used to generate the live preview, using a Backbone JavaScript template.
	 *
	 * @access protected
	 */
	protected function _content_template() {
	}

	protected function add_repeater_controls( Repeater $repeater ) {
		$repeater->add_control(
			'content',
			[
				'label' => __( 'Content', 'elementor-pro' ),
				'type'  => Controls_Manager::TEXTAREA,
			]
		);

		$repeater->add_control(
			'image',
			[
				'label' => __( 'Image', 'elementor-pro' ),
				'type'  => Controls_Manager::MEDIA,
			]
		);

		$repeater->add_control(
			'name',
			[
				'label'   => __( 'Name', 'elementor-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Click Here', 'elementor-pro' ),
			]
		);

		$repeater->add_control(
			'title',
			[
				'label'   => __( 'Title', 'elementor-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Click Here', 'elementor-pro' ),
			]
		);
	}


	protected function print_slide( array $slide, array $settings, $element_key ) {
		$this->add_render_attribute( $element_key . '-testimonial', [
			'class' => 'elementor-testimonial',
		] );
		?>

        <div <?php echo $this->get_render_attribute_string( $element_key . '-testimonial' ); ?>>
			<?php if ( $slide['content'] ) : ?>
                <div class="elementor-testimonial__content">
                    <div class="elementor-testimonial__text">
						<?php echo $slide['content']; ?>
                    </div>
					<?php echo $this->print_cite( $slide, 'outside' ); ?>
                </div>
			<?php endif; ?>
            <div class="elementor-testimonial__footer">
				<?php if ( $slide['image']['url'] ) : ?>
                    <div class="elementor-testimonial__image">
                        <img src="<?php echo $this->get_slide_image_url( $slide, $settings ); ?>">
                    </div>
				<?php endif; ?>
				<?php echo $this->print_cite( $slide, 'inside' ); ?>
            </div>
        </div>
		<?php
	}

	protected function get_repeater_defaults() {
		$placeholder_image_src = Utils::get_placeholder_image_src();

		return [
			[
				'content' => __( 'I am slide content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
					'elementor-pro' ),
				'name'    => __( 'John Doe', 'elementor-pro' ),
				'title'   => __( 'CEO', 'elementor-pro' ),
				'image'   => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'content' => __( 'I am slide content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
					'elementor-pro' ),
				'name'    => __( 'John Doe', 'elementor-pro' ),
				'title'   => __( 'CEO', 'elementor-pro' ),
				'image'   => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'content' => __( 'I am slide content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
					'elementor-pro' ),
				'name'    => __( 'John Doe', 'elementor-pro' ),
				'title'   => __( 'CEO', 'elementor-pro' ),
				'image'   => [
					'url' => $placeholder_image_src,
				],
			],
		];
	}

	private function print_cite( $slide, $location ) {
		if ( empty( $slide['name'] ) && empty( $slide['title'] ) ) {
			return '';
		}

		$skin              = $this->get_settings( 'skin' );
		$layout            = 'bubble' === $skin ? 'image_inline' : $this->get_settings( 'layout' );
		$locations_outside = [ 'image_above', 'image_right', 'image_left' ];
		$locations_inside  = [ 'image_inline', 'image_stacked' ];

		$print_outside = ( 'outside' === $location && in_array( $layout, $locations_outside ) );
		$print_inside  = ( 'inside' === $location && in_array( $layout, $locations_inside ) );

		$html = '';
		if ( $print_outside || $print_inside ) {
			$html = '<cite class="elementor-testimonial__cite">';
			if ( ! empty( $slide['name'] ) ) {
				$html .= '<span class="elementor-testimonial__name">' . $slide['name'] . '</span>';
			}
			if ( ! empty( $slide['title'] ) ) {
				$html .= '<span class="elementor-testimonial__title">' . $slide['title'] . '</span>';
			}
			$html .= '</cite>';
		}

		return $html;
	}

	protected function get_slide_image_url( $slide, array $settings ) {
		$image_url = Group_Control_Image_Size::get_attachment_image_src( $slide['image']['id'], 'image_size',
			$settings );

		if ( ! $image_url ) {
			$image_url = $slide['image']['url'];
		}

		return $image_url;
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Carousel_Slider_Testimonial_Slider() );