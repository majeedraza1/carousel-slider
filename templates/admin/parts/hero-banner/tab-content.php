<?php

use CarouselSlider\Supports\Form;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div id="carousel-slider-tab-content" class="shapla-tab tab-content">
	<?php
	echo Form::textarea( array(
		'id'               => 'slide_heading',
		'group'            => 'carousel_slider_content',
		'index'            => $slide_num,
		'meta_key'         => '_content_slider',
		'label'            => esc_html__( 'Slide Heading:', 'carousel-slider' ),
		'description'      => esc_html__( 'Enter the heading for your slide. This field can take HTML markup.', 'carousel-slider' ),
		'input_attributes' => array(
			'class' => 'sp-input-textarea',
			'cols'  => 30,
			'rows'  => 3,
		),
	) );
	echo Form::textarea( array(
		'id'               => 'slide_description',
		'group'            => 'carousel_slider_content',
		'index'            => $slide_num,
		'meta_key'         => '_content_slider',
		'label'            => esc_html__( 'Slide Description:', 'carousel-slider' ),
		'description'      => esc_html__( 'Enter the description for your slide. This field can take HTML markup.', 'carousel-slider' ),
		'input_attributes' => array(
			'class' => 'sp-input-textarea',
			'cols'  => 30,
			'rows'  => 3,
		),
	) );
	?>
</div><!-- .tab-content -->
