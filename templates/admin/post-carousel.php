<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
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
			$this->form->select( array(
				'id'      => '_post_query_type',
				'name'    => esc_html__( 'Query Type', 'carousel-slider' ),
				'std'     => 'latest_posts',
				'options' => array(
					'latest_posts'    => esc_html__( 'Latest Posts', 'carousel-slider' ),
					'date_range'      => esc_html__( 'Date Range', 'carousel-slider' ),
					'post_categories' => esc_html__( 'Post Categories', 'carousel-slider' ),
					'post_tags'       => esc_html__( 'Post Tags', 'carousel-slider' ),
					'specific_posts'  => esc_html__( 'Specific posts', 'carousel-slider' ),
				),
			) );
			$this->form->date( array(
				'id'   => '_post_date_after',
				'name' => esc_html__( 'Date from', 'carousel-slider' ),
				'desc' => sprintf( esc_html__( 'Example: %s', 'carousel-slider' ), date( 'F d, Y', strtotime( '-3 months' ) ) ),
			) );
			$this->form->date( array(
				'id'   => '_post_date_before',
				'name' => esc_html__( 'Date to', 'carousel-slider' ),
				'desc' => sprintf( esc_html__( 'Example: %s', 'carousel-slider' ), date( 'F d, Y', strtotime( '-7 days' ) ) ),
			) );
			$this->form->post_terms( array(
				'id'       => '_post_categories',
				'taxonomy' => 'category',
				'multiple' => true,
				'name'     => esc_html__( 'Post Categories', 'carousel-slider' ),
				'desc'     => esc_html__( 'Show posts associated with selected categories.', 'carousel-slider' ),
			) );
			$this->form->post_terms( array(
				'id'       => '_post_tags',
				'taxonomy' => 'post_tag',
				'multiple' => true,
				'name'     => esc_html__( 'Post Tags', 'carousel-slider' ),
				'desc'     => esc_html__( 'Show posts associated with selected tags.', 'carousel-slider' ),
			) );
			$this->form->posts_list( array(
				'id'       => '_post_in',
				'multiple' => true,
				'name'     => esc_html__( 'Specific posts', 'carousel-slider' ),
				'desc'     => esc_html__( 'Select posts that you want to show as slider. Select at least 5 posts', 'carousel-slider' ),
			) );
			$this->form->number( array(
				'id'   => '_posts_per_page',
				'name' => esc_html__( 'Posts per page', 'carousel-slider' ),
				'std'  => 12,
				'desc' => esc_html__( 'How many post you want to show on carousel slide.', 'carousel-slider' ),
			) );
			$this->form->select( array(
				'id'      => '_post_order',
				'name'    => esc_html__( 'Order', 'carousel-slider' ),
				'std'     => 'DESC',
				'options' => array(
					'ASC'  => esc_html__( 'Ascending Order', 'carousel-slider' ),
					'DESC' => esc_html__( 'Descending Order', 'carousel-slider' ),
				),
			) );
			$this->form->select( array(
				'id'      => '_post_orderby',
				'name'    => esc_html__( 'Order by', 'carousel-slider' ),
				'std'     => 'ID',
				'options' => array(
					'none'          => esc_html__( 'No order', 'carousel-slider' ),
					'ID'            => esc_html__( 'Post id', 'carousel-slider' ),
					'author'        => esc_html__( 'Post author', 'carousel-slider' ),
					'title'         => esc_html__( 'Post title', 'carousel-slider' ),
					'modified'      => esc_html__( 'Last modified date', 'carousel-slider' ),
					'rand'          => esc_html__( 'Random order', 'carousel-slider' ),
					'comment_count' => esc_html__( 'Number of comments', 'carousel-slider' ),
				),
			) );
			?>
        </div>
    </div>
</div>