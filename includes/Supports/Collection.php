<?php

namespace CarouselSlider\Supports;

use CarouselSlider\Interfaces\CollectionInterface;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Collection implements CollectionInterface {

	/**
	 * @var array
	 */
	protected $collections = array();

	/********************************************************************************
	 * CollectionInterface interface
	 *******************************************************************************/

	/**
	 * Does this collection have a given key?
	 *
	 * @param string $key The data key
	 *
	 * @return bool
	 */
	public function has( $key ) {
		return isset( $this->collections[ $key ] );
	}

	/**
	 * Set collection item
	 *
	 * @param string $key The data key
	 * @param mixed $value The data value
	 */
	public function set( $key, $value ) {
		if ( is_null( $key ) ) {
			$this->collections[] = $value;
		} else {
			$this->collections[ $key ] = $value;
		}
	}

	/**
	 * Get collection item for key
	 *
	 * @param string $key The data key
	 * @param mixed $default The default value to return if data key does not exist
	 *
	 * @return mixed The key's value, or the default value
	 */
	public function get( $key, $default = null ) {
		return $this->has( $key ) ? $this->collections[ $key ] : $default;
	}

	/**
	 * Add item to collection, replacing existing items with the same data key
	 *
	 * @param array $items Key-value array of data to append to this collection
	 */
	public function replace( array $items ) {
		foreach ( $items as $key => $value ) {
			$this->set( $key, $value );
		}
	}

	/**
	 * Get all items in collections
	 *
	 * @return array The collection's source data
	 */
	public function all() {
		return $this->collections;
	}

	/**
	 * Remove item from collection
	 *
	 * @param string $key The data key
	 */
	public function remove( $key ) {
		if ( $this->has( $key ) ) {
			unset( $this->collections[ $key ] );
		}
	}

	/**
	 * Remove all items from collection
	 */
	public function clear() {
		$this->collections = array();
	}

	/********************************************************************************
	 * ArrayAccess interface
	 *******************************************************************************/

	/**
	 * Whether a offset exists
	 * @link http://php.net/manual/en/arrayaccess.offsetexists.php
	 *
	 * @param mixed $offset An offset to check for.
	 *
	 * @return boolean true on success or false on failure.
	 * @since 5.0.0
	 */
	public function offsetExists( $offset ) {
		return $this->has( $offset );
	}

	/**
	 * Offset to retrieve
	 * @link http://php.net/manual/en/arrayaccess.offsetget.php
	 *
	 * @param mixed $offset The offset to retrieve.
	 *
	 * @return mixed Can return all value types.
	 * @since 5.0.0
	 */
	public function offsetGet( $offset ) {
		return $this->get( $offset );
	}

	/**
	 * Offset to set
	 * @link http://php.net/manual/en/arrayaccess.offsetset.php
	 *
	 * @param mixed $offset The offset to assign the value to.
	 * @param mixed $value The value to set.
	 *
	 * @return void
	 * @since 5.0.0
	 */
	public function offsetSet( $offset, $value ) {
		$this->set( $offset, $value );
	}

	/**
	 * Offset to unset
	 * @link http://php.net/manual/en/arrayaccess.offsetunset.php
	 *
	 * @param mixed $offset The offset to unset.
	 *
	 * @return void
	 * @since 5.0.0
	 */
	public function offsetUnset( $offset ) {
		$this->remove( $offset );
	}

	/********************************************************************************
	 * Countable interface
	 *******************************************************************************/

	/**
	 * Count elements of an object
	 * @link http://php.net/manual/en/countable.count.php
	 * @return int The custom count as an integer.
	 * @since 5.1.0
	 */
	public function count() {
		return count( $this->all() );
	}

	/********************************************************************************
	 * IteratorAggregate interface
	 *******************************************************************************/

	/**
	 * Retrieve an external iterator
	 * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return \ArrayIterator An instance of an object implementing Iterator
	 * @since 5.0.0
	 */
	public function getIterator() {
		return new \ArrayIterator( $this->all() );
	}
}
