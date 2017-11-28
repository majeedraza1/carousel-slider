<?php

namespace CarouselSlider;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Documentation {

	protected static $instance = null;

	/**
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @return Documentation
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	public function admin_menu() {
		add_submenu_page(
			'edit.php?post_type=carousels',
			'Documentation',
			'Documentation',
			'manage_options',
			'carousel-slider-documentation',
			array( $this, 'submenu_page_callback' )
		);
	}

	public function submenu_page_callback() {
		?>
        <div class="wrap">
            <h1 class="wp-heading">
				<?php esc_html_e( 'Carousel Slider Documentation', 'carousel-slider' ); ?>
            </h1>
            <hr class="clear">
            <div class="postbox">
                <div class="inside">
                    <div class="carousel_slider_columns">
                        <div class="carousel_slider_column">
                            <div class="carousel_slider_iframe">
                                <iframe width="1280" height="720"
                                        src="https://www.youtube.com/embed/ZzI1JhElrxc"
                                        frameborder="0"
                                        allowfullscreen></iframe>
                            </div>
                            <label><?php esc_html_e( 'Images Carousel', 'carousel-slider' ); ?></label>
                            <p class="description"><?php esc_html_e( 'Image carousel using gallery images', 'carousel-slider' ); ?></p>
                        </div>
                        <div class="carousel_slider_column">
                            <div class="carousel_slider_iframe">
                                <iframe width="1280" height="720"
                                        src="https://www.youtube.com/embed/a7hqn1yNzwM" frameborder="0"
                                        allowfullscreen></iframe>
                            </div>
                            <label><?php esc_html_e( 'Images Carousel', 'carousel-slider' ); ?></label>
                            <p class="description"><?php esc_html_e( 'Image carousel using custom URLs', 'carousel-slider' ); ?></p>
                        </div>
                        <div class="carousel_slider_column">
                            <div class="carousel_slider_iframe">
                                <iframe width="1280" height="720"
                                        src="https://www.youtube.com/embed/ImJB946azy0" frameborder="0"
                                        allowfullscreen></iframe>
                            </div>
                            <label><?php esc_html_e( 'Posts Carousel', 'carousel-slider' ); ?></label>
                        </div>
                        <div class="carousel_slider_column">
                            <div class="carousel_slider_iframe">
                                <iframe width="1280" height="720"
                                        src="https://www.youtube.com/embed/yiAkvXyfakg" frameborder="0"
                                        allowfullscreen></iframe>
                            </div>
                            <label><?php esc_html_e( 'WooCommerce Products Carousel', 'carousel-slider' ); ?></label>
                        </div>
                        <div class="carousel_slider_column">
                            <div class="carousel_slider_iframe">
                                <iframe width="1280" height="720"
                                        src="https://www.youtube.com/embed/kYgp6wp27lM" frameborder="0"
                                        allowfullscreen></iframe>
                            </div>
                            <label><?php esc_html_e( 'In Widget Areas', 'carousel-slider' ); ?></label>
                        </div>
                        <div class="carousel_slider_column">
                            <div class="carousel_slider_iframe">
                                <iframe width="1280" height="720"
                                        src="https://www.youtube.com/embed/-OaYQZfr1RM" frameborder="0"
                                        allowfullscreen></iframe>
                            </div>
                            <label><?php esc_html_e( 'With Page Builder by SiteOrigin', 'carousel-slider' ); ?></label>
                        </div>
                        <div class="carousel_slider_column">
                            <div class="carousel_slider_iframe">
                                <iframe width="1280" height="720"
                                        src="https://www.youtube.com/embed/4LhDXH81whk" frameborder="0"
                                        allowfullscreen></iframe>
                            </div>
                            <label><?php esc_html_e( 'With WPBakery Visual Composer', 'carousel-slider' ); ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <br class="clear">
        </div>
		<?php
	}
}

Documentation::init();
