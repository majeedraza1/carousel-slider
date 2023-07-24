<?php

namespace CarouselSlider\Modules\HeroCarousel;

use CarouselSlider\REST\ApiController;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Controller class
 */
class Controller extends ApiController {
	/**
	 * Registers the routes for the objects of the controller.
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/carousels/hero',
			[
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'create_item' ],
					'permission_callback' => [ $this, 'create_item_permissions_check' ],
					'args'                => $this->get_create_item_params(),
				],
			]
		);
		register_rest_route(
			$this->namespace,
			'/carousels/hero/(?P<id>[\d]+)',
			[
				'args' => [
					'id' => [
						'description' => __( 'Unique identifier for the carousel.', 'carousel-slider' ),
						'type'        => 'integer',
					],
				],
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_item' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
				],
				[
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'update_item' ],
					'permission_callback' => [ $this, 'create_item_permissions_check' ],
					'args'                => $this->get_create_item_params(),
				],
			]
		);
	}

	/**
	 * Creates one item from the collection.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_REST_Response Response object.
	 */
	public function create_item( $request ) {
		$title            = $request->get_param( 'title' );
		$general_settings = $request->get_param( 'general_settings' );
		$slider_items     = $request->get_param( 'slider_items' );

		return $this->respond_created( $request->get_params() );
	}

	/**
	 * Retrieves one item from the collection.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_REST_Response Response object.
	 */
	public function get_item( $request ) {
		$id   = (int) $request->get_param( 'id' );
		$post = get_post( $id );

		$settings         = new Setting( $id );
		$general_settings = $settings->to_array();
		$items            = $settings->get_slider_items();
		$items            = array_map( [ $this, 'prepare_slider_item_for_response' ], $items );

		if ( isset( $general_settings['slider_settings'] ) ) {
			unset( $general_settings['slider_settings'] );
		}

		return $this->respond_ok(
			[
				'title'            => get_the_title( $post ),
				'slider_type'      => $settings->get_slider_type(),
				'slider_status'    => $post->post_status,
				'global_settings'  => $settings->get_global_settings(),
				'general_settings' => $general_settings,
				'slider_settings'  => $settings->get_content_settings(),
				'slider_items'     => $items,
			]
		);
	}

	/**
	 * Updates one item from the collection.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_REST_Response Response object.
	 */
	public function update_item( $request ) {
		$title            = $request->get_param( 'title' );
		$general_settings = $request->get_param( 'general_settings' );
		$slider_items     = $request->get_param( 'slider_items' );

		return $this->respond_ok(
			[
				'title'            => $title,
				'general_settings' => $general_settings,
				'slider_items'     => $slider_items,
			]
		);
	}

	/**
	 * Sanitize general settings.
	 *
	 * @param mixed $data Raw data.
	 *
	 * @return array
	 */
	public function sanitize_general_setting( $data ): array {
		$sanitized_data = parent::sanitize_general_setting( $data );

		$sanitized_data['type_of_slider']  = 'slider';
		$sanitized_data['slides_per_view'] = [ 'xs' => 1 ];

		return $sanitized_data;
	}

