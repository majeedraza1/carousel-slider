<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

if( ! class_exists('Carousel_Slider_VC_Element') ):

class Carousel_Slider_VC_Element
{
	/**
	 * Carousel_Slider_VC_Element constructor.
	 */
	public function __construct() {
        // We safely integrate with VC with this hook
        add_action( 'init', array( $this, 'integrate_with_vc' ) );
    }

	/**
	 * Integrate with visual composer
	 */
	public function integrate_with_vc() {
        // Check if Visual Composer is installed
        if ( ! defined( 'WPB_VC_VERSION' ) ) {
            return;
        }

        vc_map( array(
            "name" => __("Carousel Slider", 'carousel-slider'),
            "description" => __("Place Carousel Slider.", 'carousel-slider'),
            "base" => "carousel_slide",
            "controls" => "full",
            "icon" => plugins_url('assets/img/icon-images.svg', dirname(__FILE__)),
            "category" => __('Content', 'carousel-slider'),
            "params" => array(
                array(
                    "type"          => "dropdown",
                    "holder"        => "div",
                    "class"         => "carousel-slider-id",
                    "param_name"    => "id",
                    "value"         => $this->carousels_list(),
                    "heading"       => __("Choose Carousel Slide", 'carousel-slider'),
                ),
            ),
        ));
    }

	/**
	 * Generate array for carousel slider
	 *
	 * @return array
	 */
	private function carousels_list()
    {
        $carousels = get_posts( array(
            'post_type'     => 'carousels',
            'post_status'   => 'publish',
        ) );

        if ( count($carousels) < 1) {
            return array();
        }

        $result = array();

        foreach($carousels as $carousel) {
            $result[esc_html($carousel->post_title)] = $carousel->ID;        
        }

        return $result;
    }
}

endif;
new Carousel_Slider_VC_Element;