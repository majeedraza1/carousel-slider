<?php

use CarouselSlider\Supports\Form;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div id="carousel-slider-tab-style" class="shapla-tab tab-style">
	<?php
	echo Form::buttonset( array(
		'id'          => 'content_alignment',
		'group'       => 'carousel_slider_content',
		'index'       => $slide_num,
		'meta_key'    => '_content_slider',
		'label'       => esc_html__( 'Content Alignment:', 'carousel-slider' ),
		'description' => esc_html__( 'Choose how the heading, description and buttons will be aligned', 'carousel-slider' ),
		'default'     => 'left',
		'choices'     => array(
			'left'   => esc_html__( 'Left', 'carousel-slider' ),
			'center' => esc_html__( 'Center', 'carousel-slider' ),
			'right'  => esc_html__( 'Right', 'carousel-slider' ),
		),
	) );

	echo Form::text( array(
		'type'             => 'number',
		'id'               => 'heading_font_size',
		'group'            => 'carousel_slider_content',
		'index'            => $slide_num,
		'meta_key'         => '_content_slider',
		'default'          => 40,
		'input_attributes' => array( 'class' => 'sp-input-text' ),
		'label'            => esc_html__( 'Heading Font Size:', 'carousel-slider' ),
		'description'      => esc_html__( 'Enter heading font size without px unit. In pixels, ex: 50 instead of 50px. Default: 40', 'carousel-slider' ),
	) );

	echo Form::text( array(
		'type'             => 'text',
		'id'               => 'heading_gutter',
		'group'            => 'carousel_slider_content',
		'index'            => $slide_num,
		'meta_key'         => '_content_slider',
		'default'          => '30px',
		'input_attributes' => array( 'class' => 'sp-input-text' ),
		'label'            => esc_html__( 'Spacing/Gutter:', 'carousel-slider' ),
		'description'      => esc_html__( 'Enter gutter (space between description and heading) in px, em or rem, ex: 3rem', 'carousel-slider' ),
	) );

	echo Form::color( array(
		'id'          => 'heading_color',
		'group'       => 'carousel_slider_content',
		'index'       => $slide_num,
		'meta_key'    => '_content_slider',
		'default'     => '#ffffff',
		'label'       => esc_html__( 'Heading Color:', 'carousel-slider' ),
		'description' => esc_html__( 'Select a color for the heading font. Default: #fff', 'carousel-slider' ),
	) );

	echo Form::text( array(
		'type'             => 'number',
		'id'               => 'description_font_size',
		'group'            => 'carousel_slider_content',
		'index'            => $slide_num,
		'meta_key'         => '_content_slider',
		'default'          => 20,
		'input_attributes' => array( 'class' => 'sp-input-text' ),
		'label'            => esc_html__( 'Description Font Size:', 'carousel-slider' ),
		'description'      => esc_html__( 'Enter description font size without px unit. In pixels, ex: 50 instead of 50px. Default: 20', 'carousel-slider' ),
	) );

	echo Form::text( array(
		'type'             => 'text',
		'id'               => 'description_gutter',
		'group'            => 'carousel_slider_content',
		'index'            => $slide_num,
		'meta_key'         => '_content_slider',
		'default'          => '30px',
		'input_attributes' => array( 'class' => 'sp-input-text' ),
		'label'            => esc_html__( 'Description Spacing/Gutter:', 'carousel-slider' ),
		'description'      => esc_html__( 'Enter gutter (space between description and buttons) in px, em or rem, ex: 3rem', 'carousel-slider' ),
	) );

	echo Form::color( array(
		'id'          => 'description_color',
		'group'       => 'carousel_slider_content',
		'index'       => $slide_num,
		'meta_key'    => '_content_slider',
		'default'     => '#ffffff',
		'label'       => esc_html__( 'Description Color:', 'carousel-slider' ),
		'description' => esc_html__( 'Select a color for the description font. Default: #fff', 'carousel-slider' ),
	) );
	?>
</div><!-- .tab-style -->