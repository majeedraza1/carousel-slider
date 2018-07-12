<?php

namespace CarouselSlider\Supports;

class MagnificPopup {

	/**
	 * @param array $config
	 *
	 * @return array
	 */
	public static function settings( array $config ) {
		$slide_type = $config['slide_type'];

		$setting = array(
			'delegate' => 'a.magnific-popup',
			'type'     => 'image',
		);

		if ( 'image-carousel' == $slide_type ) {
			$setting['gallery'] = array(
				'enabled' => true,
			);
			$setting['zoom']    = array(
				'enabled'  => true,
				'duration' => 300,
				'easing'   => 'ease-in-out',
			);
		}

		if ( 'product-carousel' == $slide_type ) {
			$setting['type'] = 'ajax';
		}

		if ( 'video-carousel' == $slide_type ) {
			$setting['type'] = 'iframe';
		}

		return $setting;
	}
}