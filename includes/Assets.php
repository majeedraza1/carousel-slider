<?php

namespace CarouselSlider;

defined( 'ABSPATH' ) || exit;

/**
 * Assets class
 */
class Assets {

	/**
	 * The instance of the class
	 *
	 * @var self
	 */
	private static $instance;

	/**
	 * Plugin name slug
	 *
	 * @var string
	 */
	private $plugin_name;

	/**
	 * The plugin version
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_action( 'wp_loaded', [ self::$instance, 'register' ] );
			add_action( 'admin_head', [ self::$instance, 'admin_localize_data' ], 9 );
		}

		return self::$instance;
	}

	/**
	 * Check if script debugging is enabled
	 *
	 * @return bool
	 */
	private function is_script_debug_enabled(): bool {
		return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
	}

	/**
	 * Checks to see if the site has SSL enabled or not.
	 *
	 * @return bool
	 */
	public static function is_ssl(): bool {
		if ( is_ssl() ) {
			return true;
		} elseif ( 0 === stripos( get_option( 'siteurl' ), 'https://' ) ) {
			return true;
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && 'https' === $_SERVER['HTTP_X_FORWARDED_PROTO'] ) {
			return true;
		}

		return false;
	}

	/**
	 * Get assets URL
	 *
	 * @param string $path Optional path.
	 *
	 * @return string
	 */
	public static function get_assets_url( string $path = '' ): string {
		$url = CAROUSEL_SLIDER_ASSETS;

		if ( static::is_ssl() && 0 === stripos( $url, 'http://' ) ) {
			$url = str_replace( 'http://', 'https://', $url );
		}

		if ( ! empty( $path ) ) {
			return rtrim( $url, '/' ) . '/' . ltrim( $path, '/' );
		}

		return $url;
	}

	/**
	 * Register our app scripts and styles
	 *
	 * @return void
	 */
	public function register() {
		$this->plugin_name = CAROUSEL_SLIDER;
		$this->version     = CAROUSEL_SLIDER_VERSION;

		if ( $this->is_script_debug_enabled() ) {
			$this->version = $this->version . '-' . time();
		}

		$this->register_scripts( $this->get_scripts() );
		$this->register_styles( $this->get_styles() );
	}

	/**
	 * Register scripts
	 *
	 * @param array $scripts The scripts to register.
	 *
	 * @return void
	 */
	private function register_scripts( array $scripts ) {
		foreach ( $scripts as $handle => $script ) {
			$deps      = $script['deps'] ?? false;
			$in_footer = $script['in_footer'] ?? true;
			$version   = $script['version'] ?? $this->version;
			wp_register_script( $handle, $script['src'], $deps, $version, $in_footer );
		}
	}

	/**
	 * Register styles
	 *
	 * @param array $styles The styles to register.
	 *
	 * @return void
	 */
	public function register_styles( array $styles ) {
		foreach ( $styles as $handle => $style ) {
			$deps = $style['deps'] ?? false;
			wp_register_style( $handle, $style['src'], $deps, $this->version );
		}
	}

	/**
	 * Get all registered scripts
	 *
	 * @return array
	 */
	public function get_scripts(): array {
		return [
			'carousel-slider-admin'              => [
				'src'  => static::get_assets_url( 'js/admin.js' ),
				'deps' => [
					'jquery',
					'wp-color-picker',
					'jquery-ui-accordion',
					'jquery-ui-tabs',
					'jquery-ui-sortable',
				],
			],
			'carousel-slider-admin-new-carousel' => [
				'src' => static::get_assets_url( 'js/admin-add-new-carousel.js' ),
			],
			'carousel-slider-frontend'           => [
				'src'  => static::get_assets_url( 'js/frontend.js' ),
				'deps' => [ 'jquery' ],
			],
			'carousel-slider-frontend-v2'        => [
				'src' => static::get_assets_url( 'js/frontend-v2.js' ),
			],
		];
	}

	/**
	 * Get registered styles
	 *
	 * @return array
	 */
	public function get_styles(): array {
		return [
			'carousel-slider-frontend'           => [
				'src' => static::get_assets_url( 'css/frontend.css' ),
			],
			'carousel-slider-frontend-v2'        => [
				'src' => static::get_assets_url( 'css/frontend-v2.css' ),
			],
			'carousel-slider-admin'              => [
				'src'  => static::get_assets_url( 'css/admin.css' ),
				'deps' => [ 'wp-color-picker' ],
			],
			'carousel-slider-admin-new-carousel' => [
				'src' => static::get_assets_url( 'css/admin-add-new-carousel.css' ),
			],
		];
	}

	/**
	 * Script to load css file via javaScript
	 *
	 * @return string
	 */
	public static function get_style_loader_script(): string {
		$data = self::get_assets_url( 'css/frontend.css' );
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$javascript = file_get_contents( self::get_assets_url( '/js/frontend-style-loader.js' ) );
		$script     = '<script id="carousel-slider-style-loader">' . PHP_EOL;
		$script    .= 'window.carouselSliderCssUrl = ' . wp_json_encode( $data ) . ';' . PHP_EOL;
		$script    .= $javascript . PHP_EOL;
		$script    .= '</script>' . PHP_EOL;

		return $script;
	}

	/**
	 * Global localize data both for admin and frontend
	 */
	public static function admin_localize_data() {
		$user              = wp_get_current_user();
		$is_user_logged_in = $user->exists();

		$data = [
			'homeUrl'  => home_url(),
			'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
			'restRoot' => esc_url_raw( rest_url( 'carousel-slider/v1' ) ),
		];

		if ( $is_user_logged_in ) {
			$data['restNonce'] = wp_create_nonce( 'wp_rest' );
		}

		if ( is_admin() ) {
			$slider_types = [];
			foreach ( Helper::get_slider_types() as $slug => $args ) {
				$slider_types[] = array_merge( [ 'slug' => $slug ], $args );
			}
			$data['sliderTypes'] = $slider_types;
		}

		echo '<script>window.CarouselSliderL10n = ' . wp_json_encode( $data ) . '</script>' . PHP_EOL;
	}
}
