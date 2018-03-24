<?php

class Carousel_Slider_Owl_Carousel {

	public static function settings( array $settings ) {
		$setting = $settings['settings'];

		$owl_setting = array(
			'stagePadding'       => $setting['stage_padding'],
			'nav'                => ( 'off' != $setting['arrow'] ),
			'dots'               => ( 'off' != $setting['bullet'] ),
			'margin'             => $setting['gutter'],
			'loop'               => $setting['loop'],
			'autoplay'           => $setting['autoplay'],
			'autoplayTimeout'    => $setting['autoplay_timeout'],
			'autoplaySpeed'      => $setting['autoplay_speed'],
			'autoplayHoverPause' => $setting['autoplay_hover_pause'],
			'slideBy'            => $setting['arrow_steps'],
			'lazyLoad'           => $setting['lazy_load_image'],
			'autoWidth'          => $setting['auto_width'],
			'items'              => 1,
		);

		if ( isset( $settings['total_slide'] ) && $settings['total_slide'] <= 1 ) {
			$owl_setting['mouseDrag'] = false;
			$owl_setting['touchDrag'] = false;
			$owl_setting['nav']       = false;
			$owl_setting['dots']      = false;
			$owl_setting['autoplay']  = false;
		}

		$_responsive = array();
		foreach ( $setting['responsive'] as $item ) {
			$items   = intval( $item['items'] );
			$_config = array( 'items' => $items );
			if ( isset( $settings['total_slide'] ) && $settings['total_slide'] <= $items ) {
				$_config['mouseDrag'] = false;
				$_config['touchDrag'] = false;
				$_config['nav']       = false;
				$_config['dots']      = false;
				$_config['autoplay']  = false;
			}

			$_responsive[ $item['breakpoint'] ] = $_config;
		}
		$owl_setting['responsive'] = $_responsive;

		$owl_setting['navText'] = apply_filters( 'carousel_slider_nav_text', array(
			'<svg class="carousel-slider-nav-icon" viewBox="0 0 20 20"><path d="M14 5l-5 5 5 5-1 2-7-7 7-7z"></path></svg>',
			'<svg class="carousel-slider-nav-icon" viewBox="0 0 20 20"><path d="M6 15l5-5-5-5 1-2 7 7-7 7z"></path></svg>',
		) );

		return apply_filters( 'carousel_slider_owl_settings', $owl_setting );
	}
}