<?php

namespace CarouselSlider\Abstracts;

use CarouselSlider\Helper;
use CarouselSlider\Supports\Validate;

/**
 * SliderSetting class
 * The base slider setting for any slider type
 *
 * @package CarouselSlider/Abstracts
 */
class SliderSetting extends Data {
	/**
	 * The slider id.
	 *
	 * @var int
	 */
	protected $slider_id = 0;

	/**
	 * Is data read from server?
	 *
	 * @var bool
	 */
	protected $data_read = false;

	/**
	 * Class constructor
	 *
	 * @param int $slider_id The slider id.
	 */
	public function __construct( int $slider_id ) {
		$this->slider_id = $slider_id;
		$this->data      = self::get_defaults();
		$this->read_metadata();
	}

	/**
	 * Get option
	 *
	 * @param string $key option key.
	 * @param mixed  $default default value.
	 *
	 * @return mixed
	 */
	public function get_global_option( string $key, $default = '' ) {
		$default_args = apply_filters(
			'carousel_slider/global_options/default_args',
			[
				'load_scripts'                        => 'optimized',
				'show_structured_data'                => '1',
				'woocommerce_shop_loop_item_template' => 'v1-compatibility',
			]
		);
		$options      = wp_parse_args( get_option( 'carousel_slider_settings', [] ), $default_args );

		return $options[ $key ] ?? $default;
	}

	/**
	 * Get slider Id
	 *
	 * @return int
	 */
	public function get_slider_id(): int {
		return $this->slider_id;
	}

	/**
	 * Get image size
	 *
	 * @return string
	 */
	public function get_image_size(): string {
		$default = $this->get_global_option( 'image_size', 'medium_large' );
		$size    = $this->get_prop( 'image_size' );

		return array_key_exists( $size, Helper::get_available_image_sizes() ) ? $size : $default;
	}

	/**
	 * If it should lazy load image
	 *
	 * @return bool
	 */
	public function lazy_load_image(): bool {
		$default        = Helper::get_default_setting( 'lazy_load_image' );
		$global_setting = $this->get_global_option( 'lazy_load', $default );
		$lazy_load      = $this->get_prop( 'lazy_load', $global_setting );

		return Validate::checked( $lazy_load );
	}

	/**
	 * Set nav visibility
	 *
	 * @param mixed $value The navigation visibility.
	 */
	public function set_nav_visibility( $value ) {
		// For backup compatability.
		$value = str_replace( [ 'off', 'on' ], [ 'never', 'hover' ], $value );

		if ( in_array( $value, [ 'always', 'never', 'hover' ], true ) ) {
			$this->data['nav_visibility'] = $value;
		}
	}

	/**
	 * Set nav position
	 *
	 * @param mixed $value The navigation position.
	 */
	public function set_nav_position( $value ) {
		if ( in_array( $value, [ 'inside', 'outside' ], true ) ) {
			$this->data['nav_position'] = $value;
		}
	}

	/**
	 * Set nav steps
	 *
	 * @param mixed $value The navigation steps.
	 */
	public function set_nav_steps( $value ) {
		if ( in_array( $value, [ 'page', '-1', - 1 ], true ) ) {
			$this->data['nav_steps'] = 'page';
		} else {
			$this->data['nav_steps'] = max( 1, intval( $value ) );
		}
	}

	/**
	 * Set pagination visibility
	 *
	 * @param mixed $value The pagination visibility value.
	 */
	public function set_pagination_visibility( $value ) {
		// For backup compatability.
		$value = str_replace( [ 'off', 'on' ], [ 'never', 'always' ], $value );

		if ( in_array( $value, [ 'always', 'never', 'hover' ], true ) ) {
			$this->data['pagination_visibility'] = $value;
		}
	}

	/**
	 * Read setting from database
	 *
	 * @return void
	 */
	protected function read_metadata() {
		if ( $this->data_read ) {
			return;
		}
		$attribute_meta_key = static::props();
		foreach ( $attribute_meta_key as $attribute => $config ) {
			$method_name = 'set_' . $attribute;
			$value       = get_post_meta( $this->get_slider_id(), $config['meta_key'], true );
			if ( method_exists( $this, $method_name ) ) {
				$this->$method_name( $value );
			} else {
				$value = $this->sanitize_by_type( $config['type'], $value );
				$this->set_prop( $attribute, $value );
			}
		}
		$this->data_read = true;
	}

	/**
	 * Sanitize value by data type
	 *
	 * @param string $type The type.
	 * @param mixed  $value The value.
	 *
	 * @return mixed
	 */
	protected function sanitize_by_type( string $type, $value ) {
		if ( 'array' === $type && is_string( $value ) ) {
			$value = explode( ',', $value );
		}
		if ( 'int[]' === $type && is_string( $value ) ) {
			$value = array_filter( array_map( 'intval', explode( ',', $value ) ) );
		}
		if ( 'int' === $type ) {
			$value = (int) $value;
		}
		if ( 'bool' === $type ) {
			$value = Validate::checked( $value );
		}

		return $value;
	}

	/**
	 * Write metadata
	 *
	 * @return void
	 * @todo make sure to backward compatibility for the following props
	 * --- nav_visibility, pagination_visibility, nav_steps
	 */
	public function write_metadata() {
		$props_to_meta_keys = self::props_to_meta_keys();
		foreach ( $props_to_meta_keys as $prop_name => $meta_key ) {
			// @TODO convert bool value to yes no.
			update_post_meta( $this->slider_id, $meta_key, $this->get_prop( $prop_name ) );
		}
	}

