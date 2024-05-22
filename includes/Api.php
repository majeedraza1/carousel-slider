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
	 * Log error data to system
	 *
	 * @param  WP_Error $response  Log error data.
	 *
	 * @return void
	 */
	protected static function log_wp_error( WP_Error $response ) {
		error_log(
			wp_json_encode(
				[
					'message'      => $response->get_error_message(),
					'code'         => $response->get_error_code(),
					'request_url'  => $response->get_all_error_data( 'debug_request_url' ),
					'request_args' => $response->get_all_error_data( 'debug_request_args' ),
				],
				JSON_PRETTY_PRINT
			)
		);
	}

	/**
	 * Filter remote response
	 *
	 * @param  string         $url  The request URL.
	 * @param  array          $args  The request arguments.
	 * @param  array|WP_Error $response  The remote response or WP_Error object.
	 *
	 * @return array|WP_Error
	 */
	protected static function filter_remote_response( string $url, array $args, $response ) {
		if ( is_wp_error( $response ) ) {
			$response->add_data( $url, 'debug_request_url' );
			$response->add_data( $args, 'debug_request_args' );

			return $response;
		}

		$content_type  = wp_remote_retrieve_header( $response, 'Content-Type' );
		$response_code = (int) wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );
		if ( false !== strpos( $content_type, 'application/json' ) ) {
			$response_body = json_decode( $response_body, true );
		} elseif ( false !== strpos( $content_type, 'text/html' ) ) {
			$response_body = (array) $response_body;
		} else {
			$response_body = 'Unsupported content type: ' . $content_type;
		}

		if ( ! ( $response_code >= 200 && $response_code < 300 ) ) {
			$response_message = wp_remote_retrieve_response_message( $response );

			return new WP_Error(
				'rest_error',
				$response_message,
				[
					'debug_request_url'  => $url,
					'debug_request_args' => $args,
				]
			);
		}

		if ( ! is_array( $response_body ) ) {
			return new WP_Error(
				'unexpected_response_type',
				'Rest Client Error: unexpected response type',
				[
					'debug_request_url'  => $url,
					'debug_request_args' => $args,
				]
			);
		}

		return $response_body;
	}

	/**
	 * Send remote request
	 *
	 * @param  string $endpoint  The REST endpoint.
	 * @param  array  $data  The data to be sent.
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

		$response = wp_remote_post(
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

		return static::filter_remote_response( $endpoint, $data, $response );
	}

	/**
	 * Send Feedback.
	 * Fires a request to Carousel Slider server with the feedback data.
	 *
	 * @param  string $feedback_key  Feedback key.
	 * @param  string $feedback_text  Feedback text.
	 *
	 * @return array The response of the request.
	 * @since 2.1.0
	 */
	public static function send_deactivation_feedback( $feedback_key, $feedback_text ) {
		$response = self::send_request(
			'feedback',
			[
				'feedback_key' => $feedback_key,
				'feedback'     => $feedback_text,
			]
		);
		if ( is_wp_error( $response ) ) {
			static::log_wp_error( $response );
		}

		return $response;
	}

	/**
	 * Send tracking to remote site.
	 *
	 * @param  array $data  The tracking data.
	 *
	 * @return array|WP_Error The response of the request.
	 * @since 2.1.0
	 */
	public static function send_tracking_data( array $data ) {
		$response = self::send_request( 'tracker', $data );
		if ( is_wp_error( $response ) ) {
			static::log_wp_error( $response );
		}

		return $response;
	}
}
