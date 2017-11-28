<?php

namespace CarouselSlider\Modules\PostCarousel;

class View {

	protected static $instance = null;

	/**
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @return View
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action( 'carousel_slider_view', array( $this, 'post_carousel_view' ), 10, 3 );
	}

	/**
	 * Hero carousel view
	 *
	 * @param $id
	 * @param string $slide_type
	 * @param array $slide_options
	 *
	 * @return void
	 */
	public function post_carousel_view( $id, $slide_type, $slide_options ) {

		if ( $slide_type == 'post-carousel' ) {
			$posts = $this->carousel_slider_posts( $id );

			ob_start();
			require CAROUSEL_SLIDER_MODULES . '/post-carousel/views/public/post-carousel.php';
			$html = ob_get_contents();
			ob_end_clean();

			echo apply_filters( 'carousel_slider_posts_carousel', $html, $id, $slide_options, $posts );
		}
	}

	/**
	 * Get posts by carousel slider ID
	 *
	 * @param $carousel_id
	 *
	 * @return array
	 */
	public function carousel_slider_posts( $carousel_id ) {
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

View::init();
