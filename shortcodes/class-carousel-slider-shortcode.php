<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if( ! class_exists('Carousel_Slider_Shortcode') ):

class Carousel_Slider_Shortcode
{
	private $plugin_path;
	private $plugin_url;

	/**
	 * Carousel_Slider_Shortcode constructor.
	 *
	 * @param $plugin_path
	 * @param $plugin_url
	 */
	public function __construct( $plugin_path, $plugin_url )
	{
		$this->plugin_path = $plugin_path;
		$this->plugin_url = $plugin_url;

		add_shortcode('carousel_slide', array( $this, 'carousel_slide' ) );
	}

	/**
	 * A shortcode for rendering the carousel slide.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function carousel_slide( $attributes, $content = null )
	{
		extract(
			shortcode_atts(
				array( 'id' =>'' ),
				$attributes
			)
		);
		if ( ! $id ) return;

		$slide_type = get_post_meta( $id, '_slide_type', true );
		$slide_type = in_array($slide_type, array('image-carousel', 'post-carousel', 'image-carousel-url', 'video-carousel', 'product-carousel')) ? $slide_type : 'image-carousel';

		if ($slide_type == 'post-carousel') {
			ob_start();
		    require $this->plugin_path . '/templates/post-carousel.php';
		    $html = ob_get_contents();
		    ob_end_clean();
		    return apply_filters( 'carousel_slider_posts_carousel', $html, $id, $posts );
		}

		if ($slide_type == 'video-carousel') {
			ob_start();
		    require $this->plugin_path . '/templates/video-carousel.php';
		    $html = ob_get_contents();
		    ob_end_clean();
		    return apply_filters( 'carousel_slider_videos_carousel', $html, $id );
		}

		if ($slide_type == 'image-carousel-url') {
			ob_start();
		    require $this->plugin_path . '/templates/images-carousel-url.php';
		    $html = ob_get_contents();
		    ob_end_clean();
		    return apply_filters( 'carousel_slider_link_images_carousel', $html, $id );
		}
		
		if ($slide_type == 'image-carousel') {
			ob_start();
		    require $this->plugin_path . '/templates/images-carousel.php';
		    $html = ob_get_contents();
		    ob_end_clean();
		    return apply_filters( 'carousel_slider_gallery_images_carousel', $html, $id );
		}
		
		if ($slide_type == 'product-carousel') {
			ob_start();
		    require $this->plugin_path . '/templates/product-carousel.php';
		    $html = ob_get_contents();
		    ob_end_clean();
		    return apply_filters( 'carousel_slider_product_carousel', $html, $id );
		}

		return;
	}

	/**
	 * Generate carousel options for slider
	 *
	 * @param $id
	 *
	 * @return array
	 */
	private function carousel_options( $id )
	{
		$_nav_color         = get_post_meta( $id, '_nav_color', true );
		$_nav_active_color  = get_post_meta( $id, '_nav_active_color', true );

		$options_array = array(
			'id' 							=> 'id-' . $id,
			'class' 						=> 'owl-carousel carousel-slider',
			// General
			'data-slide-type' 				=> $this->get_meta( $id, '_slide_type', 'image-carousel' ),
			'data-margin' 					=> $this->get_meta( $id, '_margin_right', '10' ),
			'data-slide-by' 				=> $this->get_meta( $id, '_slide_by', '1' ),
			'data-loop' 					=> $this->get_meta( $id, '_inifnity_loop', 'true' ),
			'data-lazy-load' 				=> $this->get_meta( $id, '_lazy_load_image', 'false' ),
			// Navigation
			'data-dots' 					=> $this->get_meta( $id, '_dot_nav', 'false' ),
			'data-nav' 						=> $this->get_meta( $id, '_nav_button', 'false' ),
			'data-nav-previous-icon' 		=> $this->nav_previous_icon(),
			'data-nav-next-icon' 			=> $this->nav_next_icon(),
			// Video
			'data-video-width' 				=> $this->get_meta( $id, '_video_width', 'false' ),
			'data-video-height' 			=> $this->get_meta( $id, '_video_height', 'false' ),
			// Autoplay
			'data-autoplay' 				=> $this->get_meta( $id, '_autoplay', 'true' ),
			'data-autoplay-timeout' 		=> $this->get_meta( $id, '_autoplay_timeout', '5000' ),
			'data-autoplay-speed' 			=> $this->get_meta( $id, '_autoplay_speed', '500' ),
			'data-autoplay-hover-pause' 	=> $this->get_meta( $id, '_autoplay_pause', 'false' ),
			// Responsive
			'data-colums' 					=> $this->get_meta( $id, '_items', '4' ),
			'data-colums-desktop' 			=> $this->get_meta( $id, '_items_desktop', '4' ),
			'data-colums-small-desktop' 	=> $this->get_meta( $id, '_items_small_desktop', '4' ),
			'data-colums-tablet' 			=> $this->get_meta( $id, '_items_portrait_tablet', '3' ),
			'data-colums-small-tablet' 		=> $this->get_meta( $id, '_items_small_portrait_tablet', '2' ),
			'data-colums-mobile' 			=> $this->get_meta( $id, '_items_portrait_mobile', '1' ),
		);

		return $this->array_to_data( $options_array );
	}

