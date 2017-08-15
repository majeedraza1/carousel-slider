<div data-id="open" class="shapla-toggle shapla-toggle--stroke">
	<span class="shapla-toggle-title">
		<?php _e('Navigation Settings', 'carousel-slider'); ?>
	</span>
	<div class="shapla-toggle-inner">
		<div class="shapla-toggle-content">
		<?php
            $this->form->checkbox(array(
	            'id' 	=> '_nav_button',
	            'name' 	=> __('Navigation', 'carousel-slider'),
	            'label' => __('Navigation', 'carousel-slider'),
	            'desc' 	=> __('Check to show next/prev icons.', 'carousel-slider'),
	        ));
            $this->form->checkbox(array(
	            'id' 	=> '_dot_nav',
	            'name' 	=> __('Dots', 'carousel-slider'),
	            'label' => __('Dots', 'carousel-slider'),
	            'desc' 	=> __('Check to show dots navigation.', 'carousel-slider'),
	        ));
            $this->form->color(array(
	            'id' 	=> '_nav_color',
	            'type' 	=> 'color',
	            'name' 	=> __('Navigation & Dots Color	', 'carousel-slider'),
	            'desc' 	=> __('Pick a color for navigation and dots.', 'carousel-slider'),
	            'std' 	=> '#f1f1f1'
	        ));
            $this->form->color(array(
	            'id' 	=> '_nav_active_color',
	            'name' 	=> __('Navigation & Dots Color: Hover & Active', 'carousel-slider'),
	            'desc' 	=> __('Pick a color for navigation and dots for active and hover effect.', 'carousel-slider'),
	            'std' 	=> '#4caf50'
	        ));
		?>
		</div>
	</div>
</div>