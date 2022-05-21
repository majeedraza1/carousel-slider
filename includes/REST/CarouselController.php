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

		return $this->respond_created( $response_data );
	}
}
