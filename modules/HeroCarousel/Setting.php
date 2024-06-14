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
	 * Get slider items
	 *
	 * @return Item[]
	 */
	public function get_slider_items(): array {
		$items = get_post_meta( $this->get_slider_id(), '_content_slider', true );
		$items = is_array( $items ) ? array_values( $items ) : [];
		$data  = [];
		foreach ( $items as $item ) {
			$data[] = new Item( $item, $this->get_prop( 'slider_settings' ) );
		}

		return $data;
	}

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
			$value = get_post_meta( $this->get_slider_id(), $config['id'], true );
			$value = ! empty( $value ) ? $value : $config['default'];
			$value = $this->prepare_item_for_response( $config['type'], $value );
			$this->set_prop( $attribute, $value );
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
