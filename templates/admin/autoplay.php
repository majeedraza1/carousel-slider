<?php

use CarouselSlider\Supports\Form;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

echo Form::field( array(
	'type'             => 'toggle',
	'id'               => '_autoplay',
	'label'            => esc_html__( 'Autoplay', 'carousel-slider' ),
	'description'      => esc_html__( 'Check to enable autoplay', 'carousel-slider' ),
	'default'          => 'on',
	'choices'          => array(
		'on'  => esc_html__( 'Enable', 'carousel-slider' ),
		'off' => esc_html__( 'Disable', 'carousel-slider' ),
	),
	'input_attributes' => array( 'class' => 'small-text', ),
) );

echo Form::field( array(
	'type'             => 'toggle',
	'id'               => '_autoplay_pause',
	'label'            => esc_html__( 'Autoplay Hover Pause', 'carousel-slider' ),
	'description'      => esc_html__( 'Pause autoplay on mouse hover.', 'carousel-slider' ),
	'default'          => 'on',
	'choices'          => array(
		'on'  => esc_html__( 'Enable', 'carousel-slider' ),
		'off' => esc_html__( 'Disable', 'carousel-slider' ),
	),
	'input_attributes' => array( 'class' => 'small-text', ),
) );

echo Form::field( array(
	'type'             => 'number',
	'id'               => '_autoplay_timeout',
	'label'            => esc_html__( 'Autoplay Timeout', 'carousel-slider' ),
	'description'      => esc_html__( 'Autoplay interval timeout in millisecond. Default: 5000', 'carousel-slider' ),
	'default'          => 5000,
	'input_attributes' => array( 'class' => 'small-text', ),
) );

echo Form::field( array(
	'type'             => 'number',
	'id'               => '_autoplay_speed',
	'label'            => esc_html__( 'Autoplay Speed', 'carousel-slider' ),
	'description'      => esc_html__( 'Autoplay speed in millisecond. Default: 500', 'carousel-slider' ),
	'default'          => 500,
	'input_attributes' => array( 'class' => 'small-text', ),
) );
