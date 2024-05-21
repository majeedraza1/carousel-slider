<?php

namespace CarouselSlider;

use CarouselSlider\Interfaces\SliderViewInterface;
use CarouselSlider\Interfaces\TemplateParserInterface;
use WP_Error;
use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * Helper class
 */
class Helper extends ViewHelper {
	/**
	 * Get global settings
	 *
	 * @var array
	 */
	protected static $global_settings = [];

	/**
	 * Get placeholder image source.
	 *
	 * Retrieve the source of the placeholder image.
	 *
	 * @return string The source of the default placeholder image used by Elementor.
	 */
	public static function get_placeholder_image_src(): string {
		return apply_filters(
			'carousel_slider/placeholder_image_src',
			CAROUSEL_SLIDER_ASSETS . '/static-images/placeholder.svg'
		);
	}

	/**
	 * Is Elementor plugin active?
	 *
	 * @return bool
	 */
	public static function is_elementor_active(): bool {
		return class_exists( \Elementor\Plugin::class );
	}

	/**
	 * Is Divi builder active?
	 *
	 * @return bool
	 */
	public static function is_divi_builder_active(): bool {
		return ( defined( 'ET_BUILDER_THEME' ) && ET_BUILDER_THEME ) ||
			   class_exists( \ET_Builder_Plugin::class );
	}

	/**
	 * Is WPBakery Page Builder active?
	 *
	 * @return bool
	 */
	public static function is_wp_bakery_page_builder_active(): bool {
		return defined( 'WPB_VC_VERSION' );
	}

	/**
	 * Check if pro version is active.
	 *
	 * @return bool
	 */
	public static function is_pro_active(): bool {
		return in_array( 'carousel-slider-pro/carousel-slider-pro.php', get_option( 'active_plugins' ), true );
	}

	/**
	 * Should it show pro features?
	 *
	 * @return bool
	 */
	public static function show_pro_features(): bool {
		if ( defined( 'CAROUSEL_SLIDER_PRO_PROMOTION' ) ) {
			return CAROUSEL_SLIDER_PRO_PROMOTION;
		}

		return false;
	}

	/**
	 * Get sliders
	 *
	 * @param  array $args  Optional arguments.
	 *
	 * @return WP_Post[]|int[] Array of post objects or post IDs.
	 */
	public static function get_sliders( array $args = [] ): array {
		$args = wp_parse_args(
			$args,
			[
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
				'orderby'        => 'date',
				'order'          => 'DESC',
			]
		);

		$args['post_type'] = CAROUSEL_SLIDER_POST_TYPE;

		return get_posts( $args );
	}

	/**
	 * Get total sliders count
	 *
	 * @return int
	 */
	public static function get_sliders_count(): int {
		global $wpdb;
		$result = (array) $wpdb->get_row(
			$wpdb->prepare(
				"SELECT COUNT( * ) AS num_posts FROM {$wpdb->posts} WHERE post_type = %s",
				CAROUSEL_SLIDER_POST_TYPE
			),
			ARRAY_A
		);

		return isset( $result['num_posts'] ) ? intval( $result['num_posts'] ) : 0;
	}

	/**
	 * Get global settings
	 *
	 * @return array
	 */
	public static function get_global_settings(): array {
		if ( empty( static::$global_settings ) ) {
			$default_args = apply_filters(
				'carousel_slider/global_options/default_args',
				[
					'load_scripts'                        => 'optimized',
					'slider_js_package'                   => 'owl.carousel',
					'show_structured_data'                => '1',
					'woocommerce_shop_loop_item_template' => 'v1-compatibility',
					'breakpoints_width'                   => [],
				]
			);
			$options      = get_option( 'carousel_slider_settings', [] );
			$options      = is_array( $options ) ? $options : [];

			static::$global_settings = wp_parse_args( $options, $default_args );
		}

		return static::$global_settings;
	}

	/**
	 * Get setting
	 *
	 * @param  string $key  The setting key.
	 * @param  mixed  $default_value  Setting default value.
	 *
	 * @return mixed|null
	 */
	public static function get_setting( string $key, $default_value = null ) {
		$settings = self::get_global_settings();

		return $settings[ $key ] ?? $default_value;
	}

	/**
	 * Get breakpoint width
	 *
	 * @param  string $prefix  The breakpoint prefix.
	 *
	 * @return int
	 */
	public static function get_breakpoint_width( string $prefix ): int {
		$defaults    = [
			'xs'  => 300,
			'sm'  => 576,
			'md'  => 768,
			'lg'  => 1024,
			'xl'  => 1280,
			'2xl' => 1536,
		];
		$breakpoints = self::get_setting( 'breakpoints_width' );
		$breakpoints = is_array( $breakpoints ) ? $breakpoints : [];
		$breakpoints = wp_parse_args( $breakpoints, $defaults );

		return isset( $breakpoints[ $prefix ] ) && is_int( $breakpoints[ $prefix ] ) ? $breakpoints[ $prefix ] : 0;
	}

