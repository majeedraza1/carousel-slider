<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$_nav_color        = get_post_meta( $id, '_nav_color', true );
$_nav_active_color = get_post_meta( $id, '_nav_active_color', true );
$_video_width      = carousel_slider_get_meta( $id, '_video_width', '560' );
$_video_height     = carousel_slider_get_meta( $id, '_video_height', '315' );
$_video_urls       = array_filter( explode( ',', carousel_slider_get_meta( $id, '_video_url' ) ) );
$slide_options     = join( " ", carousel_slider_array_to_attribute( $slide_options ) );

if ( count( $_video_urls ) < 1 ) {
	return;
}
?>
<div class="carousel-slider-outer carousel-slider-outer-videos carousel-slider-outer-<?php echo $id; ?>">
	<?php carousel_slider_inline_style( $id ); ?>
    <div <?php echo $slide_options; ?>>
		<?php
		foreach ( $_video_urls as $url ) {
			echo $this->video_url( $url );
		}
		?>
    </div>
</div>
