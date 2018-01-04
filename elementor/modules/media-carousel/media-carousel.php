<?php

use Elementor\Plugin;
use Elementor\Widget_Base;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Carousel_Slider_Media_Slider extends Widget_Base {

	/**
	 * Retrieve the name.
	 *
	 * @return string The name.
	 */
	public function get_name() {
		return 'carousel-slider-media-carousel';
	}

	/**
	 * Retrieve element title.
	 *
	 * @return string Element title.
	 */
	public function get_title() {
		return __( 'Media Carousel', 'carousel-slider' );
	}

	/**
	 * Retrieve element icon.
	 *
	 * @return string Element icon.
	 */
	public function get_icon() {
		return 'eicon-media-carousel';
	}

	/**
	 * Retrieve widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'carousel-slider-elements' );
	}

	/**
	 * Register controls.
	 *
	 * Used to add new controls to any element type. For example, external
	 * developers use this method to register controls in a widget.
	 *
	 * Should be inherited and register new controls using `add_control()`,
	 * `add_responsive_control()` and `add_group_control()`, inside control
	 * wrappers like `start_controls_section()`, `start_controls_tabs()` and
	 * `start_controls_tab()`.
	 */
	protected function _register_controls() {
	}

	/**
	 * Render element.
	 * Generates the final HTML on the frontend.
	 *
	 * @access protected
	 */
	protected function render() {
	}

	/**
	 * Render element output in the editor.
	 * Used to generate the live preview, using a Backbone JavaScript template.
	 *
	 * @access protected
	 */
	protected function _content_template() {
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Carousel_Slider_Media_Slider() );