	/**
	 * Prepare slider item for response
	 *
	 * @param Item $item The Item object.
	 *
	 * @return array
	 */
	public function prepare_slider_item_for_response( Item $item ): array {
		$data = [
			'background_type'   => $item->get_background_type(),
			'bg_color'          => $item->get_prop( 'bg_color' ),
			'bg_image'          => [
				'img_id'           => $item->get_prop( 'img_id' ),
				'img_position'     => $item->get_prop( 'img_bg_position' ),
				'img_size'         => $item->get_prop( 'img_bg_size' ),
				'overlay_color'    => $item->get_prop( 'bg_overlay' ),
				'ken_burns_effect' => $item->get_prop( 'ken_burns_effect' ),
			],
			'content_alignment' => $item->get_prop( 'content_alignment' ),
			'content_animation' => $item->get_content_animation(),
			'heading'           => [
				'text'          => $item->get_prop( 'slide_heading' ),
				'font_size'     => $item->get_prop( 'heading_font_size' ),
				'margin_bottom' => $item->get_prop( 'heading_gutter' ),
				'color'         => $item->get_prop( 'heading_color' ),
			],
			'description'       => [
				'text'          => $item->get_prop( 'slide_description' ),
				'font_size'     => $item->get_prop( 'description_font_size' ),
				'margin_bottom' => $item->get_prop( 'description_gutter' ),
				'color'         => $item->get_prop( 'description_color' ),
			],
			'link_type'         => $item->get_link_type(),
			'full_link'         => [
				'url'    => $item->get_prop( 'slide_link' ),
				'target' => $item->get_prop( 'link_target' ),
			],
			'button_link'       => [
				'primary'   => new \ArrayObject(),
				'secondary' => new \ArrayObject(),
			],
		];

		if ( $item->has_button_one() ) {
			$data['button_link']['primary'] = [
				'text'          => $item->get_prop( 'button_one_text' ),
				'url'           => $item->get_prop( 'button_one_url' ),
				'target'        => $item->get_prop( 'button_one_target' ),
				'type'          => $item->get_prop( 'button_one_type' ),
				'size'          => $item->get_prop( 'button_one_size' ),
				'border_width'  => $item->get_prop( 'button_one_border_width' ),
				'border_radius' => $item->get_prop( 'button_one_border_radius' ),
				'bg_color'      => $item->get_prop( 'button_one_bg_color' ),
				'color'         => $item->get_prop( 'button_one_color' ),
			];
		}

		if ( $item->has_button_two() ) {
			$data['button_link']['primary'] = [
				'text'          => $item->get_prop( 'button_two_text' ),
				'url'           => $item->get_prop( 'button_two_url' ),
				'target'        => $item->get_prop( 'button_two_target' ),
				'type'          => $item->get_prop( 'button_two_type' ),
				'size'          => $item->get_prop( 'button_two_size' ),
				'border_width'  => $item->get_prop( 'button_two_border_width' ),
				'border_radius' => $item->get_prop( 'button_two_border_radius' ),
				'bg_color'      => $item->get_prop( 'button_two_bg_color' ),
				'color'         => $item->get_prop( 'button_two_color' ),
			];
		}

		return $data;
	}

	/**
	 * Validate a request argument based on details registered to the route.
	 *
	 * @param mixed $value
	 * @param WP_REST_Request $request
	 * @param string $param
	 *
	 * @return true|WP_Error
	 */
	public function validate_hero_slider_items_args( $value, $request, $param ) {
		if ( ! is_array( $value ) ) {
			return new WP_Error(
				'rest_invalid_type',
				/* translators: 1: Parameter, 2: List of types. */
				__( 'slider_items is not of type array.', 'carousel-slider' ),
				array( 'param' => $param )
			);
		}
		$attributes = $request->get_attributes();
		if ( ! isset( $attributes['args'][ $param ] ) || ! is_array( $attributes['args'][ $param ] ) ) {
			return true;
		}

		$args       = $attributes['args'][ $param ];
		$properties = $args['items']['properties'] ?? [];
		var_dump( $properties );

		foreach ( $value as $settings ) {
			$background_type = $settings['background_type'] ?? '';
		}

		return true;
	}

