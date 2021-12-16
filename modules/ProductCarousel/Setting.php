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
			'slide_type'         => [
				'meta_key' => '_slide_type',
				'type'     => 'string',
				'default'  => 'product-carousel',
			],
			'product_query_type' => [
				'meta_key' => '_product_query_type',
				'type'     => 'string',
				'default'  => 'query_product',
			],
			'product_query'      => [
				'meta_key' => '_product_query',
				'type'     => 'string',
				'default'  => 'recent',
			],
			'product_categories' => [
				'meta_key' => '_product_categories',
				'type'     => 'int[]',
				'default'  => '',
			],
			'product_tags'       => [
				'meta_key' => '_product_tags',
				'type'     => 'int[]',
				'default'  => '',
			],
			'product_in'         => [
				'meta_key' => '_product_in',
				'type'     => 'int[]',
				'default'  => '',
			],
			'per_page'           => [
				'meta_key' => '_products_per_page',
				'type'     => 'int',
				'default'  => 12,
			],
			'show_title'         => [
				'meta_key' => '_product_title',
				'type'     => 'bool',
				'default'  => true,
			],
			'show_rating'        => [
				'meta_key' => '_product_rating',
				'type'     => 'bool',
				'default'  => true,
			],
			'show_price'         => [
				'meta_key' => '_product_price',
				'type'     => 'bool',
				'default'  => true,
			],
			'show_cart_button'   => [
				'meta_key' => '_product_cart_button',
				'type'     => 'bool',
				'default'  => true,
			],
			'show_onsale_tag'    => [
				'meta_key' => '_product_onsale',
				'type'     => 'bool',
				'default'  => true,
			],
			'show_wishlist'      => [
				'meta_key' => '_product_wishlist',
				'type'     => 'bool',
				'default'  => false,
			],
			'show_quick_view'    => [
				'meta_key' => '_product_quick_view',
				'type'     => 'bool',
				'default'  => false,
			],
			'title_color'        => [
				'meta_key' => '_product_title_color',
				'type'     => 'string',
				'default'  => GlobalHelper::get_default_setting( 'product_title_color' ),
			],
			'button_color'       => [
				'meta_key' => '_product_button_bg_color',
				'type'     => 'string',
				'default'  => GlobalHelper::get_default_setting( 'product_button_bg_color' ),
			],
			'button_on_color'    => [
				'meta_key' => '_product_button_text_color',
				'type'     => 'string',
				'default'  => GlobalHelper::get_default_setting( 'product_button_text_color' ),
			],
		];
	}
}
