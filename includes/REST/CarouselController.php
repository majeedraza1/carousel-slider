<?php

namespace CarouselSlider\REST;

use CarouselSlider\Helper;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * CarouselController
 */
class CarouselController extends ApiController {

	/**
	 * Registers the routes for the objects of the controller.
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/carousels',
			[
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_items' ],
					'permission_callback' => [ $this, 'get_items_permissions_check' ],
					'args'                => $this->get_collection_params(),
				],
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'create_item' ],
					'permission_callback' => [ $this, 'create_item_permissions_check' ],
					'args'                => [
						'title' => [
							'description'       => __( 'The carousel slider title', 'carousel-slider' ),
							'type'              => 'string',
							'required'          => true,
							'sanitize_callback' => 'sanitize_text_field',
							'validate_callback' => 'rest_validate_request_arg',
						],
						'type'  => [
							'description'       => __( 'The carousel slider type', 'carousel-slider' ),
							'type'              => 'string',
							'required'          => true,
							'sanitize_callback' => 'sanitize_text_field',
							'validate_callback' => 'rest_validate_request_arg',
							'enum'              => Helper::get_enabled_slider_types_slug(),
						],
					],
				],
			]
		);
		register_rest_route(
			$this->namespace,
			'/carousels/(?P<id>[\d]+)',
			[
				'args' => [
					'id' => [
						'description' => __( 'Unique identifier for the carousel.', 'carousel-slider' ),
						'type'        => 'integer',
					],
				],
				[
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => [ $this, 'delete_item' ],
					'permission_callback' => [ $this, 'delete_item_permissions_check' ],
					'args'                => [
						'force' => [
							'type'        => 'boolean',
							'default'     => false,
							'description' => __( 'Whether to bypass Trash and force deletion.', 'carousel-slider' ),
						],
					],
				],
			]
		);
	}

	/**
	 * Checks if a given request has access to get items.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return true|WP_Error True if the request has read access, WP_Error object otherwise.
	 */
	public function get_items_permissions_check( $request ) {
		$post_type = get_post_type_object( CAROUSEL_SLIDER_POST_TYPE );
		if ( ! current_user_can( $post_type->cap->edit_posts ) ) {
			return new WP_Error(
				'rest_forbidden_context',
				__( 'Sorry, you are not allowed to edit sliders.', 'carousel-slider' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		return true;
	}

	/**
	 * Retrieves a collection of items.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	public function get_items( $request ) {
		$search   = $request->get_param( 'search' );
		$page     = (int) $request->get_param( 'page' );
		$per_page = (int) $request->get_param( 'per_page' );

		$args = [
			'posts_per_page' => $per_page,
			'paged'          => $page,
		];
		if ( ! empty( $search ) ) {
			$args['s'] = $search;
		}
		$get_sliders = Helper::get_sliders( $args );

		$items = [];
		foreach ( $get_sliders as $slider ) {
			$items[] = [
				'id'        => $slider->ID,
				'title'     => $slider->post_title,
				'type'      => get_post_meta( $slider->ID, '_slide_type', true ),
				'edit_link' => get_edit_post_link( $slider ),
			];
		}

		$counts = wp_count_posts( CAROUSEL_SLIDER_POST_TYPE );
		$count  = $counts->publish ?? 0;

		return $this->respond_ok(
			[
				'pagination' => $this->get_pagination_data( $count, $per_page, $page ),
				'items'      => $items,
			]
		);
	}

	/**
	 * Checks if a given request has access to create items.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return true|WP_Error True if the request has access to create items, WP_Error object otherwise.
	 */
	public function create_item_permissions_check( $request ) {
		$post_type = get_post_type_object( CAROUSEL_SLIDER_POST_TYPE );
		if ( ! current_user_can( $post_type->cap->create_posts ) ) {
			return new WP_Error(
				'rest_cannot_create',
				__( 'Sorry, you are not allowed to create posts as this user.', 'carousel-slider' ),
				[ 'status' => rest_authorization_required_code() ]
			);
		}

		return true;
	}

	/**
	 * Creates one item from the collection.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	public function create_item( $request ) {
		$id = Helper::create_slider(
			$request->get_param( 'title' ),
			$request->get_param( 'type' )
		);

		if ( is_wp_error( $id ) ) {
			return $this->respond_unprocessable_entity( $id );
		}

		$post = get_post( $id );

		$response_data = [
			'id'        => $post->ID,
			'title'     => $post->post_title,
			'type'      => get_post_meta( $post->ID, '_slide_type', true ),
			'edit_link' => get_edit_post_link( $post ),
		];

		do_action( 'carousel_slider/rest_create_slider', $response_data, $request->get_params() );

		return $this->respond_created( $response_data );
	}

	/**
	 * Checks if a given request has access to delete a post.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return true|WP_Error True if the request has access to delete the item, WP_Error object otherwise.
	 */
	public function delete_item_permissions_check( $request ) {
		$post = get_post( $request['id'] );
		if ( ! $post instanceof \WP_Post ) {
			return new WP_Error(
				'rest_no_item_found',
				__( 'Sorry, no item found for your request.', 'carousel-slider' ),
				array( 'status' => 404 )
			);
		}

		$post_type = get_post_type_object( CAROUSEL_SLIDER_POST_TYPE );

		if ( ! current_user_can( $post_type->cap->delete_post, $post ) ) {
			return new WP_Error(
				'rest_cannot_delete',
				__( 'Sorry, you are not allowed to delete this post.', 'carousel-slider' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		return true;
	}

	/**
	 * Deletes one item from the collection.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	public function delete_item( $request ) {
		// As we validated slider existence on permission_callback, no need to validate again.
		$force = $request->get_param( 'force' );
		$post  = get_post( (int) $request->get_param( 'id' ) );

		if ( $force ) {
			wp_delete_post( $post->ID, true );

			return $this->respond_ok(
				[
					'deleted'  => true,
					'previous' => [ 'id' => $post->ID ],
				]
			);
		}

		// Otherwise, only trash if we haven't already.
		if ( 'trash' === $post->post_status ) {
			$this->set_status_code( 410 );

			return $this->respond_with_error(
				'rest_already_trashed',
				__( 'The post has already been deleted.', 'carousel-slider' )
			);
		}

		$result = wp_trash_post( $post->ID );
		if ( ! $result ) {
			return $this->respond_with_wp_error(
				new WP_Error(
					'rest_cannot_delete',
					__( 'The post cannot be deleted.', 'carousel-slider' ),
					[ 'status' => 500 ]
				)
			);
		}

		return $this->respond_ok( [ 'id' => $post->ID ] );
	}
}