	/**
	 * Create item parameters
	 *
	 * @return array[]
	 */
	public function get_create_item_params(): array {
		return [
			'title'            => [
				'description'       => __( 'The carousel slider title', 'carousel-slider' ),
				'type'              => 'string',
				'required'          => true,
				'sanitize_callback' => 'sanitize_text_field',
				'validate_callback' => 'rest_validate_request_arg',
			],
			'general_settings' => [
				'description'       => __( 'Carousel settings.', 'carousel-slider' ),
				'type'              => 'object',
				'properties'        => $this->general_setting_args_properties(),
				'default'           => $this->general_setting_defaults,
				'sanitize_callback' => [ $this, 'sanitize_general_setting' ],
			],
			'slider_items'     => [
				'description'       => __( 'Carousel settings.', 'carousel-slider' ),
				'type'              => 'array',
				'required'          => true,
				'items'             => [
					'type'       => 'object',
					'properties' => $this->hero_carousel_setting_args_properties(),
				],
				'validate_callback' => 'rest_validate_request_arg',
//				'validate_callback' => [ $this, 'validate_hero_slider_items_args' ],
			],
		];
	}

	/**
	 * Get general setting arguments property
	 *
	 * @return array[]
	 */
	public function hero_carousel_setting_args_properties(): array {
		return [
			'background_type'   => [
				'type'     => 'string',
				'enum'     => [ 'color', 'image' ],
				'required' => true,
			],
			'bg_color'          => [ 'type' => 'string' ],
			'bg_image'          => [
				'type'       => 'object',
				'properties' => [
					'img_id'           => [ 'type' => 'int' ],
					'img_position'     => [
						'type' => 'string',
						'enum' => array_keys( Helper::background_position() ),
					],
					'img_size'         => [
						'type' => 'string',
						'enum' => array_keys( Helper::background_size() ),
					],
					'overlay_color'    => [ 'type' => 'string' ],
					'ken_burns_effect' => [
						'type' => 'string',
						'enum' => array_keys( Helper::ken_burns_effects() ),
					],
				],
			],
			'content_alignment' => [
				'required' => true,
				'type'     => 'string',
				'enum'     => array_keys( Helper::text_alignment() ),
			],
			'content_animation' => [
				'type' => 'string',
				'enum' => array_keys( Helper::animations() ),
			],
			'heading'           => [
				'required'   => true,
				'type'       => 'object',
				'properties' => $this->get_section_text_properties(),
			],
			'description'       => [
				'type'       => 'object',
				'properties' => $this->get_section_text_properties(),
			],
			'link_type'         => [
				'required' => true,
				'type'     => 'string',
				'enum'     => array_keys( Helper::link_type() ),
			],
			'full_link'         => [
				'type'       => 'object',
				'properties' => [
					'url'    => [ 'type' => 'string' ],
					'target' => [
						'type' => 'string',
						'enum' => array_keys( Helper::link_target() ),
					],
				],
			],
			'button_link'       => [
				'type'       => 'object',
				'properties' => [
					'primary'   => [
						'type'       => 'object',
						'properties' => $this->get_button_properties(),
					],
					'secondary' => [
						'type'       => 'object',
						'properties' => $this->get_button_properties(),
					],
				],
			],
		];
	}

	/**
	 * Get section text properties
	 *
	 * @return array[]
	 */
	protected function get_section_text_properties(): array {
		return [
			'text'          => [ 'type' => 'string' ],
			'font_size'     => [ 'type' => 'string' ],
			'margin_bottom' => [ 'type' => 'string' ],
			'color'         => [ 'type' => 'string' ],
		];
	}

	/**
	 * Get button properties
	 *
	 * @return array
	 */
	protected function get_button_properties(): array {
		return [
			'text'          => [ 'type' => 'string' ],
			'url'           => [ 'type' => 'string' ],
			'target'        => [ 'type' => 'string' ],
			'type'          => [
				'type' => 'string',
				'enum' => array_keys( Helper::button_type() ),
			],
			'size'          => [
				'type' => 'string',
				'enum' => array_keys( Helper::button_size() ),
			],
			'border_width'  => [ 'type' => 'string' ],
			'border_radius' => [ 'type' => 'string' ],
			'bg_color'      => [ 'type' => 'string' ],
			'color'         => [ 'type' => 'string' ],
		];
	}
}
