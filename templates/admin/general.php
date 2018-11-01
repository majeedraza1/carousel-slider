<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$this->form->image_sizes( array(
	'id'   => esc_html__( '_image_size', 'carousel-slider' ),
	'name' => esc_html__( 'Carousel Image size', 'carousel-slider' ),
	'desc' => sprintf(
		esc_html__( 'Choose "original uploaded image" for full size image or your desired image size for carousel image. You can change the default size for thumbnail, medium and large from %1$s Settings >> Media %2$s.', 'carousel-slider' ),
		'<a target="_blank" href="' . get_admin_url() . 'options-media.php">', '</a>'
	),
) );
$this->form->select( array(
	'id'      => '_lazy_load_image',
	'name'    => esc_html__( 'Lazy Loading', 'carousel-slider' ),
	'desc'    => esc_html__( 'Enable image with lazy loading.', 'carousel-slider' ),
	'std'     => carousel_slider_default_settings()->lazy_load_image,
	'options' => array(
		'on'  => esc_html__( 'Enable' ),
		'off' => esc_html__( 'Disable' ),
	),
) );
$this->form->number( array(
	'id'   => '_margin_right',
	'name' => esc_html__( 'Item Spacing.', 'carousel-slider' ),
	'desc' => esc_html__( 'Space between two slide. Enter 10 for 10px', 'carousel-slider' ),
	'std'  => carousel_slider_default_settings()->margin_right
) );
$this->form->select( array(
	'id'      => '_inifnity_loop',
	'name'    => esc_html__( 'Infinity loop', 'carousel-slider' ),
	'desc'    => esc_html__( 'Enable or disable loop(circular) of carousel.', 'carousel-slider' ),
	'std'     => 'on',
	'options' => array(
		'on'  => esc_html__( 'Enable' ),
		'off' => esc_html__( 'Disable' ),
	),
) );
$this->form->number( array(
	'id'   => '_stage_padding',
	'name' => esc_html__( 'Stage Padding', 'carousel-slider' ),
	'desc' => esc_html__( 'Add left and right padding on carousel slider stage wrapper.', 'carousel-slider' ),
	'std'  => '0',
) );
$this->form->select( array(
	'id'      => '_auto_width',
	'name'    => esc_html__( 'Auto Width', 'carousel-slider' ),
	'desc'    => esc_html__( 'Set item width according to its content width. Use width style on item to get the result you want. ', 'carousel-slider' ),
	'std'     => 'off',
	'options' => array(
		'on'  => esc_html__( 'Enable' ),
		'off' => esc_html__( 'Disable' ),
	),
) );
