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
		$raw_data = $data['post_carousel'] ?? [];
		foreach ( $this->get_settings_fields() as $field ) {
			$value = $raw_data[ $field['id'] ] ?? $field['default'];
			if ( is_array( $value ) ) {
				$value = implode( ',', $value );
			}

			update_post_meta( $post_id, $field['id'], sanitize_text_field( $value ) );
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
		foreach ( $this->get_settings_fields() as $field ) {
			\CarouselSlider\Helper::print_unescaped_internal_string( MetaBoxForm::field( $field ) );
		}
	}

	/**
	 * Get setting field
	 *
	 * @return array[]
	 */
	public function get_settings_fields(): array {
		return [
			[
				'group'   => 'post_carousel',
				'type'    => 'select',
				'id'      => '_post_query_type',
				'label'   => esc_html__( 'Query Type', 'carousel-slider' ),
				'default' => 'latest_posts',
				'choices' => [
					'latest_posts'    => esc_html__( 'Latest Posts', 'carousel-slider' ),
					'date_range'      => esc_html__( 'Date Range', 'carousel-slider' ),
					'post_categories' => esc_html__( 'Post Categories', 'carousel-slider' ),
					'post_tags'       => esc_html__( 'Post Tags', 'carousel-slider' ),
					'specific_posts'  => esc_html__( 'Specific posts', 'carousel-slider' ),
				],
			],
			[
				'group'       => 'post_carousel',
				'type'        => 'date',
				'id'          => '_post_date_after',
				'label'       => esc_html__( 'Date from', 'carousel-slider' ),
				/* translators: 1: an example date string */
				'description' => sprintf( esc_html__( 'Example: %s', 'carousel-slider' ), gmdate( 'F d, Y', strtotime( '-3 months' ) ) ),
				'default'     => '',
			],
			[
				'group'       => 'post_carousel',
				'type'        => 'date',
				'id'          => '_post_date_before',
				'label'       => esc_html__( 'Date to', 'carousel-slider' ),
				/* translators: 1: an example date string */
				'description' => sprintf( esc_html__( 'Example: %s', 'carousel-slider' ), gmdate( 'F d, Y', strtotime( '-7 days' ) ) ),
				'default'     => '',
			],
			[
				'group'       => 'post_carousel',
				'type'        => 'post_terms',
				'id'          => '_post_categories',
				'taxonomy'    => 'category',
				'multiple'    => true,
				'label'       => esc_html__( 'Post Categories', 'carousel-slider' ),
				'description' => esc_html__( 'Show posts associated with selected categories.', 'carousel-slider' ),
				'default'     => '',
			],
			[
				'group'       => 'post_carousel',
				'type'        => 'post_terms',
				'id'          => '_post_tags',
				'taxonomy'    => 'post_tag',
				'multiple'    => true,
				'label'       => esc_html__( 'Post Tags', 'carousel-slider' ),
				'description' => esc_html__( 'Show posts associated with selected tags.', 'carousel-slider' ),
				'default'     => '',
			],
			[
				'group'       => 'post_carousel',
				'type'        => 'posts_list',
				'id'          => '_post_in',
				'multiple'    => true,
				'label'       => esc_html__( 'Specific posts', 'carousel-slider' ),
				'description' => esc_html__( 'Select posts that you want to show as slider. Select at least 5 posts', 'carousel-slider' ),
				'default'     => '',
			],
			[
				'group'       => 'post_carousel',
				'type'        => 'number',
				'id'          => '_posts_per_page',
				'label'       => esc_html__( 'Posts per page', 'carousel-slider' ),
				'default'     => 12,
				'description' => esc_html__( 'How many post you want to show on carousel slide.', 'carousel-slider' ),
			],
			[
				'group'   => 'post_carousel',
				'type'    => 'select',
				'id'      => '_post_order',
				'label'   => esc_html__( 'Order', 'carousel-slider' ),
				'default' => 'DESC',
				'choices' => [
					'ASC'  => esc_html__( 'Ascending Order', 'carousel-slider' ),
					'DESC' => esc_html__( 'Descending Order', 'carousel-slider' ),
				],
			],
			[
				'group'   => 'post_carousel',
				'type'    => 'select',
				'id'      => '_post_orderby',
				'label'   => esc_html__( 'Order by', 'carousel-slider' ),
				'default' => 'ID',
				'choices' => [
					'none'          => esc_html__( 'No order', 'carousel-slider' ),
					'ID'            => esc_html__( 'Post id', 'carousel-slider' ),
					'author'        => esc_html__( 'Post author', 'carousel-slider' ),
					'title'         => esc_html__( 'Post title', 'carousel-slider' ),
					'modified'      => esc_html__( 'Last modified date', 'carousel-slider' ),
					'date'          => esc_html__( 'Publication date', 'carousel-slider' ),
					'rand'          => esc_html__( 'Random order', 'carousel-slider' ),
					'comment_count' => esc_html__( 'Number of comments', 'carousel-slider' ),
				],
			],
		];
	}
}
