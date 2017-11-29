<?php

namespace CarouselSlider;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class MetaBox {

	private $form;
	private $post_type = 'carousels';
	protected static $instance;

	/**
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @return MetaBox
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		$this->form = new \Carousel_Slider_Form();
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
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
		add_meta_box(
			"carousel-slider-usages-info",
			__( "Usage (Shortcode)", 'carousel-slider' ),
			array( $this, 'usages_callback' ),
			$this->post_type,
			"side",
			"high"
		);
		add_meta_box(
			"carousel-slider-navigation-settings",
			__( "Navigation Settings", 'carousel-slider' ),
			array( $this, 'navigation_settings_callback' ),
			$this->post_type,
			"side",
			"low"
		);
		add_meta_box(
			"carousel-slider-autoplay-settings",
			__( "Autoplay Settings", 'carousel-slider' ),
			array( $this, 'autoplay_settings_callback' ),
			$this->post_type,
			"side",
			"low"
		);
		add_meta_box(
			"carousel-slider-responsive-settings",
			__( "Responsive Settings", 'carousel-slider' ),
			array( $this, 'responsive_settings_callback' ),
			$this->post_type,
			"side",
			"low"
		);
		add_meta_box(
			"carousel-slider-general-settings",
			__( "General Settings", 'carousel-slider' ),
			array( $this, 'general_settings_callback' ),
			$this->post_type,
			"advanced",
			"low"
		);
	}

	/**
	 * Load meta box content
	 *
	 * @param \WP_Post $post
	 */
	public function carousel_slider_meta_boxes( $post ) {
		wp_nonce_field( 'carousel_slider_nonce', '_carousel_slider_nonce' );

		$slide_type = get_post_meta( $post->ID, '_slide_type', true );
		$slide_type = in_array( $slide_type, carousel_slider_slide_type() ) ? $slide_type : 'image-carousel';

		require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/types.php';

		do_action( 'carousel_slider_meta_box', $post, $slide_type );

		require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/images-settings.php';
	}

	/**
	 * @param \WP_Post $post
	 */
	public function general_settings_callback( $post ) {
		$this->form->image_sizes( array(
			'id'   => esc_html__( '_image_size', 'carousel-slider' ),
			'name' => esc_html__( 'Carousel Image size', 'carousel-slider' ),
			'desc' => sprintf(
				esc_html__( 'Choose "original uploaded image" for full size image or your desired image size for carousel image. You can change the default size for thumbnail, medium and large from %1$s Settings >> Media %2$s.', 'carousel-slider' ),
				'<a target="_blank" href="' . admin_url( 'options-media.php' ) . '">',
				'</a>'
			),
		) );
		$this->form->select( array(
			'id'      => '_lazy_load_image',
			'name'    => esc_html__( 'Lazy Loading', 'carousel-slider' ),
			'desc'    => esc_html__( 'Enable image with lazy loading.', 'carousel-slider' ),
			'std'     => carousel_slider_default_settings()->lazy_load_image,
			'options' => array(
				'on'  => esc_html__( 'Enable' ),
				'off' => esc_html__( 'Disable' ),
			),
		) );
		$this->form->number( array(
			'id'   => '_margin_right',
			'name' => esc_html__( 'Item Spacing.', 'carousel-slider' ),
			'desc' => esc_html__( 'Space between two slide. Enter 10 for 10px', 'carousel-slider' ),
			'std'  => carousel_slider_default_settings()->margin_right
		) );
		$this->form->select( array(
			'id'      => '_inifnity_loop',
			'name'    => esc_html__( 'Infinity loop', 'carousel-slider' ),
			'desc'    => esc_html__( 'Enable or disable loop(circular) of carousel.', 'carousel-slider' ),
			'std'     => 'on',
			'options' => array(
				'on'  => esc_html__( 'Enable' ),
				'off' => esc_html__( 'Disable' ),
			),
		) );
		$this->form->number( array(
			'id'   => '_stage_padding',
			'name' => esc_html__( 'Stage Padding', 'carousel-slider' ),
			'desc' => esc_html__( 'Add left and right padding on carousel slider stage wrapper.', 'carousel-slider' ),
			'std'  => '0',
		) );
		$this->form->select( array(
			'id'      => '_auto_width',
			'name'    => esc_html__( 'Auto Width', 'carousel-slider' ),
			'desc'    => esc_html__( 'Set item width according to its content width. Use width style on item to get the result you want. ', 'carousel-slider' ),
			'std'     => 'off',
			'options' => array(
				'on'  => esc_html__( 'Enable' ),
				'off' => esc_html__( 'Disable' ),
			),
		) );
	}

