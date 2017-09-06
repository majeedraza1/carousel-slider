<div data-id="open" class="shapla-toggle shapla-toggle--stroke">
	<span class="shapla-toggle-title">
		<?php esc_html_e( 'General Settings', 'carousel-slider' ); ?>
	</span>
    <div class="shapla-toggle-inner">
        <div class="shapla-toggle-content">
			<?php
			$this->form->image_sizes( array(
				'id'   => esc_html__( '_image_size', 'carousel-slider' ),
				'name' => esc_html__( 'Carousel Image size', 'carousel-slider' ),
				'desc' => sprintf(
					esc_html__( 'Select "original uploaded image" for full size image or your desired image size for carousel image. You can change the default size for thumbnail, medium and large from %1$s Settings >> Media %2$s.', 'carousel-slider' ),
					'<a target="_blank" href="' . get_admin_url() . 'options-media.php">', '</a>'
				),
			) );
			$this->form->checkbox( array(
				'id'    => '_lazy_load_image',
				'name'  => esc_html__( 'Lazy load image', 'carousel-slider' ),
				'label' => esc_html__( 'Lazy load image.', 'carousel-slider' ),
				'desc'  => esc_html__( 'Check to enable image lazy load.', 'carousel-slider' ),
				'std'   => 'off'
			) );
			$this->form->number( array(
				'id'   => '_margin_right',
				'name' => esc_html__( 'Margin Right(px) on item.', 'carousel-slider' ),
				'desc' => esc_html__( 'margin-right(px) on item. Default value is 10. Example: 20', 'carousel-slider' ),
				'std'  => 10
			) );
			$this->form->checkbox( array(
				'id'    => '_inifnity_loop',
				'name'  => esc_html__( 'Inifnity loop', 'carousel-slider' ),
				'label' => esc_html__( 'Inifnity loop.', 'carousel-slider' ),
				'desc'  => esc_html__( 'Check to show inifnity loop. Duplicate last and first items to get loop illusion', 'carousel-slider' ),
				'std'   => 'on'
			) );
			?>
        </div>
    </div>
</div>