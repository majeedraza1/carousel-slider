<?php

use CarouselSlider\Abstracts\SliderSetting;
use CarouselSlider\Helper;
use CarouselSlider\Modules\PostCarousel\Item;

defined( 'ABSPATH' ) || die;

/**
 * The global variable that are available to use here
 *
 * This template can be overridden by copying it to yourtheme/carousel-slider/loop/post-carousel.php.
 *
 * @global SliderSetting $setting Slider setting object.
 * @global Item $object The item object.
 */
?>
<div class="carousel-slider__post">
	<div class="carousel-slider__post-content">
		<div class="carousel-slider__post-header">
			<?php
			Helper::print_unescaped_internal_string(
				$object->get_thumbnail_html( $setting->get_image_size(), $setting->lazy_load_image() )
			);
			?>
			<a class="carousel-slider__post-title" href="<?php echo esc_url( $object->get_permalink() ); ?>">
				<h2><?php echo esc_html( $object->get_title() ); ?></h2>
			</a>
		</div>
		<div class="carousel-slider__post-excerpt">
			<?php Helper::print_unescaped_internal_string( $object->get_summery() ); ?>
		</div>
		<footer class="carousel-slider__post-meta">
			<div class="carousel-slider__post-publication-meta">
				<div class="carousel-slider__post-details-info">
					<div class="carousel-slider__post-author">
						<a class="carousel-slider__post-author-link"
						   href="<?php echo esc_url( $object->get_author_posts_url() ); ?>">
							<?php echo esc_html( $object->get_author_display_name() ); ?>
						</a>
					</div>
					<time class="carousel-slider__post-publication-date"
						  datetime="<?php echo esc_attr( $object->get_formatted_modified_date( 'c' ) ); ?>">
						<?php echo esc_attr( $object->get_formatted_modified_date() ); ?>
					</time>
				</div>
			</div>
			<?php if ( $object->has_category() ) { ?>
				<div class="carousel-slider__post-category">
					<a class="carousel-slider__post-category-link"
					   href="<?php echo esc_url( get_category_link( $object->get_primary_category()->term_id ) ); ?>">
						<?php echo esc_html( $object->get_primary_category()->name ); ?>
					</a>
				</div>
			<?php } ?>
		</footer>
	</div>
</div>
