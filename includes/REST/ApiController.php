<?php

namespace CarouselSlider\REST;

use CarouselSlider\Traits\ApiResponse;
use WP_REST_Controller;

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 * ApiController Class
 *
 * @package CarouselSlider\REST
 */
class ApiController extends WP_REST_Controller {
	use ApiResponse;

	/**
	 * The namespace of this controller's route.
	 *
	 * @var string
	 */
	protected $namespace = 'carousel-slider/v1';

	/**
	 * Generate pagination metadata
	 *
	 * @param int $total_items Total available items.
	 * @param int $per_page Items to show per page.
	 * @param int $current_page The current page.
	 *
	 * @return array
	 */
	protected function get_pagination_data( $total_items = 0, $per_page = 20, $current_page = 1 ): array {
		$current_page = max( intval( $current_page ), 1 );
		$per_page     = max( intval( $per_page ), 1 );
		$total_items  = intval( $total_items );

		return [
			'total_items'  => $total_items,
			'per_page'     => $per_page,
			'current_page' => $current_page,
			'total_pages'  => ceil( $total_items / $per_page ),
		];
	}
}
