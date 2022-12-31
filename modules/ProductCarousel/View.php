<?php

namespace CarouselSlider\Modules\ProductCarousel;

use CarouselSlider\Abstracts\AbstractView;
use CarouselSlider\Abstracts\SliderSetting;
use CarouselSlider\Admin\Setting as GlobalSetting;
use CarouselSlider\Helper;
use CarouselSlider\Supports\Validate;
use CarouselSlider\Modules\ProductCarousel\Helper as ProductCarouselHelper;
use CarouselSlider\TemplateParserBase;
use WC_Product;

defined( 'ABSPATH' ) || exit;

/**
 * View class
 *
 * @package Modules/ProductCarousel
 */
class View extends AbstractView {

	/**
	 * Get slider setting
	 *
	 * @return SliderSetting
	 */
	public function get_slider_setting(): SliderSetting {
		if ( ! $this->slider_setting instanceof Setting ) {
			$this->slider_setting = new Setting( $this->get_slider_id() );
		}

		return $this->slider_setting;
	}

	/**
	 * Render html view
	 *
	 * @inheritDoc
	 */
	public function render(): string {
		$query_type    = get_post_meta( $this->slider_id, '_product_query_type', true );
		$query_type    = empty( $query_type ) ? 'query_product' : $query_type;
		$query_type    = ( 'query_porduct' === $query_type ) ? 'query_product' : $query_type; // Fix type mistake.
		$product_query = get_post_meta( $this->slider_id, '_product_query', true );

		if ( 'query_product' === $query_type && 'product_categories_list' === $product_query ) {
			return $this->get_category_view();
		}

		return $this->get_view();
	}

	/**
	 * Get slider settings
	 *
	 * @param int $slider_id The slider id.
	 *
	 * @return array
	 */
	public static function get_settings( int $slider_id ): array {
		$image_size = get_post_meta( $slider_id, '_image_size', true );
		$image_size = array_key_exists( $image_size, Helper::get_available_image_sizes() ) ? $image_size : 'thumbnail';

		return [
			'slider_id'        => $slider_id,
			'image_size'       => $image_size,
			'lazy_load_image'  => Validate::checked( get_post_meta( $slider_id, '_lazy_load_image', true ) ),
			'show_title'       => Validate::checked( get_post_meta( $slider_id, '_product_title', true ) ),
			'show_rating'      => Validate::checked( get_post_meta( $slider_id, '_product_rating', true ) ),
			'show_price'       => Validate::checked( get_post_meta( $slider_id, '_product_price', true ) ),
			'show_cart_button' => Validate::checked( get_post_meta( $slider_id, '_product_cart_button', true ) ),
			'show_onsale'      => Validate::checked( get_post_meta( $slider_id, '_product_onsale', true ) ),
			'show_wishlist'    => Validate::checked( get_post_meta( $slider_id, '_product_wishlist', true ) ),
			'show_quick_view'  => Validate::checked( get_post_meta( $slider_id, '_product_quick_view', true ) ),
		];
	}

	/**
	 * Get CSS variable
	 *
	 * @return array
	 */
	public function get_css_variable(): array {
		$css_vars                            = parent::get_css_variable();
		$css_vars['--cs-product-primary']    = get_post_meta( $this->get_slider_id(), '_product_button_bg_color', true );
		$css_vars['--cs-product-on-primary'] = get_post_meta( $this->get_slider_id(), '_product_button_text_color', true );
		$css_vars['--cs-product-text']       = get_post_meta( $this->get_slider_id(), '_product_title_color', true );

		return $css_vars;
	}

	/**
	 * Get view
	 *
	 * @return string
	 */
	public function get_view(): string {
		$products = ProductCarouselHelper::get_products( $this->get_slider_id() );
		$settings = $this->get_slider_setting();

		$template_name     = GlobalSetting::get_option( 'woocommerce_shop_loop_item_template' );
		$template_filename = 'v1-compatibility' === $template_name ? 'loop/product-carousel.php' : 'loop/product-carousel-2.php';
		$template          = new TemplateParserBase( $settings );
		$template->set_template( $template_filename );

		global $post;
		global $product;

		$html = $this->start_wrapper_html();
		foreach ( $products as $product ) {
			$_post = get_post( $product->get_id() );
			setup_postdata( $_post );

			if ( ! $product->is_visible() ) {
				continue;
			}

			if ( ! $product->has_enough_stock( 1 ) ) {
				continue;
			}

			$template->set_object( $product );
			$template->set_extra_vars( 'post', $_post );
			$template->set_extra_vars( 'product', $product );

			$html .= $this->start_item_wrapper_html();
			$html .= apply_filters( 'carousel_slider/loop/product-carousel', $template->render(), $product, $settings );
			$html .= $this->end_item_wrapper_html();
		}
		wp_reset_postdata();

		$html .= $this->end_wrapper_html();

		return apply_filters( 'carousel_slider_product_carousel', $html );
	}

	/**
	 * Get item
	 *
	 * @param WC_Product    $product The WC_Product object.
	 * @param SliderSetting $settings Settings array.
	 *
	 * @return string
	 */
	public static function get_item( WC_Product $product, Setting $settings ): string {
		$template = new TemplateParserBase( $settings );
		$template->set_template( 'loop/product-carousel.php' );
		$template->set_object( $product );
		$template->set_extra_vars( 'product', $product );

		return apply_filters( 'carousel_slider/loop/product-carousel', $template->render(), $product, $settings );
	}

	/**
	 * Get product slider item
	 *
	 * @param WC_Product    $product The WC_Product object.
	 * @param SliderSetting $settings Settings array.
	 *
	 * @return string
	 */
	public static function get_slider_item( WC_Product $product, SliderSetting $settings ): string {

		$template = new TemplateParserBase( $settings );
		$template->set_template( 'loop/product-carousel-2.php' );
		$template->set_object( $product );
		$template->set_extra_vars( 'product', $product );

		return apply_filters( 'carousel_slider/loop/product-carousel', $template->render(), $product, $settings );
	}

	/**
	 * Get view
	 *
	 * @return string
	 */
	public function get_category_view(): string {
		$slider_id  = $this->get_slider_id();
		$categories = ProductCarouselHelper::product_categories();

		$template = new TemplateParserBase( $this->get_slider_setting() );
		$template->set_template( 'loop/product-categories-list.php' );

		$html = $this->start_wrapper_html();
		foreach ( $categories as $category ) {
			$template->set_object( $category );
			$html .= $this->start_item_wrapper_html();
			$html .= apply_filters( 'carousel_slider/loop/product-category', $template->render(), $category, $this->get_slider_setting() );
			$html .= $this->end_item_wrapper_html();
		}

		$html .= $this->end_wrapper_html();

		return apply_filters( 'carousel_slider_product_carousel', $html, $slider_id );
	}
}
