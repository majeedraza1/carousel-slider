<div data-id="open" class="shapla-toggle shapla-toggle--stroke">
	<span class="shapla-toggle-title">
		<?php _e('Autoplay Settings', 'carousel-slider'); ?>
	</span>
	<div class="shapla-toggle-inner">
		<div class="shapla-toggle-content">
		<?php
            $this->form->checkbox( array(
	            'id' 	=> '_autoplay',
	            'name' 	=> __('Autoplay', 'carousel-slider'),
	            'label' => __('Autoplay.', 'carousel-slider'),
	            'desc' 	=> __('Check to enable autoplay', 'carousel-slider'),
	            'std' 	=> 'on'
	        ));
            $this->form->number( array(
	            'id' 	=> '_autoplay_timeout',
	            'name' 	=> __('Autoplay Timeout', 'carousel-slider'),
	            'desc' 	=> __('Autoplay interval timeout in millisecond. Default: 5000', 'carousel-slider'),
	            'std' 	=> 5000
	        ));

            $this->form->number( array(
	            'id' 	=> '_autoplay_speed',
	            'name' 	=> __('Autoplay Speed', 'carousel-slider'),
	            'desc' 	=> __('Autoplay speen in millisecond. Default: 500', 'carousel-slider'),
	            'std' 	=> 500
	        ));
            $this->form->checkbox( array(
	            'id' 	=> '_autoplay_pause',
	            'name' 	=> __('Autoplay Hover Pause', 'carousel-slider'),
	            'label' => __('Pause on mouse hover.', 'carousel-slider'),
	            'desc' 	=> __('Pause autoplay on mouse hover.', 'carousel-slider'),
	        ));
		?>
		</div>
	</div>
</div>