<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$images_ids = array_filter( explode( ',', get_post_meta( $id, '_wpdh_image_ids', true ) ) );
if ( count( $images_ids ) < 1 ) {
	return;
}
$_image_target            = get_post_meta( $id, '_image_target', true );
$_image_target            = empty( $_image_target ) ? '_self' : $_image_target;
$_image_size              = get_post_meta( $id, '_image_size', true );
$_lazy_load_image         = get_post_meta( $id, '_lazy_load_image', true );
$_show_attachment_title   = get_post_meta( $id, '_show_attachment_title', true );
$_show_attachment_caption = get_post_meta( $id, '_show_attachment_caption', true );
$_show_lightbox           = get_post_meta( $id, '_image_lightbox', true );

?>
<div class="carousel-slider-outer carousel-slider-outer-images carousel-slider-outer-<?php echo $id; ?>">
	<?php carousel_slider_inline_style( $id ); ?>
	<div <?php echo join( " ", $this->carousel_options( $id ) ); ?>>
		<?php
		foreach ( $images_ids as $image_id ):

			$_post = get_post( $image_id );
			do_action( 'carousel_slider_image_gallery_loop', $_post );

			$image_title       = $_post->post_title;
			$image_caption     = $_post->post_excerpt;
			$image_description = $_post->post_content;
			$image_alt_text    = trim( strip_tags( get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ) );
			$image_link_url    = get_post_meta( $image_id, "_carousel_slider_link_url", true );

			echo '<div class="carousel-slider__item">';

			$title   = sprintf( '<h4 class="title">%1$s</h4>', esc_html( $image_title ) );
			$caption = sprintf( '<p class="caption">%1$s</p>', esc_html( $image_caption ) );

			if ( $_show_attachment_title == 'on' && $_show_attachment_caption == 'on' ) {

				$full_caption = sprintf( '<div class="carousel-slider__caption">%1$s%2$s</div>', $title, $caption );

			} elseif ( $_show_attachment_title == 'on' ) {

				$full_caption = sprintf( '<div class="carousel-slider__caption">%s</div>', $title );

			} elseif ( $_show_attachment_caption == 'on' ) {

				$full_caption = sprintf( '<div class="carousel-slider__caption">%s</div>', $caption );

			} else {
				$full_caption = '';
			}

			if ( $_lazy_load_image == 'on' ) {

				$image_src = wp_get_attachment_image_src( $image_id, $_image_size );
				$image     = sprintf(
					'<img class="owl-lazy" data-src="%1$s" width="%2$s" height="%3$s" alt="%4$s" />',
					$image_src[0],
					$image_src[1],
					$image_src[2],
					$image_alt_text
				);

			} else {
				$image = wp_get_attachment_image( $image_id, $_image_size, false, array( 'alt' => $image_alt_text ) );
			}

			if ( $_show_lightbox == 'on' ) {
				wp_enqueue_script( 'magnific-popup' );
				$image_src = wp_get_attachment_image_src( $image_id, 'full' );
				echo sprintf( '<a href="%1$s" class="magnific-popup">%2$s%3$s</a>', esc_url( $image_src[0] ), $image, $full_caption, $id );
			} elseif ( filter_var( $image_link_url, FILTER_VALIDATE_URL ) ) {

				echo sprintf( '<a href="%1$s" target="%4$s">%2$s%3$s</a>', esc_url( $image_link_url ), $image, $full_caption, $_image_target );

			} else {

				echo $image . $full_caption;
			}

			echo '</div>';

		endforeach;
		?>
	</div><!-- #id-## -->
</div>
