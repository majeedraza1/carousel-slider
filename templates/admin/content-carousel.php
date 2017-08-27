<div data-id="open" id="section_content_carousel" class="shapla-toggle shapla-toggle--stroke"
     style="display: <?php echo $slide_type != 'content-carousel' ? 'none' : 'block'; ?>">
	<span class="shapla-toggle-title">
		<?php esc_html_e( 'Content Carousel/Slider', 'carousel-slider' ); ?>
	</span>
    <div class="shapla-toggle-inner">
        <div class="shapla-toggle-content">
            <button class="button carousel-slider__add-slide">Add Slide</button>
            <div id="carouselSliderContentInside">

            </div>
        </div>
    </div>
</div>