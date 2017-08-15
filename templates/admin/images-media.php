<div data-id="open" id="section_images_settings" class="shapla-toggle shapla-toggle--stroke" style="display: <?php echo $slide_type != 'image-carousel' ? 'none' : 'block'; ?>">
	<span class="shapla-toggle-title">
		<?php _e('Media Images', 'carousel-slider'); ?>
	</span>
	<div class="shapla-toggle-inner">
		<div class="shapla-toggle-content">
		<?php
			$this->form->images_gallery(array(
	            'id' 	=> '_wpdh_image_ids',
	            'name' 	=> __('Carousel Images', 'carousel-slider'),
	            'desc' 	=> __('Choose carousel images from media library.', 'carousel-slider'),
			));
		?>
		</div>
	</div>
</div>