	/**
	 * Check if we are using swiper
	 *
	 * @return bool
	 */
	public static function is_using_swiper(): bool {
		$is_swiper = 'swiper' === self::get_setting( 'slider_js_package' );

		return apply_filters( 'carousel_slider/is_using_swiper', $is_swiper );
	}

	/**
	 * Get carousel slider available slide type
	 *
	 * @return array
	 */
	public static function get_slide_types(): array {
		$types = [];
		foreach ( self::get_slider_types() as $slug => $args ) {
			$types[ $slug ] = $args['label'];
		}

		return apply_filters( 'carousel_slider_slide_type', $types );
	}

	/**
	 * Get slider types
	 *
	 * @return array
	 */
	public static function get_slider_types(): array {
		$slider_types = [
			'image-carousel'     => [
				'label'   => __( 'Image Carousel', 'carousel-slider' ),
				'enabled' => true,
				'icon'    => '<span class="dashicons dashicons-format-image"></span>',
			],
			'image-carousel-url' => [
				'label'   => __( 'Image Carousel (URL)', 'carousel-slider' ),
				'enabled' => true,
				'icon'    => '<span class="dashicons dashicons-admin-links"></span>',
			],
			'post-carousel'      => [
				'label'   => __( 'Post Carousel', 'carousel-slider' ),
				'enabled' => true,
				'icon'    => '<span class="dashicons dashicons-admin-post"></span>',
			],
			'video-carousel'     => [
				'label'   => __( 'Video Carousel', 'carousel-slider' ),
				'enabled' => true,
				'icon'    => '<span class="dashicons dashicons-video-alt3"></span>',
			],
			'hero-banner-slider' => [
				'label'   => __( 'Hero Carousel', 'carousel-slider' ),
				'enabled' => true,
				'icon'    => '<span class="dashicons dashicons-media-interactive"></span>',
			],
			'product-carousel'   => [
				'label'   => __( 'Product Carousel', 'carousel-slider' ),
				'enabled' => self::is_woocommerce_active(),
				'icon'    => '<span class="dashicons dashicons-products"></span>',
			],
		];

		if ( self::show_pro_features() || self::is_pro_active() ) {
			$slider_types['product-carousel-pro'] = [
				'label'   => __( 'Product Carousel (Advance)', 'carousel-slider' ),
				'enabled' => self::is_woocommerce_active() && self::is_pro_active(),
				'icon'    => '<span class="dashicons dashicons-products"></span>',
				'pro'     => true,
			];

			$slider_types['product-categories-list-pro'] = [
				'label'   => __( 'Product Categories List', 'carousel-slider' ),
				'enabled' => self::is_woocommerce_active() && self::is_pro_active(),
				'icon'    => '<span class="dashicons dashicons-category"></span>',
				'pro'     => true,
			];
		}

		return apply_filters( 'carousel_slider/slider_types', $slider_types );
	}

	/**
	 * Get enabled slider types slug
	 *
	 * @return array
	 */
	public static function get_enabled_slider_types_slug(): array {
		$slugs = [];
		foreach ( self::get_slider_types() as $slug => $slider_type ) {
			if ( ! $slider_type['enabled'] ) {
				continue;
			}
			$slugs[] = $slug;
		}

		return $slugs;
	}

	/**
	 * Get slider view
	 *
	 * @param  string $key  The slider type slug.
	 *
	 * @return false|SliderViewInterface
	 */
	public static function get_slider_view( string $key ) {
		$views = apply_filters( 'carousel_slider/register_view', [] );

		return $views[ $key ] ?? false;
	}

	/**
	 * Get slider template parser
	 *
	 * @param  string $key  The slider type slug.
	 *
	 * @return false|TemplateParserInterface
	 */
	public static function get_template_parser( string $key ) {
		$views = apply_filters( 'carousel_slider/template_parser', [] );

		return $views[ $key ] ?? false;
	}

	/**
	 * Get default settings
	 *
	 * @return array
	 */
	public static function get_default_settings(): array {
		return apply_filters(
			'carousel_slider_default_settings',
			[
				'product_title_color'       => '#323232',
				'product_button_bg_color'   => '#00d1b2',
				'product_button_text_color' => '#f1f1f1',
				'nav_color'                 => '#f1f1f1',
				'nav_active_color'          => '#00d1b2',
				'margin_right'              => 10,
				'lazy_load_image'           => 'off',
			]
		);
	}

	/**
	 * Get default setting
	 *
	 * @param  string $key  The setting key.
	 * @param  mixed  $default_value  Default value.
	 *
	 * @return mixed|null
	 */
	public static function get_default_setting( string $key, $default_value = null ) {
		$settings = self::get_default_settings();

		return $settings[ $key ] ?? $default_value;
	}

