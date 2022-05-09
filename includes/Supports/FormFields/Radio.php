<?php

namespace CarouselSlider\Supports\FormFields;

/**
 * Radio class
 */
class Radio extends BaseField {

	/**
	 * Render html content
	 *
	 * @inheritDoc
	 */
	public function render(): string {
		$value = $this->get_value();
		$name  = $this->get_name();

		$html  = '<fieldset id="' . esc_attr( $this->get_setting( 'id' ) ) . '" class="radio-container">';
		$html .= '<legend class="screen-reader-text"><span>' . esc_html( $this->get_setting( 'label' ) ) . '</span></legend>';
		foreach ( $this->get_setting( 'choices' ) as $key => $title ) {
			$id          = sprintf( '%s-%s', $this->get_setting( 'id' ), $key );
			$label_class = sprintf( 'radio-label radio-label-%s', ( $key === $value ) ? 'on' : 'off' );
			$radio_attr  = [
				'type'    => 'radio',
				'name'    => $name,
				'id'      => $id,
				'class'   => 'radio-input',
				'value'   => $key,
				'checked' => $key === $value,
			];

			$html .= '<label class="' . esc_attr( $label_class ) . '" for="' . esc_attr( $id ) . '">';
			$html .= '<input ' . $this->array_to_attributes( $radio_attr ) . ' />';
			$html .= '<span>' . esc_html( $title ) . '</span>';
			$html .= '</label>';
			$html .= 'inline' === $this->get_setting( 'display' ) ? ' ' : '<br>';
		}

		$html .= '</fieldset>';

		return $html;
	}
}
