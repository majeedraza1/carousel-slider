<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div id="carousel-slider-tab-style" class="shapla-tab tab-style">
	<?php
	$this->form->buttonset( array(
		'id'       => 'content_alignment',
		'group'    => 'carousel_slider_content',
		'position' => $slide_num,
		'meta_key' => '_content_slider',
		'name'     => esc_html__( 'Content Alignment:', 'carousel-slider' ),
		'desc'     => esc_html__( 'Choose how the heading, description and buttons will be aligned', 'carousel-slider' ),
		'std'      => 'left',
		'options'  => array(
			'left'   => esc_html__( 'Left', 'carousel-slider' ),
			'center' => esc_html__( 'Center', 'carousel-slider' ),
			'right'  => esc_html__( 'Right', 'carousel-slider' ),
		),
	) );
	$this->form->number( array(
		'id'       => 'heading_font_size',
		'group'    => 'carousel_slider_content',
		'position' => $slide_num,
		'meta_key' => '_content_slider',
		'std'      => 40,
		'name'     => esc_html__( 'Heading Font Size:', 'carousel-slider' ),
		'desc'     => esc_html__( 'Enter heading font size without px unit. In pixels, ex: 50 instead of 50px. Default: 40', 'carousel-slider' ),
	) );
	$this->form->text( array(
		'id'       => 'heading_gutter',
		'group'    => 'carousel_slider_content',
		'position' => $slide_num,
		'meta_key' => '_content_slider',
		'std'      => '30px',
		'name'     => esc_html__( 'Spacing/Gutter:', 'carousel-slider' ),
		'desc'     => esc_html__( 'Enter gutter (space between description and heading) in px, em or rem, ex: 3rem', 'carousel-slider' ),
	) );
	$this->form->color( array(
		'id'       => 'heading_color',
		'group'    => 'carousel_slider_content',
		'position' => $slide_num,
		'meta_key' => '_content_slider',
		'std'      => '#ffffff',
		'name'     => esc_html__( 'Heading Color:', 'carousel-slider' ),
		'desc'     => esc_html__( 'Select a color for the heading font. Default: #fff', 'carousel-slider' ),
	) );
	$this->form->number( array(
		'id'       => 'description_font_size',
		'group'    => 'carousel_slider_content',
		'position' => $slide_num,
		'meta_key' => '_content_slider',
		'std'      => 20,
		'name'     => esc_html__( 'Description Font Size:', 'carousel-slider' ),
		'desc'     => esc_html__( 'Enter description font size without px unit. In pixels, ex: 50 instead of 50px. Default: 20', 'carousel-slider' ),
	) );
	$this->form->text( array(
		'id'       => 'description_gutter',
		'group'    => 'carousel_slider_content',
		'position' => $slide_num,
		'meta_key' => '_content_slider',
		'std'      => '30px',
		'name'     => esc_html__( 'Description Spacing/Gutter:', 'carousel-slider' ),
		'desc'     => esc_html__( 'Enter gutter (space between description and buttons) in px, em or rem, ex: 3rem', 'carousel-slider' ),
	) );
	$this->form->color( array(
		'id'       => 'description_color',
		'group'    => 'carousel_slider_content',
		'position' => $slide_num,
		'meta_key' => '_content_slider',
		'std'      => '#ffffff',
		'name'     => esc_html__( 'Description Color:', 'carousel-slider' ),
		'desc'     => esc_html__( 'Select a color for the description font. Default: #fff', 'carousel-slider' ),
	) );
	?>
</div>
<!-- .tab-style -->