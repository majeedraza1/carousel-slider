<?php

namespace CarouselSlider\Supports\SettingApi;

use CarouselSlider\Helper;
use CarouselSlider\Interfaces\FormBuilderInterface;

defined( 'ABSPATH' ) || exit;

/**
 * DefaultSettingApi class
 */
class DefaultSettingApi extends SettingApi {

	/**
	 * Setting page form action attribute value
	 *
	 * @var string
	 */
	protected $action = 'options.php';

	/**
	 * The FormBuilder class
	 *
	 * @var FormBuilder
	 */
	protected $form_builder;

	/**
	 * Class constructor
	 */
	public function __construct() {
		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'register_setting' ) );
			add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		}
	}

	/**
	 * Register setting and its sanitize callback.
	 */
	public function register_setting() {
		register_setting(
			$this->get_option_name(),
			$this->get_option_name(),
			array( 'sanitize_callback' => array( $this, 'sanitize_callback' ) )
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param  array|mixed $input  Contains all settings fields as array keys.
	 *
	 * @return array
	 */
	public function sanitize_callback( $input ): array {
		return $this->sanitize_options( is_array( $input ) ? $input : array() );
	}

	/**
	 * Create admin menu
	 */
	public function add_menu_page() {
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
				array( $this, 'page_content' )
			);
		} else {
			add_menu_page( $page_title, $menu_title, $capability, $menu_slug, array( $this, 'page_content' ) );
		}
	}

	/**
	 * Load page content
	 */
	public function page_content() {
		$options     = $this->get_options();
		$option_name = $this->get_option_name();

		$has_sections = false;
		$panel        = '';
		$sections     = array();
		if ( $this->has_panels() ) {
			$panels_ids   = wp_list_pluck( $this->get_panels(), 'id' );
			$current_tab  = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : null; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$panel        = in_array( $current_tab, $panels_ids, true ) ? $current_tab : $panels_ids[0];
			$sections     = $this->get_sections_by_panel( $panel );
			$has_sections = count( $sections ) > 0;
		}
		$panel_setting      = $this->get_panel( $panel );
		$hide_submit_button = false;
		if ( is_array( $panel_setting ) ) {
			$hide_submit_button = isset( $panel_setting['hide_submit_button'] ) && $panel_setting['hide_submit_button'];
		}
		ob_start(); ?>
		<div class="wrap">
			<h1><?php echo esc_html( $this->menu_fields['page_title'] ); ?></h1>
			<hr class="wp-header-end">
			<?php if ( ! empty( $this->menu_fields['about_text'] ) ) { ?>
				<div class="about-text"><?php echo esc_html( $this->menu_fields['about_text'] ); ?></div>
			<?php } ?>
			<?php
			if ( $this->has_panels() ) {
				Helper::print_unescaped_internal_string( $this->option_page_tabs() );
			}
			?>
			<form autocomplete="off" method="POST" action="<?php echo esc_attr( $this->action ); ?>">
				<?php
				settings_fields( $option_name );
				if ( $has_sections ) {
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					$html = $this->get_fields_html_by_section( $sections, $panel );
				} else {
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					$html = $this->get_form_builder()->get_fields_html(
						$this->filter_fields_by_tab(),
						$option_name,
						$options
					);
				}
				Helper::print_unescaped_internal_string( $html );
				if ( false === $hide_submit_button ) {
					submit_button();
				}
				?>
			</form>
		</div>
		<?php
		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Get fields HTML by section
	 *
	 * @param  array       $sections  Array of section.
	 * @param  string|null $panel  Panel id.
	 *
	 * @return string
	 */
	public function get_fields_html_by_section( array $sections = array(), string $panel = '' ): string {
		$options     = $this->get_options();
		$option_name = $this->get_option_name();

		$table = '';
		foreach ( $sections as $section ) {
			if ( ! empty( $section['title'] ) ) {
				$table .= '<h2 id="title--' . esc_attr( $section['id'] ) . '" class="title">' . esc_html( $section['title'] ) . '</h2>';
			}
			if ( ! empty( $section['description'] ) ) {
				$table .= '<p class="description">' . esc_js( $section['description'] ) . '</p>';
			}

			$fields = $this->get_fields_by( $section['id'], $panel );
			$table .= $this->get_form_builder()->get_fields_html( $fields, $option_name, $options );
		}

		return $table;
	}

	/**
	 * Generate Option Page Tabs
	 *
	 * @return string
	 */
	private function option_page_tabs(): string {
		$panels = $this->get_panels();
		if ( count( $panels ) < 1 ) {
			return '';
		}

		$current_tab = $_GET['tab'] ?? $panels[0]['id'];  // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$page        = $this->menu_fields['menu_slug'];

		$html = '<h2 class="nav-tab-wrapper wp-clearfix">';
		foreach ( $panels as $tab ) {
			$class    = ( $tab['id'] === $current_tab ) ? ' nav-tab-active' : '';
			$page_url = esc_url(
				add_query_arg(
					array(
						'page' => $page,
						'tab'  => $tab['id'],
					),
					admin_url( $this->menu_fields['parent_slug'] )
				)
			);
			$html    .= '<a class="nav-tab' . $class . '" href="' . $page_url . '">' . $tab['title'] . '</a>';
		}
		$html .= '</h2>';

		return $html;
	}

	/**
	 * Filter settings fields by page tab
	 *
	 * @param  string|null $current_tab  The current tab slug.
	 *
	 * @return array
	 */
	public function filter_fields_by_tab( string $current_tab = '' ): array {
		if ( ! $this->has_panels() ) {
			return $this->get_fields();
		}

		if ( empty( $current_tab ) ) {
			$panels      = $this->get_panels();
			$current_tab = $_GET['tab'] ?? $panels[0]['id']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		return $this->get_fields_by_panel( $current_tab );
	}

	/**
	 * Add new field
	 *
	 * @param  array $field  The field settings.
	 */
	public function add_field( array $field ) {
		$this->set_field( $field );
	}

	/**
	 * Check if it has panels
	 *
	 * @return bool
	 */
	public function has_panels(): bool {
		return count( $this->panels ) > 0;
	}

	/**
	 * Check if it has sections
	 *
	 * @return bool
	 */
	public function has_sections(): bool {
		return count( $this->sections ) > 0;
	}

	/**
	 * Get sections for current panel
	 *
	 * @param  string $panel  The panel slug.
	 *
	 * @return array
	 */
	public function get_sections_by_panel( string $panel = '' ): array {
		if ( empty( $panel ) || ! $this->has_panels() ) {
			return $this->get_sections();
		}

		$panels = array();
		foreach ( $this->get_sections() as $section ) {
			if ( $section['panel'] === $panel ) {
				$panels[] = $section;
			}
		}

		return $panels;
	}

	/**
	 * Get field for current section
	 *
	 * @param  string|null $section  The section slug.
	 * @param  string|null $panel  The panel slug.
	 *
	 * @return array
	 */
	public function get_fields_by( $section = '', $panel = '' ): array {
		if ( ( empty( $section ) || ! $this->has_sections() ) && empty( $panel ) ) {
			return $this->get_fields();
		}

		$fields = array();
		foreach ( $this->get_fields() as $field ) {
			if (
				( isset( $field['section'] ) && $field['section'] === $section ) ||
				( ! empty( $panel ) && isset( $field['panel'] ) && $panel === $field['panel'] )
			) {
				$fields[ $field['id'] ] = $field;
			}
		}

		return $fields;
	}

	/**
	 * Filter settings fields by page tab
	 *
	 * @param  string|null $panel  The panel slug.
	 *
	 * @return array
	 */
	public function get_fields_by_panel( string $panel = '' ): array {
		$sections = $this->get_sections_by_panel( $panel );

		if ( count( $sections ) < 1 ) {
			return $this->get_fields_by( null, $panel );
		}

		$fields = array();
		foreach ( $sections as $section ) {
			$_section = $this->get_fields_by( $section['id'], $panel );
			$fields   = array_merge( $fields, $_section );
		}

		return $fields;
	}

	/**
	 * Get for builder class
	 *
	 * @return FormBuilderInterface
	 */
	public function get_form_builder(): FormBuilderInterface {
		if ( ! $this->form_builder instanceof FormBuilderInterface ) {
			$this->set_form_builder( new FormBuilder() );
		}

		return $this->form_builder;
	}

	/**
	 * Set form builder class
	 *
	 * @param  FormBuilderInterface $form_builder  The form builder class.
	 */
	public function set_form_builder( FormBuilderInterface $form_builder ) {
		$this->form_builder = $form_builder;
	}
}
