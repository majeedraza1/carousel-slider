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
	foreach ( $content_sliders as $slide_id => $slide ):

		// Slide Content
		$_slide_heading     = isset( $slide['slide_heading'] ) ? $slide['slide_heading'] : '';
		$_slide_description = isset( $slide['slide_description'] ) ? $slide['slide_description'] : '';
		// Slide Style
		$_content_alignment = ! empty( $slide['content_alignment'] ) ? esc_attr( $slide['content_alignment'] ) : 'left';
		$_heading_font_size = ! empty( $slide['heading_font_size'] ) ? esc_attr( $slide['heading_font_size'] ) : '60px';
		$_heading_color     = ! empty( $slide['heading_color'] ) ? esc_attr( $slide['heading_color'] ) : '#ffffff';
		$_heading_bg_color  = ! empty( $slide['heading_background_color'] ) ? esc_attr( $slide['heading_background_color'] ) : '';
		$_desc_font_size    = ! empty( $slide['description_font_size'] ) ? esc_attr( $slide['description_font_size'] ) : '24px';
		$_desc_color        = ! empty( $slide['description_color'] ) ? esc_attr( $slide['description_color'] ) : '#ffffff';
		$_desc_bg_color     = ! empty( $slide['description_background_color'] ) ? esc_attr( $slide['description_background_color'] ) : '';

		// Slide Background
		$_img_bg_position = ! empty( $slide['img_bg_position'] ) ? esc_attr( $slide['img_bg_position'] ) : 'center center';
		$_img_bg_size     = ! empty( $slide['img_bg_size'] ) ? esc_attr( $slide['img_bg_size'] ) : 'contain';
		$_bg_color        = ! empty( $slide['bg_color'] ) ? esc_attr( $slide['bg_color'] ) : '';
		$_img_id          = ! empty( $slide['img_id'] ) ? absint( $slide['img_id'] ) : 0;
		$_img_src         = wp_get_attachment_image_src( $_img_id, 'full' );
		$_have_img        = is_array( $_img_src );

		// Slide background style
		$canvas_style = '';
		$canvas_style .= 'background-repeat: no-repeat;';
		$canvas_style .= 'background-position: ' . $_img_bg_position . ';';
		$canvas_style .= 'background-size: ' . $_img_bg_size . ';';
		if ( $_have_img ) {
			$canvas_style .= 'background-image: url(' . $_img_src[0] . ')';
		}

		$content_inner_style = $_bg_color ? 'background-color: ' . $_bg_color . ';' : '';

		$content_style = '';
		if ( $_content_alignment == 'left' ) {
			$content_style = 'align-items: flex-start;';
		} elseif ( $_content_alignment == 'right' ) {
			$content_style = 'align-items: flex-end;';
		} else {
			$content_style = 'align-items: center;';
		}

		$html = '<div class="carousel-slider__content" id="slide-item-' . $id . '-' . $slide_id . '" style="' . $canvas_style . '">';
		$html .= '<div class="slide-content-inner" style="' . $content_inner_style . '">';
		$html .= '<div class="slide-content" style="' . $content_style . '">';

		// Slide heading
		$heading_style         = '';
		$heading_wrapper_style = '';
		$heading_style         .= 'font-size: ' . $_heading_font_size . ';';
		$heading_style         .= 'color: ' . $_heading_color . ';';
		if ( ! empty( $_heading_bg_color ) ) {
			$heading_wrapper_style .= 'background-color: ' . $_heading_bg_color . ';';
			$heading_wrapper_style .= 'padding: 0 1rem;';
		}

		if ( $_slide_heading ) {
			$html .= '<div class="heading">';
			$html .= '<div class="heading-title-wrapper" style="' . $heading_wrapper_style . '">';
			$html .= '<h2 class="heading-title" style="' . $heading_style . '">' . wp_kses_post( $_slide_heading ) . "</h2>";
			$html .= '</div>';
			$html .= '</div>';
		}

		// Slide description
		$desc_style         = '';
		$desc_wrapper_style = '';
		$desc_style         .= 'font-size: ' . $_desc_font_size . ';';
		$desc_style         .= 'color: ' . $_desc_color . ';';
		if ( ! empty( $_desc_bg_color ) ) {
			$desc_wrapper_style .= 'background-color: ' . $_desc_bg_color . ';';
			$desc_wrapper_style .= 'padding: 0 1rem;';
		}

		if ( $_slide_description ) {
			$html .= '<div class="description">';
			$html .= '<div class="description-title-wrapper" style="' . $desc_wrapper_style . '">';
			$html .= '<h3 class="description-title" style="' . $desc_style . '">' . wp_kses_post( $_slide_description ) . "</h3>";
			$html .= '</div>';
			$html .= '</div>';
		}

		$html .= '</div>'; // .slide-content
		$html .= '</div>'; // .slide-content-inner
		$html .= '</div>'; // .carousel-slider__content

		echo apply_filters( 'carousel_slider_content', $html, $slide_id, $slide );
	endforeach;
	?>

</div>