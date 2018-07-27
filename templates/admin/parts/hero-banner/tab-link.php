<?php

use CarouselSlider\Supports\Metabox;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div id="carousel-slider-tab-link" class="shapla-tab tab-content-link">
	<?php
	echo Metabox::field( array(
		'type'             => 'buttonset',
		'group'            => 'carousel_slider_content',
		'index'            => $slide_num,
		'meta_key'         => '_content_slider',
		'id'               => 'link_type',
		'label'            => esc_html__( 'Slide Link Type:', 'carousel-slider' ),
		'description'      => esc_html__( 'Choose how the slide will link.', 'carousel-slider' ),
		'default'          => 'none',
		'choices'          => array(
			'none'   => esc_html__( 'No Link', 'carousel-slider' ),
			'full'   => esc_html__( 'Full Slide', 'carousel-slider' ),
			'button' => esc_html__( 'Button', 'carousel-slider' ),
		),
		'input_attributes' => array( 'class' => 'link_type', ),
	) );
	?>

    <div class="ContentCarouselLinkFull" style="display: <?php echo ( $_link_type == 'full' ) ? 'block' : 'none'; ?>">
		<?php
		echo Metabox::field( array(
			'type'             => 'text',
			'group'            => 'carousel_slider_content',
			'index'            => $slide_num,
			'meta_key'         => '_content_slider',
			'id'               => 'slide_link',
			'label'            => esc_html__( 'Slide Link:', 'carousel-slider' ),
			'description'      => esc_html__( 'Please enter your URL that will be used to link the full slide.', 'carousel-slider' ),
			'input_attributes' => array( 'class' => 'sp-input-text', ),
		) );
		echo Metabox::field( array(
			'type'     => 'buttonset',
			'group'    => 'carousel_slider_content',
			'index'    => $slide_num,
			'meta_key' => '_content_slider',
			'id'       => 'link_target',
			'label'    => esc_html__( 'Open Slide Link In New Window:', 'carousel-slider' ),
			'default'  => '_self',
			'choices'  => array(
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

</div><!-- .tab-content-link -->