	/**
	 * Render short code meta box content
	 *
	 * @param \WP_Post $post
	 */
	public function usages_callback( $post ) {
		ob_start(); ?>
        <p><strong>
				<?php esc_html_e( 'Copy the following shortcode and paste in post or page where you want to show.', 'carousel-slider' ); ?>
            </strong>
        </p>
        <input
                type="text"
                onmousedown="this.clicked = 1;"
                onfocus="if (!this.clicked) this.select(); else this.clicked = 2;"
                onclick="if (this.clicked === 2) this.select(); this.clicked = 0;"
                value="[carousel_slide id='<?php echo $post->ID; ?>']"
                style="background-color: #f1f1f1; width: 100%; padding: 8px;"
        >
		<?php echo ob_get_clean();
	}

	/**
	 * @param \WP_Post $post
	 */
	public function navigation_settings_callback( $post ) {
		$this->form->select( array(
			'id'      => '_nav_button',
			'name'    => esc_html__( 'Show Arrow Nav', 'carousel-slider' ),
			'desc'    => esc_html__( 'Choose when to show arrow navigator.', 'carousel-slider' ),
			'std'     => 'on',
			'class'   => 'small-text',
			'context' => 'side',
			'options' => array(
				'off'    => esc_html__( 'Never', 'carousel-slider' ),
				'on'     => esc_html__( 'Mouse Over', 'carousel-slider' ),
				'always' => esc_html__( 'Always', 'carousel-slider' ),
			),
		) );
		$this->form->text( array(
			'id'      => '_slide_by',
			'name'    => esc_html__( 'Arrow Steps', 'carousel-slider' ),
			'desc'    => esc_html__( 'Steps to go for each navigation request.', 'carousel-slider' ),
			'std'     => 1,
			'class'   => 'small-text',
			'context' => 'side',
		) );
		$this->form->select( array(
			'id'      => '_arrow_position',
			'name'    => esc_html__( 'Arrow Position', 'carousel-slider' ),
			'desc'    => esc_html__( 'Choose where to show arrow.', 'carousel-slider' ),
			'std'     => 'outside',
			'class'   => 'small-text',
			'context' => 'side',
			'options' => array(
				'outside' => esc_html__( 'Outside', 'carousel-slider' ),
				'inside'  => esc_html__( 'Inside', 'carousel-slider' ),
			),
		) );
		$this->form->number( array(
			'id'      => '_arrow_size',
			'name'    => esc_html__( 'Arrow Size', 'carousel-slider' ),
			'desc'    => esc_html__( 'Enter arrow size in pixels.', 'carousel-slider' ),
			'std'     => 48,
			'class'   => 'small-text',
			'context' => 'side',
		) );

		echo '<hr>';

		$this->form->select( array(
			'id'      => '_dot_nav',
			'name'    => esc_html__( 'Show Bullet Nav', 'carousel-slider' ),
			'desc'    => esc_html__( 'Choose when to show bullet navigator.', 'carousel-slider' ),
			'std'     => 'on',
			'class'   => 'small-text',
			'context' => 'side',
			'options' => array(
				'off'   => esc_html__( 'Never', 'carousel-slider' ),
				'hover' => esc_html__( 'Mouse Over', 'carousel-slider' ),
				'on'    => esc_html__( 'Always', 'carousel-slider' ),
			),
		) );
		$this->form->select( array(
			'id'      => '_bullet_position',
			'name'    => esc_html__( 'Bullet Position', 'carousel-slider' ),
			'desc'    => esc_html__( 'Choose where to show bullets.', 'carousel-slider' ),
			'std'     => 'center',
			'class'   => 'small-text',
			'context' => 'side',
			'options' => array(
				'left'   => esc_html__( 'Left', 'carousel-slider' ),
				'center' => esc_html__( 'Center', 'carousel-slider' ),
				'right'  => esc_html__( 'Right', 'carousel-slider' ),
			),
		) );
		$this->form->number( array(
			'id'      => '_bullet_size',
			'name'    => esc_html__( 'Bullet Size', 'carousel-slider' ),
			'desc'    => esc_html__( 'Enter bullet size in pixels.', 'carousel-slider' ),
			'std'     => 10,
			'class'   => 'small-text',
			'context' => 'side',
		) );
		$this->form->select( array(
			'id'      => '_bullet_shape',
			'name'    => esc_html__( 'Bullet Shape', 'carousel-slider' ),
			'desc'    => esc_html__( 'Choose bullet nav shape.', 'carousel-slider' ),
			'std'     => 'circle',
			'class'   => 'small-text',
			'context' => 'side',
			'options' => array(
				'square' => esc_html__( 'Square', 'carousel-slider' ),
				'circle' => esc_html__( 'Circle', 'carousel-slider' ),
			),
		) );

		echo '<hr>';

		$this->form->color( array(
			'id'      => '_nav_color',
			'name'    => esc_html__( 'Arrows & Dots Color', 'carousel-slider' ),
			'std'     => carousel_slider_default_settings()->nav_color,
			'class'   => 'color-picker',
			'context' => 'side',
		) );
		$this->form->color( array(
			'id'      => '_nav_active_color',
			'name'    => esc_html__( 'Arrows & Dots Hover Color', 'carousel-slider' ),
			'std'     => carousel_slider_default_settings()->nav_active_color,
			'class'   => 'color-picker',
			'context' => 'side',
		) );
	}

