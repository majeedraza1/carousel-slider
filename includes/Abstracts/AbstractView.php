<?php

namespace CarouselSlider\Abstracts;

use CarouselSlider\Interfaces\SliderViewInterface;

defined( 'ABSPATH' ) || exit;

abstract class AbstractView implements SliderViewInterface {
	/**
	 * Slider id
	 *
	 * @var int
	 */
	protected $slider_id = 0;

	/**
	 * Slider type
	 *
	 * @var string
	 */
	protected $slider_type = '';

	/**
	 * @inheritDoc
	 */
	abstract public function render(): string;

	/**
	 * Get slider id
	 *
	 * @return int
	 */
	public function get_slider_id(): int {
		return $this->slider_id;
	}

	/**
	 * @inheritDoc
	 */
	public function set_slider_id( int $slider_id ) {
		$this->slider_id = $slider_id;
	}

	/**
	 * Get slider type
	 *
	 * @return string
	 */
	public function get_slider_type(): string {
		return $this->slider_type;
	}

	/**
	 * @inheritDoc
	 */
	public function set_slider_type( string $slider_type ) {
		$this->slider_type = $slider_type;
	}
}
