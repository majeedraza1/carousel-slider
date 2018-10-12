<?php

namespace CarouselSlider\Admin;

use CarouselSlider\Supports\Utils;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class MetaBox {

	/**
	 * @var self
	 */
	private static $instance;

	/**
	 * @var string
	 */
	private $post_type = 'carousels';

	/**
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @return MetaBox
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_action( 'add_meta_boxes', array( self::$instance, 'add_meta_boxes' ) );
		}

		return self::$instance;
	}

	/**
	 * Add carousel slider meta box
	 */
	public function add_meta_boxes() {
		add_meta_box( "carousel-slider-meta-boxes", __( "Carousel Slider", 'carousel-slider' ),
			array( $this, 'carousel_slider_meta_boxes' ), $this->post_type, "normal", "high" );

		add_meta_box( "carousel-slider-usages-info", __( "Usage (Shortcode)", 'carousel-slider' ),
			array( $this, 'usages_callback' ), $this->post_type, "side", "high" );

		add_meta_box( "carousel-slider-settings", __( "Settings", 'carousel-slider' ),
			array( $this, 'carousel_slider_settings' ), "carousels", "advanced", "low" );
	}

	public function carousel_slider_settings() {
		?>
        <div class="carousel-slider-tabs-wrapper">
            <div id="carousel-slider-metabox-tabs" class="carousel-slider-tabs">
                <ul class="carousel-slider-tabs-list">
                    <li class="carousel-slider-tab-list--general">
                        <a href="#carousel-slider-tab-1">
							<?php esc_html_e( 'General Settings', 'dialog-contact-form' ); ?>
                        </a>
                    </li>
                    <li class="carousel-slider-tab-list--autoplay">
                        <a href="#carousel-slider-tab-2">
							<?php esc_html_e( 'Autoplay Settings', 'dialog-contact-form' ); ?>
                        </a>
                    </li>
                    <li class="carousel-slider-tab-list--navigation">
                        <a href="#carousel-slider-tab-3">
							<?php esc_html_e( 'Navigation Settings', 'dialog-contact-form' ); ?>
                        </a>
                    </li>
                    <li class="carousel-slider-tab-list--responsive">
                        <a href="#carousel-slider-tab-4">
							<?php esc_html_e( 'Responsive Settings', 'dialog-contact-form' ); ?>
                        </a>
                    </li>
                </ul>
                <div id="carousel-slider-tab-1" class="carousel-slider-options-panel">
					<?php require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/general.php'; ?>
                </div>
                <div id="carousel-slider-tab-2" class="carousel-slider-options-panel">&nbsp;
					<?php require CAROUSEL_SLIDER_TEMPLATES . '/admin/autoplay.php'; ?>
                </div>
                <div id="carousel-slider-tab-3" class="carousel-slider-options-panel">
					<?php require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/navigation.php'; ?>
                </div>
                <div id="carousel-slider-tab-4" class="carousel-slider-options-panel">
					<?php require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/responsive.php'; ?>
                </div>
            </div>
        </div>
		<?php
	}

	/**
	 * Load meta box content
	 *
	 * @param \WP_Post $post
	 */
	public function carousel_slider_meta_boxes( $post ) {
		wp_nonce_field( 'carousel_slider_nonce', '_carousel_slider_nonce' );

		$slide_type = get_post_meta( $post->ID, '_slide_type', true );
		$slide_type = in_array( $slide_type, Utils::get_slide_types() ) ? $slide_type : 'image-carousel';

		require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/types.php';
		require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/images-media.php';
		require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/images-url.php';
		require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/post-carousel.php';
		require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/product-carousel.php';
		require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/video-carousel.php';
		require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/hero-banner-slider.php';
		require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/images-settings.php';
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
        <input type="text" onmousedown="this.clicked = 1;"
               onfocus="if (!this.clicked) this.select(); else this.clicked = 2;"
               onclick="if (this.clicked === 2) this.select(); this.clicked = 0;"
               value="[carousel_slide id='<?php echo $post->ID; ?>']"
               style="background-color: #f1f1f1; width: 100%; padding: 8px;"
        >
		<?php echo ob_get_clean();
	}
}
