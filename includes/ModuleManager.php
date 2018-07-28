<?php

namespace CarouselSlider;

use CarouselSlider\Supports\Collection;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class ModuleManager extends Collection {

	/**
	 * @var ModuleManager
	 */
	private static $instance;

	/**
	 * @return ModuleManager
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * ModuleManager constructor.
	 */
	public function __construct() {
		$this->set( 'hero-banner-slider', 'CarouselSlider\\Modules\\HeroCarousel\\View' );
		$this->set( 'image-carousel', 'CarouselSlider\\Modules\\ImageCarousel\\View' );
		$this->set( 'image-carousel-url', 'CarouselSlider\\Modules\\ImageCarouselUrl\\View' );
		$this->set( 'post-carousel', 'CarouselSlider\\Modules\\PostCarousel\\View' );
		$this->set( 'product-carousel', 'CarouselSlider\\Modules\\ProductCarousel\\View' );
		$this->set( 'video-carousel', 'CarouselSlider\\Modules\\VideoCarousel\\View' );

		/**
		 * Give other plugin option to add their own action(s)
		 */
		do_action( 'carousel_slider/modules/views', $this );
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
		if ( ! $this->has( $key ) ) {
			return $default;
		}

		return '\\' . ltrim( $this->collections[ $key ], '\\' );
	}
}
