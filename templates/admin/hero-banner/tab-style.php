<?php

use CarouselSlider\Modules\HeroCarousel\HeroCarouselHelper;
use CarouselSlider\Supports\MetaBoxForm;

defined( 'ABSPATH' ) || exit;

$metaBox    = new MetaBoxForm();
$item_index = $slide_num ?? 0;
// Slide Style
$_content_alignment  = ! empty( $content_slider['content_alignment'] ) ? esc_attr( $content_slider['content_alignment'] ) : 'left';
$_heading_font_size  = ! empty( $content_slider['heading_font_size'] ) ? absint( $content_slider['heading_font_size'] ) : '40';
$_heading_gutter     = ! empty( $content_slider['heading_gutter'] ) ? esc_attr( $content_slider['heading_gutter'] ) : '30px';
$_heading_color      = ! empty( $content_slider['heading_color'] ) ? esc_attr( $content_slider['heading_color'] ) : '#ffffff';
$_desc_font_size     = ! empty( $content_slider['description_font_size'] ) ? absint( $content_slider['description_font_size'] ) : '20';
$_description_gutter = ! empty( $content_slider['description_gutter'] ) ? esc_attr( $content_slider['description_gutter'] ) : '30px';
$_desc_color         = ! empty( $content_slider['description_color'] ) ? esc_attr( $content_slider['description_color'] ) : '#ffffff';

echo '<div id="carousel-slider-tab-style" class="shapla-tab tab-style">';
$metaBox->select( [
	'id'               => 'content_alignment',
	'name'             => esc_html__( 'Content Alignment', 'carousel-slider' ),
	'desc'             => esc_html__( 'Select how the heading, description and buttons will be aligned', 'carousel-slider' ),
	'std'              => 'left',
	'options'          => HeroCarouselHelper::text_alignment(),
	'input_attributes' => [
		'name'  => sprintf( "carousel_slider_content[%s][content_alignment]", $item_index ),
		'value' => $_content_alignment,
	],
] );
$metaBox->number( [
	'id'               => 'heading_font_size',
	'name'             => esc_html__( 'Heading Font Size', 'carousel-slider' ),
	'desc'             => esc_html__( 'Enter heading font size without px unit. In pixels, ex: 50 instead of 50px. Default: 60', 'carousel-slider' ),
	'std'              => '60',
	'input_attributes' => [
		'name'  => sprintf( "carousel_slider_content[%s][heading_font_size]", $item_index ),
		'value' => $_heading_font_size,
	],
] );
$metaBox->text( [
	'id'               => 'heading_gutter',
	'name'             => esc_html__( 'Spacing/Gutter', 'carousel-slider' ),
	'desc'             => esc_html__( 'Enter gutter (space between description and heading) in px, em or rem, ex: 3rem', 'carousel-slider' ),
	'std'              => '30px',
	'input_attributes' => [
		'name'  => sprintf( "carousel_slider_content[%s][heading_gutter]", $item_index ),
		'value' => $_heading_gutter,
	],
] );
$metaBox->color( [
	'id'               => 'heading_color',
	'name'             => esc_html__( 'Heading Color', 'carousel-slider' ),
	'desc'             => esc_html__( 'Select a color for the heading font. Default: #fff', 'carousel-slider' ),
	'std'              => '#ffffff',
	'input_attributes' => [
		'name'  => sprintf( "carousel_slider_content[%s][heading_color]", $item_index ),
		'value' => $_heading_color,
	],
] );
$metaBox->text( [
	'id'               => 'description_font_size',
	'name'             => esc_html__( 'Description Font Size', 'carousel-slider' ),
	'desc'             => esc_html__( 'Enter description font size without px unit. In pixels, ex: 20 instead of 20px. Default: 24', 'carousel-slider' ),
	'std'              => '24',
	'input_attributes' => [
		'name'  => sprintf( "carousel_slider_content[%s][description_font_size]", $item_index ),
		'value' => $_desc_font_size,
	],
] );
$metaBox->text( [
	'id'               => 'description_gutter',
	'name'             => esc_html__( 'Description Spacing/Gutter', 'carousel-slider' ),
	'desc'             => esc_html__( 'Enter gutter (space between description and buttons) in px, em or rem, ex: 3rem', 'carousel-slider' ),
	'std'              => '30px',
	'input_attributes' => [
		'name'  => sprintf( "carousel_slider_content[%s][description_gutter]", $item_index ),
		'value' => $_description_gutter,
	],
] );
$metaBox->color( [
	'id'               => 'description_color',
	'name'             => esc_html__( 'Description Color', 'carousel-slider' ),
	'desc'             => esc_html__( 'Select a color for the description font. Default: #fff', 'carousel-slider' ),
	'std'              => '#ffffff',
	'input_attributes' => [
		'name'  => sprintf( "carousel_slider_content[%s][description_color]", $item_index ),
		'value' => $_desc_color,
	],
] );
echo '</div>';
