<?php

namespace CarouselSlider;

defined( 'ABSPATH' ) || exit;

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
	 * plugin version
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
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && 'https' == $_SERVER['HTTP_X_FORWARDED_PROTO'] ) {
			return true;
		}

		return false;
	}

	/**
	 * Get assets URL
	 *
	 * @param string $path
	 *
	 * @return string
	 */
	public static function get_assets_url( $path = '' ): string {
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
	 * @param array $scripts
	 *
	 * @return void
	 */
	private function register_scripts( array $scripts ) {
		foreach ( $scripts as $handle => $script ) {
			$deps      = isset( $script['deps'] ) ? $script['deps'] : false;
			$in_footer = isset( $script['in_footer'] ) ? $script['in_footer'] : true;
			$version   = isset( $script['version'] ) ? $script['version'] : $this->version;
			wp_register_script( $handle, $script['src'], $deps, $version, $in_footer );
		}
	}

	/**
	 * Register styles
	 *
	 * @param array $styles
	 *
	 * @return void
	 */
	public function register_styles( array $styles ) {
		foreach ( $styles as $handle => $style ) {
			$deps = isset( $style['deps'] ) ? $style['deps'] : false;
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
			'select2'                  => [
				'src'     => static::get_assets_url( 'lib/select2/select2.min.js' ),
				'deps'    => [ 'jquery' ],
				'version' => '4.0.5',
			],
			'wp-color-picker-alpha'    => [
				'src'     => static::get_assets_url( 'lib/wp-color-picker-alpha/wp-color-picker-alpha.min.js' ),
				'deps'    => [ 'jquery', 'wp-color-picker' ],
				'version' => '2.1.3',
			],
			"carousel-slider-admin"    => [
				'src'  => static::get_assets_url( 'js/admin.js' ),
				'deps' => [
					'jquery',
					'select2',
					'wp-color-picker-alpha',
					'jquery-ui-accordion',
					'jquery-ui-datepicker',
					'jquery-ui-sortable',
					'jquery-ui-tabs',
				],
			],
			'owl-carousel'             => [
				'src'     => static::get_assets_url( 'lib/owl-carousel/owl.carousel.min.js' ),
				'deps'    => [ 'jquery' ],
				'version' => '2.3.4',
			],
			'magnific-popup'           => [
				'src'     => static::get_assets_url( 'lib/magnific-popup/jquery.magnific-popup.min.js' ),
				'deps'    => [ 'jquery' ],
				'version' => '1.1.0',
			],
			"carousel-slider-frontend" => [
				'src'  => static::get_assets_url( 'js/frontend.js' ),
				'deps' => [ 'jquery', 'owl-carousel', 'magnific-popup' ],
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
			"carousel-slider-frontend" => [
				'src' => static::get_assets_url( 'css/frontend.css' )
			],
			"carousel-slider-admin"    => [
				'src'  => static::get_assets_url( 'css/admin.css' ),
				'deps' => [ 'wp-color-picker' ],
			],
		];
	}
}
