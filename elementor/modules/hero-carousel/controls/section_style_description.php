<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

$this->start_controls_section(
	'section_style_description',
	[
		'label' => __( 'Description', 'carousel-slider' ),
		'tab' => Controls_Manager::TAB_STYLE,
	]
);

$this->add_control(
	'description_spacing',
	[
		'label' => __( 'Spacing', 'carousel-slider' ),
		'type' => Controls_Manager::SLIDER,
		'range' => [
			'px' => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors' => [
			'{{WRAPPER}} .slick-slide-inner .elementor-slide-description:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
		],
	]
);

$this->add_control(
	'description_color',
	[
		'label' => __( 'Text Color', 'carousel-slider' ),
		'type' => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .elementor-slide-description' => 'color: {{VALUE}}',

		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name' => 'description_typography',
		'label' => __( 'Typography', 'carousel-slider' ),
		'scheme' => Scheme_Typography::TYPOGRAPHY_2,
		'selector' => '{{WRAPPER}} .elementor-slide-description',
	]
);

$this->end_controls_section();