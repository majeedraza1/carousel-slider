<?php

namespace CarouselSlider\Modules\PostCarousel;

use CarouselSlider\Supports\MetaBoxForm;

defined( 'ABSPATH' ) || exit;

/**
 * Admin class
 *
 * @package Modules/PostCarousel
 */
class Admin {
	/**
	 * The instance of the class
	 *
	 * @var self
	 */
	protected static $instance;

	/**
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_action( 'carousel_slider/meta_box_content', [ self::$instance, 'meta_box_content' ], 10, 2 );
			add_action( 'carousel_slider/save_slider', [ self::$instance, 'save_slider' ], 10, 2 );
		}

		return self::$instance;
	}

	/**
	 * Save post carousel content
	 *
	 * @param int   $post_id The post id.
	 * @param array $data User submitted data.
	 *
	 * @return void
	 */
	public function save_slider( int $post_id, array $data ) {
		if ( 'post-carousel' !== get_post_meta( $post_id, '_slide_type', true ) ) {
			return;
		}
		if ( ! isset( $data['carousel_slider']['_post_categories'] ) ) {
			update_post_meta( $post_id, '_post_categories', '' );
		}

		if ( ! isset( $data['carousel_slider']['_post_tags'] ) ) {
			update_post_meta( $post_id, '_post_tags', '' );
		}

		if ( ! isset( $data['carousel_slider']['_post_in'] ) ) {
			update_post_meta( $post_id, '_post_in', '' );
		}
	}

	/**
	 * Metabox content
	 *
	 * @param int    $slider_id The slider id.
	 * @param string $slider_type The slider type.
	 *
	 * @return void
	 */
	public function meta_box_content( int $slider_id, string $slider_type ) {
		if ( 'post-carousel' !== $slider_type ) {
			return;
		}
		$form = new MetaBoxForm();
		$form->select(
			array(
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
			)
		);
		$form->date(
			array(
				'id'   => '_post_date_after',
				'name' => esc_html__( 'Date from', 'carousel-slider' ),
				/* translators: 1: an example date string */
				'desc' => sprintf( esc_html__( 'Example: %s', 'carousel-slider' ), gmdate( 'F d, Y', strtotime( '-3 months' ) ) ),
			)
		);
		$form->date(
			array(
				'id'   => '_post_date_before',
				'name' => esc_html__( 'Date to', 'carousel-slider' ),
				/* translators: 1: an example date string */
				'desc' => sprintf( esc_html__( 'Example: %s', 'carousel-slider' ), gmdate( 'F d, Y', strtotime( '-7 days' ) ) ),
			)
		);
		$form->post_terms(
			array(
				'id'       => '_post_categories',
				'taxonomy' => 'category',
				'multiple' => true,
				'name'     => esc_html__( 'Post Categories', 'carousel-slider' ),
				'desc'     => esc_html__( 'Show posts associated with selected categories.', 'carousel-slider' ),
			)
		);
		$form->post_terms(
			array(
				'id'       => '_post_tags',
				'taxonomy' => 'post_tag',
				'multiple' => true,
				'name'     => esc_html__( 'Post Tags', 'carousel-slider' ),
				'desc'     => esc_html__( 'Show posts associated with selected tags.', 'carousel-slider' ),
			)
		);
		$form->posts_list(
			array(
				'id'       => '_post_in',
				'multiple' => true,
				'name'     => esc_html__( 'Specific posts', 'carousel-slider' ),
				'desc'     => esc_html__( 'Select posts that you want to show as slider. Select at least 5 posts', 'carousel-slider' ),
			)
		);
		$form->number(
			array(
				'id'   => '_posts_per_page',
				'name' => esc_html__( 'Posts per page', 'carousel-slider' ),
				'std'  => 12,
				'desc' => esc_html__( 'How many post you want to show on carousel slide.', 'carousel-slider' ),
			)
		);
		$form->select(
			array(
				'id'      => '_post_order',
				'name'    => esc_html__( 'Order', 'carousel-slider' ),
				'std'     => 'DESC',
				'options' => array(
					'ASC'  => esc_html__( 'Ascending Order', 'carousel-slider' ),
					'DESC' => esc_html__( 'Descending Order', 'carousel-slider' ),
				),
			)
		);
		$form->select(
			array(
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
			)
		);
	}
}
