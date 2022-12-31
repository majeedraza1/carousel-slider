<?php

namespace CarouselSlider\CLI;

use CarouselSlider\Helper;
use CarouselSlider\Modules\HeroCarousel\Template as TemplateHeroCarousel;
use CarouselSlider\Modules\ImageCarousel\Template as TemplateImageCarousel;
use CarouselSlider\Modules\ImageCarousel\TemplateUrl as TemplateUrlImageCarousel;
use CarouselSlider\Modules\PostCarousel\Template as TemplatePostCarousel;
use CarouselSlider\Modules\ProductCarousel\Template as TemplateProductCarousel;
use CarouselSlider\Modules\VideoCarousel\Template as TemplateVideoCarousel;
use WP_CLI;
use WP_CLI_Command;

defined( 'ABSPATH' ) || exit;

/**
 * Command class
 * The command line interface class handle plugin cli functionality
 *
 * @package CarouselSlider/CLI
 */
class Command extends WP_CLI_Command {
	/**
	 * Create post carousel
	 *
	 * @param  array  $args  The arguments.
	 * @param  string $slider_title  The slider title.
	 *
	 * @return int
	 */
	protected static function create_post_carousel( array $args, string $slider_title ): int {
		$post_query      = ! empty( $args['post-query'] ) ? $args['post-query'] : 'latest_posts';
		$date_from       = ! empty( $args['date-from'] ) ? $args['date-from'] : '';
		$date_to         = ! empty( $args['date-to'] ) ? $args['date-to'] : '';
		$post_categories = ! empty( $args['post-categories'] ) ? $args['post-categories'] : '';
		$post_tags       = ! empty( $args['post-tags'] ) ? $args['post-tags'] : '';
		$post_in         = ! empty( $args['post-in'] ) ? $args['post-in'] : '';
		$post_args       = array(
			'_created_via'      => 'wp-cli',
			'_post_query_type'  => $post_query,
			'_post_date_after'  => $date_from,
			'_post_date_before' => $date_to,
			'_post_categories'  => $post_categories,
			'_post_tags'        => $post_tags,
			'_post_in'          => $post_in,
		);

		return TemplatePostCarousel::create( $slider_title, $post_args );
	}

	/**
	 * Display Carousel Slider Information
	 *
	 * @subcommand info
	 */
	public function info() {
		WP_CLI::success( 'Welcome to the Carousel Slider WP-CLI Extension!' );
		WP_CLI::line( '' );
		WP_CLI::line( '- Carousel Slider Version: ' . CAROUSEL_SLIDER_VERSION );
		WP_CLI::line( '- Carousel Slider Directory: ' . CAROUSEL_SLIDER_PATH );
		WP_CLI::line( '- Carousel Slider Public URL: ' . CAROUSEL_SLIDER_URL );
		WP_CLI::line( '' );
	}