	/**
	 * @param \WP_Post $post
	 */
	public function autoplay_settings_callback( $post ) {
		$this->form->select( array(
			'id'      => '_autoplay',
			'name'    => esc_html__( 'AutoPlay', 'carousel-slider' ),
			'desc'    => esc_html__( 'Choose whether slideshow should play automatically.', 'carousel-slider' ),
			'std'     => 'on',
			'class'   => 'small-text',
			'context' => 'side',
			'options' => array(
				'on'  => esc_html__( 'Enable', 'carousel-slider' ),
				'off' => esc_html__( 'Disable', 'carousel-slider' ),
			),
		) );
		$this->form->select( array(
			'id'      => '_autoplay_pause',
			'name'    => esc_html__( 'Pause On Hover', 'carousel-slider' ),
			'desc'    => esc_html__( 'Pause automatic play on mouse hover.', 'carousel-slider' ),
			'std'     => 'on',
			'class'   => 'small-text',
			'context' => 'side',
			'options' => array(
				'on'  => esc_html__( 'Enable', 'carousel-slider' ),
				'off' => esc_html__( 'Disable', 'carousel-slider' ),
			),
		) );
		$this->form->number( array(
			'id'      => '_autoplay_timeout',
			'name'    => esc_html__( 'Autoplay Timeout', 'carousel-slider' ),
			'desc'    => esc_html__( 'Automatic play interval timeout in millisecond. Default: 5000', 'carousel-slider' ),
			'std'     => 5000,
			'class'   => 'small-text',
			'context' => 'side',
		) );
		$this->form->number( array(
			'id'      => '_autoplay_speed',
			'name'    => esc_html__( 'Autoplay Speed', 'carousel-slider' ),
			'desc'    => esc_html__( 'Automatic play speed in millisecond. Default: 500', 'carousel-slider' ),
			'std'     => 500,
			'class'   => 'small-text',
			'context' => 'side',
		) );
	}

	/**
	 * Renders the meta box.
	 *
	 * @param \WP_Post $post
	 */
	public function responsive_settings_callback( $post ) {
		$this->form->number( array(
			'id'      => '_items',
			'name'    => esc_html__( 'Columns', 'carousel-slider' ),
			'desc'    => esc_html__( 'The number of items you want to see on the Extra Large Desktop Layout (Screens size greater than 1921 pixels DP)', 'carousel-slider' ),
			'std'     => 4,
			'class'   => 'small-text',
			'context' => 'side',
		) );
		$this->form->number( array(
			'id'      => '_items_desktop',
			'name'    => esc_html__( 'Columns : Desktop', 'carousel-slider' ),
			'desc'    => esc_html__( 'The number of items you want to see on the Desktop Layout (Screens size from 1200 pixels DP to 1920 pixels DP)', 'carousel-slider' ),
			'std'     => 4,
			'class'   => 'small-text',
			'context' => 'side',
		) );
		$this->form->number( array(
			'id'      => '_items_small_desktop',
			'name'    => esc_html__( 'Columns : Small Desktop', 'carousel-slider' ),
			'desc'    => esc_html__( 'The number of items you want to see on the Small Desktop Layout (Screens size from 993 pixels DP to 1199 pixels DP)', 'carousel-slider' ),
			'std'     => 4,
			'class'   => 'small-text',
			'context' => 'side',
		) );
		$this->form->number( array(
			'id'      => '_items_portrait_tablet',
			'name'    => esc_html__( 'Columns : Tablet', 'carousel-slider' ),
			'desc'    => esc_html__( 'The number of items you want to see on the Tablet Layout (Screens size from 768 pixels DP to 992 pixels DP)', 'carousel-slider' ),
			'std'     => 3,
			'class'   => 'small-text',
			'context' => 'side',
		) );
		$this->form->number( array(
			'id'      => '_items_small_portrait_tablet',
			'name'    => esc_html__( 'Columns : Small Tablet', 'carousel-slider' ),
			'desc'    => esc_html__( 'The number of items you want to see on the Small Tablet Layout(Screens size from 600 pixels DP to 767 pixels DP)', 'carousel-slider' ),
			'std'     => 2,
			'class'   => 'small-text',
			'context' => 'side',
		) );
		$this->form->number( array(
			'id'      => '_items_portrait_mobile',
			'name'    => esc_html__( 'Columns : Mobile', 'carousel-slider' ),
			'desc'    => esc_html__( 'The number of items you want to see on the Mobile Layout (Screens size from 320 pixels DP to 599 pixels DP)', 'carousel-slider' ),
			'std'     => 1,
			'class'   => 'small-text',
			'context' => 'side',
		) );
	}
}

MetaBox::init();
