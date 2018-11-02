<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Carousel_Slider_Structured_Data' ) ):

	/**
	 * Structured data's handler and generator using JSON-LD format.
	 *
	 * @class   Carousel_Slider_Structured_Data
	 * @since   1.7.2
	 * @author  Sayful Islam <sayful.islam001@gmail.com>
	 */
	class Carousel_Slider_Structured_Data {

		protected static $instance = null;
		private $_product_data = array();
		private $_image_data = array();
		private $_post_data = array();

		/**
		 * Ensures only one instance of this class is loaded or can be loaded.
		 *
		 * @return Carousel_Slider_Structured_Data
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'carousel_slider_image_gallery_loop', array( $this, 'generate_image_data' ) );
			add_action( 'carousel_slider_post_loop', array( $this, 'generate_post_data' ) );
			add_action( 'carousel_slider_product_loop', array( $this, 'generate_product_data' ), 10, 2 );
			// Output structured data.
			add_action( 'wp_footer', array( $this, 'output_structured_data' ), 90 );
		}

		/**
		 * Outputs structured data.
		 *
		 * Hooked into `wp_footer` action hook.
		 */
		public function output_structured_data() {
			$data = $this->get_structured_product_data();
			if ( $data ) {
				echo '<script type="application/ld+json">' . wp_json_encode( $data ) . '</script>' . "\n";
			}
			$gallery_data = $this->get_structured_image_data();
			if ( $gallery_data ) {
				echo '<script type="application/ld+json">' . wp_json_encode( $gallery_data ) . '</script>' . "\n";
			}
			$post_data = $this->get_structured_post_data();
			if ( $post_data ) {
				echo '<script type="application/ld+json">' . wp_json_encode( $post_data ) . '</script>' . "\n";
			}
		}

		/**
		 * Structures and returns product data.
		 * @return array
		 */
		private function get_structured_product_data() {
			$data = array(
				'@context' => 'http://schema.org/',
				"@graph"   => $this->get_product_data()
			);

			return $this->get_product_data() ? $data : array();
		}

		/**
		 * Gets product data.
		 *
		 * @return array
		 */
		private function get_product_data() {
			return $this->_product_data;
		}

		/**
		 * Structures and returns image data.
		 * @return array
		 */
		private function get_structured_image_data() {
			$data = array(
				'@context'        => 'http://schema.org/',
				"@type"           => "ImageGallery",
				"associatedMedia" => $this->get_image_data()
			);

			return $this->get_image_data() ? $data : array();
		}

		/**
		 * Get image data
		 *
		 * @return array
		 */
		private function get_image_data() {
			return $this->_image_data;
		}

		private function get_structured_post_data() {
			$data = array(
				'@context' => 'http://schema.org/',
				"@graph"   => $this->get_post_data()
			);

			return $this->get_post_data() ? $data : array();
		}

		private function get_post_data() {
			return $this->_post_data;
		}

		/**
		 * Generates Image structured data.
		 *
		 * Hooked into `carousel_slider_image_gallery_loop` action hook.
		 *
		 * @param WP_Post $_post Post data (default: null).
		 */
		public function generate_image_data( $_post ) {
			$image                = wp_get_attachment_image_src( $_post->ID, 'full' );
			$markup['@type']      = 'ImageObject';
			$markup['contentUrl'] = $image[0];
			$markup['name']       = $_post->post_title;

			$this->set_data( apply_filters( 'carousel_slider_structured_data_image', $markup, $_post ) );
		}

		/**
		 * Sets data.
		 *
		 * @param  array $data Structured data.
		 *
		 * @return bool
		 */
		private function set_data( $data ) {
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

			if ( $data['@type'] == 'BlogPosting' ) {
				if ( ! $this->maybe_post_added( $data['mainEntityOfPage']['@id'] ) ) {
					$this->_post_data[] = $data;
				}
			}

			return true;
		}

		/**
		 * Check if image is already added to list
		 *
		 * @param  string $image_id
		 *
		 * @return boolean
		 */
		private function maybe_image_added( $image_id = null ) {
			$image_data = $this->get_image_data();
			if ( count( $image_data ) > 0 ) {
				$image_data = array_map( function ( $data ) {
					return $data['contentUrl'];
				}, $image_data );

				return in_array( $image_id, $image_data );
			}

			return false;
		}

		/**
		 * Check if product is already added to list
		 *
		 * @param  string $product_id
		 *
		 * @return boolean
		 */
		private function maybe_product_added( $product_id = null ) {
			$product_data = $this->get_product_data();
			if ( count( $product_data ) > 0 ) {
				$product_data = array_map( function ( $data ) {
					return $data['@id'];
				}, $product_data );

				return in_array( $product_id, $product_data );
			}

			return false;
		}

		/**
		 * Check if post is already added to list
		 *
		 * @param  string $post_id
		 *
		 * @return boolean
		 */
		private function maybe_post_added( $post_id ) {
			$post_data = $this->get_post_data();
			if ( count( $post_data ) > 0 ) {
				$post_data = array_map( function ( $data ) {
					return $data['mainEntityOfPage']['@id'];
				}, $post_data );

				return in_array( $post_id, $post_data );
			}

			return false;
		}

		/**
		 * Generates post structured data.
		 *
		 * Hooked into `carousel_slider_post_loop` action hook.
		 *
		 * @param WP_Post $_post
		 */
		public function generate_post_data( $_post ) {
			if ( ! $_post instanceof WP_Post ) {
				return;
			}

			$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'normal' );

			$json['@type'] = 'BlogPosting';

			$json['mainEntityOfPage'] = array(
				'@type' => 'webpage',
				'@id'   => get_the_permalink(),
			);


			$json['publisher'] = array(
				'@type' => 'organization',
				'name'  => get_bloginfo( 'name' ),
			);

			$json['author'] = array(
				'@type' => 'person',
				'name'  => get_the_author(),
			);

			if ( $image ) {
				$json['image'] = array(
					'@type'  => 'ImageObject',
					'url'    => $image[0],
					'width'  => $image[1],
					'height' => $image[2],
				);
			}

			$json['datePublished'] = get_post_time( 'c' );
			$json['dateModified']  = get_the_modified_date( 'c' );
			$json['name']          = get_the_title();
			$json['headline']      = $json['name'];
			$json['description']   = get_the_excerpt();


			$this->set_data( apply_filters( 'carousel_slider_structured_data_post', $json, $_post ) );
		}

		/**
		 * Generates Product structured data.
		 *
		 * Hooked into `carousel_slider_product_loop` action hook.
		 *
		 * @param WC_Product $product Product data (default: null).
		 * @param WP_Post $post
		 */
		public function generate_product_data( $product, $post ) {
			if ( ! $product instanceof WC_Product ) {
				return;
			}

			if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, "3.0.0", ">=" ) ) {
				$name      = $product->get_name();
				$permalink = get_permalink( $product->get_id() );
			} else {
				$name      = get_the_title( $post->ID );
				$permalink = get_permalink( $post->ID );
			}

			$markup['@type'] = 'Product';
			$markup['@id']   = $permalink;
			$markup['url']   = $markup['@id'];
			$markup['name']  = $name;

			$this->set_data( apply_filters( 'carousel_slider_structured_data_product', $markup, $product ) );
		}
	}

endif;

Carousel_Slider_Structured_Data::init();
