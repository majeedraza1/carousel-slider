<div data-id="open" id="section_video_settings" class="shapla-toggle shapla-toggle--stroke" style="display: <?php echo $slide_type != 'video-carousel' ? 'none' : 'block'; ?>">
	<span class="shapla-toggle-title">
		<?php _e('Video Settings', 'carousel-slider'); ?>
	</span>
	<div class="shapla-toggle-inner">
		<div class="shapla-toggle-content">
			<?php
				$this->form->textarea(array(
					'id' => '_video_url',
					'name' => __('Video URLs', 'carousel-slider'),
					'desc' => sprintf('%s<br><br>Example: %s', __('Only support youtube and vimeo. Enter video URL from youtube or vimeo separating each by comma', 'carousel-slider'), __('https://www.youtube.com/watch?v=O4-EM32h7b4,https://www.youtube.com/watch?v=72IO4gzB8mU,https://vimeo.com/193773669,https://vimeo.com/193517656', 'carousel-slider')),
				));
				$this->form->number(array(
					'id' => '_video_width',
					'name' => __('Video Width', 'carousel-slider'),
					'std' => 560,
					'desc' => __('Enter video width in numbers.', 'carousel-slider'),
				));
				$this->form->number(array(
					'id' => '_video_height',
					'name' => __('Video Height', 'carousel-slider'),
					'std' => 315,
					'desc' => __('Enter video height in numbers.', 'carousel-slider'),
				));
			?>
		</div>
	</div>
</div>