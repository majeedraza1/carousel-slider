<?php
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
			$this->form->buttonset( array(
				'id'      => '_show_attachment_title',
				'name'    => esc_html__( 'Image Title', 'carousel-slider' ),
				'desc'    => esc_html__( 'Show title below image. Only works with image carousel.', 'carousel-slider' ),
				'std'     => 'off',
				'options' => array(
					'on'  => esc_html__( 'Enable', 'carousel-slider' ),
					'off' => esc_html__( 'Disable', 'carousel-slider' ),
				),
			) );
			$this->form->buttonset( array(
				'id'      => '_show_attachment_caption',
				'name'    => esc_html__( 'Image Caption', 'carousel-slider' ),
				'desc'    => esc_html__( 'Display image caption below image.', 'carousel-slider' ),
				'std'     => 'off',
				'options' => array(
					'on'  => esc_html__( 'Enable', 'carousel-slider' ),
					'off' => esc_html__( 'Disable', 'carousel-slider' ),
				),
			) );
			$this->form->buttonset( array(
				'id'      => '_image_target',
				'name'    => esc_html__( 'Open Image Link in', 'carousel-slider' ),
				'desc'    => esc_html__( 'Choose where to open the linked image. No effect if you enable lightbox gallery.', 'carousel-slider' ),
				'std'     => '_self',
				'options' => array(
					'_self'  => esc_html__( 'Same Window', 'carousel-slider' ),
					'_blank' => esc_html__( 'New Window', 'carousel-slider' ),
				),
			) );
			$this->form->buttonset( array(
				'id'      => '_image_lightbox',
				'name'    => esc_html__( 'Lightbox Gallery', 'carousel-slider' ),
				'desc'    => esc_html__( 'Enable to open images in lightbox gallery.', 'carousel-slider' ),
				'std'     => 'off',
				'options' => array(
					'on'  => esc_html__( 'Enable', 'carousel-slider' ),
					'off' => esc_html__( 'Disable', 'carousel-slider' ),
				),
			) );
			?>
        </div>
    </div>
</div>