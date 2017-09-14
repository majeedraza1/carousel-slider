<?php
if ( ! class_exists( 'Carousel_Slider_Script' ) ):

	class Carousel_Slider_Script {

		protected static $instance = null;

		/**
		 * Ensures only one instance of this class is loaded or can be loaded.
		 *
		 * @return Carousel_Slider_Script
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ), 15 );
			add_action( 'wp_footer', array( $this, 'inline_script' ), 30 );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 10 );
			add_action( 'admin_footer', array( $this, 'gallery_url_template' ), 5 );
		}

		/**
		 * Load frontend scripts
		 */
		public function frontend_scripts() {
			wp_register_style(
				'carousel-slider',
				CAROUSEL_SLIDER_ASSETS . '/css/style.css',
				array(),
				CAROUSEL_SLIDER_VERSION,
				'all'
			);
			wp_register_script(
				'owl-carousel',
				CAROUSEL_SLIDER_ASSETS . '/js/vendors/owl.carousel.min.js',
				array( 'jquery' ),
				'2.2.0',
				true
			);
			wp_register_script(
				'magnific-popup',
				CAROUSEL_SLIDER_ASSETS . '/js/vendors/jquery.magnific-popup.min.js',
				array(),
				'1.1.0',
				true
			);

			if ( $this->should_load_scripts() ) {
				wp_enqueue_style( 'carousel-slider' );
				wp_enqueue_script( 'owl-carousel' );
			}
		}

		/**
		 * Load admin scripts
		 *
		 * @param $hook
		 */
		public function admin_scripts( $hook ) {
			global $post;

			if ( $hook == 'post-new.php' || $hook == 'post.php' ) {

				if ( is_a( $post, 'WP_Post' ) && 'carousels' == $post->post_type ) {
					wp_enqueue_media();
					wp_enqueue_style( 'wp-color-picker' );
					wp_enqueue_style(
						'carousel-slider-admin',
						CAROUSEL_SLIDER_ASSETS . '/css/admin.css',
						array(),
						CAROUSEL_SLIDER_VERSION,
						'all'
					);
					wp_enqueue_script(
						'select2',
						CAROUSEL_SLIDER_ASSETS . '/js/vendors/select2.min.js',
						array( 'jquery' ),
						'4.0.3',
						true
					);
					wp_enqueue_script(
						'tip-tip',
						CAROUSEL_SLIDER_ASSETS . '/js/vendors/jquery.tipTip.min.js',
						array( 'jquery' ),
						CAROUSEL_SLIDER_VERSION,
						true
					);
					wp_enqueue_script(
						'wp-color-picker-alpha',
						CAROUSEL_SLIDER_ASSETS . '/js/vendors/wp-color-picker-alpha.min.js',
						array( 'wp-color-picker' ),
						'1.2.2',
						true
					);
					wp_enqueue_script(
						'carousel-slider-admin',
						CAROUSEL_SLIDER_ASSETS . '/js/admin.min.js',
						array(
							'jquery',
							'wp-color-picker-alpha',
							'jquery-ui-accordion',
							'jquery-ui-datepicker',
							'jquery-ui-sortable',
							'jquery-ui-tabs',
							'tip-tip',
							'select2'
						),
						CAROUSEL_SLIDER_VERSION,
						true
					);
				}
			}
		}

		/**
		 * Load front end inline script
		 */
		public function inline_script() {
			if ( $this->should_load_scripts() ):
				?>
                <svg width="1" height="1" style="display: none;">
                    <symbol id="icon-arrow-left" viewBox="0 0 20 20">
                        <path d="M14 5l-5 5 5 5-1 2-7-7 7-7z"></path>
                    </symbol>
                    <symbol id="icon-arrow-right" viewBox="0 0 20 20">
                        <path d="M6 15l5-5-5-5 1-2 7 7-7 7z"></path>
                    </symbol>
                </svg>
                <script type="text/javascript">
                    jQuery(document).ready(function ($) {

                        $('body').find('.carousel-slider').each(function () {
                            var _this = $(this);
                            var isVideo = _this.data('slide-type') === 'video-carousel';
                            var videoWidth = isVideo ? _this.data('video-width') : false;
                            var videoHeight = isVideo ? _this.data('video-height') : false;
                            var autoWidth = isVideo;

                            if (jQuery().magnificPopup) {
                                var popupType = _this.data('slide-type') === 'product-carousel' ? 'ajax' : 'image';
                                var popupGallery = _this.data('slide-type') !== 'product-carousel';
                                $(this).find('.magnific-popup').magnificPopup({
                                    type: popupType,
                                    gallery: {
                                        enabled: popupGallery
                                    },
                                    zoom: {
                                        enabled: popupGallery,
                                        duration: 300,
                                        easing: 'ease-in-out'
                                    }
                                });
                            }

                            if (jQuery().owlCarousel) {
                                _this.owlCarousel({
                                    nav: _this.data('nav'),
                                    dots: _this.data('dots'),
                                    margin: _this.data('margin'),
                                    loop: _this.data('loop'),
                                    autoplay: _this.data('autoplay'),
                                    autoplayTimeout: _this.data('autoplay-timeout'),
                                    autoplaySpeed: _this.data('autoplay-speed'),
                                    autoplayHoverPause: _this.data('autoplay-hover-pause'),
                                    slideBy: _this.data('slide-by'),
                                    lazyLoad: _this.data('lazy-load'),
                                    video: isVideo,
                                    videoWidth: videoWidth,
                                    videoHeight: videoHeight,
                                    autoWidth: autoWidth,
                                    navText: [
                                        '<svg class="carousel-slider-nav-icon" width="48" height="48"><use xlink:href="#icon-arrow-left"></use></svg>',
                                        '<svg class="carousel-slider-nav-icon" width="48" height="48"><use xlink:href="#icon-arrow-right"></use></svg>'
                                    ],
                                    responsive: {
                                        320: {items: _this.data('colums-mobile')},
                                        600: {items: _this.data('colums-small-tablet')},
                                        768: {items: _this.data('colums-tablet')},
                                        993: {items: _this.data('colums-small-desktop')},
                                        1200: {items: _this.data('colums-desktop')},
                                        1921: {items: _this.data('colums')}
                                    }
                                });
                            }
                        });
                    });
                </script><?php
			endif;
		}

		/**
		 * Carousel slider gallery url template
		 *
		 * @return void
		 */
		public function gallery_url_template() {
			global $post_type;
			if ( $post_type != 'carousels' ) {
				return;
			}
			?>
            <template id="carouselSliderGalleryUrlTemplate" style="display: none;">
                <div class="carousel_slider-fields">
                    <label class="setting">
                        <span class="name"><?php esc_html_e( 'URL', 'carousel-slider' ); ?></span>
                        <input type="url" name="_images_urls[url][]" value="" autocomplete="off">
                    </label>
                    <label class="setting">
                        <span class="name"><?php esc_html_e( 'Title', 'carousel-slider' ); ?></span>
                        <input type="text" name="_images_urls[title][]" value="" autocomplete="off">
                    </label>
                    <label class="setting">
                        <span class="name"><?php esc_html_e( 'Caption', 'carousel-slider' ); ?></span>
                        <textarea name="_images_urls[caption][]"></textarea>
                    </label>
                    <label class="setting">
                        <span class="name"><?php esc_html_e( 'Alt Text', 'carousel-slider' ); ?></span>
                        <input type="text" name="_images_urls[alt][]" value="" autocomplete="off">
                    </label>
                    <label class="setting">
                        <span class="name"><?php esc_html_e( 'Link To URL', 'carousel-slider' ); ?></span>
                        <input type="text" name="_images_urls[link_url][]" value="" autocomplete="off">
                    </label>
                    <div class="actions">
                        <span><span class="dashicons dashicons-move"></span></span>
                        <span class="add_row"><span class="dashicons dashicons-plus-alt"></span></span>
                        <span class="delete_row"><span class="dashicons dashicons-trash"></span></span>
                    </div>
                </div>
            </template>
			<?php
		}

		/**
		 * Check if it should load frontend scripts
		 *
		 * @return boolean
		 */
		private function should_load_scripts() {
			global $post;
			$load_scripts = is_active_widget( false, false, 'widget_carousel_slider', true ) ||
			                ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'carousel_slide' ) ) ||
			                ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'carousel' ) );

			return apply_filters( 'carousel_slider_load_scripts', $load_scripts );
		}
	}

endif;

Carousel_Slider_Script::init();
