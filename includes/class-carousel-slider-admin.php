<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if( ! class_exists('Carousel_Slider_Admin') ):

class Carousel_Slider_Admin
{
	private $plugin_path;
	private $plugin_url;
	private $form;

	/**
	 * Carousel_Slider_Admin constructor.
	 *
	 * @param $plugin_path
	 * @param $plugin_url
	 */
	public function __construct( $plugin_path, $plugin_url )
	{
		$this->plugin_path = $plugin_path;
		$this->plugin_url = $plugin_url;
		$this->form = new Carousel_Slider_Form();
		
		add_action('init', array( $this, 'carousel_post_type' ) );
		add_action('add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action('add_meta_boxes', array( $this, 'shortcode_usage_info' ) );
		add_filter( 'manage_edit-carousels_columns', array( $this, 'columns_head') );
		add_filter( 'manage_carousels_posts_custom_column', array( $this, 'columns_content'), 10, 2 );
		add_action( 'save_post', array( $this, 'save_meta_box' ) );
        add_action( 'wp_ajax_carousel_slider_save_images', array( $this, 'save_images' ) );

        // Remove view and Quick Edit from Carousels
        add_filter( 'post_row_actions', array( $this, 'post_row_actions'), 10, 2 );

        // Add custom link to media gallery
        add_filter("attachment_fields_to_edit", array( $this, "attachment_fields_to_edit" ), null, 2);
        add_filter("attachment_fields_to_save", array( $this, "attachment_fields_to_save" ), null, 2);
	}

	/**
	 * Carousel slider post type
	 */
	public function carousel_post_type() {
		$labels = array(
			'name'                => _x( 'Slides', 'Post Type General Name', 'carousel-slider' ),
			'singular_name'       => _x( 'Slide', 'Post Type Singular Name', 'carousel-slider' ),
			'menu_name'           => __( 'Carousel Slider', 'carousel-slider' ),
			'parent_item_colon'   => __( 'Parent Slide:', 'carousel-slider' ),
			'all_items'           => __( 'All Slides', 'carousel-slider' ),
			'view_item'           => __( 'View Slide', 'carousel-slider' ),
			'add_new_item'        => __( 'Add New Slide', 'carousel-slider' ),
			'add_new'             => __( 'Add New', 'carousel-slider' ),
			'edit_item'           => __( 'Edit Slide', 'carousel-slider' ),
			'update_item'         => __( 'Update Slide', 'carousel-slider' ),
			'search_items'        => __( 'Search Slide', 'carousel-slider' ),
			'not_found'           => __( 'Not found', 'carousel-slider' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'carousel-slider' ),
		);
		$args = array(
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
		register_post_type( 'carousels', $args );
	}

	/**
	 * Hide view and quick edit from carousel slider admin
	 *
	 * @param $actions
	 * @param $post
	 *
	 * @return mixed
	 */
	public function post_row_actions( $actions, $post )
	{
		global $current_screen;
	    if( $current_screen->post_type != 'carousels' ){
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
	public function columns_head(){
	    
	    $columns = array(
	        'cb' 			=> '<input type="checkbox">',
	        'title' 		=> __('Carousel Slide Title', 'carousel-slider'),
	        'usage' 		=> __('Shortcode', 'carousel-slider'),
	        'slide_type' 	=> __('Slide Type', 'carousel-slider')
	    );

	    return $columns;

	}

	/**
	 * Generate carousel slider list table content
	 *
	 * @param $column
	 * @param $post_id
	 */
	public function columns_content($column, $post_id) {
	    switch ($column) {

	        case 'usage':
	            ?>
					<input
						type="text"
						onmousedown="this.clicked = 1;"
						onfocus="if (!this.clicked) this.select(); else this.clicked = 2;"
						onclick="if (this.clicked == 2) this.select(); this.clicked = 0;"
						value="[carousel_slide id='<?php echo $post_id; ?>']"
						style="background-color: #f1f1f1;font-family: monospace;min-width: 250px;padding: 5px 8px;"
					>
	            <?php

	            break;

	        case 'slide_type':
	        	$slide_type = get_post_meta( get_the_ID(), '_slide_type', true);
				echo ucwords(str_replace('-', ' ', $slide_type));

	        	break;
	        default :
	            break;
	    }
	}

	/**
	 * Add carousel slider meta box
	 */
	public function add_meta_boxes()
	{
	    add_meta_box( 
	    	"carousel-slider-meta-boxes",
	    	__("Carousel Slider", 'carousel-slider'), 
	    	array( $this, 'carousel_slider_meta_boxes' ),
	    	"carousels",
	    	"normal",
	    	"high"
	    );
	}

	/**
	 * Load metabox content
	 */
	public function carousel_slider_meta_boxes()
	{
		wp_nonce_field( 'carousel_slider_nonce', '_carousel_slider_nonce' );
		
		global $post;
		$slide_type = get_post_meta( $post->ID, '_slide_type', true );
		$slide_type = in_array($slide_type, array('image-carousel', 'post-carousel', 'image-carousel-url', 'video-carousel', 'product-carousel')) ? $slide_type : 'image-carousel';

		require_once $this->plugin_path . '/templates/admin/types.php';
		require_once $this->plugin_path . '/templates/admin/images-media.php';
		require_once $this->plugin_path . '/templates/admin/images-url.php';
		require_once $this->plugin_path . '/templates/admin/post-carousel.php';
		require_once $this->plugin_path . '/templates/admin/product-carousel.php';
		require_once $this->plugin_path . '/templates/admin/video-carousel.php';
		require_once $this->plugin_path . '/templates/admin/images-settings.php';
		require_once $this->plugin_path . '/templates/admin/general.php';
		require_once $this->plugin_path . '/templates/admin/navigation.php';
		require_once $this->plugin_path . '/templates/admin/autoplay.php';
		require_once $this->plugin_path . '/templates/admin/responsive.php';
	}

	/**
	 * Metabox for shortcode information
	 */
	public function shortcode_usage_info()
	{
	    add_meta_box( 
	    	"carousel-slider-shortcode-info", 
	    	__("Usage (Shortcode)", 'carousel-slider'), 
	    	array( $this, 'render_meta_box_shortcode_info' ),
	    	"carousels",
	    	"side",
	    	"low"
	    );
	}

	/**
	 * Render shortcode metabox content
	 */
	public function render_meta_box_shortcode_info()
	{
        ob_start(); ?>
        <p><strong>
        		<?php _e('Copy the following shortcode and paste in post or page where you want to show.', 'carousel-slider'); ?>
    	</strong></p>
		<input
			type="text"
			onmousedown="this.clicked = 1;"
			onfocus="if (!this.clicked) this.select(); else this.clicked = 2;"
			onclick="if (this.clicked == 2) this.select(); this.clicked = 0;"
			value="[carousel_slide id='<?php echo get_the_ID(); ?>']"
			style="background-color: #f1f1f1; width: 100%; padding: 8px;"
		>
    	<hr>
    	<p>
    		<?php _e('If you like this plugin or if you make money using this or if you want to help me to continue my contribution on open source projects, consider to make a small donation.', 'carousel-slider'); ?>
    	</p>
    	<p style="text-align: center;">
	        <a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3LZWQTHEVYWCY">
	        	<img src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" alt="PayPal Donate">
	        </a>
        </p>
        <?php echo ob_get_clean();
	}

    /**
     * Save custom meta box
     * 
     * @method save_meta_box
     * @param  int $post_id The post ID
     */
	public function save_meta_box( $post_id )
	{
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
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

        foreach( $_POST['carousel_slider'] as $key => $val ){
        	if (is_array($val)) {
        		$val = implode(',', $val);
        	}

            if ($key == '_margin_right' && $val == 0) {
                $val = 'zero';
            }
            update_post_meta( $post_id, $key, sanitize_text_field( $val ) );
        }

        if ( ! isset($_POST['carousel_slider']['_post_categories'])) {
        	update_post_meta( $post_id, '_post_categories',  '');
        }

        if ( ! isset($_POST['carousel_slider']['_post_tags'])) {
        	update_post_meta( $post_id, '_post_tags',  '');
        }

        if ( ! isset($_POST['carousel_slider']['_post_in'])) {
        	update_post_meta( $post_id, '_post_in',  '');
        }

        if (isset($_POST['_images_urls'])) {
        	$this->save_images_urls( $post_id );
        }
	}

	/**
	 * Save carousel slider gallery images
	 * 
	 * @return string
	 */
	public function save_images()
	{
    	// Check if not an autosave.
        if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
            return;
        }
        // Check if required fields are set
        if ( ! isset( $_POST['ids'], $_POST['post_id'], $_POST['nonce'] ) ) {
            return;
        }
		// Check if nonce is valid.
        if ( ! wp_verify_nonce( $_POST['nonce'], 'carousel_slider_ajax' ) ) {
            return;
        }
		// Check if user has permissions to save data.
        if ( ! current_user_can( 'edit_posts' ) ) {
        	return;
        }

        $ids = strip_tags(rtrim($_POST['ids'], ','));
        update_post_meta($_POST['post_id'], '_wpdh_image_ids', $ids);

        $thumbs = explode(',', $ids);
        $thumbs_output = '';
        foreach( $thumbs as $thumb ) {
            $thumbs_output .= '<li>' . wp_get_attachment_image( $thumb, array(75,75) ) . '</li>';
        }

        echo $thumbs_output;

        die();
	}

	/**
	 * Save images urls
	 * 
	 * @param  integer $post_id
	 * @return void
	 */
	private function save_images_urls( $post_id )
	{
		if ( ! isset( $_POST['_images_urls'] ) ) {
			return;
		}
		$url 		= $_POST['_images_urls']['url'];
		$title 		= $_POST['_images_urls']['title'];
		$caption 	= $_POST['_images_urls']['caption'];
		$alt 		= $_POST['_images_urls']['alt'];
		$link_url 	= $_POST['_images_urls']['link_url'];

		$urls 		= array();

		for ($i=0; $i < count($url); $i++) { 
			$urls[] = array(
				'url' 		=> esc_url_raw($url[$i]),
				'title' 	=> sanitize_text_field($title[$i]),
				'caption' 	=> sanitize_text_field($caption[$i]),
				'alt' 		=> sanitize_text_field($alt[$i]),
				'link_url' 	=> esc_url_raw($link_url[$i]),
			);
		}
		update_post_meta( $post_id, '_images_urls', $urls );
	}

	/**
	 * Adding our custom fields to the $form_fields array
	 * 
	 * @param array $form_fields
	 * @param object $post
	 * @return array
	 */
	public function attachment_fields_to_edit( $form_fields, $post )
	{
		$form_fields["carousel_slider_link_url"]["label"] = __("Link to URL", "carousel-slider");
	    $form_fields["carousel_slider_link_url"]["input"] = "textarea";
	    $form_fields["carousel_slider_link_url"]["value"] = get_post_meta($post->ID, "_carousel_slider_link_url", true);
	    $form_fields["carousel_slider_link_url"]["extra_rows"] = array(
	    	'carouselSliderInfo' => __('"Link to URL" only works on Carousel Slider for linking image to a custom url.', 'carousel-slider'),
	    );
	     
	    return $form_fields;
	}

	/**
	 * Save custom field value
	 * 
	 * @param array $post
	 * @param array $attachment
	 * @return object
	 */
	public function attachment_fields_to_save( $post, $attachment )
	{
		$slider_link_url = isset($attachment['carousel_slider_link_url']) ? $attachment['carousel_slider_link_url'] : null;

		if( filter_var( $slider_link_url, FILTER_VALIDATE_URL ) ) {

			update_post_meta( $post['ID'], '_carousel_slider_link_url', esc_url_raw( $slider_link_url ) );
	    }

	    return $post;
	}

	private function is_woocommerce_active()
	{
		if(in_array('woocommerce/woocommerce.php', get_option('active_plugins'))) {

			return true;
		}

		return false;
	}
}

endif;