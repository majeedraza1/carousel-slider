<?php
if ( ! class_exists( 'Carousel_Slider_Setting' ) ) {

	class Carousel_Slider_Setting {

		/**
		 * Get settings for given slider ID
		 *
		 * @param int $slider_id
		 *
		 * @return array
		 */
		public static function get( $slider_id ) {
			$meta = get_post_meta( $slider_id );
			if ( empty( $meta ) ) {
				return array();
			}

			$meta = array_map( function ( $setting ) {
				if ( is_serialized( $setting[0] ) ) {
					return unserialize( $setting[0] );
				}

				return $setting[0];
			}, $meta );

			$nav_visibility = array( 'never', 'hover', 'always' );
			$slide_type     = isset( $meta['_slide_type'] ) && in_array( $meta['_slide_type'],
				carousel_slider_slide_type() ) ? esc_attr( $meta['_slide_type'] ) : 'image-carousel';

			$settings             = array(
				'slide_id'    => $slider_id,
				'slide_type'  => $slide_type,
				'total_slide' => 0,
			);
			$settings['settings'] = array(
				// General
				'image_size'           => isset( $meta['_image_size'] ) ? esc_attr( $meta['_image_size'] ) : 'full',
				'gutter'               => isset( $meta['_margin_right'] ) ? intval( $meta['_margin_right'] ) : 10,
				'stage_padding'        => isset( $meta['_stage_padding'] ) ? intval( $meta['_stage_padding'] ) : 0,
				'lazy_load_image'      => self::checked( $meta['_lazy_load_image'] ),
				'loop'                 => self::checked( $meta['_inifnity_loop'] ),
				'auto_width'           => self::checked( $meta['_auto_width'] ),
				// Responsive
				'responsive'           => self::responsive( $slider_id ),
				// Automatic play
				'autoplay'             => self::checked( $meta['_autoplay'] ),
				'autoplay_hover_pause' => self::checked( $meta['_autoplay_pause'] ),
				'autoplay_timeout'     => self::number( $meta['_autoplay_timeout'], 5000 ),
				'autoplay_speed'       => self::number( $meta['_autoplay_speed'], 500 ),
				// Navigation
				'arrow'                => isset( $meta['_nav_button'] ) && in_array( $meta['_nav_button'],
					array( 'off', 'on', 'always' ) ) ? $meta['_nav_button'] : 'always',
				'arrow_steps'          => isset( $meta['_slide_by'] ) && 'page' == $meta['_slide_by'] ? 'page' : intval( $meta['_slide_by'] ),
				'arrow_position'       => isset( $meta['_arrow_position'] ) && 'outside' == $meta['_arrow_position'] ? 'outside' : 'inside',
				'arrow_size'           => isset( $meta['_arrow_size'] ) ? intval( $meta['_arrow_size'] ) : 48,
				'bullet'               => isset( $meta['_dot_nav'] ) && in_array( $meta['_dot_nav'],
					array( 'off', 'on', 'hover' ) ) ? $meta['_dot_nav'] : 'hover',
				'bullet_position'      => isset( $meta['_bullet_position'] ) ? esc_attr( $meta['_bullet_position'] ) : 'center',
				'bullet_size'          => isset( $meta['_bullet_size'] ) ? intval( $meta['_bullet_size'] ) : 10,
				'bullet_shape'         => isset( $meta['_bullet_shape'] ) && 'circle' == $meta['_bullet_shape'] ? 'circle' : 'square',
				'nav_color'            => isset( $meta['_nav_color'] ) ? carousel_slider_sanitize_color( $meta['_nav_color'] ) : carousel_slider_default_settings()->nav_color,
				'nav_active_color'     => isset( $meta['_nav_active_color'] ) ? carousel_slider_sanitize_color( $meta['_nav_active_color'] ) : carousel_slider_default_settings()->nav_active_color,
			);

			// Content Slider
			if ( 'hero-banner-slider' == $slide_type ) {
				$settings['hero_carousel'] = array(
					'content'  => isset( $meta['_content_slider'] ) ? $meta['_content_slider'] : array(),
					'settings' => isset( $meta['_content_slider_settings'] ) ? $meta['_content_slider_settings'] : array(),
				);
				$settings['total_slide']   = count( $settings['hero_carousel']['content'] );
			}

			// Video Carousel
			if ( 'video-carousel' == $slide_type ) {
				$video_url  = isset( $meta['_video_url'] ) ? esc_attr( $meta['_video_url'] ) : null;
				$video_urls = explode( ',', $video_url );
				$video_urls = is_array( $video_urls ) ? array_map( 'esc_url', $video_urls ) : array();

				$settings['video_carousel'] = array(
					'video_url' => $video_urls,
				);
				$settings['total_slide']    = count( $video_urls );
			}

			// Image Carousel
			if ( 'image-carousel' == $slide_type ) {
				$image_ids = isset( $meta['_wpdh_image_ids'] ) ? esc_attr( $meta['_wpdh_image_ids'] ) : null;
				$image_ids = explode( ',', $image_ids );
				$image_ids = is_array( $image_ids ) ? array_map( 'intval', $image_ids ) : array();

				$settings['image_carousel'] = array(
					'image_ids'          => $image_ids,
					'image_target'       => isset( $meta['_image_target'] ) && '_blank' == $meta['_image_target'] ? '_blank' : '_self',
					'show_image_title'   => self::checked( $meta['_show_attachment_title'] ),
					'show_image_caption' => self::checked( $meta['_show_attachment_caption'] ),
					'image_lightbox'     => self::checked( $meta['_image_lightbox'] ),
				);
				$settings['total_slide']    = count( $image_ids );
			}

			// Image Carousel from URL
			if ( 'image-carousel-url' == $slide_type ) {
				$images_urls                    = isset( $meta['_images_urls'] ) ? $meta['_images_urls'] : array();
				$settings['image_carousel_url'] = array(
					'images_urls'        => $images_urls,
					'show_image_title'   => self::checked( $meta['_show_attachment_title'] ),
					'show_image_caption' => self::checked( $meta['_show_attachment_caption'] ),
					'image_target'       => self::choices( $meta['_image_target'], array(
						'_self',
						'_blank'
					), '_self' ),
				);
				$settings['total_slide']        = count( $images_urls );
			}

			// Post Carousel
			if ( 'post-carousel' == $slide_type ) {
				$settings['post_carousel'] = array(
					'post_categories'  => isset( $meta['_post_categories'] ) ? $meta['_post_categories'] : null,
					'post_tags'        => isset( $meta['_post_tags'] ) ? $meta['_post_tags'] : null,
					'post_in'          => isset( $meta['_post_in'] ) ? $meta['_post_in'] : null,
					'post_query_type'  => isset( $meta['_post_query_type'] ) ? $meta['_post_query_type'] : 'latest_posts',
					'post_date_after'  => isset( $meta['_post_date_after'] ) ? $meta['_post_date_after'] : null,
					'post_date_before' => isset( $meta['_post_date_before'] ) ? $meta['_post_date_before'] : null,
					'post_order'       => isset( $meta['_post_order'] ) ? $meta['_post_order'] : 'DESC',
					'post_orderby'     => isset( $meta['_post_orderby'] ) ? $meta['_post_orderby'] : 'ID',
					'post_height'      => isset( $meta['_post_height'] ) ? intval( $meta['_post_height'] ) : 450,
				);
				$settings['total_slide']   = 4;
			}

			// Product Carousel
			if ( 'product-carousel' == $slide_type ) {
				$_color_title       = carousel_slider_default_settings()->product_title_color;
				$_color_button      = carousel_slider_default_settings()->product_button_bg_color;
				$_color_button_text = carousel_slider_default_settings()->product_button_text_color;

				$settings['product_carousel'] = array(
					'product_query_type'        => ! empty( $meta['_product_query_type'] ) ? esc_attr( $meta['_product_query_type'] ) : 'query_porduct',
					'product_query'             => isset( $meta['_product_query'] ) ? esc_attr( $meta['_product_query'] ) : 'featured',
					'product_categories'        => isset( $meta['_product_categories'] ) ? esc_attr( $meta['_product_categories'] ) : null,
					'product_tags'              => isset( $meta['_product_tags'] ) ? esc_attr( $meta['_product_tags'] ) : null,
					'products_per_page'         => isset( $meta['_products_per_page'] ) ? intval( $meta['_products_per_page'] ) : 12,
					'product_title'             => self::checked( $meta['_product_title'] ),
					'product_rating'            => self::checked( $meta['_product_rating'] ),
					'product_price'             => self::checked( $meta['_product_price'] ),
					'product_cart_button'       => self::checked( $meta['_product_cart_button'] ),
					'product_onsale'            => self::checked( $meta['_product_onsale'] ),
					'product_wishlist'          => self::checked( $meta['_product_wishlist'] ),
					'product_quick_view'        => self::checked( $meta['_product_quick_view'] ),
					'product_title_color'       => self::color( $meta['_product_title_color'], $_color_title ),
					'product_button_bg_color'   => self::color( $meta['_product_button_bg_color'], $_color_button ),
					'product_button_text_color' => self::color( $meta['_product_button_text_color'], $_color_button_text ),
				);
				$settings['total_slide']      = 4;
			}

			$settings['class'] = self::css_class( $settings );

			return $settings;
		}

		public static function navigation( $slider_id ) {
			$_nav_button      = get_post_meta( $slider_id, '_nav_button', true );
			$_nav_button      = in_array( $_nav_button, array( 'off', 'on', 'always' ) ) ? $_nav_button : 'always';
			$_slide_by        = get_post_meta( $slider_id, '_slide_by', true );
			$_slide_by        = ( 'page' == $_slide_by ) ? 'page' : intval( $_slide_by );
			$_arrow_position  = get_post_meta( $slider_id, '_arrow_position', true );
			$_arrow_position  = ( 'outside' == $_arrow_position ) ? 'outside' : 'inside';
			$_arrow_size      = get_post_meta( $slider_id, '_arrow_size', true );
			$_dot_nav         = get_post_meta( $slider_id, '_dot_nav', true );
			$_dot_nav         = in_array( $_dot_nav, array( 'off', 'on', 'hover' ) ) ? $_dot_nav : 'hover';
			$_bullet_position = get_post_meta( $slider_id, '_bullet_position', true );
			$_bullet_size     = get_post_meta( $slider_id, '_bullet_size', true );
			$_bullet_shape    = get_post_meta( $slider_id, '_bullet_shape', true );
			$_bullet_shape    = ( 'circle' == $_bullet_shape ) ? 'circle' : 'square';

			$default = array(
				'arrow'            => $_nav_button,
				'arrow_steps'      => $_slide_by,
				'arrow_position'   => $_arrow_position,
				'arrow_size'       => ! empty( $_arrow_size ) ? intval( $_arrow_size ) : 48,
				'bullet'           => $_dot_nav,
				'bullet_position'  => ! empty( $_bullet_position ) ? esc_attr( $_bullet_position ) : 'center',
				'bullet_size'      => isset( $_bullet_size ) ? intval( $_bullet_size ) : 10,
				'bullet_shape'     => $_bullet_shape,
				'nav_color'        => isset( $meta['_nav_color'] ) ? carousel_slider_sanitize_color( $meta['_nav_color'] ) : carousel_slider_default_settings()->nav_color,
				'nav_active_color' => isset( $meta['_nav_active_color'] ) ? carousel_slider_sanitize_color( $meta['_nav_active_color'] ) : carousel_slider_default_settings()->nav_active_color,
			);
		}

		/**
		 * @param $slider_id
		 *
		 * @return array
		 */
		public static function responsive( $slider_id ) {
			$_items               = get_post_meta( $slider_id, '_items', true );
			$_responsive_settings = get_post_meta( $slider_id, '_responsive_settings', true );

			if ( empty( $_items ) && empty( $_responsive_settings ) ) {
				return array(
					array( 'breakpoint' => 300, 'items' => 1, ),
					array( 'breakpoint' => 768, 'items' => 2, ),
					array( 'breakpoint' => 1024, 'items' => 3, ),
					array( 'breakpoint' => 1216, 'items' => 4, ),
				);
			}

			if ( is_array( $_responsive_settings ) ) {
				return $_responsive_settings;
			}

			$responsive = array(
				array(
					'breakpoint' => 300,
					'items'      => intval( get_post_meta( $slider_id, '_items_portrait_mobile', true ) ),
				),
				array(
					'breakpoint' => 600,
					'items'      => intval( get_post_meta( $slider_id, '_items_small_portrait_tablet', true ) ),
				),
				array(
					'breakpoint' => 769,
					'items'      => intval( get_post_meta( $slider_id, '_items_portrait_tablet', true ) ),
				),
				array(
					'breakpoint' => 1024,
					'items'      => intval( get_post_meta( $slider_id, '_items_small_desktop', true ) ),
				),
				array(
					'breakpoint' => 1216,
					'items'      => intval( get_post_meta( $slider_id, '_items_desktop', true ) ),
				),
				array(
					'breakpoint' => 1408,
					'items'      => intval( $_items ),
				),
			);

			return $responsive;
		}

		/**
		 * Get css class for slider
		 *
		 * @param array $settings
		 *
		 * @return array
		 */
		private static function css_class( $settings ) {
			$setting = $settings['settings'];
			$class   = array( 'carousel-slider', 'owl-carousel' );

			// Arrows position
			if ( 'inside' == $setting['arrow_position'] ) {
				$class[] = 'arrows-inside';
			} else {
				$class[] = 'arrows-outside';
			}

			// Arrows visibility
			if ( 'always' == $setting['arrow'] ) {
				$class[] = 'arrows-visible-always';
			} elseif ( 'off' == $setting['arrow'] || 'never' == $setting['arrow'] ) {
				$class[] = 'arrows-hidden';
			} else {
				$class[] = 'arrows-visible-hover';
			}

			// Dots visibility
			if ( $setting['bullet'] == 'on' || 'always' == $setting['bullet'] ) {
				$class[] = 'dots-visible-always';
			} elseif ( $setting['bullet'] == 'off' || 'never' == $setting['bullet'] ) {
				$class[] = 'dots-hidden';
			} else {
				$class[] = 'dots-visible-hover';
			}

			// Dots position
			if ( $setting['bullet_position'] == 'left' ) {
				$class[] = 'dots-left';
			} elseif ( $setting['bullet_position'] == 'right' ) {
				$class[] = 'dots-right';
			} else {
				$class[] = 'dots-center';
			}

			// Dots shape
			if ( $setting['bullet_shape'] == 'circle' ) {
				$class[] = 'dots-circle';
			} else {
				$class[] = 'dots-square';
			}

			return apply_filters( 'carousel_slider_slide_class', array_unique( $class ) );
		}

		/**
		 * @param $value
		 * @param $default
		 * @param array $settings
		 *
		 * @return mixed
		 */
		private static function choices( $value, array $settings, $default = '' ) {
			if ( empty( $value ) ) {
				return $default;
			}

			return in_array( $value, $settings ) ? $value : $default;
		}

		/**
		 * @param $value
		 * @param int $default
		 *
		 * @return int|mixed|string
		 */
		private static function number( $value, $default = 0 ) {
			if ( empty( $value ) ) {
				return intval( $default );
			}

			return intval( $value );
		}

		/**
		 * @param $value
		 * @param $default
		 *
		 * @return mixed|string
		 */
		private static function color( $value, $default = '' ) {
			if ( empty( $value ) ) {
				return $default;
			}

			return carousel_slider_sanitize_color( $value );
		}

		/**
		 * @param mixed $value
		 *
		 * @return bool
		 */
		private static function checked( $value ) {
			if ( empty( $value ) ) {
				return false;
			}

			return in_array( $value, array( 'yes', 'on', '1', 1, true, 'true' ), true );
		}

		/**
		 * Sanitizes css dimensions.
		 *
		 * @param string $value The value to be sanitized.
		 *
		 * @return string
		 */
		public static function css_dimension( $value ) {
			// If the value is empty, return empty.
			if ( empty( $value ) ) {
				return '';
			}

			// Trim it.
			$value = trim( $value );

			// If the value is round, then return 50%.
			if ( 'round' === $value ) {
				$value = '50%';
			}

			// If auto, inherit or initial, return the value.
			if ( 'auto' === $value || 'initial' === $value || 'inherit' === $value ) {
				return $value;
			}

			// Return empty if there are no numbers in the value.
			if ( ! preg_match( '#[0-9]#', $value ) ) {
				return '';
			}

			// If we're using calc() then return the value.
			if ( false !== strpos( $value, 'calc(' ) ) {
				return $value;
			}

			// The raw value without the units.
			$raw_value = self::filter_number( $value );
			$unit_used = '';

			// An array of all valid CSS units. Their order was carefully chosen for this evaluation, don't mix it up!!!
			$units = array(
				'rem',
				'em',
				'ex',
				'%',
				'px',
				'cm',
				'mm',
				'in',
				'pt',
				'pc',
				'ch',
				'vh',
				'vw',
				'vmin',
				'vmax'
			);
			foreach ( $units as $unit ) {
				if ( false !== strpos( $value, $unit ) ) {
					$unit_used = $unit;
				}
			}

			// Hack for rem values.
			if ( 'em' === $unit_used && false !== strpos( $value, 'rem' ) ) {
				$unit_used = 'rem';
			}

			return $raw_value . $unit_used;
		}

		/**
		 * Filters numeric values.
		 *
		 * @param string $value The value to be sanitized.
		 *
		 * @return int|float
		 */
		public static function filter_number( $value ) {
			return filter_var( $value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
		}
	}
}