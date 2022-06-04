<?php

namespace CarouselSlider\Abstracts;

use BadMethodCallException;
use CarouselSlider\Admin\MetaBoxConfig;
use CarouselSlider\Helper;
use CarouselSlider\Interfaces\SliderSettingInterface;
use CarouselSlider\Supports\Sanitize;
use CarouselSlider\Supports\Validate;

/**
 * SliderSetting class
 * The base slider setting for any slider type
 *
 * @method string get_nav_visibility()
 * @method string get_pagination_visibility()
 * @method string get_nav_steps()
 * @method int get_stage_padding()
 * @method int get_space_between()
 * @method bool is_loop()
 * @method bool is_lazy_load()
 * @method bool is_auto_width()
 * @method bool is_autoplay()
 * @method bool has_autoplay_hover_pause()
 * @method int get_autoplay_delay()
 * @method int get_autoplay_speed()
 * @method int get_items_on_mobile()
 * @method int get_items_on_small_tablet()
 * @method int get_items_on_tablet()
 * @method int get_items_on_desktop()
 * @method int get_items_on_widescreen()
 * @method int get_items_on_fullhd()
 *
 * @package CarouselSlider/Abstracts
 */
class SliderSetting extends Data implements SliderSettingInterface {
	/**
	 * The slider id.
	 *
	 * @var int
	 */
	protected $slider_id = 0;

	/**
	 * Get slider type
	 *
	 * @var string
	 */
	protected $slider_type = null;

	/**
	 * Is data read from server?
	 *
	 * @var bool
	 */
	protected $data_read = false;

	/**
	 * Global settings
	 *
	 * @var array
	 */
	protected static $global_settings = [];

	/**
	 * Class constructor
	 *
	 * @param int $slider_id The slider id.
	 * @param bool $read_metadata Should read metadata immediately.
	 */
	public function __construct( int $slider_id, bool $read_metadata = true ) {
		$this->slider_id = $slider_id;
		if ( $read_metadata ) {
			$this->read_metadata();
			if ( method_exists( $this, 'read_extra_metadata' ) ) {
				$this->read_extra_metadata();
			}
		}
	}

	/**
	 * Get global settings
	 *
	 * @return array
	 */
	public static function get_global_settings(): array {
		if ( empty( static::$global_settings ) ) {
			$default_args = apply_filters(
				'carousel_slider/global_options/default_args',
				[
					'load_scripts'                        => 'optimized',
					'show_structured_data'                => '1',
					'woocommerce_shop_loop_item_template' => 'v1-compatibility',
				]
			);
			$options      = get_option( 'carousel_slider_settings', [] );
			$options      = is_array( $options ) ? $options : [];

			static::$global_settings = wp_parse_args( $options, $default_args );
		}

		return static::$global_settings;
	}

	/**
	 * Does this collection have a given key?
	 *
	 * @param string $key The data key.
	 *
	 * @return bool
	 */
	public static function has_global_option( string $key ): bool {
		return array_key_exists( $key, self::get_global_settings() );
	}

	/**
	 * Get option
	 *
	 * @param string $key option key.
	 * @param mixed $default default value.
	 *
	 * @return mixed
	 */
	public function get_global_option( string $key, $default = '' ) {
		if ( static::has_global_option( $key ) ) {
			return static::get_global_settings()[ $key ];
		}

		return $default;
	}

