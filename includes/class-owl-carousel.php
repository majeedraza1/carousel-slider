<?php

class Carousel_Slider_Owl_Carousel {

	public static function settings( array $settings ) {
		$setting = $settings['settings'];

		$owl_setting = array(
			'stagePadding'       => $setting['stage_padding'],
			'nav'                => ( 'never' != $setting['arrow'] ),
			'dots'               => ( 'never' != $setting['bullet'] ),
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

		$owl_setting['responsive'] = array(
			'300'  => array(
				'items' => $setting['mobile'],
			),
			'600'  => array(
				'items' => $setting['small_tablet'],
			),
			'768'  => array(
				'items' => $setting['tablet'],
			),
			'993'  => array(
				'items' => $setting['desktop'],
			),
			'1200' => array(
				'items' => $setting['wide_screen'],
			),
			'1921' => array(
				'items' => $setting['full_hd'],
			),
		);

		$owl_setting['navText'] = apply_filters( 'carousel_slider_nav_text', array(
			'<svg class="carousel-slider-nav-icon" viewBox="0 0 20 20"><path d="M14 5l-5 5 5 5-1 2-7-7 7-7z"></path></svg>',
			'<svg class="carousel-slider-nav-icon" viewBox="0 0 20 20"><path d="M6 15l5-5-5-5 1-2 7 7-7 7z"></path></svg>',
		) );

		return $owl_setting;
	}
}