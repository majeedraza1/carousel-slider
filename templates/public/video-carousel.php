<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( count( $urls ) < 1 ) {
	return;
}
?>
<div class="carousel-slider-outer carousel-slider-outer-videos carousel-slider-outer-<?php echo $id; ?>">
	<?php carousel_slider_inline_style( $id ); ?>
    <div id="id-<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>"
         data-slide_type="<?php echo esc_attr( $slide_type ); ?>"
         data-owl_carousel='<?php echo json_encode( $owl_options ); ?>'
         data-magnific_popup='<?php echo json_encode( $magnific_popup ); ?>'>
		<?php
		foreach ( $urls as $url ) {
			?>
            <div class="carousel-slider-item-video">
                <div class="carousel-slider-video-wrapper">
                    <a class="magnific-popup" href="<?php echo $url['url']; ?>">
                        <div class="carousel-slider-video-play-icon"></div>
                        <div class="carousel-slider-video-overlay"></div>
                        <img class="owl-lazy" data-src="<?php echo $url['thumbnail']['large']; ?>"/>
                    </a>
                </div>
            </div>
			<?php
		}
		?>
    </div>
</div>
