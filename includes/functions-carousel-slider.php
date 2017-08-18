<?php

if ( ! function_exists( 'carousel_slider_is_url' ) ) {
	/**
	 * Check if url is valid as per RFC 2396 Generic Syntax
	 *
	 * @param  string $url
	 *
	 * @return boolean
	 */
	function carousel_slider_is_url( $url ) {
		if ( filter_var( $url, FILTER_VALIDATE_URL ) ) {

			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'carousel_slider_get_meta' ) ) {
	/**
	 * Get post meta by id and key
	 *
	 * @param $id
	 * @param $key
	 * @param null $default
	 *
	 * @return string
	 */
	function carousel_slider_get_meta( $id, $key, $default = null ) {
		$meta = get_post_meta( $id, $key, true );

		if ( empty( $meta ) && $default ) {
			$meta = $default;
		}

		if ( $meta == 'zero' ) {
			$meta = '0';
		}
		if ( $meta == 'on' ) {
			$meta = 'true';
		}
		if ( $meta == 'off' ) {
			$meta = 'false';
		}
		if ( $key == '_margin_right' && $meta == 0 ) {
			$meta = '0';
		}

		return esc_attr( $meta );
	}
}

if ( ! function_exists( 'carousel_slider_array_to_attribute' ) ) {
	/**
	 * Convert array to html data attribute
	 *
	 * @param $array
	 *
	 * @return array|string
	 */
	function carousel_slider_array_to_attribute( $array ) {
		if ( ! is_array( $array ) ) {
			return '';
		}

		$attribute = array_map( function ( $key, $value ) {
			// If boolean value
			if ( is_bool( $value ) ) {
				if ( $value ) {

					return sprintf( '%s="%s"', $key, 'true' );
				} else {

					return sprintf( '%s="%s"', $key, 'false' );
				}
			}
			// If array value
			if ( is_array( $value ) ) {

				return sprintf( '%s="%s"', $key, implode( " ", $value ) );
			}

			// If string value
			return sprintf( '%s="%s"', $key, esc_attr( $value ) );

		}, array_keys( $array ), array_values( $array ) );

		return $attribute;
	}
}

if ( ! function_exists( 'carousel_slider_is_woocommerce_active' ) ) {
	/**
	 * Check if WooCommerce is active
	 *
	 * @return bool
	 */
	function carousel_slider_is_woocommerce_active() {

		if ( in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ) ) ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'carousel_slider_posts' ) ) {
	/**
	 * Get post by carousel slider ID
	 *
	 * @param $carousel_id
	 *
	 * @return array
	 */
	function carousel_slider_posts( $carousel_id ) {
		$id = $carousel_id;
		// Get settings from carousel slider
		$order      = get_post_meta( $id, '_post_order', true );
		$orderby    = get_post_meta( $id, '_post_orderby', true );
		$per_page   = intval( get_post_meta( $id, '_posts_per_page', true ) );
		$query_type = get_post_meta( $id, '_post_query_type', true );
		$query_type = empty( $query_type ) ? 'latest_posts' : $query_type;

		$args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'order'          => $order,
			'orderby'        => $orderby,
			'posts_per_page' => $per_page
		);

		// Get posts by post IDs
		if ( $query_type == 'specific_posts' ) {
			$post_in = explode( ',', get_post_meta( $id, '_post_in', true ) );
			$post_in = array_map( 'intval', $post_in );
			unset( $args['posts_per_page'] );
			$args = array_merge( $args, array( 'post__in' => $post_in ) );
		}

		// Get posts by post catagories IDs
		if ( $query_type == 'post_categories' ) {
			$post_categories = get_post_meta( $id, '_post_categories', true );
			$args            = array_merge( $args, array( 'cat' => $post_categories ) );
		}

		// Get posts by post tags IDs
		if ( $query_type == 'post_tags' ) {
			$post_tags = get_post_meta( $id, '_post_tags', true );
			$post_tags = array_map( 'intval', explode( ',', $post_tags ) );
			$args      = array_merge( $args, array( 'tag__in' => $post_tags ) );
		}

		// Get posts by date range
		if ( $query_type == 'date_range' ) {

			$post_date_after  = get_post_meta( $id, '_post_date_after', true );
			$post_date_before = get_post_meta( $id, '_post_date_before', true );

			if ( $post_date_after && $post_date_before ) {
				$args = array_merge( $args, array(
					'date_query' => array(
						array(
							'after'     => $post_date_after,
							'before'    => $post_date_before,
							'inclusive' => true,
						),
					),
				) );
			} elseif ( $post_date_after ) {
				$args = array_merge( $args, array(
					'date_query' => array(
						array(
							'before'    => $post_date_before,
							'inclusive' => true,
						),
					),
				) );
			} elseif ( $post_date_before ) {
				$args = array_merge( $args, array(
					'date_query' => array(
						array(
							'before'    => $post_date_before,
							'inclusive' => true,
						),
					),
				) );
			}
		}

		$posts = get_posts( $args );

		return $posts;
	}
}