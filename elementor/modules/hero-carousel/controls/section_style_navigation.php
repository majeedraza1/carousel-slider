<?php

use Elementor\Controls_Manager;

$this->start_controls_section(
	'section_style_navigation',
	[
		'label' => __( 'Navigation', 'carousel-slider' ),
		'tab' => Controls_Manager::TAB_STYLE,
		'condition' => [
			'navigation' => [ 'arrows', 'dots', 'both' ],
		],
	]
);

$this->add_control(
	'heading_style_arrows',
	[
		'label' => __( 'Arrows', 'carousel-slider' ),
		'type' => Controls_Manager::HEADING,
		'separator' => 'before',
		'condition' => [
			'navigation' => [ 'arrows', 'both' ],
		],
	]
);

$this->add_control(
	'arrows_position',
	[
		'label' => __( 'Arrows Position', 'carousel-slider' ),
		'type' => Controls_Manager::SELECT,
		'default' => 'inside',
		'options' => [
			'inside' => __( 'Inside', 'carousel-slider' ),
			'outside' => __( 'Outside', 'carousel-slider' ),
		],
		'condition' => [
			'navigation' => [ 'arrows', 'both' ],
		],
	]
);

$this->add_control(
	'arrows_size',
	[
		'label' => __( 'Arrows Size', 'carousel-slider' ),
		'type' => Controls_Manager::SLIDER,
		'range' => [
			'px' => [
				'min' => 20,
				'max' => 60,
			],
		],
		'selectors' => [
			'{{WRAPPER}} .elementor-slides-wrapper .slick-slider .slick-prev:before, {{WRAPPER}} .elementor-slides-wrapper .slick-slider .slick-next:before' => 'font-size: {{SIZE}}{{UNIT}};',
		],
		'condition' => [
			'navigation' => [ 'arrows', 'both' ],
		],
	]
);

$this->add_control(
	'arrows_color',
	[
		'label' => __( 'Arrows Color', 'carousel-slider' ),
		'type' => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .elementor-slides-wrapper .slick-slider .slick-prev:before, {{WRAPPER}} .elementor-slides-wrapper .slick-slider .slick-next:before' => 'color: {{VALUE}};',
		],
		'condition' => [
			'navigation' => [ 'arrows', 'both' ],
		],
	]
);

$this->add_control(
	'heading_style_dots',
	[
		'label' => __( 'Dots', 'carousel-slider' ),
		'type' => Controls_Manager::HEADING,
		'separator' => 'before',
		'condition' => [
			'navigation' => [ 'dots', 'both' ],
		],
	]
);

$this->add_control(
	'dots_position',
	[
		'label' => __( 'Dots Position', 'carousel-slider' ),
		'type' => Controls_Manager::SELECT,
		'default' => 'inside',
		'options' => [
			'outside' => __( 'Outside', 'carousel-slider' ),
			'inside' => __( 'Inside', 'carousel-slider' ),
		],
		'condition' => [
			'navigation' => [ 'dots', 'both' ],
		],
	]
);

$this->add_control(
	'dots_size',
	[
		'label' => __( 'Dots Size', 'carousel-slider' ),
		'type' => Controls_Manager::SLIDER,
		'range' => [
			'px' => [
				'min' => 5,
				'max' => 15,
			],
		],
		'selectors' => [
			'{{WRAPPER}} .elementor-slides-wrapper .elementor-slides .slick-dots li button:before' => 'font-size: {{SIZE}}{{UNIT}};',
		],
		'condition' => [
			'navigation' => [ 'dots', 'both' ],
		],
	]
);

$this->add_control(
	'dots_color',
	[
		'label' => __( 'Dots Color', 'carousel-slider' ),
		'type' => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .elementor-slides-wrapper .elementor-slides .slick-dots li button:before' => 'color: {{VALUE}};',
		],
		'condition' => [
			'navigation' => [ 'dots', 'both' ],
		],
	]
);

$this->end_controls_section();