	/**
	 * Create Slider
	 *
	 * ## OPTIONS
	 *
	 * <name>
	 * : The name of the slider to create.
	 *
	 * [--type=<type>]
	 * : Carousel slider slider type.
	 * ---
	 * default: image-carousel
	 * options:
	 *  - image-carousel
	 *  - image-carousel-url
	 *  - post-carousel
	 *  - product-carousel
	 *  - video-carousel
	 *  - hero-banner-slider
	 * ---
	 *
	 * [--post-query=<post-query>]
	 * : Post carousel query type.
	 * ---
	 * default: latest_posts
	 * options:
	 *  - latest_posts
	 *  - date_range
	 *  - post_categories
	 *  - post_tags
	 *  - specific_posts
	 * ---
	 *
	 * [--date-from=<date-from>]
	 * : Post carousel query starting date.
	 *
	 * [--date-to=<date-to>]
	 * : Post carousel query starting date.
	 *
	 * [--post-categories=<post-categories>]
	 * : Comma separated post category id
	 *
	 * [--post-tags=<post-tags>]
	 * : Comma separated post tag id
	 *
	 * [--post-in=<post-in>]
	 * : Comma separated post id
	 *
	 * ## EXAMPLES
	 *
	 * wp carousel-slider create_slider 'Post Carousel - LP' --type='post-carousel'
	 * wp carousel-slider create_slider 'Post Carousel - LP' --type='post-carousel' --post-query='latest_posts'
	 * wp carousel-slider create_slider 'Post Carousel - SP' --type='post-carousel' --post-query='specific_posts'
	 * wp carousel-slider create_slider 'Post Carousel - DR' --type='post-carousel' --post-query='date_range'
	 * wp carousel-slider create_slider 'Post Carousel - PC' --type='post-carousel' --post-query='post_categories'
	 * wp carousel-slider create_slider 'Post Carousel - PT' --type='post-carousel' --post-query='post_tags'
	 *
	 * @param  mixed $args  The arguments.
	 * @param  mixed $assoc_args  The additional arguments.
	 *
	 * @throws WP_CLI\ExitException The Exception.
	 */
	public function create_slider( $args, $assoc_args ) {
		list( $slider_title ) = $args;
		$type                 = ! empty( $assoc_args['type'] ) ? $assoc_args['type'] : 'image-carousel';
		$slider_id            = 0;

		if ( 'image-carousel' === $type ) {
			$slider_id = TemplateImageCarousel::create(
				$slider_title,
				array(
					'_created_via' => 'wp-cli',
				)
			);
		}

		if ( 'image-carousel-url' === $type ) {
			$slider_id = TemplateUrlImageCarousel::create(
				$slider_title,
				array(
					'_created_via' => 'wp-cli',
				)
			);
		}

		if ( 'video-carousel' === $type ) {
			$slider_id = TemplateVideoCarousel::create(
				$slider_title,
				array(
					'_created_via' => 'wp-cli',
				)
			);
		}

		if ( 'post-carousel' === $type ) {
			$slider_id = self::create_post_carousel( $assoc_args, (string) $slider_title );
		}

		if ( ! $slider_id ) {
			WP_CLI::error( __( 'Could not create slider.', 'carousel-slider' ) );

			return;
		}

		$response = sprintf(
			/* translators: 1: the slider id, 2: the slider title */
			__( '#%1$s - %2$s has been created successfully.', 'carousel-slider' ),
			$slider_id,
			$slider_title
		);
		WP_CLI::success( $response );
	}

