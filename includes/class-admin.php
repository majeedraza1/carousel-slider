<?php

namespace CarouselSlider;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


class Admin {

	private $post_type = 'carousels';
	protected static $instance = null;

	/**
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @return Admin
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Carousel_Slider_Admin constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'carousel_post_type' ) );
		add_filter( "manage_edit-{$this->post_type}_columns", array( $this, 'columns_head' ) );
		add_filter( "manage_{$this->post_type}_posts_custom_column", array( $this, 'columns_content' ), 10, 2 );
		add_action( 'save_post', array( $this, 'save_meta_box' ), 10, 3 );

		// Remove view and Quick Edit from Carousels
		add_filter( 'post_row_actions', array( $this, 'post_row_actions' ), 10, 2 );

		// Add custom link to media gallery
		add_filter( "attachment_fields_to_edit", array( $this, "attachment_fields_to_edit" ), null, 2 );
		add_filter( "attachment_fields_to_save", array( $this, "attachment_fields_to_save" ), null, 2 );
	}

	/**
	 * Carousel slider post type
	 */
	public function carousel_post_type() {
		$labels = array(
			'name'               => _x( 'Slides', 'Post Type General Name', 'carousel-slider' ),
			'singular_name'      => _x( 'Slide', 'Post Type Singular Name', 'carousel-slider' ),
			'menu_name'          => __( 'Carousel Slider', 'carousel-slider' ),
			'parent_item_colon'  => __( 'Parent Slide:', 'carousel-slider' ),
			'all_items'          => __( 'All Slides', 'carousel-slider' ),
			'view_item'          => __( 'View Slide', 'carousel-slider' ),
			'add_new_item'       => __( 'Add New Slide', 'carousel-slider' ),
			'add_new'            => __( 'Add New', 'carousel-slider' ),
			'edit_item'          => __( 'Edit Slide', 'carousel-slider' ),
			'update_item'        => __( 'Update Slide', 'carousel-slider' ),
			'search_items'       => __( 'Search Slide', 'carousel-slider' ),
			'not_found'          => __( 'Not found', 'carousel-slider' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'carousel-slider' ),
		);
		$args   = array(
			'label'               => __( 'Slide', 'carousel-slider' ),
			'description'         => __( 'The easiest way to create carousel slide', 'carousel-slider' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5.55525,
			'menu_icon'           => 'dashicons-slides',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'rewrite'             => false,
			'capability_type'     => 'post',
		);

		register_post_type( $this->post_type, $args );
	}

	/**
	 * Hide view and quick edit from carousel slider admin
	 *
	 * @param array $actions
	 * @param \WP_Post $post
	 *
	 * @return mixed
	 */
	public function post_row_actions( $actions, $post ) {
		if ( $post->post_type !== $this->post_type ) {
			return $actions;
		}

		unset( $actions['view'] );
		unset( $actions['inline hide-if-no-js'] );

		return $actions;
	}

	/**
	 * Customize Carousel slider list table head
	 *
	 * @return array
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
	 * Generate carousel slider list table content
	 *
	 * @param $column
	 * @param $post_id
	 */
	public function columns_content( $column, $post_id ) {
		switch ( $column ) {

			case 'usage':
				?>
                <input
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
				$slide_type = get_post_meta( get_the_ID(), '_slide_type', true );
				echo ucwords( str_replace( '-', ' ', $slide_type ) );

				break;
			default :
				break;
		}
	}

	/**
	 * Save custom meta box
	 *
	 * @param int $post_id Post ID.
	 * @param WP_Post $post Post object.
	 * @param bool $update Whether this is an existing post being updated or not.
	 */
	public function save_meta_box( $post_id, $post, $update ) {
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

		do_action( 'carousel_slider_save_meta_box', $post_id, $post, $update );

		foreach ( $_POST['carousel_slider'] as $key => $val ) {
			if ( is_array( $val ) ) {
				$val = implode( ',', $val );
			}

			if ( $key == '_margin_right' && $val == 0 ) {
				$val = 'zero';
			}
			update_post_meta( $post_id, $key, sanitize_text_field( $val ) );
		}
	}

	/**
	 * Adding our custom fields to the $form_fields array
	 *
	 * @param array $form_fields
	 * @param \WP_Post $post
	 *
	 * @return array
	 */
	public function attachment_fields_to_edit( $form_fields, $post ) {
		$form_fields["carousel_slider_link_url"]["label"]      = __( "Link to URL", "carousel-slider" );
		$form_fields["carousel_slider_link_url"]["input"]      = "textarea";
		$form_fields["carousel_slider_link_url"]["value"]      = get_post_meta( $post->ID, "_carousel_slider_link_url", true );
		$form_fields["carousel_slider_link_url"]["extra_rows"] = array(
			'carouselSliderInfo' => __( '"Link to URL" only works on Carousel Slider for linking image to a custom url.', 'carousel-slider' ),
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
		}

		return $post;
	}
}


Admin::init();
