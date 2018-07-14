<?php

namespace CarouselSlider\Modules\VideoCarousel;

use CarouselSlider\Abstracts\AbstractView;
use CarouselSlider\Supports\Utils;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class View extends AbstractView {

	/**
	 * Render element.
	 * Generates the final HTML on the frontend.
	 */
	public function render() {
		$video_urls = $this->get_meta( '_video_url' );
		$video_urls = array_filter( explode( ',', $video_urls ) );
		$urls       = $this->get_video_url( $video_urls );

		$this->set_total_slides( count( $urls ) );

		$_html = $this->slider_wrapper_start();

		foreach ( $urls as $url ) {
			$html = '<div class="carousel-slider-item-video">';
			$html .= '<div class="carousel-slider-video-wrapper">';
			$html .= '<a class="magnific-popup" href="' . $url['url'] . '">';
			$html .= '<div class="carousel-slider-video-play-icon"></div>';
			$html .= '<div class="carousel-slider-video-overlay"></div>';
			$html .= '<img class="owl-lazy" data-src="' . $url['thumbnail']['large'] . '"/>';
			$html .= '</a>';
			$html .= '</div>';
			$html .= '</div>';

			$_html .= apply_filters( 'carousel_slider/view/video', $html, $url );
		}

		$_html .= $this->slider_wrapper_end();

		return $_html;
	}

	/**
	 * @param $video_urls
	 *
	 * @return array
	 */
	public function get_video_url( array $video_urls ) {
		$_url = array();
		foreach ( $video_urls as $video_url ) {
			if ( ! Utils::is_url( $video_url ) ) {
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
}
