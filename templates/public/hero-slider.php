<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$content_sliders  = get_post_meta( $id, '_content_slider', true );
$settings         = get_post_meta( $id, '_content_slider_settings', true );
$_lazy_load_image = get_post_meta( $id, '_lazy_load_image', true );
$_be_lazy         = in_array( $_lazy_load_image, array( 'on', 'off' ) ) ? $_lazy_load_image : 'on';

?>
<div class="carousel-slider-outer carousel-slider-outer-contents carousel-slider-outer-<?php echo $id; ?>">
    <div <?php echo join( " ", $this->carousel_options( $id ) ); ?>>
		<?php
		foreach (
			$content_sliders

			as $slide_id => $slide
		):

			$html = '';

			$_link_type   = isset( $slide['link_type'] ) && in_array( $slide['link_type'],
				array( 'full', 'button' ) ) ? $slide['link_type'] : 'full';
			$_slide_link  = ! empty( $slide['slide_link'] ) ? esc_url( $slide['slide_link'] ) : '';
			$_link_target = ! empty( $slide['link_target'] ) && in_array( $slide['link_target'],
				array( '_self', '_blank' ) ) ? esc_url( $slide['link_target'] ) : '_self';
			if ( $_link_type == 'full' && carousel_slider_is_url( $_slide_link ) ) {
				$html .= '<a class="carousel-slider-hero__cell" href="' . $_slide_link . '" target="' . $_link_target . '">';
			} else {
				$html .= '<div class="carousel-slider-hero__cell">';
			}

			// Slide Background
			$_img_bg_position = ! empty( $slide['img_bg_position'] ) ? esc_attr( $slide['img_bg_position'] ) : 'center center';
			$_img_bg_size     = ! empty( $slide['img_bg_size'] ) ? esc_attr( $slide['img_bg_size'] ) : 'contain';
			$_bg_color        = ! empty( $slide['bg_color'] ) ? esc_attr( $slide['bg_color'] ) : '';
			$_img_id          = ! empty( $slide['img_id'] ) ? absint( $slide['img_id'] ) : 0;
			$_img_src         = wp_get_attachment_image_src( $_img_id, 'full' );
			$_have_img        = is_array( $_img_src );

			// Slide background
			$_slide_bg_style = '';
			$_slide_bg_style .= 'background-position: ' . $_img_bg_position . ';';
			$_slide_bg_style .= 'background-size: ' . $_img_bg_size . ';';
			if ( $_have_img && $_be_lazy == 'off' ) {
				$_slide_bg_style .= 'background-image: url(' . $_img_src[0] . ');';
			}
			if ( ! empty( $_bg_color ) ) {
				$_slide_bg_style .= 'background-color: ' . $_bg_color . ';';
			}

			// Background class
			$_slide_bg_class = 'carousel-slider-hero__cell__background carousel-slider-hero-ken-in';

			if ( $_be_lazy == 'on' ) {
				$html .= '<div class="' . $_slide_bg_class . ' owl-lazy" data-src="' . $_img_src[0] . '" id="slide-item-' . $id . '-' . $slide_id . '" style="' . $_slide_bg_style . '"></div>';
			} else {
				$html .= '<div class="' . $_slide_bg_class . '" id="slide-item-' . $id . '-' . $slide_id . '" style="' . $_slide_bg_style . '"></div>';
			}

			$_content_alignment = ! empty( $slide['content_alignment'] ) ? esc_attr( $slide['content_alignment'] ) : 'left';
			$_cell_inner_class  = 'carousel-slider-hero__cell__inner';
			if ( $_content_alignment == 'left' ) {
				$_cell_inner_class .= ' carousel-slider--v-position-middle carousel-slider--text-left';
			} elseif ( $_content_alignment == 'right' ) {
				$_cell_inner_class .= ' carousel-slider--v-position-middle carousel-slider--text-right';
			} else {
				$_cell_inner_class .= ' carousel-slider--v-position-middle carousel-slider--text-center';
			}
			$html .= '<div class="' . $_cell_inner_class . '">';

			// Background Overlay
			if ( ! empty( $_bg_color ) ) {
				$_bg_overlay_style = 'background-color: ' . $_bg_color . ';';

				$html .= '<div class="carousel-slider-hero__cell__background_overlay" style="' . $_bg_overlay_style . '"></div>';
			}

			$html .= '<div class="carousel-slider-hero__cell__content">';

			// Slide Heading
			$_slide_heading = isset( $slide['slide_heading'] ) ? $slide['slide_heading'] : '';

			$html .= '<div class="carousel-slider-hero__cell__heading">';
			$html .= wp_kses_post( $_slide_heading );
			$html .= '</div>'; // .carousel-slider-hero__cell__heading

			$_slide_description = isset( $slide['slide_description'] ) ? $slide['slide_description'] : '';

			$html .= '<div class="carousel-slider-hero__cell__description">';
			$html .= wp_kses_post( $_slide_description );
			$html .= '</div>'; // .carousel-slider-hero__cell__content

			$html .= '<div class="carousel-slider-hero__cell__button"></div>';

			$html .= '</div>'; // .carousel-slider-hero__cell__content
			$html .= '</div>'; // .carousel-slider-hero__cell__inner

			if ( $_link_type == 'full' && carousel_slider_is_url( $_slide_link ) ) {
				$html .= '</a>'; // .carousel-slider-hero__cell
			} else {
				$html .= '</div>'; // .carousel-slider-hero__cell
			}

			echo apply_filters( 'carousel_slider_content', $html, $slide_id, $slide );
		endforeach;
		?>
    </div>
</div>
