<?php

namespace CarouselSlider;

use CarouselSlider\Admin\Admin;
use CarouselSlider\Admin\Feedback;
use CarouselSlider\Admin\GutenbergBlock;
use CarouselSlider\Admin\MetaBox;
use CarouselSlider\Admin\Setting;
use CarouselSlider\Admin\Upgrader;
use CarouselSlider\CLI\Command;
use CarouselSlider\Frontend\Frontend;
use CarouselSlider\Frontend\Preview;
use CarouselSlider\Frontend\StructuredData;
use CarouselSlider\Integration\DiviBuilder\DiviBuilderModule;
use CarouselSlider\Integration\Elementor\ElementorExtension;
use CarouselSlider\Integration\VisualComposer\Element;
use CarouselSlider\Modules\HeroCarousel\Module as HeroCarouselModule;
use CarouselSlider\Modules\ImageCarousel\Module as ImageCarouselModule;
use CarouselSlider\Modules\PostCarousel\Module as PostCarouselModule;
use CarouselSlider\Modules\ProductCarousel\Module as ProductCarouselModule;
use CarouselSlider\Modules\VideoCarousel\Module as VideoCarouselModule;
use CarouselSlider\REST\CarouselController;
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
	 * Get class from container
	 *
	 * @param string $key The key used for class name.
	 *
	 * @return false|mixed
	 */
	public function get( string $key ) {
		return $this->container[ $key ] ?? false;
	}

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
	 * Load the plugin text domain for translation.
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			CAROUSEL_SLIDER,
			false,
			basename( CAROUSEL_SLIDER_PATH ) . '/languages'
		);
	}

	/**
	 * Instantiate the required classes
	 *
	 * @return void
	 */
	public function includes() {
		// Register custom post type.
		add_action( 'init', [ $this, 'load_plugin_textdomain' ] );
		// Register custom post type.
		add_action( 'init', [ $this, 'register_post_type' ] );

		$this->container['assets']   = Assets::init();
		$this->container['feedback'] = Feedback::init();
		$this->container['widget']   = CarouselSliderWidget::init();

		// Load classes for admin area.
		if ( $this->is_request( 'admin' ) ) {
			$this->admin_includes();
		}

		// Load classes for frontend area.
		if ( $this->is_request( 'frontend' ) ) {
			$this->frontend_includes();
		}

		// Load classes for ajax functionality.
		if ( $this->is_request( 'ajax' ) ) {
			$this->ajax_includes();
		}

		// WP-CLI Commands.
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

		if ( Helper::is_wp_bakery_page_builder_active() ) {
			$this->container['vc_element'] = Element::init();
		}

		if ( Helper::is_divi_builder_active() ) {
			$this->container['divi_module'] = DiviBuilderModule::init();
		}

		if ( Helper::is_elementor_active() ) {
			$this->container['elementor_extension'] = ElementorExtension::init();
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
		$this->container['upgrader']        = Upgrader::init();
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

		add_action( 'rest_api_init', [ new CarouselController(), 'register_routes' ] );
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
	 * Carousel slider post type
	 */
	public function register_post_type() {
		$labels = [
			'name'               => _x( 'Sliders', 'Post Type General Name', 'carousel-slider' ),
			'singular_name'      => _x( 'Slider', 'Post Type Singular Name', 'carousel-slider' ),
			'menu_name'          => __( 'Carousel Slider', 'carousel-slider' ),
			'parent_item_colon'  => __( 'Parent Slider:', 'carousel-slider' ),
			'all_items'          => __( 'All Sliders', 'carousel-slider' ),
			'view_item'          => __( 'View Slider', 'carousel-slider' ),
			'add_new_item'       => __( 'Add New Slider', 'carousel-slider' ),
			'add_new'            => __( 'Add New', 'carousel-slider' ),
			'edit_item'          => __( 'Edit Slider', 'carousel-slider' ),
			'update_item'        => __( 'Update Slider', 'carousel-slider' ),
			'search_items'       => __( 'Search Slider', 'carousel-slider' ),
			'not_found'          => __( 'Not found', 'carousel-slider' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'carousel-slider' ),
		];
		$args   = [
			'label'               => __( 'Slider', 'carousel-slider' ),
			'description'         => __( 'The easiest way to create carousel slider', 'carousel-slider' ),
			'labels'              => $labels,
			'supports'            => [ 'title' ],
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => false,
			'menu_position'       => 5.55525,
			'menu_icon'           => 'dashicons-slides',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'rewrite'             => false,
			'capability_type'     => 'page',
		];

		register_post_type( CAROUSEL_SLIDER_POST_TYPE, $args );
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
