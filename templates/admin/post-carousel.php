<?php

use CarouselSlider\Supports\Metabox;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
?>
<div data-id="open" id="section_post_query" class="shapla-toggle shapla-toggle--stroke"
     style="display: <?php echo $slide_type != 'post-carousel' ? 'none' : 'block'; ?>">
	<span class="shapla-toggle-title">
		<?php esc_html_e( 'Post Query', 'carousel-slider' ); ?>
	</span>
    <div class="shapla-toggle-inner">
        <div class="shapla-toggle-content">
			<?php
			echo Metabox::field( array(
				'type'             => 'select',
				'id'               => '_post_query_type',
				'label'            => esc_html__( 'Query Type', 'carousel-slider' ),
				'default'          => 'latest_posts',
				'choices'          => array(
					'latest_posts'    => esc_html__( 'Latest Posts', 'carousel-slider' ),
					'date_range'      => esc_html__( 'Date Range', 'carousel-slider' ),
					'post_categories' => esc_html__( 'Post Categories', 'carousel-slider' ),
					'post_tags'       => esc_html__( 'Post Tags', 'carousel-slider' ),
					'specific_posts'  => esc_html__( 'Specific posts', 'carousel-slider' ),
				),
				'input_attributes' => array( 'class' => 'sp-input-text post_query_type' ),
			) );

			echo Metabox::field( array(
				'type'             => 'date',
				'id'               => '_post_date_after',
				'label'            => esc_html__( 'Date from', 'carousel-slider' ),
				'description'      => sprintf( esc_html__( 'Example: %s', 'carousel-slider' ), date( 'F d, Y', strtotime( '-3 months' ) ) ),
				'input_attributes' => array( 'class' => 'sp-input-text post_date_after' ),
			) );

			echo Metabox::field( array(
				'type'             => 'date',
				'id'               => '_post_date_before',
				'label'            => esc_html__( 'Date to', 'carousel-slider' ),
				'description'      => sprintf( esc_html__( 'Example: %s', 'carousel-slider' ), date( 'F d, Y', strtotime( '-7 days' ) ) ),
				'input_attributes' => array( 'class' => 'sp-input-text post_date_before' ),
			) );

			echo Metabox::field( array(
				'type'             => 'post_terms',
				'id'               => '_post_categories',
				'taxonomy'         => 'category',
				'label'            => esc_html__( 'Post Categories', 'carousel-slider' ),
				'description'      => esc_html__( 'Show posts associated with selected categories.', 'carousel-slider' ),
				'input_attributes' => array(
					'class'    => 'sp-input-text select2 post_categories',
					'multiple' => true,
				),
			) );

			echo Metabox::field( array(
				'type'             => 'post_terms',
				'id'               => '_post_tags',
				'taxonomy'         => 'post_tag',
				'label'            => esc_html__( 'Post Tags', 'carousel-slider' ),
				'description'      => esc_html__( 'Show posts associated with selected tags.', 'carousel-slider' ),
				'input_attributes' => array(
					'class'    => 'sp-input-text select2 post_tags',
					'multiple' => true,
				),
			) );

			echo Metabox::field( array(
				'type'             => 'posts_list',
				'id'               => '_post_in',
				'label'            => esc_html__( 'Specific posts', 'carousel-slider' ),
				'description'      => esc_html__( 'Select posts that you want to show as slider. Select at least 5 posts', 'carousel-slider' ),
				'input_attributes' => array(
					'class'    => 'sp-input-text select2 post_in',
					'multiple' => true,
				),
			) );

			echo Metabox::field( array(
				'type'             => 'number',
				'id'               => '_posts_per_page',
				'label'            => esc_html__( 'Posts per page', 'carousel-slider' ),
				'default'          => 12,
				'description'      => esc_html__( 'How many post you want to show on carousel slide.', 'carousel-slider' ),
				'input_attributes' => array( 'class' => 'sp-input-text posts_per_page' ),
			) );

			echo Metabox::field( array(
				'type'             => 'select',
				'id'               => '_post_orderby',
				'label'            => esc_html__( 'Order by', 'carousel-slider' ),
				'default'          => 'ID',
				'choices'          => array(
					'none'          => esc_html__( 'No order', 'carousel-slider' ),
					'ID'            => esc_html__( 'Post id', 'carousel-slider' ),
					'author'        => esc_html__( 'Post author', 'carousel-slider' ),
					'title'         => esc_html__( 'Post title', 'carousel-slider' ),
					'modified'      => esc_html__( 'Last modified date', 'carousel-slider' ),
					'rand'          => esc_html__( 'Random order', 'carousel-slider' ),
					'comment_count' => esc_html__( 'Number of comments', 'carousel-slider' ),
				),
				'input_attributes' => array( 'class' => 'sp-input-text' ),
			) );

			echo Metabox::field( array(
				'type'             => 'buttonset',
				'id'               => '_post_order',
				'label'            => esc_html__( 'Order', 'carousel-slider' ),
				'default'          => 'DESC',
				'choices'          => array(
					'ASC'  => esc_html__( 'Ascending', 'carousel-slider' ),
					'DESC' => esc_html__( 'Descending', 'carousel-slider' ),
				),
				'input_attributes' => array( 'class' => 'sp-input-text' ),
			) );

			echo Metabox::field( array(
				'type'             => 'number',
				'id'               => '_post_height',
				'label'            => esc_html__( 'Colums Height', 'carousel-slider' ),
				'description'      => esc_html__( 'Enter colums height for posts carousel in numbers. 450 (px) is perfect when columns width is around 300px or higher. Otherwise you need to change it for perfection.', 'carousel-slider' ),
				'default'          => 450,
				'input_attributes' => array( 'class' => 'sp-input-text' ),
			) );
			?>
        </div>
    </div>
</div>