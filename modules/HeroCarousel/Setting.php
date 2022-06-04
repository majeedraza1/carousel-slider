<?php

namespace CarouselSlider\Modules\HeroCarousel;

use CarouselSlider\Abstracts\SliderSetting;

/**
 * Settings class
 */
class Setting extends SliderSetting {
	/**
	 * Is data read from server?
	 *
	 * @var bool
	 */
	protected $extra_data_read = false;

	/**
	 * Get content settings
	 *
	 * @return array
	 */
	public function get_content_settings(): array {
		return $this->get_prop( 'slider_settings', [] );
	}

	/**
	 * Read extra metadata
	 *
	 * @return void
	 */
	public function read_extra_metadata() {
		if ( $this->extra_data_read ) {
			return;
		}
		foreach ( self::extra_props() as $attribute => $config ) {
			$this->read_single_metadata( $attribute, $config );
		}
		$this->extra_data_read = true;
	}

	/**
	 * Slider extra props
	 *
	 * @return array
	 */
	public static function extra_props(): array {
		return [
			'slider_settings' => [
				'id'      => '_content_slider_settings',
				'type'    => 'array',
				'default' => [],
			],
		];
	}
}
