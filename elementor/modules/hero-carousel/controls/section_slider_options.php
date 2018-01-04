<?php

use Elementor\Controls_Manager;

$this->start_controls_section(
	'section_slider_options',
	[
		'label' => __( 'Slider Options', 'carousel-slider' ),
		'type' => Controls_Manager::SECTION,
	]
);

$this->add_control(
	'navigation',
	[
		'label' => __( 'Navigation', 'carousel-slider' ),
		'type' => Controls_Manager::SELECT,
		'default' => 'both',
		'options' => [
			'both' => __( 'Arrows and Dots', 'carousel-slider' ),
			'arrows' => __( 'Arrows', 'carousel-slider' ),
			'dots' => __( 'Dots', 'carousel-slider' ),
			'none' => __( 'None', 'carousel-slider' ),
		],
	]
);

$this->add_control(
	'pause_on_hover',
	[
		'label' => __( 'Pause on Hover', 'carousel-slider' ),
		'type' => Controls_Manager::SWITCHER,
		'return_value' => 'yes',
		'default' => 'yes',
	]
);

$this->add_control(
	'autoplay',
	[
		'label' => __( 'Autoplay', 'carousel-slider' ),
		'type' => Controls_Manager::SWITCHER,
		'return_value' => 'yes',
		'default' => 'yes',
	]
);

$this->add_control(
	'autoplay_speed',
	[
		'label' => __( 'Autoplay Speed', 'carousel-slider' ),
		'type' => Controls_Manager::NUMBER,
		'default' => 5000,
		'condition' => [
			'autoplay' => 'yes',
		],
		'selectors' => [
			'{{WRAPPER}} .slick-slide-bg' => 'animation-duration: calc({{VALUE}}ms*1.2); transition-duration: calc({{VALUE}}ms)',
		],
	]
);

$this->add_control(
	'infinite',
	[
		'label' => __( 'Infinite Loop', 'carousel-slider' ),
		'type' => Controls_Manager::SWITCHER,
		'return_value' => 'yes',
		'default' => 'yes',
	]
);

$this->add_control(
	'transition',
	[
		'label' => __( 'Transition', 'carousel-slider' ),
		'type' => Controls_Manager::SELECT,
		'default' => 'slide',
		'options' => [
			'slide' => __( 'Slide', 'carousel-slider' ),
			'fade' => __( 'Fade', 'carousel-slider' ),
		],
	]
);

$this->add_control(
	'transition_speed',
	[
		'label' => __( 'Transition Speed (ms)', 'carousel-slider' ),
		'type' => Controls_Manager::NUMBER,
		'default' => 500,
	]
);

$this->add_control(
	'content_animation',
	[
		'label' => __( 'Content Animation', 'carousel-slider' ),
		'type' => Controls_Manager::SELECT,
		'default' => 'fadeInUp',
		'options' => [
			'' => __( 'None', 'carousel-slider' ),
			'fadeInDown' => __( 'Down', 'carousel-slider' ),
			'fadeInUp' => __( 'Up', 'carousel-slider' ),
			'fadeInRight' => __( 'Right', 'carousel-slider' ),
			'fadeInLeft' => __( 'Left', 'carousel-slider' ),
			'zoomIn' => __( 'Zoom', 'carousel-slider' ),
		],
	]
);

$this->end_controls_section();