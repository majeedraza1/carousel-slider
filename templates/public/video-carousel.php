<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( count( $urls ) < 1 ) {
	return;
}
$_lazy_load_image = get_post_meta( $id, '_lazy_load_image', true );
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
						<?php if ( $_lazy_load_image == 'on' ) { ?>
							<img class="owl-lazy" data-src="<?php echo $url['thumbnail']['large']; ?>" alt=""/>
						<?php } else { ?>
							<img src="<?php echo $url['thumbnail']['large']; ?>" alt=""/>
						<?php } ?>
					</a>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>
