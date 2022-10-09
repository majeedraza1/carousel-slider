<?php

namespace CarouselSlider\Admin;

use CarouselSlider\Api;
use CarouselSlider\Helper;
use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * Admin class
 * The admin functionality specific class of the plugin
 *
 * @package CarouselSlider/Admin
 */
class Admin {

	const POST_TYPE = 'carousels';

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

			// Modify carousel slider list table columns.
			add_filter( 'manage_edit-' . self::POST_TYPE . '_columns', [ self::$instance, 'columns_head' ] );
			add_filter(
				'manage_' . self::POST_TYPE . '_posts_custom_column',
				[ self::$instance, 'columns_content' ],
				10,
				2
			);
			// Remove view and Quick Edit from Carousels.
			add_filter( 'post_row_actions', [ self::$instance, 'post_row_actions' ], 10, 2 );
			add_filter( 'preview_post_link', [ self::$instance, 'preview_post_link' ], 10, 2 );

			add_action( 'admin_enqueue_scripts', [ self::$instance, 'admin_scripts' ], 10 );
			add_action( 'admin_menu', [ self::$instance, 'documentation_menu' ] );
			add_filter( 'admin_footer_text', [ self::$instance, 'admin_footer_text' ] );
			add_action( 'admin_menu', [ self::$instance, 'go_pro_menu' ], 999 );
			add_filter(
				'plugin_action_links_' . plugin_basename( CAROUSEL_SLIDER_FILE ),
				[ self::$instance, 'action_links' ]
			);

