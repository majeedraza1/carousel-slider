<?php

namespace CarouselSlider\REST;

use CarouselSlider\Admin\MetaBoxConfig;
use CarouselSlider\Helper;
use CarouselSlider\Supports\Sanitize;
use CarouselSlider\Traits\ApiResponse;
use WP_Error;
use WP_REST_Controller;
use WP_REST_Request;

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 * ApiController Class
 *
 * @package CarouselSlider\REST
 */
class ApiController extends WP_REST_Controller {
	use ApiResponse;

	/**
	 * The namespace of this controller's route.
	 *
	 * @var string
	 */
	protected $namespace = 'carousel-slider/v1';

	/**
	 * Get general setting default data
	 *
	 * @var array
	 */
	protected $general_setting_defaults = [];

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
	 * Checks if a given request has access to get a specific item.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return true|WP_Error True if the request has access to delete the item, WP_Error object otherwise.
	 */
	public function get_item_permissions_check( $request ) {
		$post = get_post( $request['id'] );
		if ( ! $post instanceof \WP_Post ) {
			return new WP_Error(
				'rest_no_item_found',
				__( 'Sorry, no item found for your request.', 'carousel-slider' ),
				array( 'status' => 404 )
			);
		}

		$post_type = get_post_type_object( CAROUSEL_SLIDER_POST_TYPE );

		if ( ! current_user_can( $post_type->cap->read_post, $post ) ) {
			return new WP_Error(
				'rest_forbidden_context',
				__( 'Sorry, you are not allowed to view this post.', 'carousel-slider' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		return true;
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
	 * Get general setting arguments property
	 *
	 * @return array[]
	 */
	public function general_setting_args_properties(): array {
		$field_settings = MetaBoxConfig::get_fields_settings();
		$properties     = [];
		foreach ( $field_settings as $key => $args ) {
			$setting = [
				'required'    => false,
				'description' => $this->html_entity_decode( $args['label'] ),
			];
			if ( 'number' === $args['type'] ) {
				$setting['type'] = 'number';
			} elseif ( 'switch' === $args['type'] ) {
				$setting['type'] = 'string';
				$setting['enum'] = [ 'on', 'off' ];
			} elseif ( 'color' === $args['type'] ) {
				$setting['type'] = 'string';
			} elseif ( 'responsive_control' === $args['type'] ) {
				$setting['type'] = [ 'object', 'array' ];
			} elseif ( 'image_sizes' === $args['type'] ) {
				$setting['type'] = 'string';
				$setting['enum'] = array_keys( Helper::get_available_image_sizes() );
			}
			if ( isset( $args['default'] ) ) {
				$setting['default']                     = $args['default'];
				$this->general_setting_defaults[ $key ] = $args['default'];
			}
			if ( isset( $args['choices'] ) && is_array( $args['choices'] ) ) {
				$setting['type'] = 'string';
				foreach ( $args['choices'] as $choice_value => $choice ) {
					if ( is_string( $choice_value ) ) {
						$setting['enum'][] = $choice_value;
					} elseif ( isset( $choice['value'] ) ) {
						$setting['enum'][] = $choice['value'];
					}
				}
			}
			$properties[ $key ] = $setting;
		}

		return $properties;
	}

	/**
	 * Sanitize general settings.
	 *
	 * @param mixed $data Raw data.
	 *
	 * @return array
	 */
	public function sanitize_general_setting( $data ): array {
		if ( ! is_array( $data ) ) {
			return [];
		}

		$sanitized_data = [];
		$field_settings = MetaBoxConfig::get_fields_settings();
		foreach ( $field_settings as $key => $args ) {
			$default = $args['default'] ?? '';
			$value   = $data[ $key ] ?? $default;
			if ( 'number' === $args['type'] ) {
				$sanitized_data[ $key ] = Sanitize::number( $value );
			} elseif ( 'switch' === $args['type'] ) {
				$sanitized_data[ $key ] = Sanitize::checked( $value );
			} elseif ( 'color' === $args['type'] ) {
				$sanitized_data[ $key ] = Sanitize::color( $value );
			} elseif ( 'image_sizes' === $args['type'] ) {
				$sizes                  = array_keys( Helper::get_available_image_sizes() );
				$sanitized_data[ $key ] = in_array( $value, $sizes, true ) ? $value : 'medium_large';
			} else {
				$sanitized_data[ $key ] = Sanitize::deep( $value );
			}
		}

		return $sanitized_data;
	}

	/**
	 * Update slider general settings
	 *
	 * @param int   $slider_id The slider id.
	 * @param array $values The values to be saved.
	 *
	 * @return void
	 */
	public function update_general_setting( int $slider_id, array $values ) {
		$field_settings = MetaBoxConfig::get_fields_settings();
		foreach ( $field_settings as $key => $setting ) {
			$default = $setting['default'] ?? null;
			$value   = $values[ $key ] ?? $default;
			update_post_meta( $slider_id, $setting['id'], $value );
		}
	}

	/**
	 * Generate pagination metadata
	 *
	 * @param int $total_items Total available items.
	 * @param int $per_page Items to show per page.
	 * @param int $current_page The current page.
	 *
	 * @return array
	 */
	protected function get_pagination_data( $total_items = 0, $per_page = 20, $current_page = 1 ): array {
		$current_page = max( intval( $current_page ), 1 );
		$per_page     = max( intval( $per_page ), 1 );
		$total_items  = intval( $total_items );

		return [
			'total_items'  => $total_items,
			'per_page'     => $per_page,
			'current_page' => $current_page,
			'total_pages'  => ceil( $total_items / $per_page ),
		];
	}
}
