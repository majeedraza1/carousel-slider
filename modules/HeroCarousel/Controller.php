<?php

namespace CarouselSlider\Modules\HeroCarousel;

use CarouselSlider\REST\ApiController;
use CarouselSlider\Supports\Sanitize;
use WP_Error;
use WP_Post;
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
		$title = $request->get_param( 'title' );

		$slider_id = \CarouselSlider\Helper::create_slider( $title, 'hero-banner-slider' );
		if ( is_wp_error( $slider_id ) ) {
			return $this->respond_with_wp_error( $slider_id );
		}

		$general_settings = $request->get_param( 'general_settings' );
		$this->update_general_setting( $slider_id, $general_settings );

		$items        = $request->get_param( 'slider_items' );
		$slider_items = array_map( [ $this, 'prepare_slider_item_for_database' ], $items );
		update_post_meta( $slider_id, '_content_slider', $slider_items );

		$slider_settings = $request->get_param( 'slider_settings' );
		update_post_meta( $slider_id, '_content_slider_settings', $slider_settings );

		$settings         = new Setting( $slider_id );
		$general_settings = $settings->to_array();

		if ( isset( $general_settings['slider_settings'] ) ) {
			unset( $general_settings['slider_settings'] );
		}

		return $this->respond_created(
			[
				'title'            => $title,
				'slider_type'      => $settings->get_slider_type(),
				'slider_status'    => get_post_status( $slider_id ),
				'global_settings'  => $settings->get_global_settings(),
				'general_settings' => $general_settings,
				'slider_settings'  => $settings->get_content_settings(),
				'slider_items'     => $items,
			]
		);
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
		$slider_id = (int) $request->get_param( 'id' );
		$post      = get_post( $slider_id );
		if ( ! ( $post instanceof WP_Post && CAROUSEL_SLIDER_POST_TYPE === $post->post_type ) ) {
			return $this->respond_not_found();
		}

		$title = $request->get_param( 'title' );
		if ( get_the_title( $post ) !== $title ) {
			wp_update_post(
				[
					'ID'    => $slider_id,
					'title' => $title,
				]
			);
		}

		$general_settings = $request->get_param( 'general_settings' );
		$this->update_general_setting( $slider_id, $general_settings );

		$items        = $request->get_param( 'slider_items' );
		$slider_items = array_map( [ $this, 'prepare_slider_item_for_database' ], $items );
		update_post_meta( $slider_id, '_content_slider', $slider_items );

		$slider_settings = $request->get_param( 'slider_settings' );
		update_post_meta( $slider_id, '_content_slider_settings', $slider_settings );

		$settings         = new Setting( $slider_id );
		$general_settings = $settings->to_array();

		if ( isset( $general_settings['slider_settings'] ) ) {
			unset( $general_settings['slider_settings'] );
		}

		return $this->respond_ok(
			[
				'title'            => $title,
				'slider_type'      => $settings->get_slider_type(),
				'slider_status'    => get_post_status( $slider_id ),
				'global_settings'  => $settings->get_global_settings(),
				'general_settings' => $general_settings,
				'slider_settings'  => $settings->get_content_settings(),
				'slider_items'     => $items,
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
	 * Sanitize hero slider settings
	 *
	 * @param mixed $data The raw data.
	 *
	 * @return array
	 */
	public function sanitize_hero_slider_settings( $data ): array {
		$default = [
			'slide_height'      => '400px',
			'content_width'     => '850px',
			'content_animation' => 'zoomIn',
			'slide_padding'     => [
				'top'    => '1rem',
				'right'  => '1rem',
				'bottom' => '1rem',
				'left'   => '1rem',
			],
		];
		if ( ! is_array( $data ) ) {
			return $default;
		}

		return wp_parse_args( $data, $default );
	}

	/**
	 * Sanitize hero slider items
	 *
	 * @param mixed $data The data to be sanitized.
	 *
	 * @return array
	 */
	public function sanitize_hero_slider_items( $data ): array {
		if ( ! is_array( $data ) ) {
			return [];
		}
		$sanitized_data = [];
		foreach ( $data as $index => $value ) {
			if ( ! is_array( $value ) ) {
				continue;
			}
			$sanitized_data[] = $this->sanitize_hero_slider_item( $value );
		}

		return $sanitized_data;
	}

	/**
	 * Sanitize hero slider item.
	 *
	 * @param array $value The value to be sanitized.
	 *
	 * @return array
	 */
	public function sanitize_hero_slider_item( array $value ): array {
		$sanitized_data = [
			'background_type' => $value['background_type'],
		];

		if ( 'image' === $value['background_type'] && is_array( $value['bg_image'] ) ) {
			$default                    = [
				'img_id'           => '',
				'img_position'     => Item::get_default_value( 'img_bg_position' ),
				'img_size'         => Item::get_default_value( 'img_bg_size' ),
				'overlay_color'    => Item::get_default_value( 'bg_overlay' ),
				'ken_burns_effect' => Item::get_default_value( 'ken_burns_effect' ),
			];
			$bg_image                   = wp_parse_args( $value['bg_image'], $default );
			$sanitized_data['bg_image'] = [
				'img_id'           => Sanitize::int( $bg_image['img_id'] ),
				'img_position'     => Sanitize::text( $bg_image['img_position'] ),
				'img_size'         => Sanitize::text( $bg_image['img_size'] ),
				'overlay_color'    => Sanitize::color( $bg_image['overlay_color'] ),
				'ken_burns_effect' => Sanitize::text( $bg_image['ken_burns_effect'] ),
			];
		}

		if ( 'color' === $value['background_type'] ) {
			$sanitized_data['bg_color'] = Sanitize::color( $value['bg_color'] );
		}

		$sanitized_data['content_alignment'] = $value['content_alignment'] ?? Item::get_default_value( 'content_alignment' );
		$sanitized_data['content_animation'] = $value['content_animation'] ?? '';

		$heading_default           = [
			'text'          => Item::get_default_value( 'slide_heading' ),
			'font_size'     => Item::get_default_value( 'heading_font_size' ),
			'margin_bottom' => Item::get_default_value( 'heading_gutter' ),
			'color'         => Item::get_default_value( 'heading_color' ),
		];
		$sanitized_data['heading'] = wp_parse_args( $value['heading'], $heading_default );

		if ( ! empty( $value['description']['text'] ) ) {
			$description_default           = [
				'text'          => Item::get_default_value( 'slide_description' ),
				'font_size'     => Item::get_default_value( 'description_font_size' ),
				'margin_bottom' => Item::get_default_value( 'description_gutter' ),
				'color'         => Item::get_default_value( 'description_color' ),
			];
			$sanitized_data['description'] = wp_parse_args( $value['description'], $description_default );
		}

		$sanitized_data['link_type'] = $value['link_type'] ?? Item::get_default_value( 'link_type' );

		if ( 'full' === $sanitized_data['link_type'] ) {
			$sanitized_data['full_link'] = [
				'url'    => $value['full_link']['url'],
				'target' => $value['full_link']['target'] ?? Item::get_default_value( 'link_target' ),
			];
		}
		if ( 'button' === $sanitized_data['link_type'] ) {
			if ( ! empty( $value['button_link']['primary']['text'] ) && ! empty( $value['button_link']['primary']['url'] ) ) {
				$primary_button_default = [
					'text'          => Item::get_default_value( 'button_one_text' ),
					'url'           => Item::get_default_value( 'button_one_url' ),
					'target'        => Item::get_default_value( 'button_one_target' ),
					'type'          => Item::get_default_value( 'button_one_type' ),
					'size'          => Item::get_default_value( 'button_one_size' ),
					'border_width'  => Item::get_default_value( 'button_one_border_width' ),
					'border_radius' => Item::get_default_value( 'button_one_border_radius' ),
					'bg_color'      => Item::get_default_value( 'button_one_bg_color' ),
					'color'         => Item::get_default_value( 'button_one_color' ),
				];
				$primary_button         = wp_parse_args( $value['button_link']['primary'], $primary_button_default );

				$sanitized_data['button_link']['primary'] = $primary_button;
			}
			if ( ! empty( $value['button_link']['secondary']['text'] ) && ! empty( $value['button_link']['secondary']['url'] ) ) {
				$secondary_button_default = [
					'text'          => Item::get_default_value( 'button_two_text' ),
					'url'           => Item::get_default_value( 'button_two_url' ),
					'target'        => Item::get_default_value( 'button_two_target' ),
					'type'          => Item::get_default_value( 'button_two_type' ),
					'size'          => Item::get_default_value( 'button_two_size' ),
					'border_width'  => Item::get_default_value( 'button_two_border_width' ),
					'border_radius' => Item::get_default_value( 'button_two_border_radius' ),
					'bg_color'      => Item::get_default_value( 'button_two_bg_color' ),
					'color'         => Item::get_default_value( 'button_two_color' ),
				];
				$secondary_button         = wp_parse_args( $value['button_link']['secondary'], $secondary_button_default );

				$sanitized_data['button_link']['secondary'] = $secondary_button;
			}
		}

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
	 * Prepare slider item for database
	 *
	 * @param array $data Raw data.
	 *
	 * @return string[]
	 */
	public function prepare_slider_item_for_database( array $data ): array {
		$default        = Item::get_default();
		$sanitized_data = [
			'background_type'   => $data['background_type'],
			'content_alignment' => $data['content_alignment'],
			'content_animation' => $data['content_animation'],
			'link_type'         => $data['link_type'],
		];
		if ( 'color' === $data['background_type'] ) {
			$sanitized_data['bg_color'] = $data['bg_color'] ?? '';
		}
		if ( 'image' === $data['background_type'] && is_array( $data['bg_image'] ) ) {
			$sanitized_data['img_id']           = $data['bg_image']['img_id'];
			$sanitized_data['img_bg_position']  = $data['bg_image']['img_position'];
			$sanitized_data['img_bg_size']      = $data['bg_image']['img_size'];
			$sanitized_data['ken_burns_effect'] = $data['bg_image']['ken_burns_effect'];
			$sanitized_data['bg_overlay']       = $data['bg_image']['overlay_color'];
		}

		if ( ! empty( $data['heading']['text'] ) ) {
			$sanitized_data['slide_heading']     = $data['heading']['text'];
			$sanitized_data['heading_font_size'] = $data['heading']['font_size'];
			$sanitized_data['heading_gutter']    = $data['heading']['margin_bottom'];
			$sanitized_data['heading_color']     = $data['heading']['color'];
		}

		if ( ! empty( $data['description']['text'] ) ) {
			$sanitized_data['slide_description']     = $data['description']['text'];
			$sanitized_data['description_font_size'] = $data['description']['font_size'];
			$sanitized_data['description_gutter']    = $data['description']['margin_bottom'];
			$sanitized_data['description_color']     = $data['description']['color'];
		}

		if ( 'full' === $data['link_type'] ) {
			$sanitized_data['slide_link']  = $data['full_link']['url'];
			$sanitized_data['link_target'] = $data['full_link']['target'];
		}

		if ( 'button' === $data['link_type'] && isset( $data['button_link'] ) ) {
			if ( isset( $data['button_link']['primary'] ) ) {
				foreach ( $data['button_link']['primary'] as $key_suffix => $value ) {
					$sanitized_data[ 'button_one_' . $key_suffix ] = $value;
				}
			}
			if ( isset( $data['button_link']['secondary'] ) ) {
				foreach ( $data['button_link']['secondary'] as $key_suffix => $value ) {
					$sanitized_data[ 'button_two_' . $key_suffix ] = $value;
				}
			}
		}

		return wp_parse_args( $sanitized_data, $default );
	}

	/**
	 * Validate a request argument based on details registered to the route.
	 *
	 * @param mixed           $value The value.
	 * @param WP_REST_Request $request The Request object.
	 * @param string          $param Parameter name.
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

		foreach ( $value as $index => $item ) {
			$is_valid = $this->validate_hero_slider_item_args( $item, $index );
			if ( is_wp_error( $is_valid ) ) {
				return $is_valid;
			}
		}

		return true;
	}

	/**
	 * Validate hero slider single item arguments.
	 *
	 * @param array $item User submitted data.
	 * @param int   $index Item index.
	 *
	 * @return true|WP_Error
	 */
	public function validate_hero_slider_item_args( array $item, int $index = 0 ) {
		$background_type = $item['background_type'] ?? '';
		$name            = 'slider_items[' . $index . ']';
		if ( 'color' === $background_type ) {
			if ( empty( $item['bg_color'] ) ) {
				return new WP_Error(
					'rest_property_required',
					/* translators: %s will be replaced with name*/
					sprintf( __( 'bg_color is a required property of %s when background_type is set as color', 'carousel-slider' ), $name )
				);
			}
		}
		if ( 'image' === $background_type ) {
			if ( empty( $item['bg_image'] ) ) {
				return new WP_Error(
					'rest_property_required',
					/* translators: %s will be replaced with name*/
					sprintf( __( 'bg_image is a required property of %s when background_type is set as image', 'carousel-slider' ), $name )
				);
			}
			if ( empty( $item['bg_image']['img_id'] ) ) {
				return new WP_Error(
					'rest_property_required',
					/* translators: %s will be replaced with name*/
					sprintf( __( 'img_id is a required property of %s[bg_image]', 'carousel-slider' ), $name )
				);
			}

			$src = wp_get_attachment_image_src( $item['bg_image']['img_id'] );
			if ( ! is_array( $src ) ) {
				return new WP_Error(
					'rest_invalid_value',
					/* translators: %s will be replaced with name*/
					sprintf( __( 'The value of img_id of %s[bg_image] is invalid.', 'carousel-slider' ), $name )
				);
			}
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
				'validate_callback' => 'rest_validate_request_arg',
			],
			'slider_settings'  => [
				'description'       => __( 'Hero carousel specific settings.', 'carousel-slider' ),
				'type'              => 'object',
				'required'          => true,
				'properties'        => [
					'slide_height'      => [
						'type'     => 'string',
						'required' => true,
					],
					'content_animation' => [
						'type'     => 'string',
						'required' => true,
						'enum'     => array_keys( Helper::animations() ),
					],
					'content_width'     => [ 'type' => 'string' ],
					'slide_padding'     => [
						'type'       => 'object',
						'properties' => [
							'top'    => [ 'type' => 'string' ],
							'right'  => [ 'type' => 'string' ],
							'bottom' => [ 'type' => 'string' ],
							'left'   => [ 'type' => 'string' ],
						],
					],
				],
				'sanitize_callback' => [ $this, 'sanitize_hero_slider_settings' ],
			],
			'slider_items'     => [
				'description'       => __( 'Carousel settings.', 'carousel-slider' ),
				'type'              => 'array',
				'required'          => true,
				'items'             => [
					'type'       => 'object',
					'properties' => $this->hero_carousel_setting_args_properties(),
				],
				'sanitize_callback' => [ $this, 'sanitize_hero_slider_items' ],
				'validate_callback' => [ $this, 'validate_hero_slider_items_args' ],
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
			'bg_color'          => [
				'type'              => 'string',
				'sanitize_callback' => [ Sanitize::class, 'color' ],
			],
			'bg_image'          => [
				'type'       => 'object',
				'properties' => [
					'img_id'           => [
						'type'              => 'int',
						'sanitize_callback' => [ Sanitize::class, 'int' ],
					],
					'img_position'     => [
						'type' => 'string',
						'enum' => array_keys( Helper::background_position() ),
					],
					'img_size'         => [
						'type' => 'string',
						'enum' => array_keys( Helper::background_size() ),
					],
					'overlay_color'    => [
						'type'              => 'string',
						'sanitize_callback' => [ Sanitize::class, 'color' ],
					],
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
