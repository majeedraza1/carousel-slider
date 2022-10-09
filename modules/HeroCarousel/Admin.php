<?php

namespace CarouselSlider\Modules\HeroCarousel;

use CarouselSlider\Helper;
use CarouselSlider\Modules\HeroCarousel\Helper as HeroCarouselHelper;
use CarouselSlider\Supports\MetaBoxForm;

defined( 'ABSPATH' ) || exit;

/**
 * Admin class
 *
 * @package Modules/HeroCarousel
 */
class Admin {

	/**
	 * The instance of the class
	 *
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
			self::$instance = new self();

			add_action( 'carousel_slider/meta_box_content', [ self::$instance, 'meta_box_content' ], 10, 2 );
		}

		return self::$instance;
	}

	/**
	 * Load meta box content
	 *
	 * @param int    $slider_id The slider id.
	 * @param string $slide_type The slider type.
	 */
	public function meta_box_content( int $slider_id, string $slide_type ) {
		if ( 'hero-banner-slider' !== $slide_type ) {
			return;
		}
		global $post;
		?>
		<button class="button carousel-slider__add-slide" data-post-id="<?php echo esc_attr( $slider_id ); ?>">
			Add Slide
		</button>
		<div id="carouselSliderContentInside">
			<?php
			$content_sliders  = get_post_meta( $post->ID, '_content_slider', true );
			$content_sliders  = is_array( $content_sliders ) ? array_values( $content_sliders ) : [];
			$content_settings = get_post_meta( $post->ID, '_content_slider_settings', true );
			$content_settings = is_array( $content_settings ) ? $content_settings : [];

			if ( count( $content_sliders ) > 0 ) {
				$total_sliders = count( $content_sliders );
				foreach ( $content_sliders as $slide_num => $content_slider ) {
					$item = new Item( $content_slider, $content_settings );
					$item->set_setting( new Setting( $post->ID ) );
					$item->set_prop( 'id', $slide_num + 1 );
					$item->set_prop( 'total_items', $total_sliders );

					self::item_meta_box( $item, $total_sliders );
				}
			}
			?>
		</div>

		<?php self::content_meta_box_settings( $post->ID ); ?>
		<?php
	}

