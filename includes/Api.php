<?php

namespace CarouselSlider;

use WP_Error;

/**
 * Carousel Slider API.
 *
 * Carousel Slider API handler class is responsible for communicating with Carousel Slider
 * remote servers retrieving templates data and to send uninstall feedback.
 *
 * @since 2.1.0
 */
class Api {
	const BASE_URL         = 'https://api.carousel-slider.com/v1';
	const PRIVACY_URL      = 'https://carousel-slider.com/privacy-policy';
	const GO_PRO_URL       = 'https://carousel-slider.com/?utm_source=wp-menu&utm_campaign=gopro&utm_medium=wp-dash';
	const PRO_SUPPORT_URL  = 'https://carousel-slider.com/?utm_source=wp-menu&utm_campaign=pro-support&utm_medium=wp-dash';
	const FREE_SUPPORT_URL = 'https://wordpress.org/support/plugin/carousel-slider';

	/**
	 * Send remote request
	 *
	 * @param string $endpoint The REST endpoint.
	 * @param array  $data The data to be sent.
	 *
	 * @return array|WP_Error
	 */
	public static function send_request( string $endpoint, array $data = [] ) {
		$endpoint = self::BASE_URL . '/' . ltrim( $endpoint, '/' );
		$data     = wp_parse_args(
			$data,
			[
				'api_version' => CAROUSEL_SLIDER_VERSION,
				'site_lang'   => get_bloginfo( 'language' ),
				'wp_version'  => get_bloginfo( 'version' ),
				'site_url'    => esc_url( home_url() ),
			]
		);

		return wp_remote_post(
			$endpoint,
			[
				'method'      => 'POST',
				'timeout'     => 30,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => false,
				'body'        => $data,
			]
		);
	}

	/**
	 * Send Feedback.
	 * Fires a request to Carousel Slider server with the feedback data.
	 *
	 * @param string $feedback_key Feedback key.
	 * @param string $feedback_text Feedback text.
	 *
	 * @return array The response of the request.
	 * @since 2.1.0
	 */
	public static function send_deactivation_feedback( $feedback_key, $feedback_text ) {
		return self::send_request(
			'feedback',
			[
				'feedback_key' => $feedback_key,
				'feedback'     => $feedback_text,
			]
		);
	}

	/**
	 * Send tracking to remote site.
	 *
	 * @param array $data The tracking data.
	 *
	 * @return array|WP_Error The response of the request.
	 * @since 2.1.0
	 */
	public static function send_tracking_data( array $data ) {
		return self::send_request( 'tracker', $data );
	}
}
