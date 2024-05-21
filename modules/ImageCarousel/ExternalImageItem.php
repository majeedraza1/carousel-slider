<?php

namespace CarouselSlider\Modules\ImageCarousel;

use CarouselSlider\Abstracts\Data;
use CarouselSlider\Helper;

/**
 * ExternalImageItem class
 *
 * @package CarouselSlider/Modules/ImageCarousel
 */
class ExternalImageItem extends Data {

	/**
	 * The image url
	 *
	 * @param  array $data  The data.
	 */
	public function __construct( array $data ) {
		$this->data = $data;
	}

	/**
	 * The image url.
	 *
	 * @return string
	 */
	public function get_image_url(): string {
		return $this->get_prop( 'url' );
	}

	/**
	 * Get title
	 *
	 * @return string
	 */
	public function get_title(): string {
		return $this->get_prop( 'title' );
	}

	/**
	 * Get caption
	 *
	 * @return string
	 */
	public function get_caption(): string {
		return $this->get_prop( 'caption' );
	}

	/**
	 * Get alt text
	 *
	 * @return string
	 */
	public function get_alt_text(): string {
		return $this->get_prop( 'alt' );
	}

	/**
	 * Get link
	 *
	 * @return string
	 */
	public function get_link_url(): string {
		return $this->get_prop( 'link_url' );
	}

	/**
	 * Get image html
	 *
	 * @param  bool $lazy  Load image lazily.
	 *
	 * @return string
	 */
	public function get_image_html( bool $lazy = true ): string {
		$attrs = [ 'alt' => esc_attr( $this->get_alt_text() ) ];
		if ( $lazy ) {
			if ( Helper::is_using_swiper() ) {
				$attrs['src']     = esc_url( $this->get_image_url() );
				$attrs['loading'] = 'lazy';
			} else {
				$attrs['class']    = 'owl-lazy';
				$attrs['data-src'] = esc_url( $this->get_image_url() );
			}
		} else {
			$attrs['src'] = esc_url( $this->get_image_url() );
		}

		return '<img ' . join( ' ', Helper::array_to_attribute( $attrs ) ) . ' />';
	}

	/**
	 * Get link start html
	 *
	 * @param  string $target  The target.
	 *
	 * @return string
	 */
	public function get_link_html_start( string $target = '_blank' ): string {
		$link_url = $this->get_link_url();
		if ( $link_url ) {
			return '<a href="' . esc_url( $link_url ) . '" target="' . esc_attr( $target ) . '">';
		}

		return '';
	}

	/**
	 * Get link end html
	 *
	 * @return string
	 */
	public function get_link_html_end(): string {
		$link_url = $this->get_link_url();
		if ( $link_url ) {
			return '</a>';
		}

		return '';
	}
}
