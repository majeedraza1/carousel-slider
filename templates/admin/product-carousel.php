<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div data-id="open" id="section_product_query" class="shapla-toggle shapla-toggle--stroke"
     style="display: <?php echo $slide_type != 'product-carousel' ? 'none' : 'block'; ?>">
	<span class="shapla-toggle-title">
		<?php esc_html_e( 'Product Query', 'carousel-slider' ); ?>
	</span>
    <div class="shapla-toggle-inner">
        <div class="shapla-toggle-content">
			<?php
			$this->form->select( array(
				'id'      => '_product_query_type',
				'name'    => esc_html__( 'Query Type', 'carousel-slider' ),
				'std'     => 'query_porduct',
				'options' => array(
					'query_porduct'      => esc_html__( 'Query Products', 'carousel-slider' ),
					'product_categories' => esc_html__( 'Product Categories', 'carousel-slider' ),
					'product_tags'       => esc_html__( 'Product Tags', 'carousel-slider' ),
					'specific_products'  => esc_html__( 'Specific Products', 'carousel-slider' ),
				),
			) );
			$this->form->select( array(
				'id'      => '_product_query',
				'name'    => esc_html__( 'Choose Query', 'carousel-slider' ),
				'std'     => 'featured',
				'options' => array(
					'featured'                => esc_html__( 'Featured Products', 'carousel-slider' ),
					'recent'                  => esc_html__( 'Recent Products', 'carousel-slider' ),
					'sale'                    => esc_html__( 'Sale Products', 'carousel-slider' ),
					'best_selling'            => esc_html__( 'Best-Selling Products', 'carousel-slider' ),
					'top_rated'               => esc_html__( 'Top Rated Products', 'carousel-slider' ),
					'product_categories_list' => esc_html__( 'Product Categories List', 'carousel-slider' ),
				),
			) );
			$this->form->post_terms( array(
				'id'       => '_product_categories',
				'taxonomy' => 'product_cat',
				'multiple' => true,
				'name'     => esc_html__( 'Product Categories', 'carousel-slider' ),
				'desc'     => esc_html__( 'Show products associated with selected categories.', 'carousel-slider' ),
			) );
			$this->form->post_terms( array(
				'id'       => '_product_tags',
				'taxonomy' => 'product_tag',
				'multiple' => true,
				'name'     => esc_html__( 'Product Tags', 'carousel-slider' ),
				'desc'     => esc_html__( 'Show products associated with selected tags.', 'carousel-slider' ),
			) );
			$this->form->posts_list( array(
				'id'        => '_product_in',
				'post_type' => 'product',
				'multiple'  => true,
				'name'      => esc_html__( 'Specific products', 'carousel-slider' ),
				'desc'      => esc_html__( 'Select products that you want to show as slider. Select at least 5 products', 'carousel-slider' ),
			) );
			$this->form->number( array(
				'id'   => '_products_per_page',
				'name' => esc_html__( 'Product per page', 'carousel-slider' ),
				'std'  => 12,
				'desc' => esc_html__( 'How many products you want to show on carousel slide.', 'carousel-slider' ),
			) );
			$this->form->checkbox( array(
				'id'    => '_product_title',
				'name'  => esc_html__( 'Show Title', 'carousel-slider' ),
				'label' => esc_html__( 'Show Title.', 'carousel-slider' ),
				'desc'  => esc_html__( 'Check to show product title.', 'carousel-slider' ),
				'std'   => 'on'
			) );
			$this->form->checkbox( array(
				'id'    => '_product_rating',
				'name'  => esc_html__( 'Show Rating', 'carousel-slider' ),
				'label' => esc_html__( 'Show Rating.', 'carousel-slider' ),
				'desc'  => esc_html__( 'Check to show product rating.', 'carousel-slider' ),
				'std'   => 'on'
			) );
			$this->form->checkbox( array(
				'id'    => '_product_price',
				'name'  => esc_html__( 'Show Price', 'carousel-slider' ),
				'label' => esc_html__( 'Show Price.', 'carousel-slider' ),
				'desc'  => esc_html__( 'Check to show product price.', 'carousel-slider' ),
				'std'   => 'on'
			) );
			$this->form->checkbox( array(
				'id'    => '_product_cart_button',
				'name'  => esc_html__( 'Show Cart Button', 'carousel-slider' ),
				'label' => esc_html__( 'Show Cart Button.', 'carousel-slider' ),
				'desc'  => esc_html__( 'Check to show product add to cart button.', 'carousel-slider' ),
				'std'   => 'on'
			) );
			$this->form->checkbox( array(
				'id'    => '_product_onsale',
				'name'  => esc_html__( 'Show Sale Tag', 'carousel-slider' ),
				'label' => esc_html__( 'Show Sale Tag', 'carousel-slider' ),
				'desc'  => esc_html__( 'Check to show product sale tag for onsale products.', 'carousel-slider' ),
				'std'   => 'on'
			) );
			$this->form->checkbox( array(
				'id'    => '_product_wishlist',
				'name'  => esc_html__( 'Show Wishlist Button', 'carousel-slider' ),
				'label' => esc_html__( 'Show Wishlist Button', 'carousel-slider' ),
				'std'   => 'off',
				'desc'  => sprintf( esc_html__( 'Check to show wishlist button. This feature needs %s plugin to be installed.', 'carousel-slider' ), sprintf( '<a href="https://wordpress.org/plugins/yith-woocommerce-wishlist/" target="_blank" >%s</a>', __( 'YITH WooCommerce Wishlist', 'carousel-slider' ) ) ),
			) );
			$this->form->checkbox( array(
				'id'    => '_product_quick_view',
				'name'  => esc_html__( 'Show Quick View', 'carousel-slider' ),
				'label' => esc_html__( 'Show Quick View', 'carousel-slider' ),
				'desc'  => esc_html__( 'Check to show quick view button.', 'carousel-slider' ),
				'std'   => 'on'
			) );
			$this->form->color( array(
				'id'   => '_product_title_color',
				'type' => 'color',
				'name' => esc_html__( 'Title Color', 'carousel-slider' ),
				'desc' => esc_html__( 'Pick a color for product title. This color will also apply to sale tag and price.', 'carousel-slider' ),
				'std'  => carousel_slider_default_settings()->product_title_color,
			) );
			$this->form->color( array(
				'id'   => '_product_button_bg_color',
				'type' => 'color',
				'name' => esc_html__( 'Button Background Color', 'carousel-slider' ),
				'desc' => esc_html__( 'Pick a color for button background color. This color will also apply to product rating.', 'carousel-slider' ),
				'std'  => carousel_slider_default_settings()->product_button_bg_color
			) );
			$this->form->color( array(
				'id'   => '_product_button_text_color',
				'type' => 'color',
				'name' => esc_html__( 'Button Text Color', 'carousel-slider' ),
				'desc' => esc_html__( 'Pick a color for button text color.', 'carousel-slider' ),
				'std'  => carousel_slider_default_settings()->product_button_text_color
			) );
			?>
        </div>
    </div>
</div>