	/**
	 * Create sliders for testing
	 */
	public function create_sliders() {
		$ids     = [];
		$sliders = [
			[
				'type'  => 'hero-banner-slider',
				'title' => 'Test: Hero Carousel',
				'args'  => [],
			],
			[
				'type'  => 'image-carousel',
				'title' => 'Test: Image Carousel - Gallery',
				'args'  => [],
			],
			[
				'type'  => 'image-carousel-url',
				'title' => 'Test: Image Carousel - URL',
				'args'  => [],
			],
			[
				'type'  => 'video-carousel',
				'title' => 'Test: Video Carousel - Youtube',
				'args'  => [],
			],
			// Post Carousel.
			[
				'type'  => 'post-carousel',
				'title' => 'Test: Post Carousel - Latest Posts',
				'args'  => [ '_post_query_type' => 'latest_posts' ],
			],
			[
				'type'  => 'post-carousel',
				'title' => 'Test: Post Carousel - Date Range',
				'args'  => [ '_post_query_type' => 'date_range' ],
			],
			[
				'type'  => 'post-carousel',
				'title' => 'Test: Post Carousel - Categories',
				'args'  => [ '_post_query_type' => 'post_categories' ],
			],
			[
				'type'  => 'post-carousel',
				'title' => 'Test: Post Carousel - Tags',
				'args'  => [ '_post_query_type' => 'post_tags' ],
			],
			[
				'type'  => 'post-carousel',
				'title' => 'Test: Post Carousel - IDs',
				'args'  => [ '_post_query_type' => 'specific_posts' ],
			],
			// Product Carousel.
			[
				'type'  => 'product-carousel',
				'title' => 'Test: Product Carousel - IDs',
				'args'  => [ '_product_query_type' => 'specific_products' ],
			],
			[
				'type'  => 'product-carousel',
				'title' => 'Test: Product Carousel - Categories',
				'args'  => [ '_product_query_type' => 'product_categories' ],
			],
			[
				'type'  => 'product-carousel',
				'title' => 'Test: Product Carousel - Tags',
				'args'  => [ '_product_query_type' => 'product_tags' ],
			],
			[
				'type'  => 'product-carousel',
				'title' => 'Test: Product Carousel - Recent Products',
				'args'  => [
					'_product_query_type' => 'query_product',
					'_product_query'      => 'recent',
				],
			],
			[
				'type'  => 'product-carousel',
				'title' => 'Test: Product Carousel - Featured Products',
				'args'  => [
					'_product_query_type' => 'query_product',
					'_product_query'      => 'featured',
				],
			],
			[
				'type'  => 'product-carousel',
				'title' => 'Test: Product Carousel - Sale Products',
				'args'  => [
					'_product_query_type' => 'query_product',
					'_product_query'      => 'sale',
				],
			],
			[
				'type'  => 'product-carousel',
				'title' => 'Test: Product Carousel - Best Selling Products',
				'args'  => [
					'_product_query_type' => 'query_product',
					'_product_query'      => 'best_selling',
				],
			],
			[
				'type'  => 'product-carousel',
				'title' => 'Test: Product Carousel - Top Rated Products',
				'args'  => [
					'_product_query_type' => 'query_product',
					'_product_query'      => 'top_rated',
				],
			],
			[
				'type'  => 'product-carousel',
				'title' => 'Test: Product Carousel - Product Categories List',
				'args'  => [
					'_product_query_type' => 'query_product',
					'_product_query'      => 'product_categories_list',
				],
			],
		];

		foreach ( $sliders as $slider ) {
			switch ( $slider['type'] ) {
				case 'image-carousel':
					$ids[] = TemplateImageCarousel::create( $slider['title'], $slider['args'] );
					WP_CLI::line( "{$slider['title']} has been created successfully." );
					break;
				case 'image-carousel-url':
					$ids[] = TemplateUrlImageCarousel::create( $slider['title'], $slider['args'] );
					WP_CLI::line( "{$slider['title']} has been created successfully." );
					break;
				case 'video-carousel':
					$ids[] = TemplateVideoCarousel::create( $slider['title'], $slider['args'] );
					WP_CLI::line( "{$slider['title']} has been created successfully." );
					break;
				case 'post-carousel':
					$ids[] = TemplatePostCarousel::create( $slider['title'], $slider['args'] );
					WP_CLI::line( "{$slider['title']} has been created successfully." );
					break;
				case 'hero-banner-slider':
					$ids[] = TemplateHeroCarousel::create( $slider['title'], $slider['args'] );
					WP_CLI::line( "{$slider['title']} has been created successfully." );
					break;
				case 'product-carousel':
					$ids[] = TemplateProductCarousel::create( $slider['title'], $slider['args'] );
					WP_CLI::line( "{$slider['title']} has been created successfully." );
					break;
			}
		}

		Helper::create_test_page( $ids );

		WP_CLI::success( 'All test sliders has been created successfully.' );
	}

	/**
	 * Delete a slider by slider id
	 *
	 * ## OPTIONS
	 *
	 * <id>
	 * : The slider id.
	 *
	 * @param  array|mixed $args  The arguments.
	 */
	public function delete_slider( $args ) {
		list( $id ) = $args;

		if ( wp_delete_post( $id, true ) ) {
			WP_CLI::success( "#{$id} has been deleted successfully." );
		}
	}

	/**
	 * Delete all sliders
	 */
	public function delete_sliders() {
		$sliders = get_posts(
			[
				'post_type'   => 'carousels',
				'post_status' => 'any',
				'numberposts' => - 1,
			]
		);
		foreach ( $sliders as $slider ) {
			if ( wp_delete_post( $slider->ID, true ) ) {
				WP_CLI::line( "Carousel Slider #{$slider->ID} has been deleted successfully." );
			}
		}
		WP_CLI::success( 'Carousel Slider: all sliders has been deleted successfully.' );
	}

	/**
	 * Delete all slider settings
	 */
	public function delete_options() {
		$options = [
			'wp_carousel_free_version',
			'wp_carousel_free_db_version',
			'carousel_slider_settings',
			'carousel_slider_allow_tracking',
			'carousel_slider_tracking_notice',
			'carousel_slider_tracking_skipped',
			'widget_widget_carousel_slider',
		];
		foreach ( $options as $option ) {
			if ( delete_option( $option ) ) {
				WP_CLI::line( "Option '{$option}' has been deleted successfully." );
			}
		}
		WP_CLI::success( 'Carousel Slider: all options has been deleted successfully.' );
	}
}
