<?php

namespace CarouselSlider;

/**
 * Carousel Slider API.
 *
 * Carousel Slider API handler class is responsible for communicating with Carousel Slider
 * remote servers retrieving templates data and to send uninstall feedback.
 *
 * @since 2.1.0
 */
class Api {
	/**
	 * API feedback URL.
	 * Holds the URL of the feedback API.
	 *
	 * @var string API feedback URL.
	 */
	private static $api_feedback_url = 'https://carousel-slider.com/api/v1/feedback/';

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
	public static function send_feedback( $feedback_key, $feedback_text ) {
		return wp_remote_post(
			self::$api_feedback_url,
			[
				'timeout' => 30,
				'body'    => [
					'api_version'  => CAROUSEL_SLIDER_VERSION,
					'site_lang'    => get_bloginfo( 'language' ),
					'feedback_key' => $feedback_key,
					'feedback'     => $feedback_text,
				],
			]
		);
	}
}
