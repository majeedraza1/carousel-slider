<?php

use Elementor\Controls_Manager;

$this->start_controls_section(
	'section_style_navigation',
	array(
		'label'     => __( 'Navigation', 'text_domain' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => array(
			'navigation' => array( 'arrows', 'dots', 'both' ),
		),
	)
);

$this->add_control(
	'heading_style_arrows',
	array(
		'label'     => __( 'Arrows', 'text_domain' ),
		'type'      => Controls_Manager::HEADING,
		'separator' => 'before',
		'condition' => array(
			'navigation' => array( 'arrows', 'both' ),
		),
	)
);

$this->add_control(
	'arrows_position',
	array(
		'label'     => __( 'Arrows Position', 'text_domain' ),
		'type'      => Controls_Manager::SELECT,
		'default'   => 'inside',
		'options'   => array(
			'inside'  => __( 'Inside', 'text_domain' ),
			'outside' => __( 'Outside', 'text_domain' ),
		),
		'condition' => array(
			'navigation' => array( 'arrows', 'both' ),
		),
	)
);

$this->add_control(
	'arrows_size',
	array(
		'label'     => __( 'Arrows Size', 'text_domain' ),
		'type'      => Controls_Manager::SLIDER,
		'range'     => array(
			'px' => array(
				'min' => 20,
				'max' => 60,
			),
		),
		'selectors' => array(
			'{{WRAPPER}} .hero-carousel-wrapper .flickity-prev-next-button' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
		),
		'condition' => array(
			'navigation' => array( 'arrows', 'both' ),
		),
	)
);

$this->add_control(
	'arrows_background_color',
	array(
		'label'     => __( 'Arrows Background Color', 'text_domain' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .hero-carousel-wrapper .flickity-prev-next-button' => 'background: {{VALUE}};',
		),
		'condition' => array(
			'navigation' => array( 'arrows', 'both' ),
		),
	)
);

$this->add_control(
	'arrows_color',
	array(
		'label'     => __( 'Arrows Color', 'text_domain' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .hero-carousel-wrapper .flickity-prev-next-button .arrow' => 'fill: {{VALUE}};',
		),
		'condition' => array(
			'navigation' => array( 'arrows', 'both' ),
		),
	)
);

$this->add_control(
	'heading_style_dots',
	array(
		'label'     => __( 'Dots', 'text_domain' ),
		'type'      => Controls_Manager::HEADING,
		'separator' => 'before',
		'condition' => array(
			'navigation' => array( 'dots', 'both' ),
		),
	)
);

$this->add_control(
	'dots_position',
	array(
		'label'     => __( 'Dots Position', 'text_domain' ),
		'type'      => Controls_Manager::SELECT,
		'default'   => 'inside',
		'options'   => array(
			'outside' => __( 'Outside', 'text_domain' ),
			'inside'  => __( 'Inside', 'text_domain' ),
		),
		'condition' => array(
			'navigation' => array( 'dots', 'both' ),
		),
	)
);

$this->add_control(
	'dots_size',
	array(
		'label'     => __( 'Dots Size', 'text_domain' ),
		'type'      => Controls_Manager::SLIDER,
		'range'     => array(
			'px' => array(
				'min' => 5,
				'max' => 15,
			),
		),
		'selectors' => array(
			'{{WRAPPER}} .hero-carousel-wrapper .flickity-page-dots .dot' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
		),
		'condition' => array(
			'navigation' => array( 'dots', 'both' ),
		),
	)
);

$this->add_control(
	'dots_color',
	array(
		'label'     => __( 'Dots Color', 'text_domain' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .hero-carousel-wrapper .flickity-page-dots .dot' => 'background: {{VALUE}};',
		),
		'condition' => array(
			'navigation' => array( 'dots', 'both' ),
		),
	)
);

$this->end_controls_section();