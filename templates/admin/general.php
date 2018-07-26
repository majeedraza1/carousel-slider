<?php

use CarouselSlider\Supports\Metabox;
use CarouselSlider\Supports\Utils;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

echo Metabox::field( array(
	'type'             => 'image_sizes',
	'id'               => esc_html__( '_image_size', 'carousel-slider' ),
	'label'            => esc_html__( 'Carousel Image size', 'carousel-slider' ),
	'default'          => 'medium_large',
	'description'      => sprintf( esc_html__( 'Choose "original uploaded image" for full size image or your desired image size for carousel image. You can change the default size for thumbnail, medium and large from %1$s Settings >> Media %2$s.', 'carousel-slider' ),
		'<a target="_blank" href="' . get_admin_url() . 'options-media.php">', '</a>'
	),
	'input_attributes' => array( 'class' => 'sp-input-text' ),
) );

echo Metabox::field( array(
	'type'             => 'number',
	'id'               => '_margin_right',
	'label'            => esc_html__( 'Item Spacing.', 'carousel-slider' ),
	'description'      => esc_html__( 'Space between two slide. Enter 10 for 10px', 'carousel-slider' ),
	'default'          => Utils::get_default_setting( 'margin_right' ),
	'input_attributes' => array(
		'class' => 'sp-input-text',
	),
) );

echo Metabox::field( array(
	'type'             => 'number',
	'id'               => '_stage_padding',
	'label'            => esc_html__( 'Stage Padding', 'carousel-slider' ),
	'description'      => esc_html__( 'Add left and right padding on carousel slider stage wrapper.', 'carousel-slider' ),
	'default'          => 0,
	'input_attributes' => array(
		'class' => 'sp-input-text',
	),
) );

echo Metabox::field( array(
	'type'        => 'toggle',
	'id'          => '_lazy_load_image',
	'label'       => esc_html__( 'Lazy Loading', 'carousel-slider' ),
	'description' => esc_html__( 'Enable image with lazy loading.', 'carousel-slider' ),
	'default'     => Utils::get_default_setting( 'lazy_load_image' ),
	'choices'     => array(
		'on'  => esc_html__( 'Enable', 'carousel-slider' ),
		'off' => esc_html__( 'Disable', 'carousel-slider' ),
	),
) );

echo Metabox::field( array(
	'type'        => 'toggle',
	'id'          => '_inifnity_loop',
	'label'       => esc_html__( 'Infinity loop', 'carousel-slider' ),
	'description' => esc_html__( 'Enable or disable loop(circular) of carousel.', 'carousel-slider' ),
	'default'     => 'on',
	'choices'     => array(
		'on'  => esc_html__( 'Enable', 'carousel-slider' ),
		'off' => esc_html__( 'Disable', 'carousel-slider' ),
	),
) );

echo Metabox::field( array(
	'type'        => 'toggle',
	'id'          => '_auto_width',
	'label'       => esc_html__( 'Auto Width', 'carousel-slider' ),
	'description' => esc_html__( 'Set item width according to its content width. Use width style on item to get the result you want. ', 'carousel-slider' ),
	'default'     => 'off',
	'choices'     => array(
		'on'  => esc_html__( 'Enable', 'carousel-slider' ),
		'off' => esc_html__( 'Disable', 'carousel-slider' ),
	),
) );
