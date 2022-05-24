<?php

namespace CarouselSlider\Modules\ProductCarousel;

use CarouselSlider\Abstracts\AbstractView;
use CarouselSlider\Abstracts\SliderSetting;
use CarouselSlider\Admin\Setting as GlobalSetting;
use CarouselSlider\Helper;
use CarouselSlider\Supports\Validate;
use CarouselSlider\Modules\ProductCarousel\Helper as ProductCarouselHelper;
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
			$template = GlobalSetting::get_option( 'woocommerce_shop_loop_item_template' );
			if ( 'v1-compatibility' === $template ) {
				$html .= self::get_item( $product, $this->get_slider_setting() ) . PHP_EOL;
			} else {
				$html .= self::get_slider_item( $product, $this->get_slider_setting() ) . PHP_EOL;
			}
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
		ob_start();

		echo '<div class="product carousel-slider__product">';

		do_action( 'carousel_slider_before_shop_loop_item', $product );

		// Show product image.
		if ( $product->get_image_id() ) {
			echo '<a class="woocommerce-LoopProduct-link" href="' . esc_url( $product->get_permalink() ) . '">';
			if ( $settings->get_prop( 'lazy_load' ) ) {
				$image = wp_get_attachment_image_src( $product->get_image_id(), $settings->get_image_size() );
				echo '<img class="owl-lazy" data-src="' . esc_url( $image[0] ) . '" />';
			} else {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $product->get_image( $settings->get_image_size() );
			}
			echo '</a>';
		}

		// Show title.
		if ( $settings->get_prop( 'show_title' ) ) {
			echo '<a href="' . esc_attr( $product->get_permalink() ) . '">';
			echo '<h3 class="woocommerce-loop-product__title">' . esc_html( $product->get_title() ) . '</h3>';
			echo '</a>';
		}

		// Show Rating.
		if ( $settings->get_prop( 'show_rating' ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo wc_get_rating_html( $product->get_average_rating() );
		}

		// Sale Product batch.
		if ( $product->is_on_sale() && $settings->get_prop( 'show_onsale_tag' ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . __( 'Sale!', 'carousel-slider' ) . '</span>', $product );
		}

		// Show Price.
		if ( $settings->get_prop( 'show_price' ) ) {
			$price_html = '<span class="price">' . $product->get_price_html() . '</span>';
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo apply_filters( 'carousel_slider_product_price', $price_html, $product );
		}

		// Show button.
		if ( $settings->get_prop( 'show_cart_button' ) ) {
			echo '<div style="clear: both;"></div>';
			woocommerce_template_loop_add_to_cart();
		}

		do_action( 'carousel_slider_after_shop_loop_item', $product, $settings->get_slider_id(), $settings );

		echo '</div>';

		$item_html = ob_get_clean();

		return apply_filters( 'carousel_slider/loop/product-carousel', $item_html, $product, $settings );
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
		ob_start();
		echo '<div class="product carousel-slider__product">';

		do_action( 'carousel_slider_before_shop_loop_item', $product, $settings->get_slider_id() );

		do_action( 'woocommerce_before_shop_loop_item' );
		do_action( 'woocommerce_before_shop_loop_item_title' );
		do_action( 'woocommerce_shop_loop_item_title' );
		do_action( 'woocommerce_after_shop_loop_item_title' );
		do_action( 'woocommerce_after_shop_loop_item' );

		do_action( 'carousel_slider_after_shop_loop_item', $product, $settings->get_slider_id() );

		echo '</div>';
		$item_html = ob_get_clean();

		return apply_filters( 'carousel_slider/loop/product-carousel', $item_html, $product, $settings );
	}

	/**
	 * Get view
	 *
	 * @return string
	 */
	public function get_category_view(): string {
		$slider_id  = $this->get_slider_id();
		$categories = ProductCarouselHelper::product_categories();

		$html = $this->start_wrapper_html();
		foreach ( $categories as $category ) {
			ob_start();
			echo '<div class="product carousel-slider__product">';
			do_action( 'woocommerce_before_subcategory', $category );
			do_action( 'woocommerce_before_subcategory_title', $category );
			do_action( 'woocommerce_shop_loop_subcategory_title', $category );
			do_action( 'woocommerce_after_subcategory_title', $category );
			do_action( 'woocommerce_after_subcategory', $category );
			echo '</div>' . PHP_EOL;
			$html .= apply_filters( 'carousel_slider/loop/product-category', ob_get_clean(), $category, $this->get_slider_setting() );
		}

		$html .= $this->end_wrapper_html();

		return apply_filters( 'carousel_slider_product_carousel', $html, $slider_id );
	}
}
