<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$_nav_color        = get_post_meta( $id, '_nav_color', true );
$_nav_active_color = get_post_meta( $id, '_nav_active_color', true );
$_video_width      = $this->get_meta( $id, '_video_width' );
$_video_height     = $this->get_meta( $id, '_video_height' );
$_video_urls       = array_filter( explode( ',', $this->get_meta( $id, '_video_url' ) ) );

if ( count( $_video_urls ) < 1 ) {
	return;
}
?>
<div class="carousel-slider-outer carousel-slider-outer-videos carousel-slider-outer-<?php echo $id; ?>">
	<?php carousel_slider_inline_style( $id ); ?>
    <div <?php echo join( " ", $this->carousel_options( $id ) ); ?>>
		<?php
		foreach ( $_video_urls as $url ) {
			echo $this->video_url( $url );
		}
		?>
    </div>
</div>
