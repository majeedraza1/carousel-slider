<?php

use CarouselSlider\Modules\HeroCarousel\Item;

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

if ( ! class_exists( 'Carousel_Slider_Admin' ) ) {

	class Carousel_Slider_Admin {

		/**
		 * @var Carousel_Slider_Form
		 */
		private $form;

		/**
		 * The instance of the class
		 *
		 * @var self
		 */
		protected static $instance = null;

		/**
		 * Ensures only one instance of this class is loaded or can be loaded.
		 *
		 * @return Carousel_Slider_Admin
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();

				add_action( 'init', [ self::$instance, 'carousel_post_type' ] );
				add_action( 'add_meta_boxes', [ self::$instance, 'add_meta_boxes' ] );
				add_filter( 'manage_edit-carousels_columns', [ self::$instance, 'columns_head' ] );
				add_filter( 'manage_carousels_posts_custom_column', [ self::$instance, 'columns_content' ], 10, 2 );
				add_action( 'save_post', [ self::$instance, 'save_meta_box' ] );

				// Remove view and Quick Edit from Carousels
				add_filter( 'post_row_actions', [ self::$instance, 'post_row_actions' ], 10, 2 );

				// Add custom link to media gallery
				add_filter( "attachment_fields_to_edit", [ self::$instance, "attachment_fields_to_edit" ], 10, 2 );
				add_filter( "attachment_fields_to_save", [ self::$instance, "attachment_fields_to_save" ], 10, 2 );
			}

			return self::$instance;
		}

		/**
		 * Carousel_Slider_Admin constructor.
		 */
		public function __construct() {

		}

		/**
		 * Carousel slider post type
		 */
		public function carousel_post_type() {
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
				'capability_type'     => 'post',
			];

			register_post_type( 'carousels', $args );
		}

		/**
		 * Hide view and quick edit from carousel slider admin
		 *
		 * @param array $actions
		 * @param WP_Post $post
		 *
		 * @return array
		 */
		public function post_row_actions( $actions, $post ) {
			if ( $post->post_type != 'carousels' ) {
				return $actions;
			}

			$view_url        = add_query_arg( array(
				'carousel_slider_preview' => true,
				'carousel_slider_iframe'  => true,
				'slider_id'               => $post->ID,
			), site_url( '/' ) );
			$actions['view'] = '<a href="' . $view_url . '" target="_blank">' . esc_html__( 'Preview', 'carousel-slider' ) . '</a>';

			unset( $actions['inline hide-if-no-js'] );

			return $actions;
		}

		/**
		 * Customize Carousel slider list table head
		 *
		 * @return array A list of column headers.
		 */
		public function columns_head() {

			$columns = array(
				'cb'         => '<input type="checkbox">',
				'title'      => __( 'Carousel Slide Title', 'carousel-slider' ),
				'usage'      => __( 'Shortcode', 'carousel-slider' ),
				'slide_type' => __( 'Slide Type', 'carousel-slider' )
			);

			return $columns;

		}

		/**
		 * Generate carousel slider list table content for each custom column
		 *
		 * @param string $column_name The name of the column to display.
		 * @param int $post_id The current post ID.
		 */
		public function columns_content( $column_name, $post_id ) {
			$slide_types = carousel_slider_slide_type( false );
			switch ( $column_name ) {

				case 'usage':
					?>
					<label class="screen-reader-text" for="carousel_slider_usage_<?php echo $post_id; ?>">Copy
						shortcode</label>
					<input
						id="carousel_slider_usage_<?php echo $post_id; ?>"
						type="text"
						onmousedown="this.clicked = 1;"
						onfocus="if (!this.clicked) this.select(); else this.clicked = 2;"
						onclick="if (this.clicked === 2) this.select(); this.clicked = 0;"
						value="[carousel_slide id='<?php echo $post_id; ?>']"
						style="background-color: #f1f1f1;min-width: 250px;padding: 5px 8px;"
					>
					<?php
					break;

				case 'slide_type':
					$slide_type = get_post_meta( $post_id, '_slide_type', true );
					echo isset( $slide_types[ $slide_type ] ) ? esc_attr( $slide_types[ $slide_type ] ) : '';

					break;
				default :
					break;
			}
		}

		/**
		 * Add carousel slider meta box
		 */
		public function add_meta_boxes() {
			add_meta_box(
				"carousel-slider-meta-boxes",
				__( "Carousel Slider", 'carousel-slider' ),
				array( $this, 'carousel_slider_meta_boxes' ),
				"carousels",
				"normal",
				"high"
			);
		}

		/**
		 * Load meta box content
		 *
		 * @param WP_Post $post
		 */
		public function carousel_slider_meta_boxes( WP_Post $post ) {
			wp_nonce_field( 'carousel_slider_nonce', '_carousel_slider_nonce' );

			$this->form = new Carousel_Slider_Form();

			$slide_type = get_post_meta( $post->ID, '_slide_type', true );
			$slide_type = in_array( $slide_type, carousel_slider_slide_type() ) ? $slide_type : 'image-carousel';

			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/types.php';
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/images-media.php';
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/images-url.php';
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/post-carousel.php';
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/product-carousel.php';
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/hero-banner-slider.php';
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/images-settings.php';

			/**
			 * Allow third part plugin to add custom fields
			 */
			do_action( 'carousel_slider/meta_box_content', $post->ID, $slide_type );
		}

		/**
		 * Save custom meta box
		 *
		 * @param int $post_id The post ID
		 */
		public function save_meta_box( $post_id ) {
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			// Check if nonce is set.
			if ( ! isset( $_POST['_carousel_slider_nonce'], $_POST['carousel_slider'] ) ) {
				return;
			}
			// Check if nonce is valid.
			if ( ! wp_verify_nonce( $_POST['_carousel_slider_nonce'], 'carousel_slider_nonce' ) ) {
				return;
			}
			// Check if user has permissions to save data.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			if ( isset( $_POST['carousel_slider_content'] ) ) {
				$this->update_content_slider( $post_id );
			}

			if ( isset( $_POST['content_settings'] ) ) {
				$this->update_content_settings( $post_id );
			}

			foreach ( $_POST['carousel_slider'] as $key => $val ) {
				if ( is_array( $val ) ) {
					$val = implode( ',', $val );
				}

				if ( $key == '_margin_right' && $val == 0 ) {
					$val = 'zero';
				}
				update_post_meta( $post_id, $key, sanitize_text_field( $val ) );
			}

			if ( ! isset( $_POST['carousel_slider']['_post_categories'] ) ) {
				update_post_meta( $post_id, '_post_categories', '' );
			}

			if ( ! isset( $_POST['carousel_slider']['_post_tags'] ) ) {
				update_post_meta( $post_id, '_post_tags', '' );
			}

			if ( ! isset( $_POST['carousel_slider']['_post_in'] ) ) {
				update_post_meta( $post_id, '_post_in', '' );
			}

			if ( isset( $_POST['_images_urls'] ) ) {
				$this->save_images_urls( $post_id );
			}

			do_action( 'carousel_slider/save_slider', $post_id );
		}

		/**
		 * Save images urls
		 *
		 * @param integer $post_id
		 *
		 * @return void
		 */
		private function save_images_urls( $post_id ) {
			if ( ! isset( $_POST['_images_urls'] ) ) {
				return;
			}
			$url      = $_POST['_images_urls']['url'];
			$title    = $_POST['_images_urls']['title'];
			$caption  = $_POST['_images_urls']['caption'];
			$alt      = $_POST['_images_urls']['alt'];
			$link_url = $_POST['_images_urls']['link_url'];

			$urls = array();

			for ( $i = 0; $i < count( $url ); $i ++ ) {
				$urls[] = array(
					'url'      => esc_url_raw( $url[ $i ] ),
					'title'    => sanitize_text_field( $title[ $i ] ),
					'caption'  => sanitize_text_field( $caption[ $i ] ),
					'alt'      => sanitize_text_field( $alt[ $i ] ),
					'link_url' => esc_url_raw( $link_url[ $i ] ),
				);
			}
			update_post_meta( $post_id, '_images_urls', $urls );
		}

		/**
		 * Adding our custom fields to the $form_fields array
		 *
		 * @param array $form_fields
		 * @param WP_Post $post
		 *
		 * @return array
		 */
		public function attachment_fields_to_edit( $form_fields, $post ) {
			$form_fields["carousel_slider_link_url"]["label"]      = __( "Link to URL", "carousel-slider" );
			$form_fields["carousel_slider_link_url"]["input"]      = "textarea";
			$form_fields["carousel_slider_link_url"]["value"]      = get_post_meta( $post->ID,
				"_carousel_slider_link_url", true );
			$form_fields["carousel_slider_link_url"]["extra_rows"] = array(
				'carouselSliderInfo' => __( '"Link to URL" only works on Carousel Slider for linking image to a custom url.',
					'carousel-slider' ),
			);

			return $form_fields;
		}

		/**
		 * Save custom field value
		 *
		 * @param array $post
		 * @param array $attachment
		 *
		 * @return object|array
		 */
		public function attachment_fields_to_save( $post, $attachment ) {
			$slider_link_url = isset( $attachment['carousel_slider_link_url'] ) ? $attachment['carousel_slider_link_url'] : null;

			if ( filter_var( $slider_link_url, FILTER_VALIDATE_URL ) ) {
				update_post_meta( $post['ID'], '_carousel_slider_link_url', esc_url_raw( $slider_link_url ) );
			} else {
				delete_post_meta( $post['ID'], '_carousel_slider_link_url' );
			}

			return $post;
		}

		/**
		 * Update content slider
		 *
		 * @param int $post_id
		 */
		private function update_content_slider( $post_id ) {
			$_content_slides = $_POST['carousel_slider_content'];
			$_slides         = array_map( function ( $slide ) {
				return Item::sanitize( $slide );
			}, $_content_slides );

			update_post_meta( $post_id, '_content_slider', $_slides );
		}

		/**
		 * Update hero carousel settings
		 *
		 * @param int $post_id post id
		 */
		private function update_content_settings( $post_id ) {
			$setting   = $_POST['content_settings'];
			$_settings = [
				'slide_height'      => sanitize_text_field( $setting['slide_height'] ),
				'content_width'     => sanitize_text_field( $setting['content_width'] ),
				'content_animation' => sanitize_text_field( $setting['content_animation'] ),
				'slide_padding'     => [
					'top'    => sanitize_text_field( $setting['slide_padding']['top'] ),
					'right'  => sanitize_text_field( $setting['slide_padding']['right'] ),
					'bottom' => sanitize_text_field( $setting['slide_padding']['bottom'] ),
					'left'   => sanitize_text_field( $setting['slide_padding']['left'] ),
				],
			];
			update_post_meta( $post_id, '_content_slider_settings', $_settings );
		}
	}
}

Carousel_Slider_Admin::init();
