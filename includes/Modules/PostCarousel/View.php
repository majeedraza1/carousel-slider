<?php

namespace CarouselSlider\Modules\PostCarousel;

use CarouselSlider\Abstracts\AbstractView;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class View extends AbstractView {

	/**
	 * Render element.
	 * Generates the final HTML on the frontend.
	 */
	public function render() {
		$posts = self::get_posts( $this->get_slider_id() );

		$this->set_total_slides( count( $posts ) );

		$_html = $this->slider_wrapper_start();

		foreach ( $posts as $_post ) {
			global $post;
			$post = $_post;
			setup_postdata( $post );

			do_action( 'carousel_slider/loop/post', $post );

			$html = '<div class="blog-grid-inside">';

			$html .= $this->post_thumbnail( $post );

			$html .= '<header class="entry-header">';
			$html .= $this->post_category( $post );
			$html .= $this->post_title( $post );
			$html .= '</header>';

			$html .= '<div class="entry-summary">';
			$html .= wp_trim_words( wp_strip_all_tags( $post->post_content ), '20', ' ...' );
			$html .= '</div>';

			$html .= $this->post_tag( $post );

			$html .= '<footer class="entry-footer">';
			$html .= $this->post_author( $post );
			$html .= $this->post_date( $post );
			$html .= '</footer>';

			$html .= '</div>';

			$_html .= apply_filters( 'carousel_slider/view/post', $html, $post );
		}
		wp_reset_postdata();

		$_html .= $this->slider_wrapper_end();

		return $_html;
	}

	/**
	 * Get post thumbnail
	 *
	 * @param \WP_Post $post
	 *
	 * @return string
	 */
	public function post_thumbnail( $post ) {
		if ( ! has_post_thumbnail( $post ) ) {
			return '';
		}

		$permalink = esc_url( get_permalink( $post ) );

		if ( $this->lazy_load_image() ) {
			$thumbnail_id = get_post_thumbnail_id( $post );
			$image_src    = wp_get_attachment_image_src( $thumbnail_id, $this->image_size() );

			$html = '<a href="' . $permalink . '" class="post-thumbnail">';
			$html .= '<img class="owl-lazy" data-src="' . $image_src[0] . '"/>';
			$html .= '</a>';

			return $html;
		}

		$post_thumbnail = get_the_post_thumbnail( $post, $this->image_size(), array(
			'alt' => the_title_attribute( array( 'echo' => false ) )
		) );

		$thumbnail = sprintf( '<a class="post-thumbnail" href="%s">%s</a>',
			$permalink, $post_thumbnail );

		return $thumbnail;
	}

	/**
	 * Get post category list
	 *
	 * @param \WP_Post $post
	 *
	 * @return string
	 */
	public function post_category( $post ) {
		$categories_list = get_the_category_list( ', ', '', $post->ID );
		if ( $categories_list ) {
			return '<div class="cat-links">' . $categories_list . '</div>';
		}

		return '';
	}

	/**
	 * Get post title
	 *
	 * @param \WP_Post $post
	 *
	 * @return string
	 */
	public function post_title( $post ) {
		$title = sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">%s</a></h2>',
			esc_url( get_permalink( $post ) ), get_the_title( $post ) );

		return $title;
	}

	/**
	 * Get post tag list
	 *
	 * @param \WP_Post $post
	 *
	 * @return string
	 */
	public function post_tag( $post ) {
		$tags_list = get_the_tag_list( '', ', ', '', $post->ID );
		$html      = '';

		if ( $tags_list ) {
			$html .= '<div class="tags-links">';
			$html .= $tags_list;
			$html .= '</div>';
		}

		return $html;
	}

	/**
	 * Get blog entry author
	 *
	 * @param \WP_Post $post
	 *
	 * @return string
	 */
	public function post_author( $post ) {

		$author_url  = esc_url( get_author_posts_url( intval( $post->post_author ) ) );
		$author_name = esc_html( get_the_author_meta( 'display_name', intval( $post->post_author ) ) );

		$html = '<span class="byline">';
		$html .= '<span class="vcard">' . get_avatar( $post->post_author, 20 ) . '</span> ';
		$html .= sprintf( '<span class="author">%s <a class="url fn n" href="%s">%s</a></span>',
			esc_html__( 'by', 'carousel-slider' ), $author_url, $author_name );
		$html .= '</span>';

		return $html;
	}

	/**
	 * Get blog entry date
	 *
	 * @param \WP_Post $post
	 *
	 * @return string
	 */
	public function post_date( $post ) {
		// @TODO change this to plugin option
		$blog_date_format = get_theme_mod( 'blog_date_format', 'human' );

		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U', $post ) !== get_the_modified_time( 'U', $post ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		if ( $blog_date_format == 'human' ) {
			$_created_time  = sprintf( '%s ago', human_time_diff( get_the_date( 'U', $post ) ) );
			$_modified_time = sprintf( '%s ago', human_time_diff( get_the_modified_date( 'U', $post ) ) );
		} else {
			$_created_time  = get_the_date( '', $post );
			$_modified_time = get_the_modified_date( '', $post );
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c', $post ) ),
			esc_html( $_created_time ),
			esc_attr( get_the_modified_date( 'c', $post ) ),
			esc_html( $_modified_time )
		);

		$date_string = sprintf( '<span class="posted-on"><a href="%s" rel="bookmark">%s</a></span>',
			esc_url( get_permalink( $post ) ), $time_string );

		return $date_string;
	}

	/**
	 * Get posts by carousel slider ID
	 *
	 * @param int $carousel_id
	 *
	 * @return array
	 */
	public static function get_posts( $carousel_id ) {
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

		return get_posts( $args );
	}
}
