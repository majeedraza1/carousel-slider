<div data-id="open" id="section_product_query" class="shapla-toggle shapla-toggle--stroke" style="display: <?php echo $slide_type != 'product-carousel' ? 'none' : 'block'; ?>">
	<span class="shapla-toggle-title">
		<?php _e('Product Query', 'carousel-slider'); ?>
	</span>
	<div class="shapla-toggle-inner">
		<div class="shapla-toggle-content">
			<?php
				$this->form->select(array(
					'id' => '_product_query_type',
					'name' => __('Query Type', 'carousel-slider'),
					'std' => 'query_porduct',
					'options' => array(
						'query_porduct' 	=> __('Query Products', 'carousel-slider'),
						'product_categories' => __('Product Categories', 'carousel-slider'),
						'product_tags' 		=> __('Product Tags', 'carousel-slider'),
						'specific_products' => __('Specific Products', 'carousel-slider'),
					),
				));
				$this->form->select(array(
					'id' => '_product_query',
					'name' => __('Choose Query', 'carousel-slider'),
					'std' => 'featured',
					'options' => array(
						'featured' 		=> __('Featured Products', 'carousel-slider'),
						'recent' 		=> __('Recent Products', 'carousel-slider'),
						'sale' 			=> __('Sale Products', 'carousel-slider'),
						'best_selling' 	=> __('Best-Selling Products', 'carousel-slider'),
						'top_rated' 	=> __('Top Rated Products', 'carousel-slider'),
					),
				));
				$this->form->post_terms(array(
					'id' 		=> '_product_categories',
					'taxonomy' 	=> 'product_cat',
					'multiple' 	=> true,
					'name' 		=> __('Product Categories', 'carousel-slider'),
					'desc' 		=> __('Show products associated with selected categories.', 'carousel-slider'),
				));
				$this->form->post_terms(array(
					'id' 		=> '_product_tags',
					'taxonomy' 	=> 'product_tag',
					'multiple' 	=> true,
					'name' 		=> __('Product Tags', 'carousel-slider'),
					'desc' 		=> __('Show products associated with selected tags.', 'carousel-slider'),
				));
				$this->form->posts_list(array(
					'id' 		=> '_product_in',
					'post_type' => 'product',
					'multiple' 	=> true,
					'name' 		=> __('Specific products', 'carousel-slider'),
					'desc' 		=> __('Select products that you want to show as slider. Select at least 5 products', 'carousel-slider'),
				));
				$this->form->number(array(
					'id' => '_products_per_page',
					'name' => __('Product per page', 'carousel-slider'),
					'std' => 12,
					'desc' => __('How many products you want to show on carousel slide.', 'carousel-slider'),
				));
	            $this->form->checkbox( array(
		            'id' 	=> '_product_title',
		            'name' 	=> __('Show Title', 'carousel-slider'),
		            'label' => __('Show Title.', 'carousel-slider'),
		            'desc' 	=> __('Check to show product title.', 'carousel-slider'),
		            'std' 	=> 'on'
		        ));
	            $this->form->checkbox( array(
		            'id' 	=> '_product_rating',
		            'name' 	=> __('Show Rating', 'carousel-slider'),
		            'label' => __('Show Rating.', 'carousel-slider'),
		            'desc' 	=> __('Check to show product rating.', 'carousel-slider'),
		            'std' 	=> 'on'
		        ));
	            $this->form->checkbox( array(
		            'id' 	=> '_product_price',
		            'name' 	=> __('Show Price', 'carousel-slider'),
		            'label' => __('Show Price.', 'carousel-slider'),
		            'desc' 	=> __('Check to show product price.', 'carousel-slider'),
		            'std' 	=> 'on'
		        ));
	            $this->form->checkbox( array(
		            'id' 	=> '_product_cart_button',
		            'name' 	=> __('Show Cart Button', 'carousel-slider'),
		            'label' => __('Show Cart Button.', 'carousel-slider'),
		            'desc' 	=> __('Check to show product add to cart button.', 'carousel-slider'),
		            'std' 	=> 'on'
		        ));
	            $this->form->checkbox( array(
		            'id' 	=> '_product_onsale',
		            'name' 	=> __('Show Sale Tag', 'carousel-slider'),
		            'label' => __('Show Sale Tag', 'carousel-slider'),
		            'desc' 	=> __('Check to show product sale tag for onsale products.', 'carousel-slider'),
		            'std' 	=> 'on'
		        ));
	            $this->form->checkbox( array(
		            'id' 	=> '_product_wishlist',
		            'name' 	=> __('Show Wishlist Button', 'carousel-slider'),
		            'label' => __('Show Wishlist Button', 'carousel-slider'),
		            'std' 	=> 'off',
		            'desc' 	=> sprintf( esc_html__('Check to show wishlist button. This feature needs %s plugin to be installed.', 'carousel-slider'), sprintf('<a href="https://wordpress.org/plugins/yith-woocommerce-wishlist/" target="_blank" >%s</a>', __('YITH WooCommerce Wishlist', 'carousel-slider'))),
		        ));
	            $this->form->checkbox( array(
		            'id' 	=> '_product_quick_view',
		            'name' 	=> __('Show Quick View', 'carousel-slider'),
		            'label' => __('Show Quick View', 'carousel-slider'),
		            'desc' 	=> __('Check to show quick view button.', 'carousel-slider'),
		            'std' 	=> 'on'
		        ));
	            $this->form->color(array(
		            'id' 	=> '_product_title_color',
		            'type' 	=> 'color',
		            'name' 	=> __('Title Color', 'carousel-slider'),
		            'desc' 	=> __('Pick a color for product title. This color will also apply to sale tag and price.', 'carousel-slider'),
		            'std' 	=> '#333333'
		        ));
	            $this->form->color(array(
		            'id' 	=> '_product_button_bg_color',
		            'type' 	=> 'color',
		            'name' 	=> __('Button Background Color', 'carousel-slider'),
		            'desc' 	=> __('Pick a color for button background color. This color will also apply to product rating.', 'carousel-slider'),
		            'std' 	=> '#96588a'
		        ));
	            $this->form->color(array(
		            'id' 	=> '_product_button_text_color',
		            'type' 	=> 'color',
		            'name' 	=> __('Button Text Color', 'carousel-slider'),
		            'desc' 	=> __('Pick a color for button text color.', 'carousel-slider'),
		            'std' 	=> '#f1f1f1'
		        ));
			?>
		</div>
	</div>
</div>