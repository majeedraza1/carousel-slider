<?php

namespace CarouselSlider\Supports\FormFields;

/**
 * TermsList class
 */
class SelectTerms extends Select {
	/**
	 * Render field html
	 *
	 * @inerhitDoc
	 */
	public function render(): string {
		$this->set_setting( 'searchable', true );
		$terms   = get_terms( [ 'taxonomy' => $this->get_setting( 'taxonomy', 'category' ) ] );
		$choices = [];
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$choices[] = [
					'value' => $term->term_id,
					'label' => sprintf( '%s (%s)', $term->name, $term->count ),
				];
			}
		}
		$this->set_setting( 'choices', $choices );

		return parent::render();
	}
}
