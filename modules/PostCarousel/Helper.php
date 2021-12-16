<?php

namespace CarouselSlider\Modules\PostCarousel;

use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * Helper class
 *
 * @package Modules/PostCarousel
 */
class Helper {
	/**
	 * Get posts by carousel slider ID
	 *
	 * @param int $slider_id The slider id.
	 *
	 * @return WP_Post[]
	 */
	public static function get_posts( int $slider_id ): array {
		// Get settings from carousel slider.
		$order      = get_post_meta( $slider_id, '_post_order', true );
		$orderby    = get_post_meta( $slider_id, '_post_orderby', true );
		$per_page   = intval( get_post_meta( $slider_id, '_posts_per_page', true ) );
		$query_type = get_post_meta( $slider_id, '_post_query_type', true );
		$query_type = empty( $query_type ) ? 'latest_posts' : $query_type;

		$args = [
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'order'          => $order,
			'orderby'        => $orderby,
			'posts_per_page' => $per_page,
		];

		// Get posts by post IDs.
		if ( 'specific_posts' === $query_type ) {
			$post_in = explode( ',', get_post_meta( $slider_id, '_post_in', true ) );
			$post_in = array_map( 'intval', $post_in );
			unset( $args['posts_per_page'] );
			$args = array_merge( $args, array( 'post__in' => $post_in ) );
		}

		// Get posts by post catagories IDs.
		if ( 'post_categories' === $query_type ) {
			$post_categories = get_post_meta( $slider_id, '_post_categories', true );
			$args            = array_merge( $args, array( 'cat' => $post_categories ) );
		}

		// Get posts by post tags IDs.
		if ( 'post_tags' === $query_type ) {
			$post_tags = get_post_meta( $slider_id, '_post_tags', true );
			$post_tags = array_map( 'intval', explode( ',', $post_tags ) );
			$args      = array_merge( $args, [ 'tag__in' => $post_tags ] );
		}

		// Get posts by date range.
		if ( 'date_range' === $query_type ) {

			$post_date_after  = get_post_meta( $slider_id, '_post_date_after', true );
			$post_date_before = get_post_meta( $slider_id, '_post_date_before', true );

			if ( $post_date_after && $post_date_before ) {
				$args = array_merge(
					$args,
					[
						'date_query' => [
							[
								'after'     => $post_date_after,
								'before'    => $post_date_before,
								'inclusive' => true,
							],
						],
					]
				);
			} elseif ( $post_date_after ) {
				$args = array_merge(
					$args,
					[
						'date_query' => [
							[
								'before'    => $post_date_before,
								'inclusive' => true,
							],
						],
					]
				);
			} elseif ( $post_date_before ) {
				$args = array_merge(
					$args,
					[
						'date_query' => [
							[
								'before'    => $post_date_before,
								'inclusive' => true,
							],
						],
					]
				);
			}
		}

		return get_posts( $args );
	}
}
