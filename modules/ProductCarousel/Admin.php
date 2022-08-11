<?php

namespace CarouselSlider\Modules\ProductCarousel;

use CarouselSlider\Helper;
use CarouselSlider\Supports\MetaBoxForm;
use CarouselSlider\Admin\Setting;
use CarouselSlider\Supports\Sanitize;

defined( 'ABSPATH' ) || exit;

/**
 * Admin class
 *
 * @package Modules/ProductCarousel
 */
class Admin {
	/**
	 * The instance of the class
	 *
	 * @var self
	 */
	protected static $instance;

	/**
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_action( 'carousel_slider/meta_box_content', [ self::$instance, 'meta_box_content' ], 10, 2 );
			add_filter( 'carousel_slider/admin/metabox_color_settings', [ self::$instance, 'color_settings' ] );

			add_action( 'carousel_slider/save_slider', [ self::$instance, 'save_slider' ], 10, 2 );
		}

		return self::$instance;
	}

	/**
	 * Save post carousel content
	 *
	 * @param int   $post_id The post id.
	 * @param array $data User submitted data.
	 *
	 * @return void
	 */
	public function save_slider( int $post_id, array $data ) {
		$raw_data = $data['product_carousel'] ?? [];
		foreach ( $raw_data as $meta_key => $meta_value ) {
			if ( is_array( $meta_value ) ) {
				$meta_value = implode( ',', $meta_value );
			}

			update_post_meta( $post_id, $meta_key, sanitize_text_field( $meta_value ) );
		}
	}

	/**
	 * Show meta box content for product carousel
	 *
	 * @param int    $slider_id The slider id.
	 * @param string $slider_type The slider type.
	 */
	public function meta_box_content( int $slider_id, string $slider_type ) {
		if ( 'product-carousel' !== $slider_type ) {
			return;
		}
		$form     = new MetaBoxForm();
		$template = Setting::get_option( 'woocommerce_shop_loop_item_template', 'v1-compatibility' );

		$form->select(
			array(
				'group'   => 'product_carousel',
				'type'    => 'select',
				'id'      => '_product_query_type',
				'name'    => esc_html__( 'Query Type', 'carousel-slider' ),
				'std'     => 'query_product',
				'options' => array(
					'query_product'      => esc_html__( 'Query Products', 'carousel-slider' ),
					'product_categories' => esc_html__( 'Product Categories', 'carousel-slider' ),
					'product_tags'       => esc_html__( 'Product Tags', 'carousel-slider' ),
					'specific_products'  => esc_html__( 'Specific Products', 'carousel-slider' ),
				),
			)
		);
		$form->select(
			array(
				'group'   => 'product_carousel',
				'type'    => 'select',
				'id'      => '_product_query',
				'name'    => esc_html__( 'Choose Query', 'carousel-slider' ),
				'std'     => 'featured',
				'options' => array(
					'featured'                => esc_html__( 'Featured Products', 'carousel-slider' ),
					'recent'                  => esc_html__( 'Recent Products', 'carousel-slider' ),
					'sale'                    => esc_html__( 'Sale Products', 'carousel-slider' ),
					'best_selling'            => esc_html__( 'Best-Selling Products', 'carousel-slider' ),
					'top_rated'               => esc_html__( 'Top Rated Products', 'carousel-slider' ),
					'product_categories_list' => esc_html__( 'Product Categories List', 'carousel-slider' ),
				),
			)
		);
		$form->post_terms(
			array(
				'group'    => 'product_carousel',
				'type'     => 'post_terms',
				'id'       => '_product_categories',
				'taxonomy' => 'product_cat',
				'multiple' => true,
				'name'     => esc_html__( 'Product Categories', 'carousel-slider' ),
				'desc'     => esc_html__( 'Show products associated with selected categories.', 'carousel-slider' ),
			)
		);
		$form->post_terms(
			array(
				'group'    => 'product_carousel',
				'type'     => 'post_terms',
				'id'       => '_product_tags',
				'taxonomy' => 'product_tag',
				'multiple' => true,
				'name'     => esc_html__( 'Product Tags', 'carousel-slider' ),
				'desc'     => esc_html__( 'Show products associated with selected tags.', 'carousel-slider' ),
			)
		);
		$form->posts_list(
			array(
				'group'     => 'product_carousel',
				'type'      => 'posts_list',
				'id'        => '_product_in',
				'post_type' => 'product',
				'multiple'  => true,
				'name'      => esc_html__( 'Specific products', 'carousel-slider' ),
				'desc'      => esc_html__( 'Select products that you want to show as slider. Select at least 5 products', 'carousel-slider' ),
			)
		);
		$form->number(
			array(
				'group' => 'product_carousel',
				'type'  => 'number',
				'id'    => '_products_per_page',
				'name'  => esc_html__( 'Product per page', 'carousel-slider' ),
				'std'   => 12,
				'desc'  => esc_html__( 'How many products you want to show on carousel slide.', 'carousel-slider' ),
			)
		);
		if ( 'v1-compatibility' === $template ) {
			$settings = self::get_settings_for_toggle_sections();

			foreach ( $settings as $setting ) {
				Helper::print_unescaped_internal_string( MetaBoxForm::field( $setting ) );
			}
		}
	}

