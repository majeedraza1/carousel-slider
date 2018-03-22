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
		);


		$_responsive = array();
		foreach ( $setting['responsive'] as $item ) {
			$_responsive[ $item['breakpoint'] ] = array( 'items' => intval( $item['items'] ) );
		}
		$owl_setting['responsive'] = $_responsive;

		$owl_setting['navText'] = apply_filters( 'carousel_slider_nav_text', array(
			'<svg class="carousel-slider-nav-icon" viewBox="0 0 20 20"><path d="M14 5l-5 5 5 5-1 2-7-7 7-7z"></path></svg>',
			'<svg class="carousel-slider-nav-icon" viewBox="0 0 20 20"><path d="M6 15l5-5-5-5 1-2 7 7-7 7z"></path></svg>',
		) );

		return $owl_setting;
	}
}