			add_filter( 'plugin_row_meta', [ self::$instance, 'plugin_row_meta' ], 10, 2 );
		}

		return self::$instance;
	}

	/**
	 * Add custom links on plugins page.
	 *
	 * @param array $links An array of plugin action links.
	 *
	 * @return array
	 */
	public function action_links( $links ) {
		$setting_url  = admin_url( 'edit.php?post_type=carousels&page=settings' );
		$plugin_links = [
			'<a href="' . $setting_url . '">' . __( 'Settings', 'carousel-slider' ) . '</a>',
		];

		$pro_links = [];
		if ( ! Helper::is_pro_active() ) {
			if ( Helper::show_pro_features() ) {
				$pro_links = [
					'<a href="' . Api::GO_PRO_URL . '" target="_blank" class="carousel-slider-plugins-gopro">' . __( 'Go Pro', 'carousel-slider' ) . '</a>',
				];
			}
		}

		return array_merge( $plugin_links, $links, $pro_links );
	}

	/**
	 * Filters the array of row meta for the plugin in the Plugins list table.
	 *
	 * @param string[] $plugin_meta An array of the plugin's metadata, including
	 *                              the version, author, author URI, and plugin URI.
	 * @param string   $plugin_file Path to the plugin file relative to the plugins directory.
	 *
	 * @return array
	 */
	public function plugin_row_meta( $plugin_meta, $plugin_file ) {
		if ( plugin_basename( CAROUSEL_SLIDER_FILE ) === $plugin_file ) {
			$plugin_meta[] = '<a href="' . Api::FREE_SUPPORT_URL . '" target="_blank">' . __( 'Community support', 'carousel-slider' ) . '</a>';
			$plugin_meta[] = '<a href="' . Api::PRO_SUPPORT_URL . '" target="_blank">' . __( 'Pro Support', 'carousel-slider' ) . '</a>';
		}

		return $plugin_meta;
	}

	/**
	 * Modify preview post link for carousel slider
	 *
	 * @param string  $preview_link The preview link.
	 * @param WP_Post $post The WP_Post object.
	 *
	 * @return string
	 */
	public function preview_post_link( string $preview_link, WP_Post $post ): string {
		if ( self::POST_TYPE === $post->post_type ) {
			$preview_link = Helper::get_preview_link( $post );
		}

		return $preview_link;
	}

	/**
	 * Customize Carousel slider list table head
	 *
	 * @return array A list of column headers.
	 */
	public function columns_head(): array {
		return [
			'cb'         => '<input type="checkbox">',
			'title'      => __( 'Carousel Slide Title', 'carousel-slider' ),
			'usage'      => __( 'Shortcode', 'carousel-slider' ),
			'slide_type' => __( 'Slide Type', 'carousel-slider' ),
		];
	}

	/**
	 * Generate carousel slider list table content for each custom column
	 *
	 * @param string $column_name The name of the column to display.
	 * @param int    $post_id The current post ID.
	 *
	 * @return void
	 */
	public function columns_content( string $column_name, int $post_id ) {
		$slide_types = Helper::get_slide_types();
		switch ( $column_name ) {

			case 'usage':
				?>
				<label class="screen-reader-text" for="carousel_slider_usage_<?php echo esc_attr( $post_id ); ?>">
					Copy shortcode
				</label>
				<input
					id="carousel_slider_usage_<?php echo esc_attr( $post_id ); ?>"
					type="text"
					onmousedown="this.clicked = 1;"
					onfocus="if (!this.clicked) this.select(); else this.clicked = 2;"
					onclick="if (this.clicked === 2) this.select(); this.clicked = 0;"
					value="[carousel_slide id='<?php echo esc_attr( $post_id ); ?>']"
					style="background-color: #f1f1f1;min-width: 250px;padding: 5px 8px;"
				>
				<?php
				break;

			case 'slide_type':
				$slide_type = get_post_meta( $post_id, '_slide_type', true );
				echo isset( $slide_types[ $slide_type ] ) ? esc_attr( $slide_types[ $slide_type ] ) : '';

				break;
			default:
				break;
		}
	}

	/**
	 * Hide view and quick edit from carousel slider admin
	 *
	 * @param array   $actions The post row actions list.
	 * @param WP_Post $post The WP_Post object.
	 *
	 * @return array
	 */
	public function post_row_actions( array $actions, WP_Post $post ): array {
		if ( self::POST_TYPE !== $post->post_type ) {
			return $actions;
		}

		$view_url        = Helper::get_preview_link( $post );
		$actions['view'] = '<a href="' . $view_url . '" target="_blank">' . esc_html__( 'Preview', 'carousel-slider' ) . '</a>';

		unset( $actions['inline hide-if-no-js'] );

		return $actions;
	}

	/**
	 * Load admin scripts
	 *
	 * @param string|mixed $hook Page hook.
	 */
	public function admin_scripts( $hook ) {
		global $post;

		$_is_carousel    = is_a( $post, 'WP_Post' ) && ( 'carousels' === $post->post_type );
		$_is_doc         = ( 'carousels_page_carousel-slider-documentation' === $hook );
		$_is_settings    = ( 'carousels_page_settings' === $hook );
		$_is_plugin_page = 'plugins.php' === $hook;

		if ( ! ( $_is_carousel || $_is_doc || $_is_plugin_page || $_is_settings ) ) {
			// Load add new carousel script and style on every page of admin.
			wp_enqueue_script( 'carousel-slider-admin-new-carousel' );
			wp_enqueue_style( 'carousel-slider-admin-new-carousel' );

			return;
		}

		wp_enqueue_media();
		wp_enqueue_style( 'carousel-slider-admin' );
		wp_enqueue_script( 'carousel-slider-admin' );
		wp_localize_script(
			'carousel-slider-admin',
			'CarouselSliderAdminL10n',
			[
				'url'           => esc_html__( 'URL', 'carousel-slider' ),
				'title'         => esc_html__( 'Title', 'carousel-slider' ),
				'caption'       => esc_html__( 'Caption', 'carousel-slider' ),
				'altText'       => esc_html__( 'Alt Text', 'carousel-slider' ),
				'linkToUrl'     => esc_html__( 'Link To URL', 'carousel-slider' ),
				'addNew'        => esc_html__( 'Add New Item', 'carousel-slider' ),
				'moveCurrent'   => esc_html__( 'Move Current Item', 'carousel-slider' ),
				'deleteCurrent' => esc_html__( 'Delete Current Item', 'carousel-slider' ),
			]
		);
	}

	/**
	 * Add documentation menu
	 */
	public function documentation_menu() {
		add_submenu_page(
			'edit.php?post_type=carousels',
			__( 'Documentation', 'carousel-slider' ),
			__( 'Documentation', 'carousel-slider' ),
			'manage_options',
			'carousel-slider-documentation',
			[ $this, 'documentation_page_callback' ]
		);
	}

	/**
	 * Documentation page callback
	 */
	public function documentation_page_callback() {
		$items = [
			[
				'youtube_id' => '_hVsamgr1k4',
				'title'      => __( 'Hero Image Carousel', 'carousel-slider' ),
			],
			[
				'youtube_id' => 'UOYK79yVrJ4',
				'title'      => __( 'Image carousel (gallery images)', 'carousel-slider' ),
			],
			[
				'youtube_id' => 'a7hqn1yNzwM',
				'title'      => __( 'Image carousel (custom URLs)', 'carousel-slider' ),
			],
			[
				'youtube_id' => 'ImJB946azy0',
				'title'      => __( 'Posts Carousel', 'carousel-slider' ),
			],
			[
				'youtube_id' => 'yiAkvXyfakg',
				'title'      => __( 'WooCommerce Product Carousel', 'carousel-slider' ),
			],
			[
				'youtube_id' => 'kYgp6wp27lM',
				'title'      => __( 'In Widget Areas', 'carousel-slider' ),
			],
			[
				'youtube_id' => '-OaYQZfr1RM',
				'title'      => __( 'With Page Builder by SiteOrigin', 'carousel-slider' ),
			],
			[
				'youtube_id' => '4LhDXH81whk',
				'title'      => __( 'With Visual Composer Website Builder', 'carousel-slider' ),
			],
		];
		$html  = '<div class="wrap">';
		$html .= '<h1 class="wp-heading">' . esc_html__( 'Carousel Slider Documentation', 'carousel-slider' ) . '</h1>';
		$html .= '<div class="clear"></div>';
		$html .= '<div class="postbox"><div class="inside">';
		$html .= '<div class="carousel_slider_columns">';
		foreach ( $items as $item ) {
			$html .= '<div class="carousel_slider_column">';
			$html .= '<div class="carousel_slider_iframe">';
			$html .= sprintf(
				'<iframe width="1280" height="720" src="https://www.youtube.com/embed/%s" allowfullscreen></iframe>',
				$item['youtube_id']
			);
			$html .= '</div>';
			if ( ! empty( $item['title'] ) ) {
				$html .= '<label>' . esc_html( $item['title'] ) . '</label>';
			}
			if ( ! empty( $item['description'] ) ) {
				$html .= '<p class="description">' . esc_html( $item['description'] ) . '</p>';
			}
			$html .= '</div>';
		}
		$html .= '</div>';
		$html .= '</div></div>';
		$html .= '</div>';
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Add custom footer text on plugins page.
	 *
	 * @param string|null $text The custom admin footer text.
	 *
	 * @return string|null Admin footer text
	 */
	public function admin_footer_text( $text ) {
		global $post_type, $hook_suffix;

		$footer_text = sprintf(
		/* translators: 1: plugin review page link */
			__( 'If you like <strong>Carousel Slider</strong> please leave us a %s rating. A huge thanks in advance!', 'carousel-slider' ),
			'<a href="https://wordpress.org/support/view/plugin-reviews/carousel-slider?filter=5#postform" target="_blank" data-rated="Thanks :)">&starf;&starf;&starf;&starf;&starf;</a>'
		);

		if ( 'carousels' === $post_type || 'carousels_page_carousel-slider-documentation' === $hook_suffix ) {
			return $footer_text;
		}

		return $text;
	}

	/**
	 * Go pro admin menu link
	 *
	 * @return void
	 */
	public function go_pro_menu() {
		if ( Helper::is_pro_active() ) {
			return;
		}
		if ( ! Helper::show_pro_features() ) {
			return;
		}
		add_submenu_page(
			'edit.php?post_type=carousels',
			'',
			'<span class="dashicons dashicons-star-filled" style="font-size: 17px"></span> ' . esc_html__( 'Go Pro', 'carousel-slider' ),
			'manage_options',
			'go_carousel_slider_pro',
			[ $this, 'handle_external_redirects' ]
		);
	}

	/**
	 * Go Elementor Pro.
	 *
	 * Redirect the Elementor Pro page the clicking the Elementor Pro menu link.
	 *
	 * Fired by `admin_init` action.
	 *
	 * @since 2.0.3
	 * @access public
	 */
	public function handle_external_redirects() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( empty( $_GET['page'] ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( 'go_carousel_slider_pro' === $_GET['page'] ) {
			wp_redirect( Api::GO_PRO_URL ); // phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
			die;
		}
	}
}
