<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

if ( ! class_exists( 'Carousel_Slider_Shortcode' ) ):

	class Carousel_Slider_Shortcode {

		/**
		 * The instance of the class
		 *
		 * @var self
		 */
		protected static $instance = null;

		/**
		 * Ensures only one instance of this class is loaded or can be loaded.
		 *
		 * @return Carousel_Slider_Shortcode
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * CarouselSliderShortcode constructor.
		 */
		public function __construct() {
			add_shortcode( 'carousel_slide', array( $this, 'carousel_slide' ) );
		}

		/**
		 * A shortcode for rendering the carousel slide.
		 *
		 * @param  array $attributes Shortcode attributes.
		 *
		 * @return string  The shortcode output
		 */
		public function carousel_slide( $attributes ) {
			if ( empty( $attributes['id'] ) ) {
				return '';
			}

			$id = intval( $attributes['id'] );

			$slide_type = get_post_meta( $id, '_slide_type', true );
			$slide_type = in_array( $slide_type, carousel_slider_slide_type() ) ? $slide_type : 'image-carousel';

			if ( $slide_type == 'post-carousel' ) {
				ob_start();
				require CAROUSEL_SLIDER_TEMPLATES . '/public/post-carousel.php';
				$html = ob_get_contents();
				ob_end_clean();

				return apply_filters( 'carousel_slider_posts_carousel', $html, $id );
			}

			if ( $slide_type == 'video-carousel' ) {
				wp_enqueue_script( 'magnific-popup' );
				$_video_urls = array_filter( explode( ',', $this->get_meta( $id, '_video_url' ) ) );
				$urls        = $this->get_video_url( $_video_urls );

				ob_start();
				require CAROUSEL_SLIDER_TEMPLATES . '/public/video-carousel.php';
				$html = ob_get_contents();
				ob_end_clean();

				return apply_filters( 'carousel_slider_videos_carousel', $html, $id );
			}

			if ( $slide_type == 'image-carousel-url' ) {
				ob_start();
				require CAROUSEL_SLIDER_TEMPLATES . '/public/images-carousel-url.php';
				$html = ob_get_contents();
				ob_end_clean();

				return apply_filters( 'carousel_slider_link_images_carousel', $html, $id );
			}

			if ( $slide_type == 'image-carousel' ) {
				ob_start();
				require CAROUSEL_SLIDER_TEMPLATES . '/public/images-carousel.php';
				$html = ob_get_contents();
				ob_end_clean();

				return apply_filters( 'carousel_slider_gallery_images_carousel', $html, $id );
			}

			if ( $slide_type == 'product-carousel' ) {

				$query_type = get_post_meta( $id, '_product_query_type', true );
				$query_type = empty( $query_type ) ? 'query_product' : $query_type;
				// Type mistake
				$query_type    = ( 'query_porduct' == $query_type ) ? 'query_product' : $query_type;
				$product_query = get_post_meta( $id, '_product_query', true );

				if ( $query_type == 'query_product' && $product_query == 'product_categories_list' ) {
					ob_start();

					echo $this->product_categories( $id );
					$html = ob_get_contents();
					ob_end_clean();

					return apply_filters( 'carousel_slider_product_carousel', $html, $id );
				}

				ob_start();
				require CAROUSEL_SLIDER_TEMPLATES . '/public/product-carousel.php';
				$html = ob_get_contents();
				ob_end_clean();

				return apply_filters( 'carousel_slider_product_carousel', $html, $id );
			}

			if ( $slide_type == 'hero-banner-slider' ) {

				ob_start();
				require CAROUSEL_SLIDER_TEMPLATES . '/public/hero-banner-slider.php';
				$html = ob_get_contents();
				ob_end_clean();

				return apply_filters( 'Carousel_Slider_Hero_Carousel', $html, $id );
			}

			return '';
		}

		/**
		 * Generate carousel options for slider
		 *
		 * @param $id
		 *
		 * @return array
		 */
		private function carousel_options( $id ) {
			$_nav_button      = get_post_meta( $id, '_nav_button', true );
			$_arrow_position  = get_post_meta( $id, '_arrow_position', true );
			$_dot_nav         = get_post_meta( $id, '_dot_nav', true );
			$_bullet_position = get_post_meta( $id, '_bullet_position', true );
			$_bullet_shape    = get_post_meta( $id, '_bullet_shape', true );

			$class = 'owl-carousel carousel-slider';

			// Arrows position
			if ( $_arrow_position == 'inside' ) {
				$class .= ' arrows-inside';
			} else {
				$class .= ' arrows-outside';
			}

			// Arrows visibility
			if ( $_nav_button == 'always' ) {
				$class .= ' arrows-visible-always';
			} elseif ( $_nav_button == 'off' ) {
				$class .= ' arrows-hidden';
			} else {
				$class .= ' arrows-visible-hover';
			}

			// Dots visibility
			if ( $_dot_nav == 'on' ) {
				$class .= ' dots-visible-always';
			} elseif ( $_dot_nav == 'off' ) {
				$class .= ' dots-hidden';
			} else {
				$class .= ' dots-visible-hover';
			}

			// Dots position
			if ( $_bullet_position == 'left' ) {
				$class .= ' dots-left';
			} elseif ( $_bullet_position == 'right' ) {
				$class .= ' dots-right';
			} else {
				$class .= ' dots-center';
			}

			// Dots shape
			if ( $_bullet_shape == 'circle' ) {
				$class .= ' dots-circle';
			} else {
				$class .= ' dots-square';
			}

			$_dot_nav    = ( get_post_meta( $id, '_dot_nav', true ) != 'off' );
			$_nav_button = ( get_post_meta( $id, '_nav_button', true ) != 'off' );

			$options_array = array(
				'id'                        => 'id-' . $id,
				'class'                     => $class,
				// General
				'data-slide-type'           => $this->get_meta( $id, '_slide_type', 'image-carousel' ),
				'data-margin'               => $this->get_meta( $id, '_margin_right', '10' ),
				'data-slide-by'             => $this->get_meta( $id, '_slide_by', '1' ),
				'data-loop'                 => $this->get_meta( $id, '_inifnity_loop', 'true' ),
				'data-lazy-load'            => $this->get_meta( $id, '_lazy_load_image', 'false' ),
				'data-stage-padding'        => $this->get_meta( $id, '_stage_padding', '0' ),
				'data-auto-width'           => $this->get_meta( $id, '_auto_width', 'false' ),
				// Navigation
				'data-dots'                 => $_dot_nav,
				'data-nav'                  => $_nav_button,
				// Autoplay
				'data-autoplay'             => $this->get_meta( $id, '_autoplay', 'true' ),
				'data-autoplay-timeout'     => $this->get_meta( $id, '_autoplay_timeout', '5000' ),
				'data-autoplay-speed'       => $this->get_meta( $id, '_autoplay_speed', '500' ),
				'data-autoplay-hover-pause' => $this->get_meta( $id, '_autoplay_pause', 'false' ),
				// Responsive
				'data-colums'               => $this->get_meta( $id, '_items', '4' ),
				'data-colums-desktop'       => $this->get_meta( $id, '_items_desktop', '4' ),
				'data-colums-small-desktop' => $this->get_meta( $id, '_items_small_desktop', '4' ),
				'data-colums-tablet'        => $this->get_meta( $id, '_items_portrait_tablet', '3' ),
				'data-colums-small-tablet'  => $this->get_meta( $id, '_items_small_portrait_tablet', '2' ),
				'data-colums-mobile'        => $this->get_meta( $id, '_items_portrait_mobile', '1' ),
			);

			return $this->array_to_data( $options_array );
		}

		/**
		 * Get post meta by id and key
		 *
		 * @param $id
		 * @param $key
		 * @param null $default
		 *
		 * @return string
		 */
		public function get_meta( $id, $key, $default = null ) {
			return carousel_slider_get_meta( $id, $key, $default );
		}

		/**
		 * Convert array to html data attribute
		 *
		 * @param $array
		 *
		 * @return array
		 */
		public function array_to_data( $array ) {
			return carousel_slider_array_to_attribute( $array );
		}

		/**
		 * Get product categories list carousel
		 *
		 * @param int $id
		 *
		 * @return string
		 */
		private function product_categories( $id = 0 ) {

			$product_carousel   = new Carousel_Slider_Product();
			$product_categories = $product_carousel->product_categories();

			$options = $this->carousel_options( $id );
			$options = join( " ", $options );

			ob_start();
			if ( $product_categories ) {
				echo '<div class="carousel-slider-outer carousel-slider-outer-products carousel-slider-outer-' . $id . '">';
				carousel_slider_inline_style( $id );
				echo '<div ' . $options . '>';


				foreach ( $product_categories as $category ) {
					echo '<div class="product carousel-slider__product">';
					do_action( 'woocommerce_before_subcategory', $category );
					do_action( 'woocommerce_before_subcategory_title', $category );
					do_action( 'woocommerce_shop_loop_subcategory_title', $category );
					do_action( 'woocommerce_after_subcategory_title', $category );
					do_action( 'woocommerce_after_subcategory', $category );
					echo '</div>';
				}

				echo '</div>';
				echo '</div>';
			}

			$html = ob_get_contents();
			ob_end_clean();

			return $html;
		}

		/**
		 * Get Youtube video ID from URL
		 *
		 * @param string $url
		 *
		 * @return mixed Youtube video ID or FALSE if not found
		 */
		private function get_youtube_id_from_url( $url ) {
			$parts = parse_url( $url );
			if ( isset( $parts['query'] ) ) {
				parse_str( $parts['query'], $qs );
				if ( isset( $qs['v'] ) ) {
					return $qs['v'];
				} elseif ( isset( $qs['vi'] ) ) {
					return $qs['vi'];
				}
			}
			if ( isset( $parts['path'] ) ) {
				$path = explode( '/', trim( $parts['path'], '/' ) );

				return $path[ count( $path ) - 1 ];
			}

			return false;
		}

		/**
		 * Get Vimeo video ID from URL
		 *
		 * @param string $url
		 *
		 * @return mixed Vimeo video ID or FALSE if not found
		 */
		private function get_vimeo_id_from_url( $url ) {
			$parts = parse_url( $url );
			if ( isset( $parts['path'] ) ) {
				$path = explode( '/', trim( $parts['path'], '/' ) );

				return $path[ count( $path ) - 1 ];
			}

			return false;
		}

		/**
		 * @param $video_urls
		 *
		 * @return array
		 */
		public function get_video_url( array $video_urls ) {
			$_url = array();
			foreach ( $video_urls as $video_url ) {
				if ( ! filter_var( $video_url, FILTER_VALIDATE_URL ) ) {
					continue;
				}
				$provider  = '';
				$video_id  = '';
				$thumbnail = '';
				if ( false !== strpos( $video_url, 'youtube.com' ) ) {
					$provider  = 'youtube';
					$video_id  = $this->get_youtube_id_from_url( $video_url );
					$thumbnail = array(
						'large'  => 'https://img.youtube.com/vi/' . $video_id . '/hqdefault.jpg',
						'medium' => 'https://img.youtube.com/vi/' . $video_id . '/mqdefault.jpg',
						'small'  => 'https://img.youtube.com/vi/' . $video_id . '/sddefault.jpg',
					);

				} elseif ( false !== strpos( $video_url, 'vimeo.com' ) ) {
					$provider  = 'vimeo';
					$video_id  = $this->get_vimeo_id_from_url( $video_url );
					$response  = wp_remote_get( "https://vimeo.com/api/v2/video/$video_id.json" );
					$thumbnail = json_decode( wp_remote_retrieve_body( $response ), true );
					$thumbnail = array(
						'large'  => isset( $thumbnail[0]['thumbnail_large'] ) ? $thumbnail[0]['thumbnail_large'] : null,
						'medium' => isset( $thumbnail[0]['thumbnail_medium'] ) ? $thumbnail[0]['thumbnail_medium'] : null,
						'small'  => isset( $thumbnail[0]['thumbnail_small'] ) ? $thumbnail[0]['thumbnail_small'] : null,
					);
				}

				$_url[] = array(
					'provider'  => $provider,
					'url'       => $video_url,
					'video_id'  => $video_id,
					'thumbnail' => $thumbnail,
				);
			}

			return $_url;
		}
	}

endif;

Carousel_Slider_Shortcode::init();
