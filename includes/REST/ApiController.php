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
}
