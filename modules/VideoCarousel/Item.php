<?php

namespace CarouselSlider\Modules\VideoCarousel;

use CarouselSlider\Abstracts\Data;

/**
 * Item class
 */
class Item extends Data {
	/**
	 * Default data
	 *
	 * @var string[]
	 */
	protected $defaults = [
		'provider'  => '',
		'url'       => '',
		'video_id'  => '',
		'thumbnail' => '',
	];

	/**
	 * Class constructor
	 *
	 * @param array $data Video item data.
	 */
	public function __construct( array $data = [] ) {
		$this->data = wp_parse_args( $data, $this->defaults );
	}

	/**
	 * Get video provider
	 *
	 * @return string
	 */
	public function get_provider(): string {
		return $this->get_prop( 'provider' );
	}

	/**
	 * Get video id
	 *
	 * @return string|int youtube, vimeo or integer value for self hosted video.
	 */
	public function get_video_id() {
		return $this->get_prop( 'video_id' );
	}

	/**
	 * Get video url
	 *
	 * @return string
	 */
	public function get_url(): string {
		return $this->get_prop( 'url' );
	}

	/**
	 * Get video embed url
	 *
	 * @return string
	 */
	public function get_embed_url(): string {
		if ( 'youtube' === $this->get_provider() ) {
			return sprintf( '//youtube.com/embed/%s?autoplay=1', $this->get_video_id() );
		}
		if ( 'vimeo' === $this->get_provider() ) {
			return sprintf( '//player.vimeo.com/video/%s?autoplay=1', $this->get_video_id() );
		}

		return '//about:blank';
	}

	/**
	 * Get video thumbnail
	 *
	 * @param string $size Image size.
	 *
	 * @return string
	 */
	public function get_thumbnail_url( string $size = 'large' ): string {
		$thumbnail = $this->get_prop( 'thumbnail' );

		return $thumbnail[ $size ] ?? '';
	}
}
