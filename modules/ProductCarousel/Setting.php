<?php

namespace CarouselSlider\Modules\ProductCarousel;

use CarouselSlider\Abstracts\SliderSetting;
use CarouselSlider\Helper as GlobalHelper;

/**
 * Setting class
 *
 * @package Modules/ProductCarousel
 */
class Setting extends SliderSetting {
	/**
	 * Is data read from server?
	 *
	 * @var bool
	 */
	protected $extra_data_read = false;

	/**
	 * Available types
	 *
	 * @var string[]
	 */
	protected static $types = [
		'product_categories_list',
		'product_categories',
		'product_tags',
		'specific_products',
		'featured',
		'recent',
		'sale',
		'best_selling',
		'top_rated',
	];

	/**
	 * Get query type
	 *
	 * @return string
	 */
	public function get_query_type(): string {
		$slide_type = $this->get_prop( 'product_query_type' );
		// For backward compatibility of typo.
		$slide_type    = str_replace( 'query_porduct', 'query_product', $slide_type );
		$product_query = $this->get_prop( 'product_query' );
		if ( 'query_product' === $slide_type ) {
			$slide_type = $product_query;
		}

		return in_array( $slide_type, self::$types, true ) ? $slide_type : 'recent';
	}

	/**
	 * Get product category slug
	 *
	 * @return array
	 */
	public function get_categories_slug(): array {
		$ids = $this->get_prop( 'product_categories' );

		return Helper::format_term_slug( $ids, 'product_cat' );
	}

	/**
	 * Get product category slug
	 *
	 * @return array
	 */
	public function get_tags_slug(): array {
		$ids = $this->get_prop( 'product_tags' );

		return Helper::format_term_slug( $ids, 'product_tag' );
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
			'slide_type'         => [
				'id'      => '_slide_type',
				'type'    => 'string',
				'default' => 'product-carousel',
			],
			'product_query_type' => [
				'id'      => '_product_query_type',
				'type'    => 'string',
				'default' => 'query_product',
			],
			'product_query'      => [
				'id'      => '_product_query',
				'type'    => 'string',
				'default' => 'recent',
			],
			'product_categories' => [
				'id'      => '_product_categories',
				'type'    => 'int[]',
				'default' => '',
			],
			'product_tags'       => [
				'id'      => '_product_tags',
				'type'    => 'int[]',
				'default' => '',
			],
			'product_in'         => [
				'id'      => '_product_in',
				'type'    => 'int[]',
				'default' => '',
			],
			'per_page'           => [
				'id'      => '_products_per_page',
				'type'    => 'int',
				'default' => 12,
			],
			'show_title'         => [
				'id'      => '_product_title',
				'type'    => 'bool',
				'default' => true,
			],
			'show_rating'        => [
				'id'      => '_product_rating',
				'type'    => 'bool',
				'default' => true,
			],
			'show_price'         => [
				'id'      => '_product_price',
				'type'    => 'bool',
				'default' => true,
			],
			'show_cart_button'   => [
				'id'      => '_product_cart_button',
				'type'    => 'bool',
				'default' => true,
			],
			'show_onsale_tag'    => [
				'id'      => '_product_onsale',
				'type'    => 'bool',
				'default' => true,
			],
			'show_wishlist'      => [
				'id'      => '_product_wishlist',
				'type'    => 'bool',
				'default' => false,
			],
			'show_quick_view'    => [
				'id'      => '_product_quick_view',
				'type'    => 'bool',
				'default' => false,
			],
			'title_color'        => [
				'id'      => '_product_title_color',
				'type'    => 'string',
				'default' => GlobalHelper::get_default_setting( 'product_title_color' ),
			],
			'button_color'       => [
				'id'      => '_product_button_bg_color',
				'type'    => 'string',
				'default' => GlobalHelper::get_default_setting( 'product_button_bg_color' ),
			],
			'button_on_color'    => [
				'id'      => '_product_button_text_color',
				'type'    => 'string',
				'default' => GlobalHelper::get_default_setting( 'product_button_text_color' ),
			],
		];
	}
}
