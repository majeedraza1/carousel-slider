<div data-id="open" class="shapla-toggle shapla-toggle--stroke">
	<span class="shapla-toggle-title">
		<?php _e('General Settings', 'carousel-slider'); ?>
	</span>
	<div class="shapla-toggle-inner">
		<div class="shapla-toggle-content">
		<?php
			$this->form->image_sizes(array(
				'id' 	=> __('_image_size', 'carousel-slider'),
				'name' 	=> __('Carousel Image size', 'carousel-slider'),
	            'desc' 	=> sprintf(__( 'Select "original uploaded image" for full size image or your desired image size for carousel image. You can change the default size for thumbnail, medium and large from %1$s Settings >> Media %2$s.', 'carousel-slider' ),'<a target="_blank" href="'.get_admin_url().'options-media.php">','</a>'),
			));
			$this->form->checkbox(array(
	            'id' 	=> '_lazy_load_image',
	            'name' 	=> __('Lazy load image', 'carousel-slider'),
	            'label' => __('Lazy load image.', 'carousel-slider'),
	            'desc' 	=> __('Check to enable image lazy load.', 'carousel-slider'),
	            'std' 	=> 'off'
			));
            $this->form->text( array(
	            'id' 	=> '_slide_by',
	            'name' 	=> __('Slide By', 'carousel-slider'),
	            'desc' 	=> __('Navigation slide by x number. Write "page" with inverted comma to slide by page. Default value is 1.', 'carousel-slider'),
	            'std' 	=> 1
	        ));
            $this->form->number( array(
	            'id' 	=> '_margin_right',
	            'name' 	=> __('Margin Right(px) on item.', 'carousel-slider'),
	            'desc' 	=> __('margin-right(px) on item. Default value is 10. Example: 20', 'carousel-slider'),
	            'std' 	=> 10
	        ));
            $this->form->checkbox( array(
	            'id' 	=> '_inifnity_loop',
	            'name' 	=> __('Inifnity loop', 'carousel-slider'),
	            'label' => __('Inifnity loop.', 'carousel-slider'),
	            'desc' 	=> __('Check to show inifnity loop. Duplicate last and first items to get loop illusion', 'carousel-slider'),
	            'std' 	=> 'on'
	        ));
		?>
		</div>
	</div>
</div>