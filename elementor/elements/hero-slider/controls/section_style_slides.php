<?php

use Elementor\Controls_Manager;

$this->start_controls_section(
	'section_style_slides',
	array(
		'label' => __( 'Slides', 'text_domain' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	)
);

$this->add_responsive_control(
	'content_max_width',
	array(
		'label'          => __( 'Content Width', 'text_domain' ),
		'type'           => Controls_Manager::SLIDER,
		'range'          => array(
			'px' => array(
				'min' => 0,
				'max' => 1000,
			),
			'%'  => array(
				'min' => 0,
				'max' => 100,
			),
		),
		'size_units'     => array( '%', 'px' ),
		'default'        => array(
			'size' => '66',
			'unit' => '%',
		),
		'tablet_default' => array(
			'unit' => '%',
		),
		'mobile_default' => array(
			'unit' => '%',
		),
		'selectors'      => array(
			'{{WRAPPER}} .hero-carousel__cell__content' => 'max-width: {{SIZE}}{{UNIT}};',
		),
	)
);

$this->add_responsive_control(
	'slides_padding',
	array(
		'label'      => __( 'Padding', 'text_domain' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', 'em', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .hero-carousel__cell__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);

$this->add_control(
	'slides_horizontal_position',
	array(
		'label'        => __( 'Horizontal Position', 'text_domain' ),
		'type'         => Controls_Manager::CHOOSE,
		'label_block'  => false,
		'default'      => 'center',
		'options'      => array(
			'left'   => array(
				'title' => __( 'Left', 'text_domain' ),
				'icon'  => 'eicon-h-align-left',
			),
			'center' => array(
				'title' => __( 'Center', 'text_domain' ),
				'icon'  => 'eicon-h-align-center',
			),
			'right'  => array(
				'title' => __( 'Right', 'text_domain' ),
				'icon'  => 'eicon-h-align-right',
			),
		),
		'prefix_class' => 'elementor--h-position-',
	)
);

$this->add_control(
	'slides_vertical_position',
	array(
		'label'        => __( 'Vertical Position', 'text_domain' ),
		'type'         => Controls_Manager::CHOOSE,
		'label_block'  => false,
		'default'      => 'middle',
		'options'      => array(
			'top'    => array(
				'title' => __( 'Top', 'text_domain' ),
				'icon'  => 'eicon-v-align-top',
			),
			'middle' => array(
				'title' => __( 'Middle', 'text_domain' ),
				'icon'  => 'eicon-v-align-middle',
			),
			'bottom' => array(
				'title' => __( 'Bottom', 'text_domain' ),
				'icon'  => 'eicon-v-align-bottom',
			),
		),
		'prefix_class' => 'elementor--v-position-',
	)
);

$this->add_control(
	'slides_text_align',
	array(
		'label'       => __( 'Text Align', 'text_domain' ),
		'type'        => Controls_Manager::CHOOSE,
		'label_block' => false,
		'options'     => array(
			'left'   => array(
				'title' => __( 'Left', 'text_domain' ),
				'icon'  => 'fa fa-align-left',
			),
			'center' => array(
				'title' => __( 'Center', 'text_domain' ),
				'icon'  => 'fa fa-align-center',
			),
			'right'  => array(
				'title' => __( 'Right', 'text_domain' ),
				'icon'  => 'fa fa-align-right',
			),
		),
		'default'     => 'center',
		'selectors'   => array(
			'{{WRAPPER}} .hero-carousel__cell__inner' => 'text-align: {{VALUE}}',
		),
	)
);

$this->end_controls_section();