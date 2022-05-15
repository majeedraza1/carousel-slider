<?php

namespace CarouselSlider\Modules\ImageCarousel;

use CarouselSlider\Abstracts\SliderSetting;
use CarouselSlider\Supports\Validate;

/**
 * Setting class
 */
class Setting extends SliderSetting {

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
			'image_ids'      => [
				'meta_key' => '_wpdh_image_ids',
				'type'     => 'int[]',
				'default'  => '',
			],
			'shuffle_images' => [
				'meta_key' => '_shuffle_images',
				'type'     => 'bool',
				'default'  => 'no',
			],
			'image_target'   => [
				'meta_key' => '_image_target',
				'type'     => 'string',
				'default'  => '_self',
			],
			'show_title'     => [
				'meta_key' => '_show_attachment_title',
				'type'     => 'bool',
				'default'  => 'off',
			],
			'show_caption'   => [
				'meta_key' => '_show_attachment_caption',
				'type'     => 'bool',
				'default'  => 'off',
			],
			'show_lightbox'  => [
				'meta_key' => '_image_lightbox',
				'type'     => 'bool',
				'default'  => 'off',
			],
		];
	}
}
