<div data-id="open" id="section_content_carousel" class="shapla-toggle shapla-toggle--stroke"
     style="display: <?php echo $slide_type != 'content-carousel' ? 'none' : 'block'; ?>">
	<span class="shapla-toggle-title">
		<?php esc_html_e( 'Content Carousel/Slider', 'carousel-slider' ); ?>
	</span>
    <div class="shapla-toggle-inner">
        <div class="shapla-toggle-content">
            <button class="button carousel-slider__add-slide" data-post-id="<?php echo $post->ID; ?>">Add Slide</button>
            <div id="carouselSliderContentInside">
				<?php
				$content_sliders = get_post_meta( $post->ID, '_content_slider', true );
				$num_to_word     = new Carousel_Slider_Number_To_Word;

				if ( is_array( $content_sliders ) && count( $content_sliders ) > 0 ) {
					foreach ( $content_sliders as $slide_num => $content_slider ) {
						$_to_word = str_replace( array( ' ', '-' ), '_', $num_to_word->convert( $slide_num ) );
						?>
                        <div style="margin-bottom: 1rem;">
							<?php
							wp_editor(
								$content_slider['content'],
								'carousel_slider_content_' . $_to_word,
								array(
									'textarea_name' => 'carousel_slider_content[' . $slide_num . '][content]',
									'textarea_rows' => 5,
								)
							);
							?>
                        </div>
						<?php
					}
				}
				?>
            </div>
        </div>
    </div>
</div>