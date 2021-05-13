<?php

use CarouselSlider\Supports\MetaBoxForm;

defined( 'ABSPATH' ) || exit;

$metaBox      = new MetaBoxForm;
$item_index   = $slide_num = $slide_num ?? 0;
$_link_type   = ! empty( $content_slider['link_type'] ) ? esc_attr( $content_slider['link_type'] ) : 'full';
$_slide_link  = ! empty( $content_slider['slide_link'] ) ? esc_url( $content_slider['slide_link'] ) : '';
$_link_target = ! empty( $content_slider['link_target'] ) ? esc_attr( $content_slider['link_target'] ) : '_blank';

echo '<div id="carousel-slider-tab-link" class="shapla-tab tab-content-link">';
$metaBox->select( [
	'id'               => 'link_type',
	'class'            => 'sp-input-text link_type',
	'name'             => esc_html__( 'Slide Link Type', 'carousel-slider' ),
	'desc'             => esc_html__( 'Select how the slide will link.', 'carousel-slider' ),
	'options'          => [
		'none'   => esc_html__( 'No Link', 'carousel-slider' ),
		'full'   => esc_html__( 'Full Slide', 'carousel-slider' ),
		'button' => esc_html__( 'Button', 'carousel-slider' ),
	],
	'input_attributes' => [
		'name'  => sprintf( "carousel_slider_content[%s][link_type]", $slide_num ),
		'value' => $_link_type,
	],
] );

$is_full = $_link_type == 'full' ? 'display:block' : 'display:none';
echo '<div class="ContentCarouselLinkFull" style="' . $is_full . '">';
$metaBox->text( [
	'id'               => 'slide_link',
	'name'             => esc_html__( 'Slide Link', 'carousel-slider' ),
	'desc'             => esc_html__( 'Please enter your URL that will be used to link the full slide.', 'carousel-slider' ),
	'input_attributes' => [
		'name'  => sprintf( "carousel_slider_content[%s][link_type]", $slide_num ),
		'value' => $_slide_link,
	],
] );
$metaBox->select( [
	'id'               => 'link_target',
	'name'             => esc_html__( 'Open Slide Link In New Window', 'carousel-slider' ),
	'input_attributes' => [
		'name'  => sprintf( "carousel_slider_content[%s][link_target]", $slide_num ),
		'value' => $_link_target,
	],
	'options'          => [
		'_blank' => esc_html__( 'Yes', 'carousel-slider' ),
		'_self'  => esc_html__( 'No', 'carousel-slider' ),
	],
] );
echo '</div>';

$is_button = $_link_type == 'button' ? 'display:block' : 'display:none';
echo '<div class="ContentCarouselLinkButtons" style="' . $is_button . '">';
include CAROUSEL_SLIDER_TEMPLATES . '/admin/hero-banner/tab-link-button-one.php';
include CAROUSEL_SLIDER_TEMPLATES . '/admin/hero-banner/tab-link-button-two.php';
echo '</div>';

echo '</div>';
