<?php

use CarouselSlider\Supports\MetaBoxForm;

defined( 'ABSPATH' ) || exit;

$slide_num          = $slide_num ?? 0;
$_slide_heading     = $content_slider['slide_heading'] ?? '';
$_slide_description = $content_slider['slide_description'] ?? '';
$metaBox            = new MetaBoxForm;

echo '<div id="carousel-slider-tab-content" class="shapla-tab tab-content">';
$metaBox->textarea( [
	'id'               => 'slide_heading',
	'name'             => esc_html__( 'Slide Heading', 'carousel-slider' ),
	'desc'             => esc_html__( 'Enter the heading for your slide. This field can take HTML markup.', 'carousel-slider' ),
	'rows'             => 3,
	'input_attributes' => [
		'name'  => sprintf( "carousel_slider_content[%s][slide_heading]", $slide_num ),
		'value' => $_slide_heading,
	],
] );
$metaBox->textarea( [
	'id'               => 'slide_description',
	'name'             => esc_html__( 'Slide Description', 'carousel-slider' ),
	'desc'             => esc_html__( 'Enter the description for your slide. This field can take HTML markup.', 'carousel-slider' ),
	'rows'             => 4,
	'input_attributes' => [
		'name'  => sprintf( "carousel_slider_content[%s][slide_description]", $slide_num ),
		'value' => $_slide_description,
	],
] );
echo '</div>';
