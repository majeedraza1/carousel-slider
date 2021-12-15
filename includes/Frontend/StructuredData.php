<?php
/**
 * The structure-data specific file of the plugin
 *
 * @package CarouselSlider/Frontend
 */

namespace CarouselSlider\Frontend;

use CarouselSlider\Helper;
use CarouselSlider\Supports\Validate;
use WC_Product;
use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * StructuredData class
 *
 * The structure-data specific class of the plugin
 */
class StructuredData {
	/**
	 * The instance of the class
	 *
	 * @var null | self
	 */
	private static $instance = null;

	/**
	 * Product structured data
	 *
	 * @var array
	 */
	private $product_data = [];

	/**
	 * Image structured data
	 *
	 * @var array
	 */
	private $image_data = [];

	/**
	 * Post structured data
	 *
	 * @var array
	 */
	private $post_data = [];

	/**
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			$show = Validate::checked( Helper::get_setting( 'show_structured_data', '1' ) );

			if ( $show ) {
				add_action( 'carousel_slider_image_gallery_loop', [ self::$instance, 'generate_image_data' ] );
				add_action( 'carousel_slider_post_loop', [ self::$instance, 'generate_post_data' ] );
				add_action( 'carousel_slider_after_shop_loop_item', [ self::$instance, 'generate_product_data' ] );
				// Output structured data.
				add_action( 'wp_footer', [ self::$instance, 'output_structured_data' ], 90 );
			}
		}

		return self::$instance;
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
	 *
	 * @return array
	 */
	private function get_structured_product_data(): array {
		$data = [
			'@context' => 'https://schema.org/',
			'@graph'   => $this->get_product_data(),
		];

		return $this->get_product_data() ? $data : [];
	}

	/**
	 * Gets product data.
	 *
	 * @return array
	 */
	private function get_product_data(): array {
		return $this->product_data;
	}

	/**
	 * Structures and returns image data.
	 *
	 * @return array
	 */
	private function get_structured_image_data(): array {
		$data = [
			'@context'        => 'https://schema.org/',
			'@type'           => 'ImageGallery',
			'associatedMedia' => $this->get_image_data(),
		];

		return $this->get_image_data() ? $data : [];
	}

	/**
	 * Get image data
	 *
	 * @return array
	 */
	private function get_image_data(): array {
		return $this->image_data;
	}

	/**
	 * Get structured data for post
	 *
	 * @return array
	 */
	private function get_structured_post_data(): array {
		$data = array(
			'@context' => 'https://schema.org/',
			'@graph'   => $this->get_post_data(),
		);

		return $this->get_post_data() ? $data : array();
	}

	/**
	 * Get post data
	 *
	 * @return array
	 */
	private function get_post_data(): array {
		return $this->post_data;
	}

	/**
	 * Generates Image structured data.
	 *
	 * Hooked into `carousel_slider_image_gallery_loop` action hook.
	 *
	 * @param WP_Post $post Post data (default: null).
	 */
	public function generate_image_data( WP_Post $post ) {
		$image                = wp_get_attachment_image_src( $post->ID, 'full' );
		$markup['@type']      = 'ImageObject';
		$markup['contentUrl'] = $image[0];
		$markup['name']       = $post->post_title;

		$this->set_data( apply_filters( 'carousel_slider_structured_data_image', $markup, $post ) );
	}

	/**
	 * Sets data.
	 *
	 * @param array $data Structured data.
	 *
	 * @return void
	 */
	private function set_data( array $data ) {
		if ( ! isset( $data['@type'] ) || ! preg_match( '|^[a-zA-Z]{1,20}$|', $data['@type'] ) ) {
			return;
		}

		if ( 'ImageObject' === $data['@type'] ) {
			if ( ! $this->maybe_image_added( $data['contentUrl'] ) ) {
				$this->image_data[] = $data;
			}
		}

		if ( 'Product' === $data['@type'] ) {
			if ( ! $this->maybe_product_added( $data['@id'] ) ) {
				$this->product_data[] = $data;
			}
		}

		if ( 'BlogPosting' === $data['@type'] ) {
			if ( ! $this->maybe_post_added( $data['mainEntityOfPage']['@id'] ) ) {
				$this->post_data[] = $data;
			}
		}
	}

	/**
	 * Check if image is already added to list
	 *
	 * @param string|null $image_id The image id.
	 *
	 * @return boolean
	 */
	private function maybe_image_added( string $image_id ): bool {
		$image_data = $this->get_image_data();
		if ( count( $image_data ) > 0 ) {
			$image_data = array_map(
				function ( $data ) {
					return $data['contentUrl'];
				},
				$image_data
			);

			return in_array( $image_id, $image_data, true );
		}

		return false;
	}

	/**
	 * Check if product is already added to list
	 *
	 * @param string $product_id The product id.
	 *
	 * @return boolean
	 */
	private function maybe_product_added( string $product_id ): bool {
		$product_data = $this->get_product_data();
		if ( count( $product_data ) > 0 ) {
			$product_data = array_map(
				function ( $data ) {
					return $data['@id'];
				},
				$product_data
			);

			return in_array( $product_id, $product_data, true );
		}

		return false;
	}

	/**
	 * Check if post is already added to list
	 *
	 * @param string $post_id The post id.
	 *
	 * @return boolean
	 */
	private function maybe_post_added( string $post_id ): bool {
		$post_data = $this->get_post_data();
		if ( count( $post_data ) > 0 ) {
			$post_data = array_map(
				function ( $data ) {
					return $data['mainEntityOfPage']['@id'];
				},
				$post_data
			);

			return in_array( $post_id, $post_data, true );
		}

		return false;
	}

	/**
	 * Generates post structured data.
	 *
	 * Hooked into `carousel_slider_post_loop` action hook.
	 *
	 * @param WP_Post|mixed $post The WP_Post object.
	 */
	public function generate_post_data( $post ) {
		if ( ! $post instanceof WP_Post ) {
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

		$this->set_data( apply_filters( 'carousel_slider_structured_data_post', $json, $post ) );
	}

	/**
	 * Generates Product structured data.
	 *
	 * @param WC_Product|mixed $product Product data (default: null).
	 */
	public function generate_product_data( $product ) {
		if ( ! $product instanceof WC_Product ) {
			return;
		}

		$name      = $product->get_name();
		$permalink = get_permalink( $product->get_id() );

		$markup['@type'] = 'Product';
		$markup['@id']   = $permalink;
		$markup['url']   = $markup['@id'];
		$markup['name']  = $name;

		$this->set_data( apply_filters( 'carousel_slider_structured_data_product', $markup, $product ) );
	}
}