	/**
	 * Item meta box
	 *
	 * @param Item $item The Item object.
	 * @param int  $total_items Total items.
	 */
	public static function item_meta_box( Item $item, int $total_items = 0 ) {
		$title       = sprintf( '%s %s', __( 'Slide', 'carousel-slider' ), $item->get_item_id() );
		$action_html = self::get_actions_html( $item->get_slider_id(), $item->get_item_id() - 1, $total_items );
		?>
		<div class="shapla-toggle shapla-toggle--normal" data-id="closed">
			<div class="shapla-toggle-title"><?php echo esc_html( $title ); ?></div>
			<div class="shapla-toggle-inner">
				<div class="shapla-toggle-content">

					<div class="carousel_slider__slide_actions"><?php echo wp_kses_post( $action_html ); ?></div>
					<div class="clear" style="width: 100%; margin-bottom: 1rem; height: 1px;"></div>

					<div class="shapla-section shapla-tabs shapla-tabs--stroke">
						<div class="shapla-tab-inner">

							<ul class="shapla-nav shapla-clearfix">
								<li>
									<a href="#carousel-slider-tab-background">
										<?php
										esc_html_e(
											'Slide Background',
											'carousel-slider'
										);
										?>
									</a>
								</li>
								<li>
									<a href="#carousel-slider-tab-content">
										<?php
										esc_html_e(
											'Slide Content',
											'carousel-slider'
										);
										?>
									</a>
								</li>
								<li>
									<a href="#carousel-slider-tab-link">
										<?php
										esc_html_e(
											'Slide Link',
											'carousel-slider'
										);
										?>
									</a>
								</li>
								<li>
									<a href="#carousel-slider-tab-style">
										<?php
										esc_html_e(
											'Slide Style',
											'carousel-slider'
										);
										?>
									</a>
								</li>
							</ul>

							<div id="carousel-slider-tab-content" class="shapla-tab tab-content">
								<?php self::get_item_tab_content( $item ); ?>
							</div>
							<div id="carousel-slider-tab-link" class="shapla-tab tab-content-link">
								<?php self::get_item_tab_link( $item ); ?>
							</div>
							<div id="carousel-slider-tab-background" class="shapla-tab tab-background">
								<?php self::get_item_tab_background( $item ); ?>
							</div>
							<div id="carousel-slider-tab-style" class="shapla-tab tab-style">
								<?php self::get_item_tab_style( $item ); ?>
							</div>
						</div>
					</div>

					<div class="clear"></div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Get loop item content
	 *
	 * @param Item $item The Item object.
	 */
	public static function get_item_tab_content( Item $item ) {
		$form = new MetaBoxForm();

		$form->textarea(
			[
				'id'               => 'slide_heading',
				'name'             => esc_html__( 'Slide Heading', 'carousel-slider' ),
				'desc'             => esc_html__( 'Enter the heading for your slide. This field can take HTML markup.', 'carousel-slider' ),
				'rows'             => 3,
				'input_attributes' => [
					'name'  => sprintf( 'carousel_slider_content[%s][slide_heading]', $item->get_item_id() ),
					'value' => $item->get_prop( 'slide_heading' ),
				],
			]
		);
		$form->textarea(
			[
				'id'               => 'slide_description',
				'name'             => esc_html__( 'Slide Description', 'carousel-slider' ),
				'desc'             => esc_html__( 'Enter the description for your slide. This field can take HTML markup.', 'carousel-slider' ),
				'rows'             => 4,
				'input_attributes' => [
					'name'  => sprintf( 'carousel_slider_content[%s][slide_description]', $item->get_item_id() ),
					'value' => $item->get_prop( 'slide_description' ),
				],
			]
		);
	}

	/**
	 * Get item tab link
	 *
	 * @param Item $item The Item object.
	 */
	public static function get_item_tab_link( Item $item ) {
		$form      = new MetaBoxForm();
		$link_type = $item->get_prop( 'link_type', 'full' );

		$form->select(
			[
				'id'               => 'link_type',
				'class'            => 'sp-input-text link_type',
				'name'             => esc_html__( 'Slide Link Type', 'carousel-slider' ),
				'desc'             => esc_html__( 'Select how the slide will link.', 'carousel-slider' ),
				'options'          => [
					'none'   => esc_html__( 'No Link', 'carousel-slider' ),
					'full'   => esc_html__( 'Full Slide', 'carousel-slider' ),
					'button' => esc_html__( 'Button', 'carousel-slider' ),
				],
				'input_attributes' => [
					'name'  => sprintf( 'carousel_slider_content[%s][link_type]', $item->get_item_id() ),
					'value' => $item->get_prop( 'link_type' ),
				],
			]
		);

		echo '<div class="ContentCarouselLinkFull" style="' . ( 'full' === $link_type ? 'display:block' : 'display:none' ) . '">';
		$form->text(
			[
				'id'               => 'slide_link',
				'name'             => esc_html__( 'Slide Link', 'carousel-slider' ),
				'desc'             => esc_html__( 'Please enter your URL that will be used to link the full slide.', 'carousel-slider' ),
				'input_attributes' => [
					'name'  => sprintf( 'carousel_slider_content[%s][slide_link]', $item->get_item_id() ),
					'value' => $item->get_prop( 'slide_link' ),
				],
			]
		);
		$form->select(
			[
				'id'               => 'link_target',
				'name'             => esc_html__( 'Open Slide Link In New Window', 'carousel-slider' ),
				'input_attributes' => [
					'name'  => sprintf( 'carousel_slider_content[%s][link_target]', $item->get_item_id() ),
					'value' => $item->get_prop( 'link_target' ),
				],
				'options'          => [
					'_blank' => esc_html__( 'Yes', 'carousel-slider' ),
					'_self'  => esc_html__( 'No', 'carousel-slider' ),
				],
			]
		);
		echo '</div>';

		echo '<div class="ContentCarouselLinkButtons" style="' . ( 'button' === $link_type ? 'display:block' : 'display:none' ) . '">';
		self::get_item_tab_link_button_one( $item );
		self::get_item_tab_link_button_two( $item );
		echo '</div>';
	}

	/**
	 * Get loop item button one link
	 *
	 * @param Item $item The Item object.
	 */
	public static function get_item_tab_link_button_one( Item $item ) {
		$form = new MetaBoxForm();
		?>
		<div data-id="closed" id="content_carousel_button_one" class="shapla-toggle shapla-toggle--stroke">
			<span class="shapla-toggle-title">
				<?php esc_html_e( 'Button #1', 'carousel-slider' ); ?>
			</span>
			<div class="shapla-toggle-inner">
				<div class="shapla-toggle-content">
					<?php
					$form->text(
						[
							'id'               => 'button_one_text',
							'name'             => esc_html__( 'Button Text', 'carousel-slider' ),
							'desc'             => esc_html__( 'Add button text', 'carousel-slider' ),
							'input_attributes' => [
								'name'  => sprintf( 'carousel_slider_content[%s][button_one_text]', $item->get_item_id() ),
								'value' => $item->get_prop( 'button_one_text' ),
							],
						]
					);
					$form->text(
						[
							'id'               => 'button_one_url',
							'name'             => esc_html__( 'Button URL', 'carousel-slider' ),
							'desc'             => esc_html__( 'Add the button url e.g. https://example.com', 'carousel-slider' ),
							'input_attributes' => [
								'name'  => sprintf( 'carousel_slider_content[%s][button_one_url]', $item->get_item_id() ),
								'value' => $item->get_prop( 'button_one_url' ),
							],
						]
					);
					$form->select(
						[
							'id'               => 'button_one_target',
							'name'             => esc_html__( 'Open Button Link In', 'carousel-slider' ),
							'desc'             => esc_html__( 'Add the button url e.g. https://example.com', 'carousel-slider' ),
							'options'          => [
								'_blank' => esc_html__( 'New Window', 'carousel-slider' ),
								'_self'  => esc_html__( 'Same Window', 'carousel-slider' ),
							],
							'input_attributes' => [
								'name'  => sprintf( 'carousel_slider_content[%s][button_one_target]', $item->get_item_id() ),
								'value' => $item->get_prop( 'button_one_target', '_self' ),
							],
						]
					);
					$form->select(
						[
							'id'               => 'button_one_type',
							'name'             => esc_html__( 'Button Type', 'carousel-slider' ),
							'options'          => [
								'normal' => esc_html__( 'Normal', 'carousel-slider' ),
								'stroke' => esc_html__( 'Stroke', 'carousel-slider' ),
							],
							'input_attributes' => [
								'name'  => sprintf( 'carousel_slider_content[%s][button_one_type]', $item->get_item_id() ),
								'value' => $item->get_prop( 'button_one_type' ),
							],
						]
					);
					$form->select(
						[
							'id'               => 'button_one_size',
							'name'             => esc_html__( 'Button Size', 'carousel-slider' ),
							'options'          => [
								'large'  => esc_html__( 'Large', 'carousel-slider' ),
								'medium' => esc_html__( 'Medium', 'carousel-slider' ),
								'small'  => esc_html__( 'Small', 'carousel-slider' ),
							],
							'input_attributes' => [
								'name'  => sprintf( 'carousel_slider_content[%s][button_one_size]', $item->get_item_id() ),
								'value' => $item->get_prop( 'button_one_size' ),
							],
						]
					);
					$form->text(
						[
							'id'               => 'button_one_border_width',
							'name'             => esc_html__( 'Border Width', 'carousel-slider' ),
							'desc'             => esc_html__( 'Enter border width in pixel. e.g. 2px', 'carousel-slider' ),
							'input_attributes' => [
								'name'  => sprintf( 'carousel_slider_content[%s][button_one_border_width]', $item->get_item_id() ),
								'value' => $item->get_prop( 'button_one_border_width' ),
							],
						]
					);
					$form->text(
						[
							'id'               => 'button_one_border_radius',
							'name'             => esc_html__( 'Border Radius', 'carousel-slider' ),
							'desc'             => esc_html__( 'Enter border radius in pixel. e.g. 2px', 'carousel-slider' ),
							'input_attributes' => [
								'name'  => sprintf( 'carousel_slider_content[%s][button_one_border_radius]', $item->get_item_id() ),
								'value' => $item->get_prop( 'button_one_border_radius' ),
							],
						]
					);
					$form->color(
						[
							'id'               => 'button_one_bg_color',
							'name'             => esc_html__( 'Button Color', 'carousel-slider' ),
							'std'              => '#00d1b2',
							'input_attributes' => [
								'name'  => sprintf( 'carousel_slider_content[%s][button_one_bg_color]', $item->get_item_id() ),
								'value' => $item->get_prop( 'button_one_bg_color', '#00d1b2' ),
							],
						]
					);
					$form->color(
						[
							'id'               => 'button_one_color',
							'name'             => esc_html__( 'Button Text Color', 'carousel-slider' ),
							'std'              => '#ffffff',
							'input_attributes' => [
								'name'  => sprintf( 'carousel_slider_content[%s][button_one_color]', $item->get_item_id() ),
								'value' => $item->get_prop( 'button_one_color', '#ffffff' ),
							],
						]
					);
					?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Get loop item button two link
	 *
	 * @param Item $item The Item object.
	 */
	public static function get_item_tab_link_button_two( Item $item ) {
		$form = new MetaBoxForm();
		?>
		<div data-id="closed" id="content_carousel_button_one" class="shapla-toggle shapla-toggle--stroke">
			<span class="shapla-toggle-title">
				<?php esc_html_e( 'Button #2', 'carousel-slider' ); ?>
			</span>
			<div class="shapla-toggle-inner">
				<div class="shapla-toggle-content">
					<?php
					$form->text(
						[
							'id'               => 'button_two_text',
							'name'             => esc_html__( 'Button Text', 'carousel-slider' ),
							'desc'             => esc_html__( 'Add button text', 'carousel-slider' ),
							'input_attributes' => [
								'name'  => sprintf( 'carousel_slider_content[%s][button_two_text]', $item->get_item_id() ),
								'value' => $item->get_prop( 'button_two_text' ),
							],
						]
					);
					$form->text(
						[
							'id'               => 'button_two_url',
							'name'             => esc_html__( 'Button URL', 'carousel-slider' ),
							'desc'             => esc_html__( 'Add the button url e.g. https://example.com', 'carousel-slider' ),
							'input_attributes' => [
								'name'  => sprintf( 'carousel_slider_content[%s][button_two_url]', $item->get_item_id() ),
								'value' => $item->get_prop( 'button_two_url' ),
							],
						]
					);
					$form->select(
						[
							'id'               => 'button_two_target',
							'name'             => esc_html__( 'Open Button Link In', 'carousel-slider' ),
							'desc'             => esc_html__( 'Add the button url e.g. https://example.com', 'carousel-slider' ),
							'options'          => [
								'_blank' => esc_html__( 'New Window', 'carousel-slider' ),
								'_self'  => esc_html__( 'Same Window', 'carousel-slider' ),
							],
							'input_attributes' => [
								'name'  => sprintf( 'carousel_slider_content[%s][button_two_target]', $item->get_item_id() ),
								'value' => $item->get_prop( 'button_two_target' ),
							],
						]
					);
					$form->select(
						[
							'id'               => 'button_two_type',
							'name'             => esc_html__( 'Button Type', 'carousel-slider' ),
							'options'          => [
								'normal' => esc_html__( 'Normal', 'carousel-slider' ),
								'stroke' => esc_html__( 'Stroke', 'carousel-slider' ),
							],
							'input_attributes' => [
								'name'  => sprintf( 'carousel_slider_content[%s][button_two_type]', $item->get_item_id() ),
								'value' => $item->get_prop( 'button_two_type', 'normal' ),
							],
						]
					);
					$form->select(
						[
							'id'               => 'button_two_size',
							'name'             => esc_html__( 'Button Size', 'carousel-slider' ),
							'options'          => [
								'large'  => esc_html__( 'Large', 'carousel-slider' ),
								'medium' => esc_html__( 'Medium', 'carousel-slider' ),
								'small'  => esc_html__( 'Small', 'carousel-slider' ),
							],
							'input_attributes' => [
								'name'  => sprintf( 'carousel_slider_content[%s][button_two_size]', $item->get_item_id() ),
								'value' => $item->get_prop( 'button_two_size' ),
							],
						]
					);
					$form->text(
						[
							'id'               => 'button_two_border_width',
							'name'             => esc_html__( 'Border Width', 'carousel-slider' ),
							'desc'             => esc_html__( 'Enter border width in pixel. e.g. 2px', 'carousel-slider' ),
							'input_attributes' => [
								'name'  => sprintf( 'carousel_slider_content[%s][button_two_border_width]', $item->get_item_id() ),
								'value' => $item->get_prop( 'button_two_border_width' ),
							],
						]
					);
					$form->text(
						[
							'id'               => 'button_two_border_radius',
							'name'             => esc_html__( 'Border Radius', 'carousel-slider' ),
							'desc'             => esc_html__( 'Enter border radius in pixel. e.g. 2px', 'carousel-slider' ),
							'input_attributes' => [
								'name'  => sprintf( 'carousel_slider_content[%s][button_two_border_radius]', $item->get_item_id() ),
								'value' => $item->get_prop( 'button_two_border_radius' ),
							],
						]
					);
					$form->color(
						[
							'id'               => 'button_two_bg_color',
							'name'             => esc_html__( 'Button Color', 'carousel-slider' ),
							'std'              => '#00d1b2',
							'input_attributes' => [
								'name'  => sprintf( 'carousel_slider_content[%s][button_two_bg_color]', $item->get_item_id() ),
								'value' => $item->get_prop( 'button_two_bg_color' ),
							],
						]
					);
					$form->color(
						[
							'id'               => 'button_two_color',
							'name'             => esc_html__( 'Button Text Color', 'carousel-slider' ),
							'std'              => '#ffffff',
							'input_attributes' => [
								'name'  => sprintf( 'carousel_slider_content[%s][button_two_color]', $item->get_item_id() ),
								'value' => $item->get_prop( 'button_two_color' ),
							],
						]
					);
					?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Get loop item background
	 *
	 * @param Item $item The Item object.
	 */
	public static function get_item_tab_background( Item $item ) {
		$form     = new MetaBoxForm();
		$bg_image = wp_get_attachment_image_src( $item->get_prop( 'img_id' ), 'full' );

		// Canvas style.
		$canvas_style = [
			'background-repeat'   => 'no-repeat',
			'background-position' => $item->get_prop( 'img_bg_position', 'center center' ),
			'background-size'     => $item->get_prop( 'img_bg_size', 'cover' ),
			'background-color'    => $item->get_prop( 'bg_color' ),
		];
		if ( is_array( $bg_image ) ) {
			$canvas_style['background-image'] = 'url(' . $bg_image[0] . ')';
		}
		$canvas_style = Helper::array_to_style( $canvas_style );
		?>
		<div class="slide_bg_wrapper">
			<div class="slide-media-left">
				<div class="slide_thumb">
					<div class="content_slide_canvas"
						 style="<?php echo esc_attr( $canvas_style ); ?>"></div>
					<span class="delete-bg-img<?php echo ! is_array( $bg_image ) ? ' hidden' : ''; ?>"
						  title="<?php esc_html_e( 'Delete the background image for this slide', 'carousel-slider' ); ?>">&times;</span>
				</div>
			</div>
			<div class="slide-media-right">
				<?php
				$form->upload_iframe(
					[
						'id'               => 'img_id',
						'class'            => 'background_image_id',
						'label'            => esc_html__( 'Background Image', 'carousel-slider' ),
						'input_attributes' => [
							'name'  => sprintf( 'carousel_slider_content[%s][img_id]', $item->get_item_id() ),
							'value' => $item->get_prop( 'img_id' ),
						],
					]
				);
				$form->select(
					[
						'id'               => 'img_bg_position',
						'class'            => 'sp-input-text background_image_position',
						'name'             => esc_html__( 'Background Position', 'carousel-slider' ),
						'options'          => HeroCarouselHelper::background_position(),
						'input_attributes' => [
							'name'  => sprintf( 'carousel_slider_content[%s][img_bg_position]', $item->get_item_id() ),
							'value' => $item->get_prop( 'img_bg_position' ),
						],
					]
				);
				$form->select(
					[
						'id'               => 'img_bg_size',
						'class'            => 'sp-input-text background_image_size',
						'name'             => esc_html__( 'Background Size', 'carousel-slider' ),
						'options'          => HeroCarouselHelper::background_size(),
						'input_attributes' => [
							'name'  => sprintf( 'carousel_slider_content[%s][img_bg_size]', $item->get_item_id() ),
							'value' => $item->get_prop( 'img_bg_size' ),
						],
					]
				);
				$form->select(
					[
						'id'               => 'ken_burns_effect',
						'name'             => esc_html__( 'Ken Burns Effect', 'carousel-slider' ),
						'options'          => HeroCarouselHelper::ken_burns_effects(),
						'input_attributes' => [
							'name'  => sprintf( 'carousel_slider_content[%s][ken_burns_effect]', $item->get_item_id() ),
							'value' => $item->get_prop( 'ken_burns_effect' ),
						],
					]
				);
				$form->color(
					[
						'id'               => 'bg_color',
						'name'             => esc_html__( 'Background Color', 'carousel-slider' ),
						'std'              => 'rgba(255,255,255,0.5)',
						'input_attributes' => [
							'name'  => sprintf( 'carousel_slider_content[%s][bg_color]', $item->get_item_id() ),
							'value' => $item->get_prop( 'bg_color' ),
						],
					]
				);
				$form->color(
					[
						'id'               => 'bg_overlay',
						'name'             => esc_html__( 'Background Overlay', 'carousel-slider' ),
						'std'              => 'rgba(0,0,0,0.5)',
						'input_attributes' => [
							'name'  => sprintf( 'carousel_slider_content[%s][bg_overlay]', $item->get_item_id() ),
							'value' => $item->get_prop( 'bg_overlay' ),
						],
					]
				);
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Get item tab style
	 *
	 * @param Item $item The Item object.
	 */
	public static function get_item_tab_style( Item $item ) {
		$form = new MetaBoxForm();

		$form->select(
			[
				'id'               => 'content_alignment',
				'name'             => esc_html__( 'Content Alignment', 'carousel-slider' ),
				'desc'             => esc_html__( 'Select how the heading, description and buttons will be aligned', 'carousel-slider' ),
				'std'              => 'left',
				'options'          => HeroCarouselHelper::text_alignment(),
				'input_attributes' => [
					'name'  => sprintf( 'carousel_slider_content[%s][content_alignment]', $item->get_item_id() ),
					'value' => $item->get_prop( 'content_alignment' ),
				],
			]
		);
		$form->number(
			[
				'id'               => 'heading_font_size',
				'name'             => esc_html__( 'Heading Font Size', 'carousel-slider' ),
				'desc'             => esc_html__( 'Enter heading font size without px unit. In pixels, ex: 50 instead of 50px. Default: 60', 'carousel-slider' ),
				'std'              => '60',
				'input_attributes' => [
					'name'  => sprintf( 'carousel_slider_content[%s][heading_font_size]', $item->get_item_id() ),
					'value' => $item->get_prop( 'heading_font_size' ),
				],
			]
		);
		$form->text(
			[
				'id'               => 'heading_gutter',
				'name'             => esc_html__( 'Spacing/Gutter', 'carousel-slider' ),
				'desc'             => esc_html__( 'Enter gutter (space between description and heading) in px, em or rem, ex: 3rem', 'carousel-slider' ),
				'std'              => '30px',
				'input_attributes' => [
					'name'  => sprintf( 'carousel_slider_content[%s][heading_gutter]', $item->get_item_id() ),
					'value' => $item->get_prop( 'heading_gutter' ),
				],
			]
		);
		$form->color(
			[
				'id'               => 'heading_color',
				'name'             => esc_html__( 'Heading Color', 'carousel-slider' ),
				'desc'             => esc_html__( 'Select a color for the heading font. Default: #fff', 'carousel-slider' ),
				'std'              => '#ffffff',
				'input_attributes' => [
					'name'  => sprintf( 'carousel_slider_content[%s][heading_color]', $item->get_item_id() ),
					'value' => $item->get_prop( 'heading_color' ),
				],
			]
		);
		$form->text(
			[
				'id'               => 'description_font_size',
				'name'             => esc_html__( 'Description Font Size', 'carousel-slider' ),
				'desc'             => esc_html__( 'Enter description font size without px unit. In pixels, ex: 20 instead of 20px. Default: 24', 'carousel-slider' ),
				'std'              => '24',
				'input_attributes' => [
					'name'  => sprintf( 'carousel_slider_content[%s][description_font_size]', $item->get_item_id() ),
					'value' => $item->get_prop( 'description_font_size' ),
				],
			]
		);
		$form->text(
			[
				'id'               => 'description_gutter',
				'name'             => esc_html__( 'Description Spacing/Gutter', 'carousel-slider' ),
				'desc'             => esc_html__( 'Enter gutter (space between description and buttons) in px, em or rem, ex: 3rem', 'carousel-slider' ),
				'std'              => '30px',
				'input_attributes' => [
					'name'  => sprintf( 'carousel_slider_content[%s][description_gutter]', $item->get_item_id() ),
					'value' => $item->get_prop( 'description_gutter' ),
				],
			]
		);
		$form->color(
			[
				'id'               => 'description_color',
				'name'             => esc_html__( 'Description Color', 'carousel-slider' ),
				'desc'             => esc_html__( 'Select a color for the description font. Default: #fff', 'carousel-slider' ),
				'std'              => '#ffffff',
				'input_attributes' => [
					'name'  => sprintf( 'carousel_slider_content[%s][description_color]', $item->get_item_id() ),
					'value' => $item->get_prop( 'description_color' ),
				],
			]
		);
	}

	/**
	 * Get action html
	 *
	 * @param int $slider_id The slider id.
	 * @param int $item_index The slider item index number.
	 * @param int $total_items Total slide in a slider.
	 *
	 * @return string
	 */
	public static function get_actions_html( int $slider_id, int $item_index, int $total_items ): string {
		$can_move_up   = 0 !== $item_index;
		$can_move_down = ( $total_items - 1 ) !== $item_index;
		$buttons       = [
			[
				'action' => 'delete_slide',
				'icon'   => 'trash',
				'title'  => __( 'Delete current slide', 'carousel-slider' ),
			],
		];
		if ( $can_move_up ) {
			$buttons[] = [
				'action' => 'move_top',
				'icon'   => 'arrow-up-alt',
				'title'  => __( 'Move Slide to Top', 'carousel-slider' ),
			];
			$buttons[] = [
				'action' => 'move_up',
				'icon'   => 'arrow-up-alt2',
				'title'  => __( 'Move Slide Up', 'carousel-slider' ),
			];
		}
		if ( $can_move_down ) {
			$buttons[] = [
				'action' => 'move_down',
				'icon'   => 'arrow-down-alt2',
				'title'  => __( 'Move Slide Down', 'carousel-slider' ),
			];
			$buttons[] = [
				'action' => 'move_bottom',
				'icon'   => 'arrow-down-alt',
				'title'  => __( 'Move Slide to Bottom', 'carousel-slider' ),
			];
		}
		$html = '';
		foreach ( $buttons as $button ) {
			$html .= sprintf(
				'<button class="button carousel_slider__%s" data-post-id="%s" data-slide-pos="%s" title="%s">',
				$button['action'],
				$slider_id,
				$item_index,
				$button['title']
			);
			$html .= sprintf( '<span class="dashicons dashicons-%s"></span>', $button['icon'] );
			$html .= '</button>';
		}

		return $html;
	}

	/**
	 * Get content settings
	 *
	 * @param int $slider_id The slider id.
	 */
	public static function content_meta_box_settings( int $slider_id ) {
		$content_settings   = get_post_meta( $slider_id, '_content_slider_settings', true );
		$_slide_height      = $content_settings['slide_height'] ?? '400px';
		$_content_width     = $content_settings['content_width'] ?? '850px';
		$_content_animation = $content_settings['content_animation'] ?? '';
		$form               = new MetaBoxForm();

		echo '<div class="content_settings">';
		$form->text(
			[
				'group'            => 'content_settings',
				'id'               => 'slide_height',
				'name'             => esc_html__( 'Slide Height', 'carousel-slider' ),
				'desc'             => esc_html__( 'Enter a px, em, rem or vh value for slide height. ex: 100vh', 'carousel-slider' ),
				'std'              => '400px',
				'input_attributes' => [
					'name'  => 'content_settings[slide_height]',
					'value' => $_slide_height,
				],
			]
		);
		$form->text(
			[
				'group'            => 'content_settings',
				'id'               => 'content_width',
				'name'             => esc_html__( 'Slider Content Max Width', 'carousel-slider' ),
				'desc'             => esc_html__( 'Enter a px, em, rem or % value for slide height. ex: 960px', 'carousel-slider' ),
				'std'              => '850px',
				'input_attributes' => [
					'name'  => 'content_settings[content_width]',
					'value' => $_content_width,
				],
			]
		);
		$form->select(
			[
				'group'            => 'content_settings',
				'id'               => 'content_animation',
				'name'             => esc_html__( 'Content Animation', 'carousel-slider' ),
				'desc'             => esc_html__( 'Select slide content animation.', 'carousel-slider' ),
				'std'              => 'fadeOut',
				'options'          => HeroCarouselHelper::animations(),
				'input_attributes' => [
					'name'  => 'content_settings[content_animation]',
					'value' => $_content_animation,
				],
			]
		);
		$form->spacing(
			[
				'meta_key' => '_content_slider_settings',
				'group'    => 'content_settings',
				'id'       => 'slide_padding',
				'name'     => esc_html__( 'Slider Padding', 'carousel-slider' ),
				'desc'     => esc_html__( 'Enter padding around slide in px, em or rem.', 'carousel-slider' ),
				'default'  => [
					'top'    => '1rem',
					'right'  => '1rem',
					'bottom' => '1rem',
					'left'   => '1rem',
				],
			]
		);
		echo '</div>';
	}
}
