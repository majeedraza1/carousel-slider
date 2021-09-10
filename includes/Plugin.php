<?php

namespace CarouselSlider;

use CarouselSlider\Admin\Admin;
use CarouselSlider\Admin\GutenbergBlock;
use CarouselSlider\Admin\MetaBox;
use CarouselSlider\Admin\Setting;
use CarouselSlider\CLI\Command;
use CarouselSlider\Frontend\Frontend;
use CarouselSlider\Frontend\Preview;
use CarouselSlider\Frontend\StructuredData;
use CarouselSlider\Integration\VisualComposerElement;
use CarouselSlider\Modules\HeroCarousel\Module as HeroCarouselModule;
use CarouselSlider\Modules\ImageCarousel\Module as ImageCarouselModule;
use CarouselSlider\Modules\PostCarousel\Module as PostCarouselModule;
use CarouselSlider\Modules\ProductCarousel\Module as ProductCarouselModule;
use CarouselSlider\Modules\VideoCarousel\Module as VideoCarouselModule;
use CarouselSlider\Widget\CarouselSliderWidget;
use WP_CLI;
use WP_CLI_Command;

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
		$this->container['upgrader']   = Upgrader::init();

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

		// WP-CLI Commands
		if ( class_exists( WP_CLI::class ) && class_exists( WP_CLI_Command::class ) ) {
			WP_CLI::add_command( 'carousel-slider', Command::class );
		}

		$this->modules_includes();
	}

	/**
	 * Include modules main classes
	 *
	 * @return void
	 */
	public function modules_includes() {
		$this->container['module_image_carousel'] = ImageCarouselModule::init();
		$this->container['module_video_carousel'] = VideoCarouselModule::init();
		$this->container['module_post_carousel']  = PostCarouselModule::init();
		$this->container['module_hero_carousel']  = HeroCarouselModule::init();
		if ( Helper::is_woocommerce_active() ) {
			$this->container['module_product_carousel'] = ProductCarouselModule::init();
		}
	}

	/**
	 * Include admin classes
	 *
	 * @return void
	 */
	public function admin_includes() {
		$this->container['admin']           = Admin::init();
		$this->container['meta_box']        = MetaBox::init();
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
		return Helper::is_request( $type );
	}
}
