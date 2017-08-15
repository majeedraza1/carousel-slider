<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$_nav_color         = get_post_meta( $id, '_nav_color', true );
$_nav_active_color  = get_post_meta( $id, '_nav_active_color', true );
$_video_width 		= $this->get_meta( $id, '_video_width' );
$_video_height 		= $this->get_meta( $id, '_video_height' );
$_video_urls 		= array_filter( explode( ',', $this->get_meta( $id, '_video_url' )));

if ( count($_video_urls) < 1 ) {
	return;
}
?>
<style>
    #id-<?php echo $id; ?> .owl-dots .owl-dot span {
        background-color: <?php echo $_nav_color; ?>
    }
    #id-<?php echo $id; ?> .owl-dots .owl-dot.active span,
    #id-<?php echo $id; ?> .owl-dots .owl-dot:hover span {
        background-color: <?php echo $_nav_active_color; ?>
    }
    #id-<?php echo $id; ?> .carousel-slider-nav-icon {
        fill: <?php echo $_nav_color; ?>;
    }
    #id-<?php echo $id; ?> .carousel-slider-nav-icon:hover {
        fill: <?php echo $_nav_active_color; ?>;
    }
</style>
<div <?php echo join(" ", $this->carousel_options($id)); ?>>
	<?php
		foreach ($_video_urls as $url) {
			echo $this->video_url($url);
		}
	?>
</div>