	/**
	 * Get default values
	 *
	 * @return array
	 */
	public static function get_defaults(): array {
		$props = static::props();
		$data  = [];
		foreach ( $props as $prop => $config ) {
			$data[ $prop ] = $config['default'];
		}

		return $data;
	}

	/**
	 * Map prop name to meta key
	 *
	 * @return string[]
	 */
	public static function props_to_meta_keys(): array {
		$props = static::props();
		$data  = [];
		foreach ( $props as $prop => $config ) {
			$data[ $prop ] = $config['meta_key'];
		}

		return $data;
	}

	/**
	 * Get group meta keys
	 *
	 * @param string $group The group name.
	 *
	 * @return array
	 */
	public function get_group_meta_keys( string $group = 'general' ): array {
		$method_name = $group . '_props';
		$props       = method_exists( $this, $method_name ) ? $this->$method_name() : self::general_props();

		return wp_list_pluck( $props, 'meta_key' );
	}

	/**
	 * Default properties
	 *
	 * @return array
	 */
	public static function props(): array {
		return array_merge(
			self::general_props(),
			self::navigation_props(),
			self::pagination_props(),
			self::autoplay_props(),
			self::breakpoints_props(),
			self::color_props()
		);
	}

	/**
	 * Get navigation props
	 *
	 * @return array
	 */
	public static function general_props(): array {
		return [
			'image_size'    => [
				'meta_key' => '_image_size',
				'type'     => 'string',
				'default'  => 'medium_large',
			],
			'space_between' => [
				'meta_key' => '_margin_right',
				'type'     => 'int',
				'default'  => 30,
			],
			'stage_padding' => [
				'meta_key' => '_stage_padding',
				'type'     => 'int',
				'default'  => 0,
			],
			'loop'          => [
				'meta_key' => '_infinity_loop',
				'type'     => 'bool',
				'default'  => true,
			],
			'lazy_load'     => [
				'meta_key' => '_lazy_load_image',
				'type'     => 'bool',
				'default'  => true,
			],
			'auto_width'    => [
				'meta_key' => '_auto_width',
				'type'     => 'bool',
				'default'  => false,
			],
		];
	}

	/**
	 * Get navigation props
	 *
	 * @return array
	 */
	public static function navigation_props(): array {
		return [
			'nav_visibility' => [
				'meta_key' => '_nav_button',
				'type'     => 'string',
				'default'  => 'hover',
			],
			'nav_position'   => [
				'meta_key' => '_arrow_position',
				'type'     => 'string',
				'default'  => 'outside',
			],
			'nav_size'       => [
				'meta_key' => '_arrow_size',
				'type'     => 'int',
				'default'  => 48,
			],
			'nav_steps'      => [
				'meta_key' => '_slide_by',
				'type'     => [ 'string', 'int' ],
				'default'  => 1,
			],
		];
	}

	/**
	 * Get pagination props
	 *
	 * @return array
	 */
	public static function pagination_props(): array {
		return [
			'pagination_visibility' => [
				'meta_key' => '_dot_nav',
				'type'     => 'string',
				'default'  => 'never',
			],
			'pagination_position'   => [
				'meta_key' => '_bullet_position',
				'type'     => 'string',
				'default'  => 'center',
			],
			'pagination_size'       => [
				'meta_key' => '_bullet_size',
				'type'     => 'int',
				'default'  => 10,
			],
			'pagination_shape'      => [
				'meta_key' => '_bullet_shape',
				'type'     => 'string',
				'default'  => 'circle',
			],
		];
	}

	/**
	 * Get pagination props
	 *
	 * @return array
	 */
	public static function autoplay_props(): array {
		return [
			'autoplay_delay'       => [
				'meta_key' => '_autoplay_timeout',
				'type'     => 'int',
				'default'  => 5000,
			],
			'autoplay_speed'       => [
				'meta_key' => '_autoplay_speed',
				'type'     => 'int',
				'default'  => 300,
			],
			'autoplay'             => [
				'meta_key' => '_autoplay',
				'type'     => 'bool',
				'default'  => true,
			],
			'autoplay_hover_pause' => [
				'meta_key' => '_autoplay_pause',
				'type'     => 'bool',
				'default'  => true,
			],
		];
	}

	/**
	 * Get breakpoints props
	 *
	 * @return array
	 */
	public static function breakpoints_props(): array {
		return [
			'items_on_mobile'       => [
				'meta_key' => '_items_portrait_mobile',
				'type'     => 'int',
				'default'  => 1,
			],
			'items_on_small_tablet' => [
				'meta_key' => '_items_small_portrait_tablet',
				'type'     => 'int',
				'default'  => 1,
			],
			'items_on_tablet'       => [
				'meta_key' => '_items_portrait_tablet',
				'type'     => 'int',
				'default'  => 2,
			],
			'items_on_desktop'      => [
				'meta_key' => '_items_small_desktop',
				'type'     => 'int',
				'default'  => 3,
			],
			'items_on_widescreen'   => [
				'meta_key' => '_items_desktop',
				'type'     => 'int',
				'default'  => 4,
			],
			'items_on_fullhd'       => [
				'meta_key' => '_items',
				'type'     => 'int',
				'default'  => 5,
			],
		];
	}

	/**
	 * Get color props
	 *
	 * @return array
	 */
	public static function color_props(): array {
		return [
			'nav_color'        => [
				'meta_key' => '_nav_color',
				'type'     => 'string',
				'default'  => Helper::get_default_setting( 'nav_color' ),
			],
			'nav_active_color' => [
				'meta_key' => '_nav_active_color',
				'type'     => 'string',
				'default'  => Helper::get_default_setting( 'nav_active_color' ),
			],
		];
	}
}
