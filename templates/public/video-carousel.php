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
    <div <?php echo join( " ", $this->carousel_options( $id ) ); ?>>
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
