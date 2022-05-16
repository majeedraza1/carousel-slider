<?php

namespace CarouselSlider\Modules\HeroCarousel;

use CarouselSlider\Abstracts\SliderSetting;

/**
 * Settings class
 */
class Setting extends SliderSetting {

	/**
	 * Get content settings
	 *
	 * @return array
	 */
	public function get_content_settings(): array {
		return $this->get_prop( 'slider_settings', [] );
	}

	/**
	 * Default properties
	 *
	 * @inerhitDoc
	 */
	public static function props(): array {
		$parent_props = parent::props();
		$extra_props  = self::extra_props();

		return wp_parse_args( $extra_props, $parent_props );
	}

	/**
	 * Slider extra props
	 *
	 * @return array
	 */
	public static function extra_props(): array {
		return [
			'slider_settings' => [
				'meta_key' => '_content_slider_settings',
				'type'     => 'array',
				'default'  => [],
			],
		];
	}
}
