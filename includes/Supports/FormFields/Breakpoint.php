<?php

namespace CarouselSlider\Supports\FormFields;

/**
 * Breakpoint class
 */
class Breakpoint extends BaseField {

	/**
	 * Render field
	 *
	 * @inheritDoc
	 */
	public function render(): string {
		$value = is_array( $this->get_value() ) ? $this->get_value() : $this->get_defaults();

		$html = '<div class="cs-field--rb-items" data-name="' . $this->get_name() . '">';
		foreach ( $value as $index => $item ) {
			$key_attrs = [
				'type'  => 'text',
				'class' => 'small-text',
				'name'  => sprintf( '%s[%s][key]', $this->get_name(), $index ),
				'value' => $item['key'],
			];

			$breakpoint_attrs = [
				'type'  => 'text',
				'class' => 'small-text',
				'name'  => sprintf( '%s[%s][breakpoint]', $this->get_name(), $index ),
				'value' => $item['breakpoint'],
			];

			$items_attrs = [
				'type'  => 'text',
				'class' => 'small-text',
				'name'  => sprintf( '%s[%s][items]', $this->get_name(), $index ),
				'value' => $item['items'],
			];

			$html .= '<div class="cs-field--rb-item cs-flex cs-mb-4 cs-items-center cs-space-x-1 cs-bg-gray-200 cs-p-2">';
			$html .= '<label>' . esc_html__( 'Key: ', 'carousel-slider' ) . '</label>';
			$html .= '<input ' . $this->array_to_attributes( $key_attrs ) . '>';
			$html .= '<label>' . esc_html__( 'Breakpoint: ', 'carousel-slider' ) . '</label>';
			$html .= '<input ' . $this->array_to_attributes( $breakpoint_attrs ) . '>';
			$html .= '<label>' . esc_html__( 'Items: ', 'carousel-slider' ) . '</label>';
			$html .= '<input ' . $this->array_to_attributes( $items_attrs ) . '>';
			$html .= '<div class="cs-field--rb-item-cross cs-text-red-600 cs-inline-flex cs-items-center cs-justify-center cs-w-8 cs-h-8">';
			$html .= '<span class="cs-field--rb-item-cross-icon dashicons dashicons-remove"></span>';
			$html .= '</div>';
			$html .= '</div>';
		}
		$html .= '</div>';
		$html .= '<div class="cs-mb-4"><button class="button btn--add-new-breakpoint" disabled>';
		$html .= esc_html__( 'Add New Breakpoint', 'carousel-slider' );
		$html .= '</button></div>';

		return $html;
	}

	/**
	 * Get default values
	 *
	 * @return array[]
	 */
	public function get_defaults(): array {
		return [
			[
				'key'        => 'xs',
				'breakpoint' => 300,
				'items'      => 1,
			],
			[
				'key'        => 'sm',
				'breakpoint' => 640,
				'items'      => 2,
			],
			[
				'key'        => 'md',
				'breakpoint' => 768,
				'items'      => 3,
			],
			[
				'key'        => 'lg',
				'breakpoint' => 1024,
				'items'      => 4,
			],
			[
				'key'        => 'xl',
				'breakpoint' => 1280,
				'items'      => 5,
			],
			[
				'key'        => '2xl',
				'breakpoint' => 1536,
				'items'      => 6,
			],
		];
	}

	/**
	 * Sanitized breakpoint value
	 *
	 * @param mixed $value The value to be sanitized.
	 *
	 * @return array
	 */
	public static function sanitize( $value ): array {
		$sanitized_value = [];
		if ( ! is_array( $value ) ) {
			return $sanitized_value;
		}
		foreach ( $value as $item ) {
			if ( ! isset( $item['key'], $item['breakpoint'], $item['items'] ) ) {
				continue;
			}
			$sanitized_value[] = [
				'key'        => sanitize_text_field( $item['key'] ),
				'breakpoint' => intval( $item['breakpoint'] ),
				'items'      => floatval( $item['items'] ),
			];
		}

		usort(
			$sanitized_value,
			function ( array $array1, array $array2 ) {
				return $array1['breakpoint'] - $array2['breakpoint'];
			}
		);

		return $sanitized_value;
	}
}
