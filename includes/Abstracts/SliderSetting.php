<?php

namespace CarouselSlider\Abstracts;

use CarouselSlider\Helper;
use CarouselSlider\Supports\Validate;

class SliderSetting extends Data {

	protected $slider_id = 0;
	protected $settings = [];

	public function __construct( int $slider_id ) {
		$this->slider_id = $slider_id;
		$this->settings  = self::get_defaults();
	}

	/**
	 * Get slider Id
	 * @return int
	 */
	public function get_slider_id(): int {
		return $this->slider_id;
	}

	/**
	 * @inerhitDoc
	 */
	public function get_prop( string $key, $default = '' ) {
		$value    = parent::get_prop( $key, $default );
		$num_keys = [
			'items_on_mobile',
			'items_on_small_tablet',
			'items_on_tablet',
			'items_on_desktop',
			'items_on_widescreen',
			'items_on_fullhd',
			'space_between',
			'stage_padding',
			'autoplay_delay',
			'autoplay_speed',
		];
		if ( in_array( $key, $num_keys ) ) {
			return (int) $value;
		}

		$bool_keys = [ 'autoplay', 'autoplay_hover_pause', 'loop', 'lazy_load', 'auto_width' ];
		if ( in_array( $key, $bool_keys ) ) {
			return Validate::checked( $value );
		}

		return $value;
	}

	/**
	 * Set nav visibility
	 *
	 * @param mixed $value
	 */
	public function set_nav_visibility( $value ) {
		// For backup compatability
		$value = str_replace( [ 'off', 'on' ], [ 'never', 'hover' ], $value );

		if ( in_array( $value, [ 'always', 'never', 'hover' ] ) ) {
			$this->settings['nav_visibility'] = $value;
		}
	}

	/**
	 * Set nav position
	 *
	 * @param mixed $value
	 */
	public function set_nav_position( $value ) {
		if ( in_array( $value, [ 'inside', 'outside' ] ) ) {
			$this->settings['nav_position'] = $value;
		}
	}

	/**
	 * Set nav steps
	 *
	 * @param mixed $value
	 */
	public function set_nav_steps( $value ) {
		if ( in_array( $value, [ 'page', '-1', - 1 ], true ) ) {
			$this->settings['nav_steps'] = 'page';
		} else {
			$this->settings['nav_steps'] = max( 1, intval( $value ) );
		}
	}

	/**
	 * Set pagination visibility
	 *
	 * @param mixed $value
	 */
	public function set_pagination_visibility( $value ) {
		// For backup compatability
		$value = str_replace( [ 'off', 'on' ], [ 'never', 'always' ], $value );

		if ( in_array( $value, [ 'always', 'never', 'hover' ] ) ) {
			$this->settings['pagination_visibility'] = $value;
		}
	}

	/**
	 * Read setting from database
	 * @return void
	 */
	protected function read_metadata() {
		$attribute_meta_key = self::props_to_meta_keys();
		foreach ( $attribute_meta_key as $attribute => $meta_key ) {
			$method_name = 'set_' . $attribute;
			$value       = get_post_meta( $this->slider_id, $meta_key, true );
			if ( method_exists( $this, $method_name ) ) {
				$this->$method_name( $value );
			} else {
				$this->set_prop( $attribute, $value );
			}
		}
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
			update_post_meta( $this->slider_id, $meta_key, $this->get_prop( $prop_name ) );
		}
	}

	/**
	 * Get default settings
	 *
	 * @return array
	 */
	public static function get_defaults(): array {
		return [
			'nav_visibility'        => 'hover',
			'nav_position'          => 'outside',
			'nav_size'              => 48,
			'nav_steps'             => 1,
			'pagination_visibility' => 'never',
			'pagination_position'   => 'center',
			'pagination_size'       => 10,
			'pagination_shape'      => 'circle',
			'nav_color'             => Helper::get_default_setting( 'nav_color' ),
			'nav_active_color'      => Helper::get_default_setting( 'nav_active_color' ),
			'items_on_mobile'       => 1,
			'items_on_small_tablet' => 2,
			'items_on_tablet'       => 2,
			'items_on_desktop'      => 3,
			'items_on_widescreen'   => 4,
			'items_on_fullhd'       => 5,
			'space_between'         => 30,
			'stage_padding'         => 0,
			'autoplay_delay'        => 5000,
			'autoplay_speed'        => 300,
			'autoplay'              => true,
			'autoplay_hover_pause'  => true,
			'loop'                  => true,
			'lazy_load'             => true,
			'auto_width'            => false,
		];
	}

	/**
	 * Map prop name to meta key
	 * @return string[]
	 */
	public static function props_to_meta_keys(): array {
		return [
			'nav_visibility'        => '_nav_button',
			'nav_position'          => '_arrow_position',
			'nav_size'              => '_arrow_size',
			'nav_steps'             => '_slide_by',
			'pagination_visibility' => '_dot_nav',
			'pagination_position'   => '_bullet_position',
			'pagination_size'       => '_bullet_size',
			'pagination_shape'      => '_bullet_shape',
			'nav_color'             => '_nav_color',
			'nav_active_color'      => '_nav_active_color',
			'items_on_mobile'       => '_items_portrait_mobile',
			'items_on_small_tablet' => '_items_small_portrait_tablet',
			'items_on_tablet'       => '_items_portrait_tablet',
			'items_on_desktop'      => '_items_small_desktop',
			'items_on_widescreen'   => '_items_desktop',
			'items_on_fullhd'       => '_items',
			'space_between'         => '_margin_right',
			'stage_padding'         => '_stage_padding',
			'autoplay_delay'        => '_autoplay_timeout',
			'autoplay_speed'        => '_autoplay_speed',
			'autoplay'              => '_autoplay',
			'autoplay_hover_pause'  => '_autoplay_pause',
			'loop'                  => '_infinity_loop',
			'lazy_load'             => '_lazy_load_image',
			'auto_width'            => '_auto_width',
		];
	}
}
