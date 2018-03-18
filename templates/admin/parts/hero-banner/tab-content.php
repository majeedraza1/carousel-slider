<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div id="carousel-slider-tab-content" class="shapla-tab tab-content">
	<?php
	$this->form->textarea( array(
		'id'          => 'slide_heading',
		'group'       => 'carousel_slider_content',
		'position'    => $slide_num,
		'meta_key'    => '_content_slider',
		'input_class' => 'regular-text',
		'cols'        => 30,
		'rows'        => 3,
		'name'        => esc_html__( 'Slide Heading:', 'carousel-slider' ),
		'desc'        => esc_html__( 'Enter the heading for your slide. This field can take HTML markup.', 'carousel-slider' ),
	) );
	$this->form->textarea( array(
		'id'          => 'slide_description',
		'group'       => 'carousel_slider_content',
		'position'    => $slide_num,
		'meta_key'    => '_content_slider',
		'input_class' => 'regular-text',
		'cols'        => 30,
		'rows'        => 5,
		'name'        => esc_html__( 'Slide Description:', 'carousel-slider' ),
		'desc'        => esc_html__( 'Enter the description for your slide. This field can take HTML markup.', 'carousel-slider' ),
	) );
	?>
</div>