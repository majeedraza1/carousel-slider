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

class View extends AbstractView {

	public function get_slider_setting(): SliderSetting {
		if ( ! $this->slider_setting instanceof Setting ) {
			$this->slider_setting = new Setting( $this->get_slider_id() );
		}

		return $this->slider_setting;
	}

	/**
	 * @inheritDoc
	 */
	public function render(): string {
		$query_type    = get_post_meta( $this->slider_id, '_product_query_type', true );
		$query_type    = empty( $query_type ) ? 'query_product' : $query_type;
		$query_type    = ( 'query_porduct' == $query_type ) ? 'query_product' : $query_type; // Type mistake
		$product_query = get_post_meta( $this->slider_id, '_product_query', true );

		if ( $query_type == 'query_product' && $product_query == 'product_categories_list' ) {
			return $this->get_category_view();
		}

		return $this->get_view();
	}

	/**
	 * Get slider settings
	 *
	 * @param int $slider_id
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
	 * @return string
	 */
	public function get_view(): string {
		$products = ProductCarouselHelper::get_products( $this->get_slider_id() );
		$settings = self::get_settings( $this->get_slider_id() );

		$html = $this->start_wrapper_html();

		global $post;
		global $product;

		foreach ( $products as $product ) {
			$post = get_post( $product->get_id() );
			setup_postdata( $post );

			if ( ! $product->is_visible() ) {
				continue;
			}

			if ( ! $product->has_enough_stock( 1 ) ) {
				continue;
			}
			$template = GlobalSetting::get_option( 'woocommerce_shop_loop_item_template' );
			if ( 'v1-compatibility' == $template ) {
				$html .= self::get_item( $product, $settings ) . PHP_EOL;
			} else {
				$html .= self::get_slider_item( $product, $settings ) . PHP_EOL;
			}
		}
		wp_reset_postdata();

		$html .= $this->end_wrapper_html();

		return apply_filters( 'carousel_slider_product_carousel', $html );
	}

	/**
	 * Get item
	 *
	 * @param WC_Product $product
	 * @param array      $settings
	 *
	 * @return string
	 */
	public static function get_item( WC_Product $product, array $settings ): string {
		ob_start();

		echo '<div class="product carousel-slider__product">';

		do_action( 'carousel_slider_before_shop_loop_item', $product );

		// Show product image
		if ( $product->get_image_id() ) {
			echo '<a class="woocommerce-LoopProduct-link" href="' . $product->get_permalink() . '">';
			if ( $settings['lazy_load_image'] ) {
				$image = wp_get_attachment_image_src( $product->get_image_id(), $settings['image_size'] );
				echo '<img class="owl-lazy" data-src="' . $image[0] . '" />';
			} else {
				echo $product->get_image( $settings['image_size'] );
			}
			echo '</a>';
		}

		// Show title
		if ( $settings['show_title'] ) {
			echo '<a href="' . esc_attr( $product->get_permalink() ) . '">';
			echo '<h3 class="woocommerce-loop-product__title">' . esc_html( $product->get_title() ) . '</h3>';
			echo '</a>';
		}

		// Show Rating
		if ( $settings['show_rating'] ) {
			echo wc_get_rating_html( $product->get_average_rating() );
		}

		// Sale Product batch
		if ( $product->is_on_sale() && $settings['show_onsale'] ) {
			echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . __( 'Sale!', 'carousel-slider' ) . '</span>', $product );
		}

		// Show Price
		if ( $settings['show_price'] ) {
			$price_html = '<span class="price">' . $product->get_price_html() . '</span>';
			echo apply_filters( 'carousel_slider_product_price', $price_html, $product );
		}

		// Show button
		if ( $settings['show_cart_button'] ) {
			echo '<div style="clear: both;"></div>';
			woocommerce_template_loop_add_to_cart();
		}

		do_action( 'carousel_slider_after_shop_loop_item', $product, $settings['slider_id'], $settings );

		echo '</div>';

		$item_html = ob_get_clean();

		return apply_filters( 'carousel_slider/product_carousel_item', $item_html, $product, $settings );
	}

	/**
	 * Get product slider item
	 *
	 * @param WC_Product $product
	 * @param array      $settings
	 *
	 * @return string
	 */
	public static function get_slider_item( WC_Product $product, array $settings ): string {
		ob_start();
		echo '<div class="product carousel-slider__product">';

		do_action( 'carousel_slider_before_shop_loop_item', $product, $settings['slider_id'] );

		do_action( 'woocommerce_before_shop_loop_item' );
		do_action( 'woocommerce_before_shop_loop_item_title' );
		do_action( 'woocommerce_shop_loop_item_title' );
		do_action( 'woocommerce_after_shop_loop_item_title' );
		do_action( 'woocommerce_after_shop_loop_item' );

		do_action( 'carousel_slider_after_shop_loop_item', $product, $settings['slider_id'] );

		echo '</div>';
		$item_html = ob_get_clean();

		return apply_filters( 'carousel_slider/product_carousel_item', $item_html, $product, $settings['slider_id'] );
	}

	/**
	 * Get view
	 *
	 * @return string
	 */
	public function get_category_view(): string {
		$slider_id  = $this->get_slider_id();
		$categories = ProductCarouselHelper::product_categories();

		ob_start();
		echo $this->start_wrapper_html();
		foreach ( $categories as $category ) {
			echo '<div class="product carousel-slider__product">';
			do_action( 'woocommerce_before_subcategory', $category );
			do_action( 'woocommerce_before_subcategory_title', $category );
			do_action( 'woocommerce_shop_loop_subcategory_title', $category );
			do_action( 'woocommerce_after_subcategory_title', $category );
			do_action( 'woocommerce_after_subcategory', $category );
			echo '</div>' . PHP_EOL;
		}

		echo $this->end_wrapper_html();
		$html = ob_get_clean();

		return apply_filters( 'carousel_slider_product_carousel', $html, $slider_id );
	}
}
