<?php

namespace CarouselSlider\Modules\VideoCarousel;

use CarouselSlider\Modules\VideoCarousel\Helper as VideoCarouselHelper;

defined( 'ABSPATH' ) || exit;

/**
 * Module class
 *
 * @package Modules/VideoCarousel
 */
class Module {
	/**
	 * The instance of the class
	 *
	 * @var self
	 */
	protected static $instance;

	/**
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_action( 'carousel_slider/meta_box_content', [ self::$instance, 'meta_box_content' ], 10, 2 );
			add_action( 'carousel_slider/save_slider', [ self::$instance, 'save_slider' ], 10, 2 );
			add_filter( 'carousel_slider/register_view', [ self::$instance, 'view' ] );
		}

		return self::$instance;
	}

	/**
	 * Meta box content
	 *
	 * @param  int    $slider_id  The slider id.
	 * @param  string $slider_type  The slider type.
	 */
	public function meta_box_content( int $slider_id, string $slider_type ) {
		if ( 'video-carousel' !== $slider_type ) {
			return;
		}
		?>
			<div class="carousel-slider-video-carousel-urls-container">
				<div class="carousel-slider-video-carousel-urls shapla-columns is-multiline" id="carousel-slider-video-carousel-urls">
					<?php
					$video_urls = get_post_meta( $slider_id, '_video_urls', true );
					if ( empty( $video_urls ) ) {
						$urls       = get_post_meta( $slider_id, '_video_url', true );
						$video_urls = VideoCarouselHelper::get_video_url( $urls );
					}
					foreach ( $video_urls as $index => $video_url ) {
						$item = new Item( $video_url );
						include CAROUSEL_SLIDER_PATH . '/templates/admin-meta-box/video-loop-item.php';
					}
					?>
				</div>
				<div class="shapla-columns">
					<div class="shapla-column is-12">
						<button class="button add_video_url_row"><?php esc_html_e( 'Add New Item', 'carousel-slider' ); ?></button>
					</div>
				</div>
			</div>
		<?php
	}

	/**
	 * Save slider video url
	 *
	 * @param  int   $slider_id  The slider id.
	 * @param  array $data  The raw data.
	 */
	public function save_slider( int $slider_id, $data ) {
		$video_urls = $data['_video_urls'] ?? [];
		if ( is_array( $video_urls ) && count( $video_urls ) ) {
			$video_urls = VideoCarouselHelper::get_video_url( $video_urls );
			update_post_meta( $slider_id, '_video_urls', $video_urls );

			if ( count( $video_urls ) ) {
				$sanitize_urls = wp_list_pluck( $video_urls, 'url' );
				update_post_meta( $slider_id, '_video_url', implode( ',', $sanitize_urls ) );
			}

			return;
		}
		$urls = $data['_video_url'] ?? '';
		if ( $urls ) {
			$urls          = is_string( $urls ) ? explode( ',', $urls ) : $urls;
			$sanitize_urls = [];
			if ( is_array( $urls ) ) {
				foreach ( $urls as $url ) {
					if ( filter_var( $url, FILTER_VALIDATE_URL ) ) {
						$sanitize_urls[] = $url;
					}
				}
			}
			update_post_meta( $slider_id, '_video_url', implode( ',', $sanitize_urls ) );
		}
	}

	/**
	 * Register view
	 *
	 * @param  array $views  Registered views.
	 *
	 * @return array
	 */
	public function view( array $views ): array {
		$views['video-carousel'] = new View();

		return $views;
	}
}
