<?php
if ( ! class_exists( 'CarouselSliderScripts' ) ):

	class CarouselSliderScripts {

		protected static $instance = null;

		/**
		 * Ensures only one instance of this class is loaded or can be loaded.
		 *
		 * @return CarouselSliderScripts
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ), 15 );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 10 );
			add_action( 'wp_footer', array( $this, 'inline_script' ), 30 );
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
				CAROUSEL_SLIDER_ASSETS . '/js/owl.carousel.min.js',
				array( 'jquery' ),
				'2.2.0',
				true
			);
			wp_register_script(
				'magnific-popup',
				CAROUSEL_SLIDER_ASSETS . '/js/jquery.magnific-popup.min.js',
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
						CAROUSEL_SLIDER_ASSETS . '/js/select2.min.js',
						array( 'jquery' ),
						'4.0.3',
						true
					);
					wp_enqueue_script(
						'livequery',
						CAROUSEL_SLIDER_ASSETS . '/js/jquery.livequery.js',
						array( 'jquery' ),
						'1.3.6',
						true
					);
					wp_enqueue_script(
						'carousel-slider-admin',
						CAROUSEL_SLIDER_ASSETS . '/js/admin.js',
						array(
							'jquery',
							'wp-color-picker',
							'jquery-ui-accordion',
							'jquery-ui-datepicker',
							'jquery-ui-sortable',
							'select2',
							'livequery'
						),
						CAROUSEL_SLIDER_VERSION,
						true
					);

					wp_localize_script( 'carousel-slider-admin', 'CarouselSlider', array(
						'post_id'           => $post->ID,
						'image_ids'         => get_post_meta( $post->ID, '_wpdh_image_ids', true ),
						'nonce'             => wp_create_nonce( 'carousel_slider_ajax' ),
						'create_btn_text'   => __( 'Create Gallery', 'carousel-slider' ),
						'edit_btn_text'     => __( 'Edit Gallery', 'carousel-slider' ),
						'save_btn_text'     => __( 'Save Gallery', 'carousel-slider' ),
						'progress_btn_text' => __( 'Saving...', 'carousel-slider' ),
						'insert_btn_text'   => __( 'Insert', 'carousel-slider' ),
					) );
				}
			}
		}

		/**
		 * Load front end inline script
		 */
		public function inline_script() {
			if ( $this->should_load_scripts() ):
				?>
                <script type="text/javascript">
                    jQuery(document).ready(function ($) {

                        $('body').find('.carousel-slider').each(function () {
                            var _this = $(this);
                            var isVideo = _this.data('slide-type') == 'video-carousel' ? true : false;
                            var videoWidth = isVideo ? _this.data('video-width') : false;
                            var videoHeight = isVideo ? _this.data('video-height') : false;
                            var autoWidth = isVideo ? true : false;

                            if (jQuery().magnificPopup) {
                                var popupType = _this.data('slide-type') == 'product-carousel' ? 'ajax' : 'image';
                                var popupGallery = _this.data('slide-type') != 'product-carousel' ? true : false;
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
                                    navText: [_this.data('nav-previous-icon'), _this.data('nav-next-icon')],
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

CarouselSliderScripts::init();
