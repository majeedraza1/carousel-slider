<?php

use CarouselSlider\Supports\Form;
use CarouselSlider\Supports\Utils;

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
			echo Form::select( array(
				'id'               => '_product_query_type',
				'label'            => esc_html__( 'Query Type', 'carousel-slider' ),
				'default'          => 'query_porduct',
				'choices'          => array(
					'query_porduct'      => esc_html__( 'Query Products', 'carousel-slider' ),
					'product_categories' => esc_html__( 'Product Categories', 'carousel-slider' ),
					'product_tags'       => esc_html__( 'Product Tags', 'carousel-slider' ),
					'specific_products'  => esc_html__( 'Specific Products', 'carousel-slider' ),
				),
				'input_attributes' => array( 'class' => 'sp-input-text select2 product_query_type' ),
			) );
			echo Form::select( array(
				'id'               => '_product_query',
				'label'            => esc_html__( 'Choose Query', 'carousel-slider' ),
				'default'          => 'featured',
				'choices'          => array(
					'featured'                => esc_html__( 'Featured Products', 'carousel-slider' ),
					'recent'                  => esc_html__( 'Recent Products', 'carousel-slider' ),
					'sale'                    => esc_html__( 'Sale Products', 'carousel-slider' ),
					'best_selling'            => esc_html__( 'Best-Selling Products', 'carousel-slider' ),
					'top_rated'               => esc_html__( 'Top Rated Products', 'carousel-slider' ),
					'product_categories_list' => esc_html__( 'Product Categories List', 'carousel-slider' ),
				),
				'input_attributes' => array( 'class' => 'sp-input-text select2 product_query' ),
			) );
			echo Form::post_terms( array(
				'id'               => '_product_categories',
				'taxonomy'         => 'product_cat',
				'multiple'         => true,
				'label'            => esc_html__( 'Product Categories', 'carousel-slider' ),
				'description'      => esc_html__( 'Show products associated with selected categories.', 'carousel-slider' ),
				'input_attributes' => array( 'class' => 'sp-input-text select2 product_categories' ),
			) );
			echo Form::post_terms( array(
				'id'               => '_product_tags',
				'taxonomy'         => 'product_tag',
				'multiple'         => true,
				'label'            => esc_html__( 'Product Tags', 'carousel-slider' ),
				'description'      => esc_html__( 'Show products associated with selected tags.', 'carousel-slider' ),
				'input_attributes' => array( 'class' => 'sp-input-text select2 product_tags' ),
			) );

			echo Form::posts_list( array(
				'id'               => '_product_in',
				'post_type'        => 'product',
				'multiple'         => true,
				'label'            => esc_html__( 'Specific products', 'carousel-slider' ),
				'description'      => esc_html__( 'Select products that you want to show as slider. Select at least 5 products', 'carousel-slider' ),
				'input_attributes' => array( 'class' => 'sp-input-text select2 product_in' ),
			) );

			echo Form::text( array(
				'type'             => 'number',
				'id'               => '_products_per_page',
				'label'            => esc_html__( 'Product per page', 'carousel-slider' ),
				'default'          => 12,
				'description'      => esc_html__( 'How many products you want to show on carousel slide.', 'carousel-slider' ),
				'input_attributes' => array( 'class' => 'sp-input-text products_per_page' ),
			) );

			echo Form::field( array(
				'type'        => 'toggle',
				'id'          => '_product_title',
				'label'       => esc_html__( 'Show Title.', 'carousel-slider' ),
				'description' => esc_html__( 'Check to show product title.', 'carousel-slider' ),
				'default'     => 'on'
			) );

			echo Form::field( array(
				'type'        => 'toggle',
				'id'          => '_product_rating',
				'label'       => esc_html__( 'Show Rating.', 'carousel-slider' ),
				'description' => esc_html__( 'Check to show product rating.', 'carousel-slider' ),
				'default'     => 'on'
			) );

			echo Form::field( array(
				'type'        => 'toggle',
				'id'          => '_product_price',
				'label'       => esc_html__( 'Show Price.', 'carousel-slider' ),
				'description' => esc_html__( 'Check to show product price.', 'carousel-slider' ),
				'default'     => 'on'
			) );

			echo Form::field( array(
				'type'        => 'toggle',
				'id'          => '_product_cart_button',
				'label'       => esc_html__( 'Show Cart Button.', 'carousel-slider' ),
				'description' => esc_html__( 'Check to show product add to cart button.', 'carousel-slider' ),
				'default'     => 'on'
			) );

			echo Form::field( array(
				'type'        => 'toggle',
				'id'          => '_product_onsale',
				'label'       => esc_html__( 'Show Sale Tag', 'carousel-slider' ),
				'description' => esc_html__( 'Check to show product sale tag for onsale products.', 'carousel-slider' ),
				'default'     => 'on'
			) );

			echo Form::field( array(
				'type'        => 'toggle',
				'id'          => '_product_wishlist',
				'label'       => esc_html__( 'Show Wishlist Button', 'carousel-slider' ),
				'description' => sprintf( esc_html__( 'Check to show wishlist button. This feature needs %s plugin to be installed.', 'carousel-slider' ), sprintf( '<a href="https://wordpress.org/plugins/yith-woocommerce-wishlist/" target="_blank" >%s</a>', __( 'YITH WooCommerce Wishlist', 'carousel-slider' ) ) ),
				'default'     => 'off',
			) );

			echo Form::field( array(
				'type'        => 'toggle',
				'id'          => '_product_quick_view',
				'label'       => esc_html__( 'Show Quick View', 'carousel-slider' ),
				'description' => esc_html__( 'Check to show quick view button.', 'carousel-slider' ),
				'default'     => 'on'
			) );

			echo Form::field( array(
				'type'        => 'color',
				'id'          => '_product_title_color',
				'label'       => esc_html__( 'Title Color', 'carousel-slider' ),
				'description' => esc_html__( 'Pick a color for product title. This color will also apply to sale tag and price.', 'carousel-slider' ),
				'default'     => Utils::get_default_setting( 'product_title_color' ),
			) );

			echo Form::field( array(
				'type'        => 'color',
				'id'          => '_product_button_bg_color',
				'label'       => esc_html__( 'Button Background Color', 'carousel-slider' ),
				'description' => esc_html__( 'Pick a color for button background color. This color will also apply to product rating.', 'carousel-slider' ),
				'default'     => Utils::get_default_setting( 'product_button_bg_color' )
			) );

			echo Form::field( array(
				'type'        => 'color',
				'id'          => '_product_button_text_color',
				'label'       => esc_html__( 'Button Text Color', 'carousel-slider' ),
				'description' => esc_html__( 'Pick a color for button text color.', 'carousel-slider' ),
				'default'     => Utils::get_default_setting( 'product_button_text_color' )
			) );
			?>
        </div>
    </div>
</div>