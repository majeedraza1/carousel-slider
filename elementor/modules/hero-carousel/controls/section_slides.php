<?php

use Elementor\Controls_Manager;
use Elementor\Repeater;

$this->start_controls_section(
	'section_slides',
	[
		'label' => __( 'Slides', 'carousel-slider' ),
	]
);

$repeater = new Repeater();

$repeater->start_controls_tabs( 'slides_repeater' );

$repeater->start_controls_tab( 'background', [ 'label' => __( 'Background', 'carousel-slider' ) ] );

$repeater->add_control(
	'background_color',
	[
		'label' => __( 'Color', 'carousel-slider' ),
		'type' => Controls_Manager::COLOR,
		'default' => '#bbbbbb',
		'selectors' => [
			'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-bg' => 'background-color: {{VALUE}}',
		],
	]
);

$repeater->add_control(
	'background_image',
	[
		'label' => _x( 'Image', 'Background Control', 'carousel-slider' ),
		'type' => Controls_Manager::MEDIA,
		'selectors' => [
			'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-bg' => 'background-image: url({{URL}})',
		],
	]
);

$repeater->add_control(
	'background_size',
	[
		'label' => _x( 'Size', 'Background Control', 'carousel-slider' ),
		'type' => Controls_Manager::SELECT,
		'default' => 'cover',
		'options' => [
			'cover' => _x( 'Cover', 'Background Control', 'carousel-slider' ),
			'contain' => _x( 'Contain', 'Background Control', 'carousel-slider' ),
			'auto' => _x( 'Auto', 'Background Control', 'carousel-slider' ),
		],
		'selectors' => [
			'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-bg' => 'background-size: {{VALUE}}',
		],
		'conditions' => [
			'terms' => [
				[
					'name' => 'background_image[url]',
					'operator' => '!=',
					'value' => '',
				],
			],
		],
	]
);

$repeater->add_control(
	'background_ken_burns',
	[
		'label' => __( 'Ken Burns Effect', 'carousel-slider' ),
		'type' => Controls_Manager::SWITCHER,
		'return_value' => 'yes',
		'default' => '',
		'separator' => 'before',
		'conditions' => [
			'terms' => [
				[
					'name' => 'background_image[url]',
					'operator' => '!=',
					'value' => '',
				],
			],
		],
	]
);

$repeater->add_control(
	'zoom_direction',
	[
		'label' => __( 'Zoom Direction', 'carousel-slider' ),
		'type' => Controls_Manager::SELECT,
		'default' => 'in',
		'options' => [
			'in' => __( 'In', 'carousel-slider' ),
			'out' => __( 'Out', 'carousel-slider' ),
		],
		'conditions' => [
			'terms' => [
				[
					'name' => 'background_ken_burns',
					'operator' => '!=',
					'value' => '',
				],
			],
		],
	]
);

$repeater->add_control(
	'background_overlay',
	[
		'label' => __( 'Background Overlay', 'carousel-slider' ),
		'type' => Controls_Manager::SWITCHER,
		'return_value' => 'yes',
		'default' => '',
		'separator' => 'before',
		'conditions' => [
			'terms' => [
				[
					'name' => 'background_image[url]',
					'operator' => '!=',
					'value' => '',
				],
			],
		],
	]
);

$repeater->add_control(
	'background_overlay_color',
	[
		'label' => __( 'Color', 'carousel-slider' ),
		'type' => Controls_Manager::COLOR,
		'default' => 'rgba(0,0,0,0.5)',
		'conditions' => [
			'terms' => [
				[
					'name' => 'background_overlay',
					'operator' => '==',
					'value' => 'yes',
				],
			],
		],
		'selectors' => [
			'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-inner .elementor-background-overlay' => 'background-color: {{VALUE}}',
		],
	]
);

$repeater->end_controls_tab();

$repeater->start_controls_tab( 'content', [ 'label' => __( 'Content', 'carousel-slider' ) ] );

$repeater->add_control(
	'heading',
	[
		'label' => __( 'Title & Description', 'carousel-slider' ),
		'type' => Controls_Manager::TEXT,
		'default' => __( 'Slide Heading', 'carousel-slider' ),
		'label_block' => true,
	]
);

