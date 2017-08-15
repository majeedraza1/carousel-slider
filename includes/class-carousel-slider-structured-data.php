<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Structured data's handler and generator using JSON-LD format.
 *
 * @class   Carousel_Slider_Structured_Data
 * @since   1.7.2
 * @author  Sayful Islam <sayful.islam001@gmail.com>
 */
class Carousel_Slider_Structured_Data {

	private $_product_data = array();
	private $_image_data = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'carousel_slider_product_loop', array( $this, 'generate_product_data' ) );
		add_action( 'carousel_slider_image_gallery_loop', array( $this, 'generate_image_data' ) );
		// Output structured data.
		add_action( 'wp_footer', array( $this, 'output_structured_data' ), 10 );
	}

	/**
	 * Outputs structured data.
	 *
	 * Hooked into `wp_footer` action hook.
	 */
	public function output_structured_data()
	{
		$data = $this->get_structured_product_data();
		if ( $data ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $data ) . '</script>' . "\n";
		}
		$gallery_data = $this->get_structured_image_data();
		if ( $gallery_data ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $gallery_data ) . '</script>' . "\n";
		}
	}

	/**
	 * Sets data.
	 *
	 * @param  array $data  Structured data.
	 * @return bool
	 */
	public function set_data( $data ) {
		if ( ! isset( $data['@type'] ) || ! preg_match( '|^[a-zA-Z]{1,20}$|', $data['@type'] ) ) {
			return false;
		}

		if ( $data['@type'] == 'ImageObject' ) {
			if ( ! $this->maybe_image_added( $data['contentUrl'] ) ) {
				$this->_image_data[] = $data;
			}
		}

		if ( $data['@type'] == 'Product' ) {
			if ( ! $this->maybe_product_added( $data['@id'] ) ) {
				$this->_product_data[] = $data;
			}
		}

		return true;
	}

	/**
	 * Check if product is already added to list
	 * 
	 * @param  string $product_id
	 * @return boolean
	 */
	private function maybe_product_added( $product_id = null )
	{
		$product_data = $this->get_product_data();
		if ( count($product_data) > 0 ) {
			$product_data = array_map( function( $data ){ return $data['@id']; }, $product_data );
			return in_array( $product_id, $product_data );
		}

		return false;
	}

	/**
	 * Check if image is already added to list
	 * 
	 * @param  string $image_id
	 * @return boolean
	 */
	private function maybe_image_added( $image_id = null )
	{
		$image_data = $this->get_image_data();
		if ( count($image_data) > 0 ) {
			$image_data = array_map( function( $data ){ return $data['contentUrl']; }, $image_data );
			return in_array( $image_id, $image_data );
		}

		return false;
	}

	/**
	 * Gets product data.
	 *
	 * @return array
	 */
	public function get_product_data() {
		return $this->_product_data;
	}

	/**
	 * Get image data
	 * 
	 * @return array
	 */
	public function get_image_data()
	{
		return $this->_image_data;
	}

	/**
	 * Structures and returns image data.
	 * @return array
	 */
	public function get_structured_image_data()
	{
		$data = array(
			'@context' => 'http://schema.org/',
			"@type" => "ImageGallery",
			"associatedMedia" => $this->get_image_data()
		);
		return $this->get_image_data() ? $data : array();
	}

	/**
	 * Structures and returns product data.
	 * @return array
	 */
	public function get_structured_product_data()
	{
		$data = array(
			'@context' => 'http://schema.org/',
			"@graph" => $this->get_product_data()
		);

		return $this->get_product_data() ? $data : array();
	}

	/**
	 * Generates Product structured data.
	 *
	 * Hooked into `carousel_slider_product_loop` action hook.
	 *
	 * @param WC_Product $product Product data (default: null).
	 */
	public function generate_product_data( $product = null ) {
		if ( ! is_object( $product ) ) {
			global $product;
		}

		$markup['@type'] = 'Product';
		$markup['@id']   = get_permalink( $product->get_id() );
		$markup['url']   = $markup['@id'];
		$markup['name']  = $product->get_name();

		$this->set_data( apply_filters( 'carousel_slider_structured_data_product', $markup, $product ) );
	}

	/**
	 * Generates Image structured data.
	 *
	 * Hooked into `carousel_slider_image_gallery_loop` action hook.
	 *
	 * @param WP_Post $cs_post Post data (default: null).
	 */
	public function generate_image_data( $cs_post = null ) {
		if ( ! is_object( $cs_post ) ) {
			global $cs_post;
		}

		$image 					= wp_get_attachment_image_src( $cs_post->ID, 'full' );
		$markup['@type'] 		= 'ImageObject';
		$markup['contentUrl']  	= $image[0];
		$markup['name']  		= $cs_post->post_title;

		$this->set_data( apply_filters( 'carousel_slider_structured_data_image', $markup, $cs_post ) );
	}
}

new Carousel_Slider_Structured_Data();