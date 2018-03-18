<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$this->form->select( array(
	'id'          => '_autoplay',
	'context'     => 'side',
	'input_class' => 'small-text',
	'name'        => esc_html__( 'Autoplay', 'carousel-slider' ),
	'desc'        => esc_html__( 'Check to enable autoplay', 'carousel-slider' ),
	'std'         => 'on',
	'options'     => array(
		'on'  => esc_html__( 'Enable', 'carousel-slider' ),
		'off' => esc_html__( 'Disable', 'carousel-slider' ),
	),
) );
$this->form->select( array(
	'id'          => '_autoplay_pause',
	'context'     => 'side',
	'input_class' => 'small-text',
	'name'        => esc_html__( 'Autoplay Hover Pause', 'carousel-slider' ),
	'desc'        => esc_html__( 'Pause autoplay on mouse hover.', 'carousel-slider' ),
	'std'         => 'on',
	'options'     => array(
		'on'  => esc_html__( 'Enable', 'carousel-slider' ),
		'off' => esc_html__( 'Disable', 'carousel-slider' ),
	),
) );
$this->form->number( array(
	'id'          => '_autoplay_timeout',
	'context'     => 'side',
	'input_class' => 'small-text',
	'name'        => esc_html__( 'Autoplay Timeout', 'carousel-slider' ),
	'desc'        => esc_html__( 'Autoplay interval timeout in millisecond. Default: 5000', 'carousel-slider' ),
	'std'         => 5000
) );

$this->form->number( array(
	'id'          => '_autoplay_speed',
	'context'     => 'side',
	'input_class' => 'small-text',
	'name'        => esc_html__( 'Autoplay Speed', 'carousel-slider' ),
	'desc'        => esc_html__( 'Autoplay speed in millisecond. Default: 500', 'carousel-slider' ),
	'std'         => 500
) );
