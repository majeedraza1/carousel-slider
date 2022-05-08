<?php

namespace CarouselSlider\Abstracts;

use ArrayAccess;
use JsonSerializable;

defined( 'ABSPATH' ) || exit;

/**
 * Class Data
 *
 * @package CarouselSlider\Abstracts
 */
class Data implements ArrayAccess, JsonSerializable {

	/**
	 * Object data
	 *
	 * @var array
	 */
	protected $data = [];

	/**
	 * String representation of the class
	 *
	 * @return string
	 */
	public function __toString() {
		return wp_json_encode( $this->to_array() );
	}

	/**
	 * Get collection item for key
	 *
	 * @param string $name The property name.
	 *
	 * @return mixed
	 */
	public function __get( string $name ) {
		return $this->get_prop( $name );
	}

	/**
	 * Does this collection have a given key?
	 *
	 * @param string $name The property name.
	 *
	 * @return bool
	 */
	public function __isset( string $name ) {
		return $this->has_prop( $name );
	}

	/**
	 * Array representation of the class
	 *
	 * @return array
	 */
	public function to_array(): array {
		return $this->data;
	}

	/**
	 * Does this collection have a given key?
	 *
	 * @param string $key The data key.
	 *
	 * @return bool
	 */
	public function has_prop( string $key ): bool {
		return isset( $this->data[ $key ] );
	}

	/**
	 * Set collection item
	 *
	 * @param string $key The data key.
	 * @param mixed  $value The data value.
	 */
	public function set_prop( string $key, $value ) {
		$this->data[ $key ] = $value;
	}

	/**
	 * Get collection item for key
	 *
	 * @param string $key The data key.
	 * @param mixed  $default The default value to return if data key does not exist.
	 *
	 * @return mixed The key's value, or the default value
	 */
	public function get_prop( string $key, $default = '' ) {
		if ( $this->has_prop( $key ) ) {
			return $this->data[ $key ];
		}

		return $default;
	}

	/**
	 * Remove item from collection
	 *
	 * @param string $key The data key.
	 */
	public function remove_prop( string $key ) {
		if ( $this->has_prop( $key ) ) {
			unset( $this->data[ $key ] );
		}
	}

	/**
	 * Whether an offset exists
	 *
	 * @param mixed $offset An offset to check for.
	 *
	 * @return boolean true on success or false on failure.
	 */
	public function offsetExists( $offset ): bool {
		return $this->has_prop( $offset );
	}

	/**
	 * Offset to retrieve
	 *
	 * @param mixed $offset The offset to retrieve.
	 *
	 * @return mixed Can return all value types.
	 */
	#[\ReturnTypeWillChange]
	public function offsetGet( $offset ) {
		return $this->get_prop( $offset );
	}

	/**
	 * Offset to set
	 *
	 * @param mixed $offset The offset to assign the value to.
	 * @param mixed $value The value to set.
	 *
	 * @return void
	 */
	#[\ReturnTypeWillChange]
	public function offsetSet( $offset, $value ) {
		$this->set_prop( $offset, $value );
	}

	/**
	 * Offset to unset
	 *
	 * @param mixed $offset The offset to unset.
	 *
	 * @return void
	 */
	#[\ReturnTypeWillChange]
	public function offsetUnset( $offset ) {
		$this->remove_prop( $offset );
	}

	/**
	 * Specify data which should be serialized to JSON
	 *
	 * @return array data which can be serialized by json_encode
	 * which is a value of any type other than a resource.
	 */
	public function jsonSerialize(): array {
		return $this->to_array();
	}
}
