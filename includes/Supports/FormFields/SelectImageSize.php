<?php

namespace CarouselSlider\Supports\FormFields;

use CarouselSlider\Helper;

/**
 * SelectImageSize class
 */
class SelectImageSize extends Select {
	/**
	 * Render field html
	 *
	 * @inerhitDoc
	 */
	public function render(): string {
		$this->set_setting( 'choices', Helper::get_available_image_sizes() );

		return parent::render();
	}
}
