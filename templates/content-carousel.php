<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

carousel_slider_inline_style( $id );
$content_sliders = get_post_meta( $id, '_content_slider', true );
?>
<div <?php echo join( " ", $this->carousel_options( $id ) ); ?>>
	<?php
	foreach ( $content_sliders as $slide ):

		$_img_bg_position = ! empty( $slide['img_bg_position'] ) ? esc_attr( $slide['img_bg_position'] ) : 'center center';
		$_img_bg_size     = ! empty( $slide['img_bg_size'] ) ? esc_attr( $slide['img_bg_size'] ) : 'contain';
		$_bg_color        = ! empty( $slide['bg_color'] ) ? esc_attr( $slide['bg_color'] ) : '#f1f1f1';
		$_img_id          = ! empty( $slide['img_id'] ) ? absint( $slide['img_id'] ) : 0;
		$_img_src         = wp_get_attachment_image_src( $_img_id, 'full' );
		$_have_img        = is_array( $_img_src );

		// Slide background style
		$canvas_style = 'background-repeat: no-repeat;';
		$canvas_style .= 'background-position: ' . $_img_bg_position . ';';
		$canvas_style .= 'background-size: ' . $_img_bg_size . ';';
		$canvas_style .= 'background-color: ' . $_bg_color . ';';
		if ( $_have_img ) {
			$canvas_style .= 'background-image: url(' . $_img_src[0] . ')';
		}

		echo '<div class="carousel-slider__content" style="' . $canvas_style . '">';
		echo wpautop( $slide['content'] );
		echo '</div>';
	endforeach;
	?>

</div>