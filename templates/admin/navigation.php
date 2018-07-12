<?php

use CarouselSlider\Supports\Utils;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
$this->form->select( array(
	'id'          => '_nav_button',
	'context'     => 'side',
	'input_class' => 'small-text',
	'name'        => esc_html__( 'Show Arrow Nav', 'carousel-slider' ),
	'desc'        => esc_html__( 'Choose when to show arrow navigator.', 'carousel-slider' ),
	'std'         => 'on',
	'options'     => array(
		'off'    => esc_html__( 'Never', 'carousel-slider' ),
		'on'     => esc_html__( 'Mouse Over', 'carousel-slider' ),
		'always' => esc_html__( 'Always', 'carousel-slider' ),
	),
) );
$this->form->text( array(
	'id'          => '_slide_by',
	'context'     => 'side',
	'input_class' => 'small-text',
	'name'        => esc_html__( 'Arrow Steps', 'carousel-slider' ),
	'desc'        => esc_html__( 'Steps to go for each navigation request. Write "page" to slide by page.',
		'carousel-slider' ),
	'std'         => 1
) );
$this->form->select( array(
	'id'          => '_arrow_position',
	'context'     => 'side',
	'input_class' => 'small-text',
	'name'        => esc_html__( 'Arrow Position', 'carousel-slider' ),
	'desc'        => esc_html__( 'Choose where to show arrow. Inside slider or outside slider.', 'carousel-slider' ),
	'std'         => 'outside',
	'options'     => array(
		'outside' => esc_html__( 'outside', 'carousel-slider' ),
		'inside'  => esc_html__( 'Inside', 'carousel-slider' ),
	),
) );
$this->form->number( array(
	'id'          => '_arrow_size',
	'context'     => 'side',
	'input_class' => 'small-text',
	'name'        => esc_html__( 'Arrow Size', 'carousel-slider' ),
	'desc'        => esc_html__( 'Enter arrow size in pixels.', 'carousel-slider' ),
	'std'         => 48
) );

echo '<hr>';

$this->form->select( array(
	'id'          => '_dot_nav',
	'context'     => 'side',
	'input_class' => 'small-text',
	'name'        => esc_html__( 'Show Bullet Nav', 'carousel-slider' ),
	'desc'        => esc_html__( 'Choose when to show bullet navigator.', 'carousel-slider' ),
	'std'         => 'on',
	'options'     => array(
		'off'   => esc_html__( 'Never', 'carousel-slider' ),
		'on'    => esc_html__( 'Always', 'carousel-slider' ),
		'hover' => esc_html__( 'Mouse Over', 'carousel-slider' ),
	),
) );
$this->form->select( array(
	'id'          => '_bullet_position',
	'context'     => 'side',
	'input_class' => 'small-text',
	'name'        => esc_html__( 'Bullet Position', 'carousel-slider' ),
	'desc'        => esc_html__( 'Choose where to show bullets.', 'carousel-slider' ),
	'std'         => 'center',
	'options'     => array(
		'left'   => esc_html__( 'Left', 'carousel-slider' ),
		'center' => esc_html__( 'Center', 'carousel-slider' ),
		'right'  => esc_html__( 'Right', 'carousel-slider' ),
	),
) );
$this->form->number( array(
	'id'          => '_bullet_size',
	'context'     => 'side',
	'input_class' => 'small-text',
	'name'        => esc_html__( 'Bullet Size', 'carousel-slider' ),
	'desc'        => esc_html__( 'Enter bullet size in pixels.', 'carousel-slider' ),
	'std'         => 10
) );
$this->form->select( array(
	'id'          => '_bullet_shape',
	'context'     => 'side',
	'input_class' => 'small-text',
	'name'        => esc_html__( 'Bullet Shape', 'carousel-slider' ),
	'desc'        => esc_html__( 'Choose bullet nav shape.', 'carousel-slider' ),
	'std'         => 'square',
	'options'     => array(
		'square' => esc_html__( 'Square', 'carousel-slider' ),
		'circle' => esc_html__( 'Circle', 'carousel-slider' ),
	),
) );

echo '<hr>';

$this->form->color( array(
	'id'      => '_nav_color',
	'type'    => 'color',
	'context' => 'side',
	'name'    => esc_html__( 'Arrows &amp; Dots Color', 'carousel-slider' ),
	'desc'    => esc_html__( 'Pick a color for navigation and dots.', 'carousel-slider' ),
	'std'     => Utils::get_default_setting( 'nav_color' ),
) );
$this->form->color( array(
	'id'      => '_nav_active_color',
	'type'    => 'color',
	'context' => 'side',
	'name'    => esc_html__( 'Arrows & Dots Hover Color', 'carousel-slider' ),
	'desc'    => esc_html__( 'Pick a color for navigation and dots for active and hover effect.', 'carousel-slider' ),
	'std'     => Utils::get_default_setting( 'nav_active_color' ),
) );