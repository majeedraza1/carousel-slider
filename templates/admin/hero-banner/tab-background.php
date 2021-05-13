<?php

use CarouselSlider\Modules\HeroCarousel\HeroCarouselHelper;
use CarouselSlider\Supports\MetaBoxForm;

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

$metaBox           = new MetaBoxForm;
$upload_link       = esc_url( get_upload_iframe_src( 'image', $post->ID ) );
$_all_bg_position  = HeroCarouselHelper::background_position();
$_all_bg_size      = HeroCarouselHelper::background_size();
$slide_num         = $slide_num ?? 0;
$_img_bg_position  = ! empty( $content_slider['img_bg_position'] ) ? esc_attr( $content_slider['img_bg_position'] ) : 'center center';
$_img_bg_size      = ! empty( $content_slider['img_bg_size'] ) ? esc_attr( $content_slider['img_bg_size'] ) : 'cover';
$_bg_color         = ! empty( $content_slider['bg_color'] ) ? esc_attr( $content_slider['bg_color'] ) : '';
$_img_id           = ! empty( $content_slider['img_id'] ) ? absint( $content_slider['img_id'] ) : 0;
$_ken_burns_effect = ! empty( $content_slider['ken_burns_effect'] ) ? esc_attr( $content_slider['ken_burns_effect'] ) : '';
$_bg_overlay       = ! empty( $content_slider['bg_overlay'] ) ? esc_attr( $content_slider['bg_overlay'] ) : '';
$_img_src          = wp_get_attachment_image_src( $_img_id, 'full' );
$_have_img         = is_array( $_img_src );

// Canvas style
$canvas_style = 'background-repeat: no-repeat;';
$canvas_style .= 'background-position: ' . $_img_bg_position . ';';
$canvas_style .= 'background-size: ' . $_img_bg_size . ';';
$canvas_style .= 'background-color: ' . $_bg_color . ';';
if ( $_have_img ) {
	$canvas_style .= 'background-image: url(' . $_img_src[0] . ')';
}
?>
<div id="carousel-slider-tab-background" class="shapla-tab tab-background">
	<div class="slide_bg_wrapper">
		<div class="slide-media-left">
			<div class="slide_thumb">
				<div class="content_slide_canvas" style="<?php echo $canvas_style; ?>"></div>
				<span class="delete-bg-img<?php echo ! $_have_img ? ' hidden' : ''; ?>"
					  title="<?php esc_html_e( 'Delete the background image for this slide', 'carousel-slider' ); ?>">&times;</span>
			</div>
		</div>
		<div class="slide-media-right">
			<?php
			$metaBox->upload_iframe( [
				'id'               => 'img_id',
				'class'            => 'background_image_id',
				'name'             => esc_html__( 'Background Image', 'carousel-slider' ),
				'input_attributes' => [
					'name'  => sprintf( "carousel_slider_content[%s][img_id]", $slide_num ),
					'value' => $_img_bg_position,
				],
			] );
			$metaBox->select( [
				'id'               => 'img_bg_position',
				'class'            => 'sp-input-text background_image_position',
				'name'             => esc_html__( 'Background Position', 'carousel-slider' ),
				'options'          => HeroCarouselHelper::background_position(),
				'input_attributes' => [
					'name'  => sprintf( "carousel_slider_content[%s][img_bg_position]", $slide_num ),
					'value' => $_img_bg_position,
				],
			] );
			$metaBox->select( [
				'id'               => 'img_bg_size',
				'class'            => 'sp-input-text background_image_size',
				'name'             => esc_html__( 'Background Size', 'carousel-slider' ),
				'options'          => HeroCarouselHelper::background_size(),
				'input_attributes' => [
					'name'  => sprintf( "carousel_slider_content[%s][img_bg_size]", $slide_num ),
					'value' => $_img_bg_size,
				],
			] );
			$metaBox->select( [
				'id'               => 'ken_burns_effect',
				'name'             => esc_html__( 'Ken Burns Effect', 'carousel-slider' ),
				'options'          => HeroCarouselHelper::ken_burns_effects(),
				'input_attributes' => [
					'name'  => sprintf( "carousel_slider_content[%s][ken_burns_effect]", $slide_num ),
					'value' => $_ken_burns_effect,
				],
			] );
			$metaBox->color( [
				'id'               => 'bg_color',
				'name'             => esc_html__( 'Background Color', 'carousel-slider' ),
				'std'              => 'rgba(255,255,255,0.5)',
				'input_attributes' => [
					'name'  => sprintf( "carousel_slider_content[%s][bg_color]", $slide_num ),
					'value' => $_bg_color,
				],
			] );
			$metaBox->color( [
				'id'               => 'bg_overlay',
				'name'             => esc_html__( 'Background Overlay', 'carousel-slider' ),
				'std'              => 'rgba(0,0,0,0.5)',
				'input_attributes' => [
					'name'  => sprintf( "carousel_slider_content[%s][bg_overlay]", $slide_num ),
					'value' => $_bg_overlay,
				],
			] );
			?>
		</div>

	</div>
</div>
<!-- .tab-background -->
