<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Carousel_Slider_Shortcode' ) ):

	class Carousel_Slider_Shortcode {
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

			$id             = intval( $attributes['id'] );
			$options        = \CarouselSlider\Supports\Setting::get( $id );
			$slide_type     = $options['slide_type'];
			$class          = implode( ' ', $options['class'] );
			$owl_options    = \CarouselSlider\Supports\OwlCarousel::settings( $options );
			$magnific_popup = \CarouselSlider\Supports\MagnificPopup::settings( $options );

			if ( $slide_type == 'post-carousel' ) {
				ob_start();
				require CAROUSEL_SLIDER_TEMPLATES . '/public/post-carousel.php';
				$html = ob_get_contents();
				ob_end_clean();

				return apply_filters( 'carousel_slider_posts_carousel', $html, $id );
			}

			if ( $slide_type == 'video-carousel' ) {
				wp_enqueue_script( 'magnific-popup' );
				$urls = $this->get_video_url( $options['video_carousel']['video_url'] );

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

				$query_type    = get_post_meta( $id, '_product_query_type', true );
				$query_type    = empty( $query_type ) ? 'query_porduct' : $query_type;
				$product_query = get_post_meta( $id, '_product_query', true );

				if ( $query_type == 'query_porduct' && $product_query == 'product_categories_list' ) {
					$html = $this->product_categories( $id );

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

				return apply_filters( 'carousel_slider_hero_carousel', $html, $id );
			}

			return '';
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

			$options                = \CarouselSlider\Supports\Setting::get( $id );
			$options['total_slide'] = count( $product_categories );
			$class                  = implode( ' ', $options['class'] );
			$owl_options            = \CarouselSlider\Supports\OwlCarousel::settings( $options );

			ob_start();
			if ( $product_categories ) {
				echo '<div class="carousel-slider-outer carousel-slider-outer-products carousel-slider-outer-' . $id . '">';
				carousel_slider_inline_style( $id );
				?>
            <div id="id-<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>"
                 data-slide_type="product-carousel" data-owl_carousel='<?php echo json_encode( $owl_options ); ?>'>
				<?php


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
