<?php

use CarouselSlider\Supports\Metabox;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

echo Metabox::field( array(
	'type'             => 'select',
	'id'               => '_autoplay',
	'context'          => 'side',
	'label'            => esc_html__( 'Autoplay', 'carousel-slider' ),
	'description'      => esc_html__( 'Check to enable autoplay', 'carousel-slider' ),
	'default'          => 'on',
	'choices'          => array(
		'on'  => esc_html__( 'Enable', 'carousel-slider' ),
		'off' => esc_html__( 'Disable', 'carousel-slider' ),
	),
	'input_attributes' => array( 'class' => 'small-text', ),
) );

echo Metabox::field( array(
	'type'             => 'select',
	'id'               => '_autoplay_pause',
	'context'          => 'side',
	'label'            => esc_html__( 'Autoplay Hover Pause', 'carousel-slider' ),
	'description'      => esc_html__( 'Pause autoplay on mouse hover.', 'carousel-slider' ),
	'default'          => 'on',
	'choices'          => array(
		'on'  => esc_html__( 'Enable', 'carousel-slider' ),
		'off' => esc_html__( 'Disable', 'carousel-slider' ),
	),
	'input_attributes' => array( 'class' => 'small-text', ),
) );

echo Metabox::field( array(
	'type'             => 'number',
	'id'               => '_autoplay_timeout',
	'context'          => 'side',
	'label'            => esc_html__( 'Autoplay Timeout', 'carousel-slider' ),
	'description'      => esc_html__( 'Autoplay interval timeout in millisecond. Default: 5000', 'carousel-slider' ),
	'default'          => 5000,
	'input_attributes' => array( 'class' => 'small-text', ),
) );

echo Metabox::field( array(
	'type'             => 'number',
	'id'               => '_autoplay_speed',
	'context'          => 'side',
	'label'            => esc_html__( 'Autoplay Speed', 'carousel-slider' ),
	'description'      => esc_html__( 'Autoplay speed in millisecond. Default: 500', 'carousel-slider' ),
	'default'          => 500,
	'input_attributes' => array( 'class' => 'small-text', ),
) );
