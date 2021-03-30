<?php

namespace CarouselSlider\Modules\HeroCarousel;

defined( 'ABSPATH' ) || exit;

class HeroCarouselModule {
	/**
	 * The instance of the class
	 *
	 * @var self
	 */
	protected static $instance;

	/**
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			Ajax::init();
		}

		return self::$instance;
	}
}
