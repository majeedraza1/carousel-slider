<?php

use CarouselSlider\Supports\Form;
use CarouselSlider\Supports\Utils;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

echo Form::field( array(
	'type'             => 'buttonset',
	'id'               => '_nav_button',
	'label'            => esc_html__( 'Show Arrow Nav', 'carousel-slider' ),
	'description'      => esc_html__( 'Choose when to show arrow navigator.', 'carousel-slider' ),
	'default'          => 'on',
	'choices'          => array(
		'off'    => esc_html__( 'Never', 'carousel-slider' ),
		'on'     => esc_html__( 'Mouse Over', 'carousel-slider' ),
		'always' => esc_html__( 'Always', 'carousel-slider' ),
	),
	'input_attributes' => array( 'class' => 'small-text', ),
) );

echo Form::field( array(
	'type'             => 'text',
	'id'               => '_slide_by',
	'label'            => esc_html__( 'Arrow Steps', 'carousel-slider' ),
	'description'      => esc_html__( 'Steps to go for each navigation request. Write "page" to slide by page.',
		'carousel-slider' ),
	'default'          => 1,
	'input_attributes' => array( 'class' => 'small-text', ),
) );

echo Form::field( array(
	'type'             => 'buttonset',
	'id'               => '_arrow_position',
	'label'            => esc_html__( 'Arrow Position', 'carousel-slider' ),
	'description'      => esc_html__( 'Choose where to show arrow. Inside slider or outside slider.', 'carousel-slider' ),
	'default'          => 'outside',
	'choices'          => array(
		'outside' => esc_html__( 'outside', 'carousel-slider' ),
		'inside'  => esc_html__( 'Inside', 'carousel-slider' ),
	),
	'input_attributes' => array( 'class' => 'small-text', ),
) );

echo Form::field( array(
	'type'             => 'number',
	'id'               => '_arrow_size',
	'label'            => esc_html__( 'Arrow Size', 'carousel-slider' ),
	'description'      => esc_html__( 'Enter arrow size in pixels.', 'carousel-slider' ),
	'default'          => 48,
	'input_attributes' => array( 'class' => 'small-text', ),
) );

echo '<hr>';

echo Form::field( array(
	'type'             => 'buttonset',
	'id'               => '_dot_nav',
	'label'            => esc_html__( 'Show Bullet Nav', 'carousel-slider' ),
	'description'      => esc_html__( 'Choose when to show bullet navigator.', 'carousel-slider' ),
	'default'          => 'on',
	'choices'          => array(
		'off'   => esc_html__( 'Never', 'carousel-slider' ),
		'on'    => esc_html__( 'Always', 'carousel-slider' ),
		'hover' => esc_html__( 'Mouse Over', 'carousel-slider' ),
	),
	'input_attributes' => array( 'class' => 'small-text', ),
) );

echo Form::field( array(
	'type'             => 'buttonset',
	'id'               => '_bullet_position',
	'label'            => esc_html__( 'Bullet Position', 'carousel-slider' ),
	'description'      => esc_html__( 'Choose where to show bullets.', 'carousel-slider' ),
	'default'          => 'center',
	'choices'          => array(
		'left'   => esc_html__( 'Left', 'carousel-slider' ),
		'center' => esc_html__( 'Center', 'carousel-slider' ),
		'right'  => esc_html__( 'Right', 'carousel-slider' ),
	),
	'input_attributes' => array( 'class' => 'small-text', ),
) );

echo Form::field( array(
	'type'             => 'number',
	'id'               => '_bullet_size',
	'label'            => esc_html__( 'Bullet Size', 'carousel-slider' ),
	'description'      => esc_html__( 'Enter bullet size in pixels.', 'carousel-slider' ),
	'default'          => 10,
	'input_attributes' => array( 'class' => 'small-text', ),
) );

echo Form::field( array(
	'type'             => 'buttonset',
	'id'               => '_bullet_shape',
	'label'            => esc_html__( 'Bullet Shape', 'carousel-slider' ),
	'description'      => esc_html__( 'Choose bullet nav shape.', 'carousel-slider' ),
	'default'          => 'square',
	'choices'          => array(
		'square' => esc_html__( 'Square', 'carousel-slider' ),
		'circle' => esc_html__( 'Circle', 'carousel-slider' ),
	),
	'input_attributes' => array( 'class' => 'small-text', ),
) );

echo '<hr>';

echo Form::field( array(
	'type'        => 'color',
	'id'          => '_nav_color',
	'label'       => esc_html__( 'Arrows &amp; Dots Color', 'carousel-slider' ),
	'description' => esc_html__( 'Pick a color for navigation and dots.', 'carousel-slider' ),
	'default'     => Utils::get_default_setting( 'nav_color' ),
) );

echo Form::field( array(
	'type'        => 'color',
	'id'          => '_nav_active_color',
	'label'       => esc_html__( 'Arrows & Dots Hover Color', 'carousel-slider' ),
	'description' => esc_html__( 'Pick a color for navigation and dots for active and hover effect.', 'carousel-slider' ),
	'default'     => Utils::get_default_setting( 'nav_active_color' ),
) );
