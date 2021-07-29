<?php

namespace CarouselSlider;

use CarouselSlider\Admin\Admin;
use CarouselSlider\Admin\GutenbergBlock;
use CarouselSlider\Admin\Setting;
use CarouselSlider\Frontend\Frontend;
use CarouselSlider\Frontend\Preview;
use CarouselSlider\Frontend\Shortcode;
use CarouselSlider\Frontend\StructuredData;
use CarouselSlider\Integration\VisualComposerElement;
use CarouselSlider\Widget\CarouselSliderWidget;

defined( 'ABSPATH' ) || exit;

/**
 * The main plugin handler class is responsible for initializing plugin. The
 * class registers all the components required to run the plugin.
 */
class Plugin {

	/**
	 * The instance of the class
	 *
	 * @var self
	 */
	private static $instance;

	/**
	 * Holds various class instances
	 *
	 * @var array
	 */
	private $container = [];

	/**
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_action( 'plugins_loaded', [ self::$instance, 'includes' ] );
			add_action( 'carousel_slider/activation', [ self::$instance, 'activation_includes' ] );
			add_action( 'carousel_slider/deactivation', [ self::$instance, 'deactivation_includes' ] );

			add_action( 'in_plugin_update_message-carousel-slider/carousel-slider.php',
				[ self::$instance, 'in_plugin_update_message' ] );
		}

		return self::$instance;
	}

	/**
	 * Instantiate the required classes
	 *
	 * @return void
	 */
	public function includes() {
		$this->container['i18n']       = i18n::init();
		$this->container['assets']     = Assets::init();
		$this->container['widget']     = CarouselSliderWidget::init();
		$this->container['vc_element'] = VisualComposerElement::init();
		$this->container['shortcode']  = Shortcode::init();

		// Load classes for admin area
		if ( $this->is_request( 'admin' ) ) {
			$this->admin_includes();
		}

		// Load classes for frontend area
		if ( $this->is_request( 'frontend' ) ) {
			$this->frontend_includes();
		}

		// Load classes for ajax functionality
		if ( $this->is_request( 'ajax' ) ) {
			$this->ajax_includes();
		}

		$this->modules_includes();
	}

	/**
	 * Include modules main classes
	 *
	 * @return void
	 */
	public function modules_includes() {

	}

	/**
	 * Include admin classes
	 *
	 * @return void
	 */
	public function admin_includes() {
		$this->container['admin']           = Admin::init();
		$this->container['setting']         = Setting::init();
		$this->container['gutenberg_block'] = GutenbergBlock::init();
	}

	/**
	 * Include frontend classes
	 *
	 * @return void
	 */
	public function frontend_includes() {
		$this->container['frontend']        = Frontend::init();
		$this->container['preview']         = Preview::init();
		$this->container['structured_data'] = StructuredData::init();
	}

	/**
	 * Include frontend classes
	 *
	 * @return void
	 */
	public function ajax_includes() {
		$this->container['ajax'] = Ajax::init();
	}

	/**
	 * Run on plugin activation
	 *
	 * @return void
	 */
	public function activation_includes() {
		flush_rewrite_rules();
	}

	/**
	 * Run on plugin deactivation
	 *
	 * @return void
	 */
	public function deactivation_includes() {
		flush_rewrite_rules();
	}

	/**
	 * What type of request is this?
	 *
	 * @param string $type admin, ajax, rest, cron or frontend.
	 *
	 * @return bool
	 */
	private function is_request( string $type ): bool {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'rest' :
				return defined( 'REST_REQUEST' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}

		return false;
	}

	/**
	 * Show in plugin update message
	 *
	 * @param array $plugin_data
	 */
	public function in_plugin_update_message( array $plugin_data ) {
		$current_version       = CAROUSEL_SLIDER_VERSION;
		$current_version_array = explode( '.', $current_version );
		$new_version           = $plugin_data['new_version'];
		$new_version_array     = explode( '.', $new_version );

		$html = '';
		if ( version_compare( $current_version_array[0], $new_version_array[0], '<' ) ) {
			$html .= '</p><div class="cs_plugin_upgrade_notice extensions_warning major_update">';
			$html .= '<div class="cs_plugin_upgrade_notice__title">';
			$html .= sprintf( __( "<strong>%s</strong> version <strong>%s</strong> is a major update.", 'carousel-slider' ), $plugin_data['Title'], $new_version );
			$html .= '</div>';
			$html .= '<div class="cs_plugin_upgrade_notice__description">';
			$html .= __( 'We made a lot of major changes to this version.', 'carousel-slider' ) . ' ';
			$html .= __( 'We believe that all functionality will remain same after update (remember to refresh you cache plugin).', 'carousel-slider' ) . ' ';
			$html .= __( 'Still make sure that you took a backup so you can role back if anything happen wrong to you.', 'carousel-slider' );
			$html .= '</div>';
			$html .= '</div><p class="dummy" style="display: none">';
		}

		echo apply_filters( 'carousel_slider/in_plugin_update_message', $html, $plugin_data );
	}
}
