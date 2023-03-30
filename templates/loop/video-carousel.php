<?php

use CarouselSlider\Abstracts\SliderSetting;
use CarouselSlider\Helper;
use CarouselSlider\Modules\VideoCarousel\Item;

defined( 'ABSPATH' ) || die;

/**
 * The global variable that are available to use here
 *
 * This template can be overridden by copying it to yourtheme/carousel-slider/loop/video-carousel.php.
 *
 * @global SliderSetting $setting Slider setting object.
 * @global Item $object The video carousel item setting.
 */
$popup_args = [
	'class'          => 'magnific-popup',
	'href'           => esc_url( $object->get_url() ),
	'data-provider'  => esc_attr( $object->get_provider() ),
	'data-id'        => esc_attr( $object->get_video_id() ),
	'data-embed_url' => esc_url( $object->get_embed_url() ),
];
$lazy_class = $setting->is_using_swiper() ? 'swiper-lazy' : 'owl-lazy';
?>
<div class="carousel-slider-item-video">
	<div class="carousel-slider-video-wrapper">
		<a <?php Helper::print_unescaped_internal_string( join( ' ', Helper::array_to_attribute( $popup_args ) ) ); ?>>
			<div class="carousel-slider-video-play-icon"></div>
			<div class="carousel-slider-video-overlay"></div>
			<?php if ( $setting->lazy_load_image() ) { ?>
				<?php if ( Helper::is_using_swiper() ) { ?>
					<img  alt="" src="<?php echo esc_url( $object->get_thumbnail_url() ); ?>" loading="lazy">
				<?php } else { ?>
					<img class="<?php echo esc_attr( $lazy_class ); ?>" alt=""
						data-src="<?php echo esc_url( $object->get_thumbnail_url() ); ?>">
				<?php } ?>
			<?php } else { ?>
				<img src="<?php echo esc_url( $object->get_thumbnail_url() ); ?>" alt="">
			<?php } ?>
		</a>
	</div>
</div>
