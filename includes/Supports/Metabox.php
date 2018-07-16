<?php

namespace CarouselSlider\Supports;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Metabox {

	/**
	 * @var self
	 */
	private static $instance;

	/**
	 * Settings panels array
	 * @var array
	 */
	private $panels = array();

	/**
	 * Settings sections array
	 * @var array
	 */
	private $sections = array();

	/**
	 * Settings fields array
	 * @var array
	 */
	private $fields = array();

	/**
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get metabox panels sorted by priority
	 *
	 * @return array
	 */
	public function get_panels() {
		$panels = $this->panels;
		// Sort by priority
		usort( $panels, array( $this, 'sort_by_priority' ) );

		return $panels;
	}

	/**
	 * Set metabox panels
	 *
	 * @param array $panels
	 */
	public function set_panels( $panels ) {
		foreach ( $panels as $panel ) {
			$this->set_panel( $panel );
		}
	}

	/**
	 * Get metabox sections sorted by priority
	 *
	 * @return array
	 */
	public function get_sections() {
		$sections = $this->sections;
		// Sort by priority
		usort( $sections, array( $this, 'sort_by_priority' ) );

		return $sections;
	}

	/**
	 * Set metabox sections
	 *
	 * @param array $sections
	 */
	public function set_sections( $sections ) {
		foreach ( $sections as $section ) {
			$this->set_section( $section );
		}
	}

	/**
	 * Get metabox fields sorted by priority
	 *
	 * @return array
	 */
	public function get_fields() {
		$fields = $this->fields;
		// Sort by priority
		usort( $fields, array( $this, 'sort_by_priority' ) );

		return $fields;
	}

	/**
	 * Set metabox fields
	 *
	 * @param array $fields
	 */
	public function set_fields( $fields ) {
		foreach ( $fields as $field ) {
			$this->set_field( $field );
		}
	}

	/**
	 * Set metabox panel
	 *
	 * @param array $args
	 */
	public function set_panel( $args = array() ) {
		$default = array( 'id' => '', 'title' => '', 'description' => '', 'priority' => 10, );

		if ( ! isset( $args['id'], $args['title'] ) ) {
			$this->panels[] = wp_parse_args( $args, $default );
		}
	}

	/**
	 * Set metabox section
	 *
	 * @param array $args
	 */
	public function set_section( $args = array() ) {
		$default = array( 'id' => '', 'title' => '', 'description' => '', 'priority' => 10, 'panel' => '', );

		if ( ! isset( $args['id'], $args['title'] ) ) {
			$this->sections[] = wp_parse_args( $args, $default );
		}
	}

	/**
	 * Set metabox field
	 *
	 * @param array $args
	 */
	public function set_field( $args = array() ) {
		$default = array(
			'type'              => 'text',
			'id'                => '',
			'label'             => '',
			'description'       => '',
			'priority'          => 10,
			'section'           => '',
			'default'           => '',
			'choices'           => array(),
			'input_attributes'  => array(),
			'sanitize_callback' => '',
			'validate_callback' => ''
		);

		if ( ! isset( $args['id'], $args['title'] ) ) {
			$this->fields[] = wp_parse_args( $args, $default );
		}
	}

	/**
	 * Sort array by it its priority value
	 *
	 * @param array $array1
	 * @param array $array2
	 *
	 * @return mixed
	 */
	private static function sort_by_priority( $array1, $array2 ) {
		return $array1['priority'] - $array2['priority'];
	}

	/**
	 * Build text field
	 *
	 * @param array $args
	 */
	public static function text( array $args ) {

	}
}
