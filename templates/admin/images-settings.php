<?php $img_settings = ($slide_type == 'image-carousel') ||  ($slide_type == 'image-carousel-url') ? true : false;?>
<div data-id="open" id="section_images_general_settings" class="shapla-toggle shapla-toggle--stroke" style="display: <?php echo !$img_settings ? 'none' : 'block'; ?>">
	<span class="shapla-toggle-title">
		<?php _e('Image Carousel Settings', 'carousel-slider'); ?>
	</span>
	<div class="shapla-toggle-inner">
		<div class="shapla-toggle-content">
		<?php
			$this->form->checkbox(array(
	            'id' 	=> '_show_attachment_title',
	            'name' 	=> __('Show Image Title', 'carousel-slider'),
	            'label' => __('Show Image Title', 'carousel-slider'),
	            'desc' 	=> __('Check to show title below image. Only works with image carousel.', 'carousel-slider'),
	            'std' 	=> 'off'
			));
			$this->form->checkbox(array(
	            'id' 	=> '_show_attachment_caption',
	            'name' 	=> __('Show Image Caption', 'carousel-slider'),
	            'label' => __('Show Image Caption', 'carousel-slider'),
	            'desc' 	=> __('Check to show caption below image. Only works with image carousel.', 'carousel-slider'),
	            'std' 	=> 'off'
			));
			$this->form->select(array(
				'id' => '_image_target',
				'name' => __('Image Target', 'carousel-slider'),
				'desc' => __('Choose where to open the linked image.', 'carousel-slider'),
				'std' => '_self',
				'options' => array(
					'_self' 	=> __('Open in the same frame as it was clicked', 'carousel-slider'),
					'_blank' 	=> __('Open in a new window or tab', 'carousel-slider'),
				),
			));
			$this->form->checkbox(array(
				'id' 	=> '_image_lightbox',
				'name' 	=> __('Show Lightbox Gallery', 'carousel-slider'),
	            'label' => __('Show Lightbox Gallery', 'carousel-slider'),
	            'desc' 	=> __('Check to show lightbox gallery.', 'carousel-slider'),
	            'std' 	=> 'off'
			));
		?>
		</div>
	</div>
</div>