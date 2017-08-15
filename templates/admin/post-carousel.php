<div data-id="open" id="section_post_query" class="shapla-toggle shapla-toggle--stroke" style="display: <?php echo $slide_type != 'post-carousel' ? 'none' : 'block'; ?>">
	<span class="shapla-toggle-title">
		<?php _e('Post Query', 'carousel-slider'); ?>
	</span>
	<div class="shapla-toggle-inner">
		<div class="shapla-toggle-content">
			<?php
				$this->form->select(array(
					'id' => '_post_query_type',
					'name' => __('Query Type', 'carousel-slider'),
					'std' => 'latest_posts',
					'options' => array(
						'latest_posts'  	=> __('Latest Posts', 'carousel-slider'),
						'date_range' 		=> __('Date Range', 'carousel-slider'),
						'post_categories' 	=> __('Post Categories', 'carousel-slider'),
						'post_tags' 		=> __('Post Tags', 'carousel-slider'),
						'specific_posts' 	=> __('Specific posts', 'carousel-slider'),
					),
				));
				$this->form->date(array(
					'id' => '_post_date_after',
					'name' => __('Date from', 'carousel-slider'),
					'desc' => sprintf(__('Example: %s', 'carousel-slider'), date('F d, Y', strtotime('-3 months'))),
				));
				$this->form->date(array(
					'id' => '_post_date_before',
					'name' => __('Date to', 'carousel-slider'),
					'desc' => sprintf(__('Example: %s', 'carousel-slider'), date('F d, Y', strtotime('-7 days'))),
				));
				$this->form->post_terms(array(
					'id' 		=> '_post_categories',
					'taxonomy' 	=> 'category',
					'multiple' 	=> true,
					'name' 		=> __('Post Categories', 'carousel-slider'),
					'desc' 		=> __('Show posts associated with selected categories.', 'carousel-slider'),
				));
				$this->form->post_terms(array(
					'id' 		=> '_post_tags',
					'taxonomy' 	=> 'post_tag',
					'multiple' 	=> true,
					'name' 		=> __('Post Tags', 'carousel-slider'),
					'desc' 		=> __('Show posts associated with selected tags.', 'carousel-slider'),
				));
				$this->form->posts_list(array(
					'id' 		=> '_post_in',
					'multiple' 	=> true,
					'name' 		=> __('Specific posts', 'carousel-slider'),
					'desc' 		=> __('Select posts that you want to show as slider. Select at least 5 posts', 'carousel-slider'),
				));
				$this->form->number(array(
					'id' => '_posts_per_page',
					'name' => __('Posts per page', 'carousel-slider'),
					'std' => 12,
					'desc' => __('How many post you want to show on carousel slide.', 'carousel-slider'),
				));
				$this->form->select(array(
					'id' => '_post_order',
					'name' => __('Order', 'carousel-slider'),
					'std' => 'DESC',
					'options' => array(
						'ASC' => __('Ascending Order', 'carousel-slider'),
						'DESC' => __('Descending Order', 'carousel-slider'),
					),
				));
				$this->form->select(array(
					'id' => '_post_orderby',
					'name' => __('Order by', 'carousel-slider'),
					'std' => 'ID',
					'options' => array(
						'none' 			=> __('No order', 'carousel-slider'),
						'ID' 			=> __('Post id', 'carousel-slider'),
						'author' 		=> __('Post author', 'carousel-slider'),
						'title' 		=> __('Post title', 'carousel-slider'),
						'modified' 		=> __('Last modified date', 'carousel-slider'),
						'rand' 			=> __('Random order', 'carousel-slider'),
						'comment_count' => __('Number of comments', 'carousel-slider'),
					),
				));
				$this->form->number(array(
					'id' => '_post_height',
					'name' => __('Colums Height', 'carousel-slider'),
					'std' => 450,
					'desc' => __('Enter colums height for posts carousel in numbers. 450 (px) is perfect when columns width is around 300px or higher. Otherwise you need to change it for perfection.', 'carousel-slider'),
				));
			?>
		</div>
	</div>
</div>