	/**
	 * Get option for key
	 * If there is no option for key, return from global option.
	 *
	 * @param string $key option key.
	 * @param mixed $default default value to return if data key does not exist.
	 *
	 * @return mixed The key's value, or the default value
	 */
	public function get_option( string $key, $default = '' ) {
		if ( $this->has_prop( $key ) ) {
			return $this->get_prop( $key, $default );
		}

		return $this->get_global_option( $key, $default );
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
	 * Get slider type
	 *
	 * @return string
	 */
	public function get_slider_type(): string {
		if ( is_null( $this->slider_type ) ) {
			$slide_type        = get_post_meta( $this->get_slider_id(), '_slide_type', true );
			$this->slider_type = array_key_exists( $slide_type, Helper::get_slide_types() ) ? $slide_type : 'image-carousel';
		}

		return $this->slider_type;
	}

	/**
	 * Get image size
	 *
	 * @return string
	 */
	public function get_image_size(): string {
		$size = $this->get_option( 'image_size', 'medium_large' );

		return array_key_exists( $size, Helper::get_available_image_sizes() ) ? $size : 'medium_large';
	}

	/**
	 * If it should lazy load image
	 *
	 * @return bool
	 */
	public function lazy_load_image(): bool {
		$default = Helper::get_default_setting( 'lazy_load_image' );

		return Validate::checked( $this->get_option( 'lazy_load', $default ) );
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
	 * @param array $values The value to be read.
	 *
	 * @return void
	 */
	public function read_metadata( array $values = [] ) {
		if ( $this->data_read ) {
			return;
		}
		if ( empty( $values ) ) {
			$metadata = get_post_meta( $this->get_slider_id() );
			foreach ( $metadata as $meta_key => $meta_value ) {
				$values[ $meta_key ] = maybe_unserialize( $meta_value[0] );
			}
		}
		$fields_settings = self::get_fields_settings();
		foreach ( $fields_settings as $attribute => $config ) {
			$this->read_single_metadata( $attribute, $config, $values );
		}
		$this->data_read = true;
	}

	/**
	 * Read single metadata
	 *
	 * @param string $attribute property name.
	 * @param array $field The field settings.
	 * @param array $values The values.
	 *
	 * @return void
	 */
	public function read_single_metadata( string $attribute, array $field, array $values ) {
		$method_name = 'set_' . $attribute;
		$value       = $values[ $field['id'] ] ?? ( $field['default'] ?? null );
		if ( method_exists( $this, $method_name ) ) {
			$this->$method_name( $value );
		} else {
			$value = $this->prepare_item_for_response( $field['type'], $value );
			$this->set_prop( $attribute, $value );
		}
	}

	/**
	 * Write metadata
	 * make sure to backward compatibility for the following props
	 * --- nav_visibility, pagination_visibility, nav_steps
	 * --- Convert boolean value to 'on' and 'off'
	 *
	 * @return void
	 */
	public function write_metadata() {
		$fields_settings = self::get_fields_settings();
		foreach ( $fields_settings as $prop_name => $field ) {
			$value = $this->get_prop( $prop_name );
			if ( 'nav_visibility' === $prop_name ) {
				$value = str_replace( [ 'never', 'hover' ], [ 'off', 'on' ], $value );
			}
			if ( 'pagination_visibility' === $prop_name ) {
				$value = str_replace( [ 'never', 'always' ], [ 'off', 'on' ], $value );
			}
			update_post_meta( $this->slider_id, $field['id'], $this->prepare_item_for_database( $value, $field ) );
		}
	}

	/**
	 * Sanitize value by data type
	 *
	 * @param string $type The type.
	 * @param mixed $value The value.
	 *
	 * @return mixed
	 */
	protected function prepare_item_for_response( string $type, $value ) {
		if ( 'array' === $type && is_string( $value ) ) {
			$value = explode( ',', $value );
		}
		if ( 'int[]' === $type && is_string( $value ) ) {
			$value = array_filter( array_map( 'intval', explode( ',', $value ) ) );
		}
		if ( in_array( $type, [ 'int', 'number' ], true ) ) {
			$value = (int) $value;
		}
		if ( in_array( $type, [ 'bool', 'switch' ], true ) ) {
			$value = Validate::checked( $value );
		}

		return $value;
	}

	/**
	 * Prepare item for database store
	 *
	 * @param mixed $value The value to be sanitized.
	 * @param array $setting The field setting.
	 *
	 * @return mixed
	 */
	protected function prepare_item_for_database( $value, array $setting ) {
		if ( isset( $setting['sanitize_callback'] ) && is_callable( $setting['sanitize_callback'] ) ) {
			return call_user_func( $setting['sanitize_callback'], $value );
		}
		$default = $setting['default'] ?? null;
		if ( isset( $setting['choices'] ) && is_array( $setting['choices'] ) ) {
			$enum = array_keys( $setting['choices'] );
			if ( isset( $setting['multiple'] ) ) {
				$sanitized_value = [];
				foreach ( (array) $value as $item ) {
					if ( in_array( $item, $enum, true ) ) {
						$sanitized_value[] = $item;
					}
				}

				return $sanitized_value;
			}

			return in_array( $value, $enum, true ) ? $value : $default;
		}
		if ( in_array( $setting['type'], [ 'bool', 'switch' ], true ) ) {
			return Validate::checked( $value ) ? 'on' : 'off';
		}

		return Sanitize::deep( $value );
	}

	/**
	 * Default properties
	 *
	 * @return array
	 */
	protected static function get_fields_settings(): array {
		return MetaBoxConfig::get_fields_settings();
	}

	/**
	 * Handle calling property via method
	 *
	 * @param string $name The name of the method being called.
	 * @param array $args An enumerated array containing the parameters passed to the $name'ed method.
	 *
	 * @return mixed
	 * @throws BadMethodCallException Exception if not method available.
	 */
	public function __call( string $name, array $args ) {
		if ( preg_match( '/^(get_|is_|has_)(?P<property>\s*.*)/', $name, $matches ) ) {
			if ( $this->has_prop( $matches['property'] ) ) {
				return $this->get_prop( $matches['property'] );
			}
			if ( static::has_global_option( $matches['property'] ) ) {
				return static::get_global_option( $matches['property'] );
			}
		}
		throw new BadMethodCallException(
			'Call to undefined method ' . sprintf( '%s::%s()', __CLASS__, $name )
		);
	}
}
