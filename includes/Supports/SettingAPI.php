<?php

namespace CarouselSlider\Supports;

use CarouselSlider\Helper;
use Exception;

defined( 'ABSPATH' ) || exit;

class SettingAPI {
	/**
	 * Setting page options
	 *
	 * @var array
	 */
	private $options = array();

	/**
	 * Setting page menu fields
	 *
	 * @var array
	 */
	private $menu_fields = array();

	/**
	 * Fields list
	 *
	 * @var array
	 */
	private $fields = array();

	/**
	 * Setting page tabs settings
	 *
	 * @var array
	 */
	private $tabs = array();

	/**
	 * Setting page form action attribute value
	 *
	 * @var string
	 */
	private $action = 'options.php';


	/**
	 * Filterable_Portfolio_Setting_API constructor.
	 */
	public function __construct() {
		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_init', array( $this, 'admin_init' ) );
		}
	}

	/**
	 * Add new admin menu
	 *
	 * This method is accessible outside the class for creating menu
	 *
	 * @param array $menu_fields
	 *
	 * @throws Exception
	 */
	public function add_menu( array $menu_fields ) {
		if ( ! isset( $menu_fields['page_title'], $menu_fields['menu_title'], $menu_fields['menu_slug'] ) ) {
			throw new Exception( 'Required key is not set properly for creating menu.' );
		}

		$this->menu_fields = $menu_fields;
	}

	/**
	 * Add new settings field
	 *
	 * This method is accessible outside the class for creating settings field
	 *
	 * @param array $field
	 *
	 * @throws Exception
	 */
	public function add_field( array $field ) {
		if ( ! isset( $field['id'], $field['name'] ) ) {
			throw new Exception( 'Required key is not set properly for creating tab.' );
		}

		$this->fields[] = $field;
	}

	/**
	 * Add setting page tab
	 *
	 * This method is accessible outside the class for creating page tab
	 *
	 * @param array $tab
	 *
	 * @throws Exception
	 */
	public function add_tab( array $tab ) {
		if ( ! isset( $tab['id'], $tab['title'] ) ) {
			throw new Exception( 'Required key is not set properly for creating tab.' );
		}

		$this->tabs[] = $tab;
	}

	/**
	 * Register setting and its sanitize callback.
	 */
	public function admin_init() {
		register_setting(
			$this->menu_fields['option_name'],
			$this->menu_fields['option_name'],
			array( $this, 'sanitize_callback' )
		);
	}

	/**
	 * Create admin menu
	 */
	public function admin_menu() {
		$page_title  = $this->menu_fields['page_title'];
		$menu_title  = $this->menu_fields['menu_title'];
		$menu_slug   = $this->menu_fields['menu_slug'];
		$capability  = $this->menu_fields['capability'] ?? 'manage_options';
		$parent_slug = $this->menu_fields['parent_slug'] ?? null;

		if ( $parent_slug ) {
			add_submenu_page(
				$parent_slug,
				$page_title,
				$menu_title,
				$capability,
				$menu_slug,
				[ $this, 'page_content' ]
			);
		} else {
			add_menu_page(
				$page_title,
				$menu_title,
				$capability,
				$menu_slug,
				[ $this, 'page_content' ]
			);
		}
	}

	/**
	 * Load page content
	 */
	public function page_content() {
		ob_start(); ?>
		<div class="wrap about-wrap">
			<h1><?php echo $this->menu_fields['page_title']; ?></h1>
			<div class="about-text"><?php echo $this->menu_fields['about_text']; ?></div>
			<?php $this->option_page_tabs(); ?>
			<form autocomplete="off" method="POST" action="<?php echo $this->action; ?>">
				<?php
				$this->get_options();
				settings_fields( $this->menu_fields['option_name'] );
				$this->setting_fields( $this->filter_fields_by_tab() );
				submit_button();
				?>
			</form>
		</div>
		<?php
		echo ob_get_clean();
	}

	/**
	 * Generate Option Page Tabs
	 * @return void
	 */
	private function option_page_tabs() {
		if ( count( $this->tabs ) < 1 ) {
			return;
		}

		$current_tab = $_GET['tab'] ?? $this->tabs[0]['id'];
		$page        = $this->menu_fields['menu_slug'];

		echo '<h2 class="nav-tab-wrapper wp-clearfix">';
		foreach ( $this->tabs as $tab ) {
			$class    = ( $tab['id'] === $current_tab ) ? ' nav-tab-active' : '';
			$page_url = esc_url( add_query_arg( array(
				'page' => $page,
				'tab'  => $tab['id']
			), admin_url( $this->menu_fields['parent_slug'] ) ) );
			echo '<a class="nav-tab' . $class . '" href="' . $page_url . '">' . $tab['title'] . '</a>';
		}
		echo '</h2>';
	}

	/**
	 * Filter settings fields by page tab
	 *
	 * @param string|null|mixed $current_tab
	 *
	 * @return array
	 */
	public function filter_fields_by_tab( $current_tab = null ): array {

		if ( count( $this->tabs ) < 1 ) {
			return $this->fields;
		}

		if ( ! $current_tab ) {
			$current_tab = $_GET['tab'] ?? $this->tabs[0]['id'];
		}

		$new_array = array();
		if ( is_array( $this->fields ) && count( $this->fields ) > 0 ) {
			foreach ( array_keys( $this->fields ) as $key ) {
				if ( isset( $this->fields[ $key ]['tab'] ) ) {
					$temp[ $key ] = $this->fields[ $key ]['tab'];
					if ( $temp[ $key ] == $current_tab ) {
						$new_array[ $key ] = $this->fields[ $key ];
					}
				} else {
					if ( $current_tab == $this->tabs[0]['id'] ) {
						$new_array[ $key ] = $this->fields[ $key ];
					}
				}
			}
		}

		return $new_array;
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 *
	 * @return array
	 */
	public function sanitize_callback( array $input ): array {
		$output_array = [];
		$fields       = $this->fields;
		$options      = (array) get_option( $this->menu_fields['option_name'] );
		$options      = array_filter( $options );

		if ( empty( $options ) ) {
			$options = $this->get_options();
		}

		if ( count( $this->tabs ) > 0 ) {
			parse_str( $_POST['_wp_http_referer'], $referrer );
			$tab    = $referrer['tab'] ?? $this->tabs[0]['id'];
			$fields = $this->filter_fields_by_tab( $tab );
		}

		// Loop through each setting being saved and
		// pass it through a filter to sanitize
		foreach ( $input as $key => $value ) {
			foreach ( $fields as $field ) {
				if ( $field['id'] == $key ) {
					$rule                 = empty( $field['validate'] ) ? $field['type'] : $field['validate'];
					$output_array[ $key ] = $this->validate( $value, $rule );
				}
			}
		}

		return array_merge( $options, $output_array );
	}

	/**
	 * Get options parsed with default value
	 * @return array
	 */
	public function get_options(): array {
		if ( empty( $this->options ) ) {
			$options_array = [];

			foreach ( $this->fields as $value ) {
				$options_array[ $value['id'] ] = $value['std'] ?? '';
			}

			$options = wp_parse_args(
				get_option( $this->menu_fields['option_name'] ),
				$options_array
			);

			$this->options = $options;
		}

		return $this->options;
	}

	/**
	 * Validate the option's value
	 *
	 * @param mixed $input
	 * @param string $validation_rule
	 *
	 * @return mixed
	 */
	private function validate( $input, string $validation_rule = 'text' ) {
		switch ( $validation_rule ) {

			case 'number':
				return is_int( $input ) ? trim( $input ) : intval( $input );

			case 'url':
				return esc_url_raw( trim( $input ) );

			case 'email':
				return sanitize_email( $input );

			case 'checkbox':
				return ( $input == 1 ) ? 1 : 0;

			case 'multi_checkbox':
				return $input;

			case 'date':
				return date( 'F d, Y', strtotime( $input ) );

			case 'textarea':
				return wp_filter_nohtml_kses( $input );

			case 'inlinehtml':
				return wp_filter_kses( force_balance_tags( $input ) );

			case 'linebreaks':
				return wp_strip_all_tags( $input );

			case 'wp_editor':
				return wp_kses_post( $input );

			default:
				return sanitize_text_field( $input );
		}
	}

	/**
	 * Settings fields
	 *
	 * @param array $fields
	 *
	 * @return void
	 */
	private function setting_fields( array $fields = [] ) {
		$fields = is_array( $fields ) ? $fields : $this->fields;

		$table = "<table class='form-table'>";

		foreach ( $fields as $field ) {
			$name  = sprintf( '%s[%s]', $this->menu_fields['option_name'], $field['id'] );
			$type  = $field['type'] ?? 'text';
			$value = $this->options[ $field['id'] ] ?? '';

			$table .= "<tr>";
			$table .= sprintf( '<th scope="row"><label for="%1$s">%2$s</label></th>', $field['id'],
				$field['name'] );
			$table .= "<td>";

			if ( method_exists( $this, $type ) ) {
				$table .= $this->$type( $field, $name, $value );
			} else {
				$table .= $this->text( $field, $name, $value );
			}

			if ( ! empty( $field['desc'] ) ) {
				$desc  = is_array( $field['desc'] ) ? implode( "<br>", $field['desc'] ) : $field['desc'];
				$table .= sprintf( '<p class="description">%s</p>', $desc );
			}
			$table .= "</td>";
			$table .= "</tr>";
		}

		$table .= "</table>";
		echo $table;
	}

	/**
	 * text input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string|mixed $value
	 *
	 * @return string
	 */
	public function text( array $field, string $name, $value ): string {
		return sprintf( '<input type="text" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value,
			$field['id'], $name );
	}

	/**
	 * email input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string|mixed $value
	 *
	 * @return string
	 */
	public function email( array $field, string $name, $value ): string {
		return sprintf( '<input type="email" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value,
			$field['id'], $name );
	}

	/**
	 * password input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string|mixed $value
	 *
	 * @return string
	 */
	public function password( array $field, string $name, $value = null ): string {
		return sprintf( '<input type="password" class="regular-text" value="" id="%1$s" name="%2$s">',
			$field['id'], $name );
	}

	/**
	 * number input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string|mixed $value
	 *
	 * @return string
	 */
	public function number( array $field, string $name, $value ): string {
		return sprintf( '<input type="number" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value,
			$field['id'], $name );
	}

	/**
	 * url input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string|mixed $value
	 *
	 * @return string
	 */
	public function url( array $field, string $name, $value ): string {
		return sprintf( '<input type="url" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value,
			$field['id'], $name );
	}

	/**
	 * color input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string|mixed $value
	 *
	 * @return string
	 */
	public function color( array $field, string $name, $value ): string {
		$default_color = ( isset( $field['std'] ) ) ? $field['std'] : "";

		return sprintf( '<input type="text" class="color-picker" value="%1$s" id="%2$s" name="%3$s" data-alpha="true" data-default-color="%4$s">',
			$value, $field['id'], $name, $default_color );
	}

	/**
	 * date input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string|mixed $value
	 *
	 * @return string
	 */
	public function date( array $field, string $name, $value ): string {
		$value = date( "Y-m-d", strtotime( $value ) );

		return sprintf( '<input type="date" class="regular-text" value="%1$s" id="%2$s" name="%3$s">',
			$value, $field['id'], $name );
	}

	/**
	 * textarea input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string|mixed $value
	 *
	 * @return string
	 */
	public function textarea( array $field, string $name, $value ): string {
		$rows        = $field['rows'] ?? 5;
		$cols        = $field['cols'] ?? 40;
		$placeholder = ( isset( $field['placeholder'] ) ) ? sprintf( 'placeholder="%s"',
			esc_attr( $field['placeholder'] ) ) : '';

		return '<textarea id="' . $field['id'] . '" name="' . $name . '" rows="' . $rows . '" cols="' . $cols . '" ' . $placeholder . '>' . $value . '</textarea>';
	}

	/**
	 * checkbox input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string|mixed $value
	 *
	 * @return string
	 */
	public function checkbox( array $field, string $name, $value ): string {
		$checked = ( 1 == $value ) ? 'checked' : '';
		$table   = '<input type="hidden" name="' . $name . '" value="0">';
		$table   .= '<fieldset><legend class="screen-reader-text"><span>' . $field['name'] . '</span></legend>';
		$table   .= '<label for="' . $field['id'] . '">';
		$table   .= '<input type="checkbox" value="1" id="' . $field['id'] . '" name="' . $name . '" ' . $checked . '>';
		$table   .= $field['name'] . '</label></fieldset>';

		return $table;
	}

	/**
	 * multi checkbox input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param array|mixed $value
	 *
	 * @return string
	 */
	public function multi_checkbox( array $field, string $name, $value ): string {
		$name = $name . "[]";

		$table = "<fieldset>";
		$table .= sprintf( '<input type="hidden" name="%1$s" value="0">', $name );
		foreach ( $field['options'] as $key => $label ) {
			$checked = ( in_array( $key, $value ) ) ? 'checked="checked"' : '';
			$table   .= '<label for="' . $key . '"><input type="checkbox" value="' . $key . '" id="' . $key . '" name="' . $name . '" ' . $checked . '>' . $label . '</label><br>';
		}
		$table .= "</fieldset>";

		return $table;
	}

	/**
	 * radio input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string|mixed $value
	 *
	 * @return string
	 */
	public function radio( array $field, string $name, $value ): string {
		$table = '<fieldset><legend class="screen-reader-text"><span>' . $field['name'] . '</span></legend><p>';

		foreach ( $field['options'] as $key => $label ) {

			$checked = ( $value == $key ) ? 'checked="checked"' : '';
			$table   .= '<label><input type="radio" ' . $checked . ' value="' . $key . '" name="' . $name . '">' . $label . '</label><br>';
		}
		$table .= "</p></fieldset>";

		return $table;
	}

	/**
	 * select input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string|mixed $value
	 *
	 * @return string
	 */
	public function select( array $field, string $name, $value ): string {
		$table = sprintf( '<select id="%1$s" name="%2$s" class="regular-text">', $field['id'], $name );
		foreach ( $field['options'] as $key => $label ) {
			$selected = ( $value == $key ) ? 'selected="selected"' : '';
			$table    .= '<option value="' . $key . '" ' . $selected . '>' . $label . '</option>';
		}
		$table .= "</select>";

		return $table;
	}

	/**
	 * Get available image sizes
	 *
	 * @param array $field
	 * @param string $name
	 * @param string|mixed $value
	 *
	 * @return string
	 */
	public function image_sizes( array $field, string $name, $value ): string {
		$sizes = Helper::get_available_image_sizes();

		$table = '<select name="' . $name . '" id="' . $field['id'] . '" class="regular-text select2">';
		foreach ( $sizes as $key => $option ) {
			$selected = ( $value == $key ) ? ' selected="selected"' : '';
			$table    .= '<option value="' . $key . '" ' . $selected . '>' . $option . '</option>';
		}
		$table .= '</select>';

		return $table;
	}

	/**
	 * wp_editor input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string|mixed $value
	 *
	 * @return string
	 */
	public function wp_editor( array $field, string $name, $value ): string {
		ob_start();
		echo "<div class='sp-wp-editor-container'>";
		wp_editor( $value, $field['id'], array(
			'textarea_name' => $name,
			'tinymce'       => false,
			'media_buttons' => false,
			'textarea_rows' => $field['rows'] ?? 6,
			'quicktags'     => array( "buttons" => "strong,em,link,img,ul,li,ol" ),
		) );
		echo "</div>";

		return ob_get_clean();
	}
}