	/**
	 * Get available image sizes
	 *
	 * @return array
	 */
	public static function get_available_image_sizes(): array {
		global $_wp_additional_image_sizes;

		$sizes = [];
		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, [ 'thumbnail', 'medium', 'medium_large', 'large' ], true ) ) {

				$width  = get_option( "{$_size}_size_w" );
				$height = get_option( "{$_size}_size_h" );
				$crop   = get_option( "{$_size}_crop" ) ? 'hard' : 'soft';

				$sizes[ $_size ] = sprintf( '%s - %s:%sx%s', $_size, $crop, $width, $height );

			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

				$width  = $_wp_additional_image_sizes[ $_size ]['width'];
				$height = $_wp_additional_image_sizes[ $_size ]['height'];
				$crop   = $_wp_additional_image_sizes[ $_size ]['crop'] ? 'hard' : 'soft';

				$sizes[ $_size ] = sprintf( '%s - %s:%sx%s', $_size, $crop, $width, $height );
			}
		}

		return array_merge( $sizes, [ 'full' => 'original uploaded image' ] );
	}

	/**
	 * Check if WooCommerce is active
	 *
	 * @return bool
	 */
	public static function is_woocommerce_active(): bool {
		return in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ), true ) ||
			   defined( 'WC_VERSION' ) ||
			   defined( 'WOOCOMMERCE_VERSION' );
	}

	/**
	 * Creates Carousel Slider test page
	 *
	 * @param  array $ids  The sliders ids.
	 *
	 * @return int|WP_Error
	 */
	public static function create_test_page( array $ids = [] ) {
		$page_path    = 'carousel-slider-test';
		$page_title   = __( 'Carousel Slider Test', 'carousel-slider' );
		$page_content = '';

		if ( empty( $ids ) ) {
			$ids = self::get_sliders();
		}

		foreach ( $ids as $id ) {
			$_post         = get_post( $id );
			$page_content .= '<!-- wp:heading {"level":4} --><h4>' . $_post->post_title . '</h4><!-- /wp:heading -->';
			$page_content .= '<!-- wp:carousel-slider/slider {"sliderID":' . $id . ',"sliderName":"' . $_post->post_title . ' ( ID: ' . $id . ' )"} -->';
			$page_content .= '<div class="wp-block-carousel-slider-slider">[carousel_slide id=\'' . $id . '\']</div>';
			$page_content .= '<!-- /wp:carousel-slider/slider -->';
			$page_content .= '<!-- wp:spacer {"height":100} --><div style="height:100px" aria-hidden="true" class="wp-block-spacer"></div><!-- /wp:spacer -->';
		}

		// Check that the page doesn't exist already.
		$_page     = get_page_by_path( $page_path );
		$page_data = [
			'post_content'   => $page_content,
			'post_name'      => $page_path,
			'post_title'     => $page_title,
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'ping_status'    => 'closed',
			'comment_status' => 'closed',
		];

		if ( $_page instanceof WP_Post ) {
			$page_data['ID'] = $_page->ID;

			return wp_update_post( $page_data );
		}

		return wp_insert_post( $page_data );
	}

	/**
	 * What type of request is this?
	 *
	 * @param  string $type  admin, ajax, rest, cron or frontend.
	 *
	 * @return bool
	 */
	public static function is_request( string $type ): bool {
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'rest':
				return defined( 'REST_REQUEST' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}

		return false;
	}

	/**
	 * Create a new slider.
	 *
	 * @param  string $title  The slider title.
	 * @param  string $type  The slider type.
	 * @param  array  $args  Additional arguments.
	 *
	 * @return int|WP_Error The post ID on success. The value 0 or \WP_Error on failure.
	 */
	public static function create_slider( string $title, string $type = '', array $args = [] ) {
		$data = wp_parse_args(
			$args,
			[
				'post_status'    => 'publish',
				'post_type'      => 'carousels',
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
			]
		);

		$data['post_title'] = $title;
		$data['post_type']  = CAROUSEL_SLIDER_POST_TYPE;

		$post_id = wp_insert_post( $data );

		if ( ! is_wp_error( $post_id ) ) {
			if ( ! empty( $type ) ) {
				update_post_meta( $post_id, '_slide_type', $type );
			}
			update_post_meta( $post_id, '_carousel_slider_version', CAROUSEL_SLIDER_VERSION );
		}

		return $post_id;
	}

	/**
	 * Get preview link
	 *
	 * @param  WP_Post $post  The WP_Post object.
	 *
	 * @return string
	 */
	public static function get_preview_link( WP_Post $post ): string {
		$args = [
			'carousel_slider_preview' => true,
			'carousel_slider_iframe'  => true,
			'slider_id'               => $post->ID,
		];

		return add_query_arg( $args, site_url( '/' ) );
	}

	/**
	 * Print internal content (not user input) without escaping.
	 *
	 * @param  string $html  The string to be print.
	 */
	public static function print_unescaped_internal_string( string $html ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Get slider ids from content
	 *
	 * @param  string $content  The content to be tested.
	 *
	 * @return array|int[]
	 */
	public static function get_slider_ids_from_content( string $content ): array {
		$slider_ids = [];
		if ( false === strpos( $content, '[carousel_slide' ) ) {
			return $slider_ids;
		}
		if ( preg_match_all(
			'/(\[carousel_slide)\s*.*id=(\'?\"?)(?P<slider_id>\d+)(\'?\"?)\s*.*(\])/',
			$content,
			$matches
		) ) {
			$slider_ids = array_map( 'intval', $matches['slider_id'] );
		}

		return $slider_ids;
	}
}