	/**
	 * Color settings
	 *
	 * @param string $html The content html.
	 *
	 * @return string
	 */
	public function color_settings( string $html ): string {
		global $post;
		$slide_type = get_post_meta( $post->ID, '_slide_type', true );
		$slide_type = array_key_exists( $slide_type, Helper::get_slide_types() ) ? $slide_type : 'image-carousel';
		if ( 'product-carousel' !== $slide_type ) {
			return $html;
		}
		$form = new MetaBoxForm();
		ob_start();
		$form->color(
			array(
				'group' => 'product_carousel',
				'id'    => '_product_title_color',
				'type'  => 'color',
				'name'  => esc_html__( 'Product Title Color', 'carousel-slider' ),
				'desc'  => esc_html__( 'Pick a color for product title. This color will also apply to sale tag and price.', 'carousel-slider' ),
				'std'   => Helper::get_default_setting( 'product_title_color' ),
			)
		);
		$form->color(
			array(
				'group' => 'product_carousel',
				'id'    => '_product_button_bg_color',
				'type'  => 'color',
				'name'  => esc_html__( 'Product Button Background Color', 'carousel-slider' ),
				'desc'  => esc_html__( 'Pick a color for button background color. This color will also apply to product rating.', 'carousel-slider' ),
				'std'   => Helper::get_default_setting( 'product_button_bg_color' ),
			)
		);
		$form->color(
			array(
				'group' => 'product_carousel',
				'id'    => '_product_button_text_color',
				'type'  => 'color',
				'name'  => esc_html__( 'Product Button Text Color', 'carousel-slider' ),
				'desc'  => esc_html__( 'Pick a color for button text color.', 'carousel-slider' ),
				'std'   => Helper::get_default_setting( 'product_button_text_color' ),
			)
		);
		$content = ob_get_clean();

		return $html . $content;
	}

	/**
	 * Get settings for toggle sections
	 *
	 * @return array[]
	 */
	public static function get_settings_for_toggle_sections(): array {
		return [
			[
				'group'             => 'product_carousel',
				'type'              => 'switch',
				'id'                => '_product_title',
				'label'             => esc_html__( 'Show Title.', 'carousel-slider' ),
				'description'       => esc_html__( 'Check to show product title.', 'carousel-slider' ),
				'default'           => 'on',
				'sanitize_callback' => [ Sanitize::class, 'checked' ],
			],
			[
				'group'             => 'product_carousel',
				'type'              => 'switch',
				'id'                => '_product_rating',
				'label'             => esc_html__( 'Show Rating.', 'carousel-slider' ),
				'description'       => esc_html__( 'Check to show product rating.', 'carousel-slider' ),
				'default'           => 'on',
				'sanitize_callback' => [ Sanitize::class, 'checked' ],
			],
			[
				'group'             => 'product_carousel',
				'type'              => 'switch',
				'id'                => '_product_price',
				'label'             => esc_html__( 'Show Price.', 'carousel-slider' ),
				'description'       => esc_html__( 'Check to show product price.', 'carousel-slider' ),
				'default'           => 'on',
				'sanitize_callback' => [ Sanitize::class, 'checked' ],
			],

			[
				'group'             => 'product_carousel',
				'type'              => 'switch',
				'id'                => '_product_cart_button',
				'label'             => esc_html__( 'Show Cart Button.', 'carousel-slider' ),
				'description'       => esc_html__( 'Check to show product add to cart button.', 'carousel-slider' ),
				'default'           => 'on',
				'sanitize_callback' => [ Sanitize::class, 'checked' ],
			],
			[
				'group'             => 'product_carousel',
				'type'              => 'switch',
				'id'                => '_product_onsale',
				'label'             => esc_html__( 'Show Sale Tag', 'carousel-slider' ),
				'description'       => esc_html__( 'Check to show product sale tag for onsale products.', 'carousel-slider' ),
				'default'           => 'on',
				'sanitize_callback' => [ Sanitize::class, 'checked' ],
			],
			[
				'group'             => 'product_carousel',
				'type'              => 'switch',
				'id'                => '_product_wishlist',
				'label'             => esc_html__( 'Show Wishlist Button', 'carousel-slider' ),
				/* translators: 1: YITH WooCommerce Wishlist plugin url*/
				'description'       => sprintf( esc_html__( 'Check to show wishlist button. This feature needs %s plugin to be installed.', 'carousel-slider' ), sprintf( '<a href="https://wordpress.org/plugins/yith-woocommerce-wishlist/" target="_blank" >%s</a>', __( 'YITH WooCommerce Wishlist', 'carousel-slider' ) ) ),
				'default'           => 'off',
				'sanitize_callback' => [ Sanitize::class, 'checked' ],
			],
			[
				'group'             => 'product_carousel',
				'type'              => 'switch',
				'id'                => '_product_quick_view',
				'label'             => esc_html__( 'Show Quick View', 'carousel-slider' ),
				'description'       => esc_html__( 'Check to show quick view button.', 'carousel-slider' ),
				'default'           => 'on',
				'sanitize_callback' => [ Sanitize::class, 'checked' ],
			],
		];
	}
}
