<?php

namespace CarouselSlider\Modules\PostCarousel;

use CarouselSlider\Abstracts\AbstractTemplate;
use WP_Term;

defined( 'ABSPATH' ) || exit;

/**
 * Template class
 *
 * @package Modules/PostCarousel
 */
class Template extends AbstractTemplate {

	/**
	 * Get default image carousel settings
	 *
	 * @return array
	 */
	public static function get_default_settings(): array {
		return wp_parse_args(
			[
				'_slide_type'       => 'post-carousel',
				// Post Carousel Settings.
				'_post_query_type'  => 'latest_posts',
				'_post_date_after'  => '',
				'_post_date_before' => '',
				'_post_categories'  => '',
				'_post_tags'        => '',
				'_post_in'          => '',
				'_posts_per_page'   => '12',
				'_post_orderby'     => 'ID',
				'_post_order'       => 'DESC',
				'_post_height'      => '450',
			],
			parent::get_default_settings()
		);
	}

	/**
	 * Create gallery image carousel with random images
	 *
	 * @param string $slider_title The slider title.
	 * @param array  $args Optional arguments.
	 *
	 * @return int The post ID on success. The value 0 on failure.
	 */
	public static function create( string $slider_title = '', array $args = array() ): int {
		if ( empty( $slider_title ) ) {
			$slider_title = 'Post Carousel with Latest Post';
		}

		$post_id = self::create_slider( $slider_title );

		if ( is_wp_error( $post_id ) ) {
			return 0;
		}

		$data       = wp_parse_args( $args, self::get_default_settings() );
		$query_type = $data['_post_query_type'];

		$query_types = [
			'specific_posts'  => [ '_post_in' => implode( ',', self::get_random_posts_ids() ) ],
			'post_categories' => [ '_post_categories' => implode( ',', self::get_post_categories_ids() ) ],
			'post_tags'       => [ '_post_tags' => implode( ',', self::get_post_tags_ids() ) ],
			'date_range'      => [
				'_post_date_after'  => gmdate( 'Y-m-d', strtotime( '-3 years' ) ),
				'_post_date_before' => gmdate( 'Y-m-d', strtotime( '-2 hours' ) ),
			],
		];

		$default_args = $query_types[ $query_type ] ?? [];

		foreach ( $default_args as $meta_key => $default_value ) {
			if ( empty( $data[ $meta_key ] ) ) {
				$data[ $meta_key ] = $default_value;
			}
		}

		foreach ( $data as $meta_key => $meta_value ) {
			update_post_meta( $post_id, $meta_key, $meta_value );
		}

		return $post_id;
	}

	/**
	 * Get random posts ID
	 *
	 * @return array List of posts ID.
	 */
	private static function get_random_posts_ids(): array {
		$args = [
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'orderby'        => 'rand',
			'posts_per_page' => 10,
		];

		$_posts = get_posts( $args );

		return wp_list_pluck( $_posts, 'ID' );
	}

	/**
	 * Get random post categories id
	 *
	 * @return array List of categories id.
	 */
	private static function get_post_categories_ids(): array {
		$terms = get_terms(
			[
				'taxonomy'   => 'category',
				'hide_empty' => true,
				'number'     => 5,
			]
		);

		return wp_list_pluck( $terms, 'term_id' );
	}

	/**
	 * Get random post tags id
	 *
	 * @return array|WP_Term[] List of tags id.
	 */
	private static function get_post_tags_ids(): array {
		$terms = get_terms(
			[
				'taxonomy'   => 'post_tag',
				'hide_empty' => true,
				'number'     => 5,
			]
		);

		return wp_list_pluck( $terms, 'term_id' );
	}
}