$repeater->add_control(
	'description',
	[
		'label' => __( 'Description', 'carousel-slider' ),
		'type' => Controls_Manager::TEXTAREA,
		'default' => __( 'I am slide content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'carousel-slider' ),
		'show_label' => false,
	]
);

$repeater->add_control(
	'button_text',
	[
		'label' => __( 'Button Text', 'carousel-slider' ),
		'type' => Controls_Manager::TEXT,
		'default' => __( 'Click Here', 'carousel-slider' ),
	]
);

$repeater->add_control(
	'link',
	[
		'label' => __( 'Link', 'carousel-slider' ),
		'type' => Controls_Manager::URL,
		'placeholder' => __( 'http://your-link.com', 'carousel-slider' ),
	]
);

$repeater->add_control(
	'link_click',
	[
		'label' => __( 'Apply Link On', 'carousel-slider' ),
		'type' => Controls_Manager::SELECT,
		'options' => [
			'slide' => __( 'Whole Slide', 'carousel-slider' ),
			'button' => __( 'Button Only', 'carousel-slider' ),
		],
		'default' => 'slide',
		'conditions' => [
			'terms' => [
				[
					'name' => 'link[url]',
					'operator' => '!=',
					'value' => '',
				],
			],
		],
	]
);

$repeater->end_controls_tab();

$repeater->start_controls_tab( 'style', [ 'label' => __( 'Style', 'carousel-slider' ) ] );

$repeater->add_control(
	'custom_style',
	[
		'label' => __( 'Custom', 'carousel-slider' ),
		'type' => Controls_Manager::SWITCHER,
		'return_value' => 'yes',
		'description'   => __( 'Set custom style that will only affect this specific slide.', 'carousel-slider' ),
	]
);

$repeater->add_control(
	'horizontal_position',
	[
		'label' => __( 'Horizontal Position', 'carousel-slider' ),
		'type' => Controls_Manager::CHOOSE,
		'label_block' => false,
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
		'selectors' => [
			'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-inner .elementor-slide-content' => '{{VALUE}}',
		],
		'selectors_dictionary' => [
			'left' => 'margin-right: auto',
			'center' => 'margin: 0 auto',
			'right' => 'margin-left: auto',
		],
		'conditions' => [
			'terms' => [
				[
					'name' => 'custom_style',
					'operator' => '==',
					'value' => 'yes',
				],
			],
		],
	]
);

$repeater->add_control(
	'vertical_position',
	[
		'label' => __( 'Vertical Position', 'carousel-slider' ),
		'type' => Controls_Manager::CHOOSE,
		'label_block' => false,
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
		'selectors' => [
			'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-inner' => 'align-items: {{VALUE}}',
		],
		'selectors_dictionary' => [
			'top' => 'flex-start',
			'middle' => 'center',
			'bottom' => 'flex-end',
		],
		'conditions' => [
			'terms' => [
				[
					'name' => 'custom_style',
					'operator' => '==',
					'value' => 'yes',
				],
			],
		],
	]
);

$repeater->add_control(
	'text_align',
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
		'selectors' => [
			'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-inner' => 'text-align: {{VALUE}}',
		],
		'conditions' => [
			'terms' => [
				[
					'name' => 'custom_style',
					'operator' => '==',
					'value' => 'yes',
				],
			],
		],
	]
);

$repeater->add_control(
	'content_color',
	[
		'label' => __( 'Content Color', 'carousel-slider' ),
		'type' => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-inner .elementor-slide-heading' => 'color: {{VALUE}}',
			'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-inner .elementor-slide-description' => 'color: {{VALUE}}',
			'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-inner .elementor-slide-button' => 'color: {{VALUE}}; border-color: {{VALUE}}',
		],
		'conditions' => [
			'terms' => [
				[
					'name' => 'custom_style',
					'operator' => '==',
					'value' => 'yes',
				],
			],
		],
	]
);

$repeater->end_controls_tab();

$repeater->end_controls_tabs();

$this->add_control(
	'slides',
	[
		'label' => __( 'Slides', 'carousel-slider' ),
		'type' => Controls_Manager::REPEATER,
		'show_label' => true,
		'default' => [
			[
				'heading' => __( 'Slide 1 Heading', 'carousel-slider' ),
				'description' => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'carousel-slider' ),
				'button_text' => __( 'Click Here', 'carousel-slider' ),
				'background_color' => '#833ca3',
			],
			[
				'heading' => __( 'Slide 2 Heading', 'carousel-slider' ),
				'description' => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'carousel-slider' ),
				'button_text' => __( 'Click Here', 'carousel-slider' ),
				'background_color' => '#4054b2',
			],
			[
				'heading' => __( 'Slide 3 Heading', 'carousel-slider' ),
				'description' => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'carousel-slider' ),
				'button_text' => __( 'Click Here', 'carousel-slider' ),
				'background_color' => '#1abc9c',
			],
		],
		'fields' => array_values( $repeater->get_controls() ),
		'title_field' => '{{{ heading }}}',
	]
);

$this->add_responsive_control(
	'slides_height',
	[
		'label' => __( 'Height', 'carousel-slider' ),
		'type' => Controls_Manager::SLIDER,
		'range' => [
			'px' => [
				'min' => 100,
				'max' => 1000,
			],
			'vh' => [
				'min' => 10,
				'max' => 100,
			],
		],
		'default' => [
			'size' => 400,
		],
		'size_units' => [ 'px', 'vh', 'em' ],
		'selectors' => [
			'{{WRAPPER}} .slick-slide' => 'height: {{SIZE}}{{UNIT}};',
		],
		'separator' => 'before',
	]
);

$this->end_controls_section();