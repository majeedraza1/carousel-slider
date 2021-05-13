<?php

namespace CarouselSlider\Modules\HeroCarousel;

use CarouselSlider\Supports\MetaBoxForm;

class HeroCarouselAdmin {

	/**
	 * @var self
	 */
	private static $instance;

	/**
	 * Only one instance of the class can be loaded
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;

			add_action( 'carousel_slider/meta_box_content', [ self::$instance, 'meta_box_content' ], 10, 2 );
		}

		return self::$instance;
	}

	/**
	 * Load meta box content
	 *
	 * @param int $slider_id
	 * @param string $slide_type
	 */
	public function meta_box_content( int $slider_id, string $slide_type ) {
		global $post;
		?>
		<div data-id="open" id="section_content_carousel" class="shapla-toggle shapla-toggle--stroke"
			 style="display: <?php echo $slide_type != 'hero-banner-slider' ? 'none' : 'block'; ?>">
			<span class="shapla-toggle-title">
				<?php esc_html_e( 'Hero Banner Slider', 'carousel-slider' ); ?>
			</span>
			<div class="shapla-toggle-inner">
				<div class="shapla-toggle-content">
					<button class="button carousel-slider__add-slide" data-post-id="<?php echo $slider_id; ?>">
						Add Slide
					</button>
					<div id="carouselSliderContentInside">
						<?php
						$content_sliders  = get_post_meta( $post->ID, '_content_slider', true );
						$content_settings = get_post_meta( $post->ID, '_content_slider_settings', true );

						if ( is_array( $content_sliders ) && count( $content_sliders ) > 0 ) {
							$total_sliders = count( $content_sliders );
							foreach ( $content_sliders as $slide_num => $content_slider ) {
								$item = new Item( $content_slider, array_merge( $content_settings, [
									'item_id'         => $slide_num,
									'slider_id'       => $post->ID,
									'total_items'     => $total_sliders,
									'lazy_load_image' => true
								] ) );

								HeroCarouselAdmin::item_meta_box( $item, $total_sliders );
							}
						}
						?>
					</div>

					<?php HeroCarouselAdmin::content_meta_box_settings( $post->ID ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	public static function item_meta_box( Item $item, int $total_items = 0 ) {
		$slide_num      = $item_index = $item->get_item_id();
		$total_sliders  = $total_items;
		$content_slider = $item->to_array();
		global $post;
		?>
		<div class="shapla-toggle shapla-toggle--normal" data-id="closed">
			<div class="shapla-toggle-title">
				<?php printf( '%s %s', esc_html__( 'Slide', 'carousel-slider' ), $slide_num + 1 ); ?>
			</div>
			<div class="shapla-toggle-inner">
				<div class="shapla-toggle-content">

					<div class="carousel_slider__slide_actions">
						<?php echo HeroCarouselAdmin::get_actions_html( $item->get_slider_id(), $slide_num, $total_sliders ) ?>
					</div>
					<div class="clear" style="width: 100%; margin-bottom: 1rem; height: 1px;"></div>

					<div class="shapla-section shapla-tabs shapla-tabs--stroke">
						<div class="shapla-tab-inner">

							<ul class="shapla-nav shapla-clearfix">
								<li>
									<a href="#carousel-slider-tab-background"><?php esc_html_e( 'Slide Background',
											'carousel-slider' ); ?></a>
								</li>
								<li>
									<a href="#carousel-slider-tab-content"><?php esc_html_e( 'Slide Content',
											'carousel-slider' ); ?></a>
								</li>
								<li>
									<a href="#carousel-slider-tab-link"><?php esc_html_e( 'Slide Link',
											'carousel-slider' ); ?></a>
								</li>
								<li>
									<a href="#carousel-slider-tab-style"><?php esc_html_e( 'Slide Style',
											'carousel-slider' ); ?></a>
								</li>
							</ul>

							<?php
							include CAROUSEL_SLIDER_TEMPLATES . '/admin/hero-banner/tab-content.php';
							include CAROUSEL_SLIDER_TEMPLATES . '/admin/hero-banner/tab-link.php';
							include CAROUSEL_SLIDER_TEMPLATES . '/admin/hero-banner/tab-background.php';
							include CAROUSEL_SLIDER_TEMPLATES . '/admin/hero-banner/tab-style.php';
							?>
						</div>
					</div>

					<div class="clear"></div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Get action html
	 *
	 * @param int $slider_id
	 * @param int $item_index
	 * @param int $total_items
	 *
	 * @return string
	 */
	public static function get_actions_html( int $slider_id, int $item_index, int $total_items ): string {
		$can_move_up   = $item_index !== 0;
		$can_move_down = $item_index !== ( $total_items - 1 );
		$buttons       = [
			[
				'action' => 'delete_slide',
				'icon'   => 'trash',
				'title'  => __( 'Delete current slide', 'carousel-slider' )
			]
		];
		if ( $can_move_up ) {
			$buttons[] = [
				'action' => 'move_top',
				'icon'   => 'arrow-up-alt',
				'title'  => __( 'Move Slide to Top', 'carousel-slider' )
			];
			$buttons[] = [
				'action' => 'move_up',
				'icon'   => 'arrow-up-alt2',
				'title'  => __( 'Move Slide Up', 'carousel-slider' )
			];
		}
		if ( $can_move_down ) {
			$buttons[] = [
				'action' => 'move_down',
				'icon'   => 'arrow-down-alt2',
				'title'  => __( 'Move Slide Down', 'carousel-slider' )
			];
			$buttons[] = [
				'action' => 'move_bottom',
				'icon'   => 'arrow-down-alt',
				'title'  => __( 'Move Slide to Bottom', 'carousel-slider' )
			];
		}
		$html = '';
		foreach ( $buttons as $button ) {
			$html .= sprintf( '<button class="button carousel_slider__%s" data-post-id="%s" data-slide-pos="%s" title="%s">',
				$button['action'], $slider_id, $item_index, $button['title'] );
			$html .= sprintf( '<span class="dashicons dashicons-%s"></span>', $button['icon'] );
			$html .= '</button>';
		}

		return $html;
	}

	/**
	 * Get content settings
	 *
	 * @param int $slider_id
	 */
	public static function content_meta_box_settings( int $slider_id ) {
		$content_settings   = get_post_meta( $slider_id, '_content_slider_settings', true );
		$_slide_height      = $content_settings['slide_height'] ?? '400px';
		$_content_width     = $content_settings['content_width'] ?? '850px';
		$_content_animation = $content_settings['content_animation'] ?? '';
		$form               = new MetaBoxForm;

		echo '<div class="content_settings">';
		$form->text( [
			'group'            => 'content_settings',
			'id'               => 'slide_height',
			'name'             => esc_html__( 'Slide Height', 'carousel-slider' ),
			'desc'             => esc_html__( 'Enter a px, em, rem or vh value for slide height. ex: 100vh', 'carousel-slider' ),
			'std'              => '400px',
			'input_attributes' => [
				'name'  => "content_settings[slide_height]",
				'value' => $_slide_height,
			],
		] );
		$form->text( [
			'group'            => 'content_settings',
			'id'               => 'content_width',
			'name'             => esc_html__( 'Slider Content Max Width', 'carousel-slider' ),
			'desc'             => esc_html__( 'Enter a px, em, rem or % value for slide height. ex: 960px', 'carousel-slider' ),
			'std'              => '850px',
			'input_attributes' => [
				'name'  => "content_settings[content_width]",
				'value' => $_content_width,
			],
		] );
		$form->select( [
			'group'            => 'content_settings',
			'id'               => 'content_animation',
			'name'             => esc_html__( 'Content Animation', 'carousel-slider' ),
			'desc'             => esc_html__( 'Select slide content animation.', 'carousel-slider' ),
			'std'              => 'fadeOut',
			'options'          => HeroCarouselHelper::animations(),
			'input_attributes' => [
				'name'  => "content_settings[content_animation]",
				'value' => $_content_animation,
			],
		] );
		$form->spacing( [
			'meta_key' => '_content_slider_settings',
			'group'    => 'content_settings',
			'id'       => 'slide_padding',
			'name'     => esc_html__( 'Slider Padding', 'carousel-slider' ),
			'desc'     => esc_html__( 'Enter padding around slide in px, em or rem.', 'carousel-slider' ),
			'default'  => [ 'top' => '1rem', 'right' => '1rem', 'bottom' => '1rem', 'left' => '1rem' ],
		] );
		echo '</div>';
	}
}
