<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if( ! class_exists('Carousel_Slider_Deprecated_Shortcode') ):

class Carousel_Slider_Deprecated_Shortcode
{
	private $plugin_path;

	/**
	 * Carousel_Slider_Deprecated_Shortcode constructor.
	 *
	 * @param $plugin_path
	 */
	public function __construct( $plugin_path )
	{
		$this->plugin_path = $plugin_path;
		// Deprecated since version 1.6.0
		add_shortcode('carousel', array( $this, 'carousel' ) );
		add_shortcode('item', array( $this, 'item' ) );
	}

	/**
	 * A shortcode for rendering the carousel slide.
	 *
	 * @param  array   $atts  		Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function carousel( $atts, $content = null )
	{
	    extract(shortcode_atts(array(
	        'id'                    => rand(1, 10),
	        'items_desktop_large'   => '4',
	        'items'                 => '4',
	        'items_desktop'         => '4',
	        'items_desktop_small'   => '3',
	        'items_tablet'          => '2',
	        'items_mobile'          => '1',
	        'auto_play'             => 'true',
	        'stop_on_hover'         => 'true',
	        'navigation'            => 'true',
	        'pagination'            => 'false',
	        'nav_color'             => '#f1f1f1',
	        'nav_active_color'      => '#4caf50',
	        'margin_right'          => '10',
	        'inifnity_loop'         => 'true',
	        'autoplay_timeout'      => '5000',
	        'autoplay_speed'        => '500',
	        'slide_by'              => '1',
	    ), $atts ) );

	    ob_start();
	    require $this->plugin_path . '/templates/carousel.php';
	    $html = ob_get_contents();
	    ob_end_clean();
	 
	    return $html;

	}

	/**
	 * A shortcode for rendering the carousel slide.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function item( $attributes, $content = null )
	{
		extract(shortcode_atts(array(
	        'img_link' 	=>'',
	        'href' 		=>'',
	        'target' 	=>'_self',
	    ), $attributes ) );

	    if( ! $this->is_valid_url( $img_link ) ) return;

	    if ( $this->is_valid_url( $href ) ) {

	        return sprintf( '<div><a target="%3$s" href="%2$s"><img src="%1$s"></a></div>', esc_url( $img_link ), esc_url( $href ), $target );
	    } else {

	    	return sprintf('<div><img src="%s"></div>', esc_url( $img_link ) );
	    }
	}

	/**
	 * Check if url is valid as per RFC 2396 Generic Syntax
	 * 
	 * @param  string $url
	 * @return boolean
	 */
	private function is_valid_url( $url )
	{
		if ( filter_var( $url, FILTER_VALIDATE_URL ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Convert array to html attributes
	 * 
	 * @param  array $array
	 * @return string
	 */
	private function array_to_data( array $array )
	{
		$array_map = array_map( function( $key, $value )
		{
			return sprintf( '%s="%s"', $key, esc_attr($value) );

		}, array_keys($array), array_values( $array ) );

		return join(" ", $array_map );
	}
}

endif;