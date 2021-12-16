<?php

namespace CarouselSlider\Modules\VideoCarousel;

defined( 'ABSPATH' ) || exit;

/**
 * Helper class
 *
 * @package Modules/VideoCarousel
 */
class Helper {
	/**
	 * Get Youtube video ID from URL
	 *
	 * @param string $url The url string.
	 *
	 * @return false|string Youtube video ID or FALSE if not found
	 */
	public static function get_youtube_id_from_url( string $url ) {
		$parts = wp_parse_url( $url );
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
	 * @param string $url The url string.
	 *
	 * @return false|string Vimeo video ID or FALSE if not found
	 */
	public static function get_vimeo_id_from_url( string $url ) {
		$parts = wp_parse_url( $url );
		if ( isset( $parts['path'] ) ) {
			$path = explode( '/', trim( $parts['path'], '/' ) );

			return $path[ count( $path ) - 1 ];
		}

		return false;
	}

	/**
	 * Get video URL
	 *
	 * @param array $video_urls The video urls.
	 *
	 * @return array
	 */
	public static function get_video_url( array $video_urls ): array {
		$_url = [];
		foreach ( $video_urls as $video_url ) {
			if ( ! filter_var( $video_url, FILTER_VALIDATE_URL ) ) {
				continue;
			}
			$provider  = '';
			$video_id  = '';
			$thumbnail = '';
			if ( false !== strpos( $video_url, 'youtube.com' ) ) {
				$provider  = 'youtube';
				$video_id  = static::get_youtube_id_from_url( $video_url );
				$thumbnail = [
					'large'  => 'https://img.youtube.com/vi/' . $video_id . '/hqdefault.jpg',
					'medium' => 'https://img.youtube.com/vi/' . $video_id . '/mqdefault.jpg',
					'small'  => 'https://img.youtube.com/vi/' . $video_id . '/sddefault.jpg',
				];

			} elseif ( false !== strpos( $video_url, 'vimeo.com' ) ) {
				$provider  = 'vimeo';
				$video_id  = static::get_vimeo_id_from_url( $video_url );
				$response  = wp_remote_get( "https://vimeo.com/api/v2/video/$video_id.json" );
				$thumbnail = json_decode( wp_remote_retrieve_body( $response ), true );
				$thumbnail = [
					'large'  => $thumbnail[0]['thumbnail_large'] ?? null,
					'medium' => $thumbnail[0]['thumbnail_medium'] ?? null,
					'small'  => $thumbnail[0]['thumbnail_small'] ?? null,
				];
			}

			$_url[] = [
				'provider'  => $provider,
				'url'       => $video_url,
				'video_id'  => $video_id,
				'thumbnail' => $thumbnail,
			];
		}

		return $_url;
	}
}
