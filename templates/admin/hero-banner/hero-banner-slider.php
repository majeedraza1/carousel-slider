<?php

use CarouselSlider\Modules\HeroCarousel\HeroCarouselAdmin;

defined( 'ABSPATH' ) || exit;
?>
<div data-id="open" id="section_content_carousel" class="shapla-toggle shapla-toggle--stroke"
	 style="display: <?php echo $slide_type != 'hero-banner-slider' ? 'none' : 'block'; ?>">
    <span class="shapla-toggle-title">
        <?php esc_html_e( 'Hero Banner Slider', 'carousel-slider' ); ?>
    </span>
	<div class="shapla-toggle-inner">
		<div class="shapla-toggle-content">
			<button class="button carousel-slider__add-slide" data-post-id="<?php echo $post->ID; ?>">Add Slide</button>
			<div id="carouselSliderContentInside">
				<?php
				$content_sliders = get_post_meta( $post->ID, '_content_slider', true );

				if ( is_array( $content_sliders ) && count( $content_sliders ) > 0 ) {
					$total_sliders = count( $content_sliders );
					foreach ( $content_sliders as $slide_num => $content_slider ) {

						?>
						<div class="shapla-toggle shapla-toggle--normal" data-id="closed">
							<div class="shapla-toggle-title">
								<?php printf( '%s %s', esc_html__( 'Slide', 'carousel-slider' ), $slide_num + 1 ); ?>
							</div>
							<div class="shapla-toggle-inner">
								<div class="shapla-toggle-content">

									<div class="carousel_slider__slide_actions">

										<button class="button carousel_slider__delete_slide"
												data-post-id="<?php echo $post->ID; ?>"
												data-slide-pos="<?php echo $slide_num; ?>"
												title="<?php esc_html_e( 'Delete current slide',
													'carousel-slider' ); ?>"
										>
											<svg class="icon icon-trash" width="20" height="26">
												<use xlink:href="#icon-trash"></use>
											</svg>
										</button>

										<?php if ( $slide_num !== 0 ): ?>
											<?php if ( $total_sliders > 2 && $slide_num > 1 ): ?>
												<button class="button carousel_slider__move_top"
														data-post-id="<?php echo $post->ID; ?>"
														data-slide-pos="<?php echo $slide_num; ?>"
														title="<?php esc_html_e( 'Move Slide to Top',
															'carousel-slider' ); ?>"
												>
													<svg class="icon icon-trash" width="20" height="26">
														<use xlink:href="#icon-angle-up-alt"></use>
													</svg>
												</button>
											<?php endif; ?>
											<button class="button carousel_slider__move_up"
													data-post-id="<?php echo $post->ID; ?>"
													data-slide-pos="<?php echo $slide_num; ?>"
													title="<?php esc_html_e( 'Move Slide Up', 'carousel-slider' ); ?>"
											>
												<svg class="icon icon-trash" width="20" height="26">
													<use xlink:href="#icon-angle-up"></use>
												</svg>
											</button>
										<?php endif; ?>

										<?php if ( $slide_num !== ( $total_sliders - 1 ) ): ?>
											<button class="button carousel_slider__move_down"
													data-post-id="<?php echo $post->ID; ?>"
													data-slide-pos="<?php echo $slide_num; ?>"
													title="<?php esc_html_e( 'Move Slide Down', 'carousel-slider' ); ?>"
											>
												<svg class="icon icon-trash" width="20" height="26">
													<use xlink:href="#icon-angle-down"></use>
												</svg>
											</button>
											<?php if ( $total_sliders > 2 && $slide_num < ( $total_sliders - 2 ) ): ?>
												<button class="button carousel_slider__move_bottom"
														data-post-id="<?php echo $post->ID; ?>"
														data-slide-pos="<?php echo $slide_num; ?>"
														title="<?php esc_html_e( 'Move Slide to Bottom',
															'carousel-slider' ); ?>"
												>
													<svg class="icon icon-trash" width="20" height="26">
														<use xlink:href="#icon-angle-down-alt"></use>
													</svg>
												</button>
											<?php endif; ?>
										<?php endif; ?>

									</div>
									<div class="clear" style="width: 100%; margin-bottom: 1rem; height: 1px;"></div>

									<div class="shapla-section shapla-tabs shapla-tabs--stroke">
										<div class="shapla-tab-inner">

											<ul class="shapla-nav shapla-clearfix">
												<li>
													<a href="#carousel-slider-tab-background"><?php esc_html_e( 'Slide Background',
															'carousel-slider' ); ?></a>
												</li>
												<li>
													<a href="#carousel-slider-tab-content"><?php esc_html_e( 'Slide Content',
															'carousel-slider' ); ?></a>
												</li>
												<li>
													<a href="#carousel-slider-tab-link"><?php esc_html_e( 'Slide Link',
															'carousel-slider' ); ?></a>
												</li>
												<li>
													<a href="#carousel-slider-tab-style"><?php esc_html_e( 'Slide Style',
															'carousel-slider' ); ?></a>
												</li>
											</ul>

											<?php
											include CAROUSEL_SLIDER_TEMPLATES . '/admin/hero-banner/tab-content.php';
											include CAROUSEL_SLIDER_TEMPLATES . '/admin/hero-banner/tab-link.php';
											include CAROUSEL_SLIDER_TEMPLATES . '/admin/hero-banner/tab-background.php';
											include CAROUSEL_SLIDER_TEMPLATES . '/admin/hero-banner/tab-style.php';
											?>

										</div>
									</div>

									<div class="clear"></div>
								</div>
							</div>
						</div>
						<?php
					}
				}
				?>
			</div>

			<?php HeroCarouselAdmin::content_meta_box_settings( $post->ID ); ?>
		</div>
	</div>
</div>

<svg width="1" height="1" style="display: none;">
	<symbol id="icon-trash" viewBox="0 0 20 20">
		<path
			d="M12 4h3c0.55 0 1 0.45 1 1v1h-13v-1c0-0.55 0.45-1 1-1h3c0.23-1.14 1.29-2 2.5-2s2.27 0.86 2.5 2zM8 4h3c-0.21-0.58-0.85-1-1.5-1s-1.29 0.42-1.5 1zM4 7h11v10c0 0.55-0.45 1-1 1h-9c-0.55 0-1-0.45-1-1v-10zM7 16v-7h-1v7h1zM10 16v-7h-1v7h1zM13 16v-7h-1v7h1z"></path>
	</symbol>
	<symbol id="icon-angle-down" viewBox="0 0 20 20">
		<path d="M5 6l5 5 5-5 2 1-7 7-7-7z"></path>
	</symbol>
	<symbol id="icon-angle-up" viewBox="0 0 20 20">
		<path d="M15 14l-5-5-5 5-2-1 7-7 7 7z"></path>
	</symbol>
	<symbol id="icon-angle-down-alt" viewBox="0 0 20 20">
		<path d="M9 2h2v12l4-4 2 1-7 7-7-7 2-1 4 4v-12z"></path>
	</symbol>
	<symbol id="icon-angle-up-alt" viewBox="0 0 20 20">
		<path d="M11 18h-2v-12l-4 4-2-1 7-7 7 7-2 1-4-4v12z"></path>
	</symbol>
</svg>
