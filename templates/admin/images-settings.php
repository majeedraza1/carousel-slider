<?php

use CarouselSlider\Supports\Metabox;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$img_settings = ( $slide_type == 'image-carousel' ) || ( $slide_type == 'image-carousel-url' ) ? true : false;
?>
<div data-id="open" id="section_images_general_settings" class="shapla-toggle shapla-toggle--stroke"
     style="display: <?php echo ! $img_settings ? 'none' : 'block'; ?>">
	<span class="shapla-toggle-title">
		<?php esc_html_e( 'Image Carousel Settings', 'carousel-slider' ); ?>
	</span>
    <div class="shapla-toggle-inner">
        <div class="shapla-toggle-content">
			<?php
			echo Metabox::field( array(
				'type'        => 'toggle',
				'id'          => '_show_attachment_title',
				'label'       => esc_html__( 'Show Image Title', 'carousel-slider' ),
				'description' => esc_html__( 'Check to show title below image. Only works with image carousel.', 'carousel-slider' ),
				'default'     => 'off'
			) );
			echo Metabox::field( array(
				'type'        => 'toggle',
				'id'          => '_show_attachment_caption',
				'label'       => esc_html__( 'Show Image Caption', 'carousel-slider' ),
				'description' => esc_html__( 'Check to show caption below image. Only works with image carousel.', 'carousel-slider' ),
				'default'     => 'off'
			) );

			echo Metabox::field( array(
				'type'        => 'toggle',
				'id'          => '_image_lightbox',
				'label'       => esc_html__( 'Show Lightbox Gallery', 'carousel-slider' ),
				'description' => esc_html__( 'Check to show lightbox gallery.', 'carousel-slider' ),
				'default'     => 'off'
			) );

			echo Metabox::field( array(
				'type'             => 'buttonset',
				'id'               => '_image_target',
				'label'            => esc_html__( 'Image Target', 'carousel-slider' ),
				'description'      => esc_html__( 'Choose where to open the linked image.', 'carousel-slider' ),
				'default'          => '_self',
				'choices'          => array(
					'_self'  => esc_html__( 'Same window', 'carousel-slider' ),
					'_blank' => esc_html__( 'New window', 'carousel-slider' ),
				),
				'input_attributes' => array( 'class' => 'sp-input-text' ),
			) );

			?>
        </div>
    </div>
</div>