	/**
	 * Get post meta by id and key
	 *
	 * @param $id
	 * @param $key
	 * @param null $default
	 *
	 * @return string|void
	 */
	public function get_meta( $id, $key, $default = null )
	{
		$meta = get_post_meta( $id, $key, true );

		if ( empty($meta) && $default ) {
			$meta = $default;
		}

		if ($meta == 'zero') { $meta = '0'; }
		if ($meta == 'on') { $meta = 'true'; }
		if ($meta == 'off') { $meta = 'false'; }
		if ($key == '_margin_right' && $meta == 0) { $meta = '0'; }

		return esc_attr( $meta );
	}

	/**
	 * Convert array to html data attribute
	 *
	 * @param $array
	 *
	 * @return array
	 */
	public function array_to_data( $array )
	{

		$array_map = array_map( function( $key, $value )
		{

			if( is_bool( $value ) )
			{
				if ($value) {

					return sprintf( '%s="%s"', $key, 'true' );
				} else {
					
					return sprintf( '%s="%s"', $key, 'false' );
				}
			}

			if ( is_array( $value ) ) {

				return sprintf( '%s="%s"', $key, implode(" ", $value ) );
			}

			return sprintf( '%s="%s"', $key, esc_attr( $value ) );

		}, array_keys($array), array_values( $array ) );

		return $array_map;
	}

	/**
	 * Check if url is valid as per RFC 2396 Generic Syntax
	 * 
	 * @param  string $url
	 * @return boolean
	 */
	public function is_valid_url( $url )
	{
		if ( filter_var( $url, FILTER_VALIDATE_URL ) ) {

			return true;
		}

		return false;
	}

	/**
	 * Convert url to youtube and vimeo video link
	 *
	 * @param $url
	 *
	 * @return string|void
	 */
	public function video_url( $url ) {
		if( ! $this->is_valid_url( $url )){
			return;
		}

		$url = esc_url( $url );

	    if (strpos($url, 'youtube.com') > 0) {
	        return '<div class="item-video"><a class="owl-video" href="'.$url.'"></a></div>';
	    }

	    if (strpos($url, 'vimeo.com') > 0) {
	        return '<div class="item-video"><a class="owl-video" href="'.$url.'"></a></div>';
	    }

	    return;
	}

	/**
	 * Previous navigation icon
	 *
	 * @return string
	 */
	public function nav_previous_icon()
	{
		return '<svg class="carousel-slider-nav-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="48" viewBox="0 0 11 28"><path d="M9.8 8.5c0 0.1-0.1 0.3-0.2 0.4l-6.1 6.1 6.1 6.1c0.1 0.1 0.2 0.2 0.2 0.4s-0.1 0.3-0.2 0.4l-0.8 0.8c-0.1 0.1-0.2 0.2-0.4 0.2s-0.3-0.1-0.4-0.2l-7.3-7.3c-0.1-0.1-0.2-0.2-0.2-0.4s0.1-0.3 0.2-0.4l7.3-7.3c0.1-0.1 0.2-0.2 0.4-0.2s0.3 0.1 0.4 0.2l0.8 0.8c0.1 0.1 0.2 0.2 0.2 0.4z"/></svg>';
	}

	/**
	 * Next navigation icon
	 *
	 * @return string
	 */
	public function nav_next_icon()
	{
		return '<svg class="carousel-slider-nav-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="48" viewBox="0 0 9 28"><path d="M9.3 15c0 0.1-0.1 0.3-0.2 0.4l-7.3 7.3c-0.1 0.1-0.2 0.2-0.4 0.2s-0.3-0.1-0.4-0.2l-0.8-0.8c-0.1-0.1-0.2-0.2-0.2-0.4 0-0.1 0.1-0.3 0.2-0.4l6.1-6.1-6.1-6.1c-0.1-0.1-0.2-0.2-0.2-0.4s0.1-0.3 0.2-0.4l0.8-0.8c0.1-0.1 0.2-0.2 0.4-0.2s0.3 0.1 0.4 0.2l7.3 7.3c0.1 0.1 0.2 0.2 0.2 0.4z"/></svg>';
	}

	/**
	 * Filter posts data
	 *
	 * @param $posts
	 *
	 * @return array|void
	 */
	public function filter_posts( $posts )
	{
		if ( ! is_array( $posts ) ) {
			return;
		}
		return array_map(function( $value ){

			$category = get_the_category( $value->ID );
			$category = (object) array(
				'link' 	=> isset($category[0]->term_id) ? esc_url( get_category_link( $category[0]->term_id ) ) : '',
				'title' => isset($category[0]->name) ? esc_html( $category[0]->name ) : '',
			);

			return (object) array(
				'id' 		=> $value->ID,
				'title' 	=> esc_attr($value->post_title),
				'permalink' => esc_url( get_permalink( $value->ID ) ),
				'excerpt' 	=> wp_trim_words(
					strip_tags($value->post_content), '19', ' ...'
				),
				'thumbnail_id' 	=> intval(get_post_thumbnail_id( $value->ID )),
				'category' 	=> $category,
				'author' 	=> (object) array(
					'id' => intval($value->post_author),
					'posts_url' => esc_url(get_author_posts_url(intval($value->post_author))),
					'display_name' => esc_html(get_the_author_meta( 'display_name', intval($value->post_author))),
				),
				'created' 	=> strtotime($value->post_date),
				'modified' 	=> strtotime($value->post_modified),
			);
		}, $posts);
	}
}

endif;