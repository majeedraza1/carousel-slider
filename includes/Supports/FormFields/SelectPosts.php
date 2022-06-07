<?php

namespace CarouselSlider\Supports\FormFields;

/**
 * PostsList class
 */
class SelectPosts extends Select {
	/**
	 * Render field html
	 *
	 * @inerhitDoc
	 */
	public function render(): string {
		$this->set_setting( 'searchable', true );
		$posts = get_posts(
			[
				'post_type'      => $this->get_setting( 'post_type', 'post' ),
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
			]
		);

		$choices = [];
		foreach ( $posts as $post ) {
			$choices[] = [
				'value' => $post->ID,
				'label' => $post->post_title,
			];
		}

		$this->set_setting( 'choices', $choices );

		return parent::render();
	}
}
