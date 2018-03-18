<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div id="carousel-slider-tab-link" class="shapla-tab tab-content-link">
	<?php
	$this->form->buttonset( array(
		'group'       => 'carousel_slider_content',
		'position'    => $slide_num,
		'meta_key'    => '_content_slider',
		'id'          => 'link_type',
		'input_class' => 'link_type',
		'name'        => esc_html__( 'Slide Link Type:', 'carousel-slider' ),
		'desc'        => esc_html__( 'Choose how the slide will link.', 'carousel-slider' ),
		'std'         => 'none',
		'options'     => array(
			'none'   => esc_html__( 'No Link', 'carousel-slider' ),
			'full'   => esc_html__( 'Full Slide', 'carousel-slider' ),
			'button' => esc_html__( 'Button', 'carousel-slider' ),
		),
	) );
	?>

    <div class="ContentCarouselLinkFull" style="display: <?php echo ( $_link_type == 'full' ) ? 'block' : 'none'; ?>">
		<?php
		$this->form->text( array(
			'group'    => 'carousel_slider_content',
			'position' => $slide_num,
			'meta_key' => '_content_slider',
			'id'       => 'slide_link',
			'name'     => esc_html__( 'Slide Link:', 'carousel-slider' ),
			'desc'     => esc_html__( 'Please enter your URL that will be used to link the full slide.', 'carousel-slider' ),
		) );
		$this->form->buttonset( array(
			'group'    => 'carousel_slider_content',
			'position' => $slide_num,
			'meta_key' => '_content_slider',
			'id'       => 'link_target',
			'name'     => esc_html__( 'Open Slide Link In New Window:', 'carousel-slider' ),
			'std'      => '_self',
			'options'  => array(
				'_blank' => esc_html__( 'Yes', 'carousel-slider' ),
				'_self'  => esc_html__( 'No', 'carousel-slider' ),
			),
		) );
		?>
    </div>

    <div class="ContentCarouselLinkButtons"
         style="display: <?php echo ( $_link_type == 'button' ) ? 'block' : 'none'; ?>">
		<?php include CAROUSEL_SLIDER_TEMPLATES . '/admin/parts/hero-banner/tab-link-button-one.php';; ?>
		<?php include CAROUSEL_SLIDER_TEMPLATES . '/admin/parts/hero-banner/tab-link-button-two.php';; ?>
    </div>
</div>