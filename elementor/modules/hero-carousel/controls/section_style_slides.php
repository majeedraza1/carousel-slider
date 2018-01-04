<?php

use Elementor\Controls_Manager;

$this->start_controls_section(
	'section_style_slides',
	[
		'label' => __( 'Slides', 'carousel-slider' ),
		'tab' => Controls_Manager::TAB_STYLE,
	]
);

$this->add_responsive_control(
	'content_max_width',
	[
		'label' => __( 'Content Width', 'carousel-slider' ),
		'type' => Controls_Manager::SLIDER,
		'range' => [
			'px' => [
				'min' => 0,
				'max' => 1000,
			],
			'%' => [
				'min' => 0,
				'max' => 100,
			],
		],
		'size_units' => [ '%', 'px' ],
		'default' => [
			'size' => '66',
			'unit' => '%',
		],
		'tablet_default' => [
			'unit' => '%',
		],
		'mobile_default' => [
			'unit' => '%',
		],
		'selectors' => [
			'{{WRAPPER}} .elementor-slide-content' => 'max-width: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'slides_padding',
	[
		'label' => __( 'Padding', 'carousel-slider' ),
		'type' => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors' => [
			'{{WRAPPER}} .slick-slide-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_control(
	'slides_horizontal_position',
	[
		'label' => __( 'Horizontal Position', 'carousel-slider' ),
		'type' => Controls_Manager::CHOOSE,
		'label_block' => false,
		'default' => 'center',
		'options' => [
			'left' => [
				'title' => __( 'Left', 'carousel-slider' ),
				'icon' => 'eicon-h-align-left',
			],
			'center' => [
				'title' => __( 'Center', 'carousel-slider' ),
				'icon' => 'eicon-h-align-center',
			],
			'right' => [
				'title' => __( 'Right', 'carousel-slider' ),
				'icon' => 'eicon-h-align-right',
			],
		],
		'prefix_class' => 'elementor--h-position-',
	]
);

$this->add_control(
	'slides_vertical_position',
	[
		'label' => __( 'Vertical Position', 'carousel-slider' ),
		'type' => Controls_Manager::CHOOSE,
		'label_block' => false,
		'default' => 'middle',
		'options' => [
			'top' => [
				'title' => __( 'Top', 'carousel-slider' ),
				'icon' => 'eicon-v-align-top',
			],
			'middle' => [
				'title' => __( 'Middle', 'carousel-slider' ),
				'icon' => 'eicon-v-align-middle',
			],
			'bottom' => [
				'title' => __( 'Bottom', 'carousel-slider' ),
				'icon' => 'eicon-v-align-bottom',
			],
		],
		'prefix_class' => 'elementor--v-position-',
	]
);

$this->add_control(
	'slides_text_align',
	[
		'label' => __( 'Text Align', 'carousel-slider' ),
		'type' => Controls_Manager::CHOOSE,
		'label_block' => false,
		'options' => [
			'left' => [
				'title' => __( 'Left', 'carousel-slider' ),
				'icon' => 'fa fa-align-left',
			],
			'center' => [
				'title' => __( 'Center', 'carousel-slider' ),
				'icon' => 'fa fa-align-center',
			],
			'right' => [
				'title' => __( 'Right', 'carousel-slider' ),
				'icon' => 'fa fa-align-right',
			],
		],
		'default' => 'center',
		'selectors' => [
			'{{WRAPPER}} .slick-slide-inner' => 'text-align: {{VALUE}}',
		],
	]
);

$this->end_controls_section();