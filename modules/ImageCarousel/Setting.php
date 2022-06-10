<?php

namespace CarouselSlider\Modules\ImageCarousel;

use CarouselSlider\Abstracts\SliderSetting;
use CarouselSlider\Supports\Validate;

/**
 * Setting class
 *
 * @method bool should_shuffle_images()
 * @method bool should_show_title()
 * @method bool should_show_caption()
 * @method bool should_show_lightbox()
 */
class Setting extends SliderSetting {
	/**
	 * Is data read from server?
	 *
	 * @var bool
	 */
	protected $extra_data_read = false;

	/**
	 * Get image ids
	 *
	 * @return array
	 */
	public function get_image_ids(): array {
		$ids = $this->get_prop( 'image_ids', [] );
		if ( is_string( $ids ) ) {
			$ids = array_filter( explode( ',', $ids ) );
		}
		if ( Validate::checked( $this->get_prop( 'shuffle_images' ) ) ) {
			shuffle( $ids );
		}

		return is_array( $ids ) ? $ids : [];
	}

	/**
	 * Get image target
	 *
	 * @return string
	 */
	public function get_image_target(): string {
		$target = $this->get_prop( 'image_target' );

		return in_array( $target, [ '_self', '_blank' ], true ) ? $target : '_self';
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
			'image_ids'      => [
				'id'      => '_wpdh_image_ids',
				'type'    => 'int[]',
				'default' => '',
			],
			'shuffle_images' => [
				'id'      => '_shuffle_images',
				'type'    => 'bool',
				'default' => 'no',
			],
			'image_target'   => [
				'id'      => '_image_target',
				'type'    => 'string',
				'default' => '_self',
			],
			'show_title'     => [
				'id'      => '_show_attachment_title',
				'type'    => 'bool',
				'default' => 'off',
			],
			'show_caption'   => [
				'id'      => '_show_attachment_caption',
				'type'    => 'bool',
				'default' => 'off',
			],
			'show_lightbox'  => [
				'id'      => '_image_lightbox',
				'type'    => 'bool',
				'default' => 'off',
			],
		];
	}
}
