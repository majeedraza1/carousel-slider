<?php

if ( ! class_exists('Carousel_Slider_Activation') ):

class Carousel_Slider_Activation
{
	/**
	 * Script that should load upon plugin activation
	 */
	public static function activate()
	{
		$version = get_option( 'carousel_slider_version' );

		if ( $version == false ) {
			self::update_meta_160();
		}

		// Add plugin version to database
		update_option( 'carousel_slider_version', CAROUSEL_SLIDER_VERSION );

		// Flush the rewrite rules on activation
		flush_rewrite_rules();
	}

	/**
	 * Update meta for prior to version 1.6.0
	 */
	public static function update_meta_160()
	{
		$carousels = get_posts( array(
			'post_type' 	=> 'carousels',
			'post_status' 	=> 'any',
		) );

		if ( count( $carousels ) > 0 ) {
			foreach ($carousels as $carousel) {

				$id = $carousel->ID;
				$_items_desktop = get_post_meta( $id, '_items', true );
				$_lazy_load 	= get_post_meta( $id, '_lazy_load_image', true );
				$_lazy_load 	= $_lazy_load == 'on' ? 'on' : 'off';
				
				update_post_meta( $id, '_lazy_load_image', $_lazy_load );
				update_post_meta( $id, '_items_desktop', $_items_desktop );
				update_post_meta( $id, '_slide_type', 'image-carousel' );
				update_post_meta( $id, '_video_width', '560' );
				update_post_meta( $id, '_video_height', '315' );
			}
		}
	}
}

endif;