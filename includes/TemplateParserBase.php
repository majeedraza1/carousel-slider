<?php

namespace CarouselSlider;

use CarouselSlider\Abstracts\SliderSetting;
use CarouselSlider\Interfaces\SliderSettingInterface;
use CarouselSlider\Interfaces\TemplateParserInterface;

/**
 * TemplateParserBase class
 */
class TemplateParserBase implements TemplateParserInterface {
	/**
	 * The template string
	 *
	 * @var string
	 */
	protected $template = '';

	/**
	 * The object that will be used to replace placeholder
	 *
	 * @var mixed
	 */
	protected $object;

	/**
	 * Placeholders
	 *
	 * @var array
	 */
	protected $placeholders = [];

	/**
	 * Slider settings object
	 *
	 * @var SliderSetting
	 */
	protected $slider_setting;

	/**
	 * Should use conditional checking
	 *
	 * @var bool
	 */
	protected $use_condition = false;

	/**
	 * Extra data to pass to the template
	 *
	 * @var array
	 */
	protected $extra_vars = [];

	/**
	 * The class constructor
	 *
	 * @param SliderSetting|null $setting The setting object.
	 */
	public function __construct( $setting = null ) {
		if ( $setting instanceof SliderSettingInterface ) {
			$this->set_slider_settings( $setting );
		}
	}

	/**
	 * Set template file name
	 *
	 * @param string $template The template file name.
	 */
	public function set_template( string $template ) {
		$this->template = $template;
	}

	/**
	 * Set object
	 *
	 * @param mixed $data_object The object to replace placeholder.
	 */
	public function set_object( $data_object ) {
		$this->object = $data_object;
	}

	/**
	 * Get slider setting
	 *
	 * @return SliderSetting
	 */
	public function get_slider_setting(): SliderSetting {
		return $this->slider_setting;
	}

	/**
	 * Set slider settings.
	 *
	 * @param SliderSetting $setting The setting class.
	 *
	 * @return void
	 */
	public function set_slider_settings( SliderSetting $setting ) {
		$this->slider_setting = $setting;
	}

	/**
	 * Get object
	 *
	 * @return mixed
	 */
	public function get_object() {
		return $this->object;
	}

	/**
	 * Global placeholder
	 *
	 * @return array
	 */
	public function global_placeholders(): array {
		return [
			'{site_title}' => wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ),
			'{site_url}'   => wp_parse_url( home_url(), PHP_URL_HOST ),
			'{slider_id}'  => $this->get_slider_setting()->get_slider_id(),
		];
	}

	/**
	 * Get template base directory
	 *
	 * @return string
	 */
	public function get_template_base_directory(): string {
		$dir = apply_filters( 'carousel_slider/template_directory', CAROUSEL_SLIDER_PATH . '/templates' );

		return rtrim( $dir, '/' );
	}

	/**
	 * Get template file path
	 *
	 * @return string
	 */
	public function get_template_file(): string {
		$theme_dir     = apply_filters( 'carousel_slider/theme_template_directory', 'carousel-slider' );
		$template_file = $theme_dir . DIRECTORY_SEPARATOR . $this->template;
		$template      = locate_template( $template_file );
		if ( '' !== $template ) {
			return $template;
		}

		return $this->get_template_base_directory() . DIRECTORY_SEPARATOR . $this->template;
	}

	/**
	 * Get template content
	 *
	 * @return string
	 */
	public function get_template_content(): string {
		$setting = $this->get_slider_setting();
		$object  = $this->get_object();
		if ( count( $this->extra_vars ) ) {
			extract( $this->extra_vars, EXTR_OVERWRITE ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
		}
		ob_start();
		include $this->get_template_file();

		return ob_get_clean();
	}

	/**
	 * Render final html string
	 *
	 * @return string
	 */
	public function render(): string {
		$subject = str_replace(
			array_keys( $this->placeholders ),
			array_values( $this->placeholders ),
			$this->get_template_content()
		);

		if ( $this->use_condition ) {
			return $this->handle_conditional_tags( $subject );
		}

		return $subject;
	}

	/**
	 * Handle conditional tags
	 *
	 * @param string $subject The html content to check.
	 *
	 * @return string|null
	 */
	public function handle_conditional_tags( string $subject ) {
		$regex  = '/'; // Start of Regex.
		$regex .= '<!--\s*{if\s*\((?P<condition>\s*.*)\)}\s*-->\s*'; // Start of condition.
		$regex .= '(?P<html>\s*.*)'; // Grab the html.
		$regex .= '\s*<!--\s{endif}\s-->'; // End of condition.
		$regex .= '/i'; // Regex options.

		return preg_replace_callback(
			$regex,
			function ( $matches ) {
				$html = $matches['html'];

				list( $value1, $operator, $value2 ) = explode( ' ', trim( $matches['condition'] ) );

				$value1 = str_replace( [ '"', "'" ], '', $value1 );
				$value2 = str_replace( [ '"', "'" ], '', $value2 );

				// phpcs:ignore Universal.Operators.StrictComparisons.LooseEqual
				if ( '==' === $operator && $value1 == $value2 ) {
					return $html;
				}

				return '';
			},
			$subject
		);
	}

	/**
	 * Set extra variables
	 *
	 * @param string $key The extra variable key.
	 * @param mixed  $value The value to replace placeholder.
	 */
	public function set_extra_vars( string $key, $value ) {
		$this->extra_vars[ $key ] = $value;
	}
}
