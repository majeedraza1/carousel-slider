<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$options_array = array(
	'id' 							=> 'id-' . $id,
	'class' 						=> 'owl-carousel carousel-slider',
	// General
	'data-slide-type' 				=> 'image-carousel-url',
	'data-margin' 					=> $margin_right,
	'data-slide-by' 				=> $slide_by,
	'data-loop' 					=> $inifnity_loop,
	'data-lazy-load' 				=> 'false',
	// Navigation
	'data-nav' 						=> $navigation,
	'data-dots' 					=> $pagination,
	'data-nav-previous-icon' 		=> '<svg class="carousel-slider-nav-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="48" viewBox="0 0 11 28"><path d="M9.8 8.5c0 0.1-0.1 0.3-0.2 0.4l-6.1 6.1 6.1 6.1c0.1 0.1 0.2 0.2 0.2 0.4s-0.1 0.3-0.2 0.4l-0.8 0.8c-0.1 0.1-0.2 0.2-0.4 0.2s-0.3-0.1-0.4-0.2l-7.3-7.3c-0.1-0.1-0.2-0.2-0.2-0.4s0.1-0.3 0.2-0.4l7.3-7.3c0.1-0.1 0.2-0.2 0.4-0.2s0.3 0.1 0.4 0.2l0.8 0.8c0.1 0.1 0.2 0.2 0.2 0.4z"/></svg>',
	'data-nav-next-icon' 			=> '<svg class="carousel-slider-nav-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="48" viewBox="0 0 9 28"><path d="M9.3 15c0 0.1-0.1 0.3-0.2 0.4l-7.3 7.3c-0.1 0.1-0.2 0.2-0.4 0.2s-0.3-0.1-0.4-0.2l-0.8-0.8c-0.1-0.1-0.2-0.2-0.2-0.4 0-0.1 0.1-0.3 0.2-0.4l6.1-6.1-6.1-6.1c-0.1-0.1-0.2-0.2-0.2-0.4s0.1-0.3 0.2-0.4l0.8-0.8c0.1-0.1 0.2-0.2 0.4-0.2s0.3 0.1 0.4 0.2l7.3 7.3c0.1 0.1 0.2 0.2 0.2 0.4z"/></svg>',
	// Video
	'data-video-width' 				=> 'false',
	'data-video-height' 			=> 'false',
	// Autoplay
	'data-autoplay' 				=> $auto_play,
	'data-autoplay-timeout' 		=> $autoplay_timeout,
	'data-autoplay-speed' 			=> $autoplay_speed,
	'data-autoplay-hover-pause' 	=> $stop_on_hover,
	// Responsive
	'data-colums' 					=> $items_desktop_large,
	'data-colums-desktop' 			=> $items,
	'data-colums-small-desktop' 	=> $items_desktop,
	'data-colums-tablet' 			=> $items_desktop_small,
	'data-colums-small-tablet' 		=> $items_tablet,
	'data-colums-mobile' 			=> $items_mobile,
);
?>
<style>
    #id-<?php echo $id; ?> .owl-dots .owl-dot span {
        background-color: <?php echo $nav_color; ?>
    }
    #id-<?php echo $id; ?> .owl-dots .owl-dot.active span,
    #id-<?php echo $id; ?> .owl-dots .owl-dot:hover span {
        background-color: <?php echo $nav_active_color; ?>
    }
    #id-<?php echo $id; ?> .carousel-slider-nav-icon {
        fill: <?php echo $nav_color; ?>;
    }
    #id-<?php echo $id; ?> .carousel-slider-nav-icon:hover {
        fill: <?php echo $nav_active_color; ?>;
    }
</style>
<div <?php echo $this->array_to_data( $options_array ); ?>>
    <?php echo do_shortcode($content); ?>
</div><!-- .carousel-slider -->
