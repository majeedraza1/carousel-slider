<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Carousel_Slider_Form' ) ) {

	class Carousel_Slider_Form {

		/**
		 * Generate text field
		 *
		 * @param array $args
		 */
		public function text( array $args ) {
			if ( ! isset( $args['id'], $args['name'] ) ) {
				return;
			}

			list( $name, $value, $input_id ) = $this->field_common( $args );
			$class = isset( $args['input_class'] ) ? esc_attr( $args['input_class'] ) : 'sp-input-text';

			echo $this->field_before( $args );
			echo sprintf( '<input type="text" class="' . $class . '" value="%1$s" id="' . $input_id . '" name="%3$s">', $value, $args['id'], $name );
			echo $this->field_after( $args );
		}

		/**
		 * Generate textarea field
		 *
		 * @param array $args
		 */
		public function textarea( array $args ) {
			global $post;

			if ( ! isset( $args['id'], $args['name'] ) ) {
				return;
			}

			list( $name, $value, $input_id ) = $this->field_common( $args );
			$cols = isset( $args['cols'] ) ? $args['cols'] : 35;
			$rows = isset( $args['rows'] ) ? $args['rows'] : 6;

			$class = empty( $args['input_class'] ) ? 'sp-input-textarea' : 'sp-input-textarea ' . esc_attr( $args['input_class'] );

			echo $this->field_before( $args );
			echo sprintf( '<textarea class="' . $class . '" id="' . $input_id . '" name="%3$s" cols="%4$d" rows="%5$d">%1$s</textarea>', esc_textarea( $value ), $args['id'], $name, $cols, $rows );
			echo $this->field_after( $args );
		}

		/**
		 * Generate color picker field
		 *
		 * @param array $args
		 */
		public function color( array $args ) {
			if ( ! isset( $args['id'], $args['name'] ) ) {
				return;
			}

			list( $name, $value, $input_id ) = $this->field_common( $args );
			$std_value = isset( $args['std'] ) ? $args['std'] : '';

			echo $this->field_before( $args );
			echo sprintf( '<input type="text" class="color-picker" value="%1$s" id="' . $input_id . '" name="%3$s" data-alpha="true" data-default-color="%4$s">', $value, $args['id'], $name, $std_value );
			echo $this->field_after( $args );
		}

		/**
		 * Generate date picker field
		 *
		 * @param array $args
		 */
		public function date( array $args ) {
			if ( ! isset( $args['id'], $args['name'] ) ) {
				return;
			}

			list( $name, $value ) = $this->field_common( $args );
			$std_value = isset( $args['std'] ) ? $args['std'] : '';

			echo $this->field_before( $args );
			echo sprintf( '<input type="text" class="sp-input-text datepicker" value="%1$s" id="%2$s" name="%3$s">', $value, $args['id'], $name, $std_value );
			echo $this->field_after( $args );
		}

		/**
		 * Generate number field
		 *
		 * @param array $args
		 */
		public function number( array $args ) {
			if ( ! isset( $args['id'], $args['name'] ) ) {
				return;
			}

			list( $name, $value, $input_id ) = $this->field_common( $args );
			$class = isset( $args['input_class'] ) ? esc_attr( $args['input_class'] ) : 'sp-input-text';

			echo $this->field_before( $args );
			echo '<input type="number" class="' . $class . '" value="' . $value . '" id="' . $input_id . '" name="' . $name . '">';
			echo $this->field_after( $args );
		}

		/**
		 * Generate checkbox field
		 *
		 * @param array $args
		 */
		public function checkbox( array $args ) {
			if ( ! isset( $args['id'], $args['name'] ) ) {
				return;
			}

			list( $name, $value ) = $this->field_common( $args );
			$checked = ( $value == 'on' ) ? ' checked' : '';
			$label   = isset( $args['label'] ) ? $args['label'] : '';

			echo $this->field_before( $args );
			echo sprintf( '<input type="hidden" name="%1$s" value="off">', $name );
			echo sprintf( '<label for="%2$s"><input type="checkbox" ' . $checked . ' value="on" id="%2$s" name="%1$s">%3$s</label>', $name, $args['id'], $label );
			echo $this->field_after( $args );
		}

		/**
		 * Generate select field
		 *
		 * @param $args
		 */
		public function select( $args ) {
			if ( ! isset( $args['id'], $args['name'] ) ) {
				return;
			}

			list( $name, $value ) = $this->field_common( $args );
			$multiple = isset( $args['multiple'] ) ? 'multiple' : '';
			$class    = isset( $args['input_class'] ) ? esc_attr( $args['input_class'] ) : 'select2 sp-input-text';

			echo $this->field_before( $args );
			echo sprintf( '<select name="%1$s" id="%2$s" class="' . $class . '" %3$s>', $name, $args['id'], $multiple );
			foreach ( $args['options'] as $key => $option ) {
				$selected = ( $value == $key ) ? ' selected="selected"' : '';
				echo '<option value="' . $key . '" ' . $selected . '>' . $option . '</option>';
			}
			echo '</select>';
			echo $this->field_after( $args );
		}

		/**
		 * @param array $args
		 */
		public function buttonset( array $args ) {
			list( $name, $value, $input_id ) = $this->field_common( $args );
			$input_class = empty( $args['input_class'] ) ? 'switch-input' : 'switch-input ' . $args['input_class'];

			echo $this->field_before( $args );

			echo '<div class="buttonset">';
			foreach ( $args['options'] as $key => $option ) {
				$input_id = $input_id . '_' . $key;
				$checked  = ( $value == $key ) ? ' checked="checked"' : '';
				echo '<input class="' . $input_class . '" id="' . $input_id . '" type="radio" value="' . $key . '"
                       name="' . $name . '" ' . $checked . '>';
				echo '<label class="switch-label switch-label-on" for="' . $input_id . '">' . $option . '</label></input>';
			}
			echo '</div>';

			echo $this->field_after( $args );
		}

		/**
		 * Generate gradient color picker
		 *
		 * @param array $args
		 */
		public function gradient_color( array $args ) {
			list( $name, $value, $input_id ) = $this->field_common( $args );
			/** @var \WP_Post $post */
			global $post;
			// Meta Name
			$group = isset( $args['group'] ) ? $args['group'] : 'carousel_slider';
			$name  = sprintf( '%s[%s]', $group, $args['id'] );

			// Meta Value
			$std_value = isset( $args['std'] ) ? $args['std'] : '';
			$meta      = get_post_meta( $post->ID, $args['id'], true );
			$value     = ! empty( $meta ) ? $meta : $std_value;

			if ( isset( $args['position'], $args['meta_key'] ) ) {
				$name = sprintf( '%s[%s][%s]', $group, $args['position'], $args['id'] );

				$meta  = get_post_meta( $post->ID, $args['meta_key'], true );
				$value = ! empty( $meta[ $args['position'] ][ $args['id'] ] ) ? $meta[ $args['position'] ][ $args['id'] ] : $std_value;
			}

			$name_colors = $name . '[colors]';
			$name_type   = $name . '[type]';
			$name_angle  = $name . '[angle]';
			$value_color = isset( $value['colors'] ) ? $value['colors'] : '';
			$value_type  = isset( $value['type'] ) ? $value['type'] : 'linear';
			$value_angle = isset( $value['angle'] ) ? intval( $value['angle'] ) : 270;

			$array_value = json_decode( $value_color, true );
			if ( is_array( $array_value ) && count( $array_value ) > 0 ) {
				$n_value = array();
				foreach ( $array_value as $_value ) {
					$n_value[] = sprintf( '%s %s%%', $_value['color'], round( $_value['position'] * 100 ) );
				}
				$colors = json_encode( $n_value );
			}

			$std_value   = isset( $args['std'] ) ? $args['std'] : '["#0fb8ad 0%", "#1fc8db 51%", "#2cb5e8 75%"]';
			$colors      = empty( $colors ) ? $std_value : $colors;
			$input_class = empty( $args['input_class'] ) ? 'gradient-color-colors' : 'gradient-color-colors ' . $args['input_class'];

			echo $this->field_before( $args );

			$types = array(
				'linear' => esc_html__( 'Linear', 'carousel-slider' ),
				'radial' => esc_html__( 'Radial', 'carousel-slider' ),
			);

			?>
            <div class="gradient-color-wrapper">
                <div class="gradient-color-picker-wrapper">
                    <strong><?php esc_html_e( 'Colors', 'carousel-slider' ); ?></strong>
                    <input type="hidden" name="<?php echo $name_colors; ?>" class="<?php echo $input_class; ?>"
                           value='<?php echo $value_color; ?>'/>
                    <div class="gradient-color-picker" data-points='<?php echo $colors; ?>'></div>
                </div>
                <div class="gradient-color-type-wrapper">
                    <strong><?php esc_html_e( 'Type', 'carousel-slider' ); ?></strong>
                    <div class="buttonset">
						<?php
						foreach ( $types as $type_slug => $type_title ) {
							?>
                            <input type="radio" id="<?php echo $input_id . '_type_' . $type_slug; ?>"
                                   class="switch-input gradient-color-type" name="<?php echo $name_type; ?>"
                                   value="<?php echo $type_slug; ?>" <?php checked( $type_slug, $value_type ) ?>>
                            <label class="switch-label"
                                   for="<?php echo $input_id . '_type_' . $type_slug; ?>"><?php echo $type_title; ?></label>
							<?php
						}
						?>
                    </div>
                </div>
                <div class="carousel-slider-range-wrapper"
                     style="display: <?php echo ( 'linear' == $value_type ) ? 'block' : 'none'; ?>;">
                    <strong><?php esc_html_e( 'Angle', 'carousel-slider' ); ?></strong>
                    <input type="range" name="<?php echo esc_attr( $name_angle ); ?>" min="0" max="360" step="1"
                           value="<?php echo $value_angle; ?>" data-reset_value="270" class="gradient-color-angle">
                    <div class="range-value">
                        <span class="value"><?php echo intval( $value_angle ); ?></span> deg
                    </div>
                    <div class="carousel-slider-range-reset"
                         title="<?php esc_attr_e( 'Reset to default value', 'carousel-slider' ) ?>">
                        <span class="dashicons dashicons-image-rotate"></span>
                    </div>
                </div>
            </div>
			<?php

			echo $this->field_after( $args );
		}

		/**
		 * Generate posts list dropdown
		 * Also support for any custom post type
		 *
		 * @param $args
		 */
		public function posts_list( $args ) {
			if ( ! isset( $args['id'], $args['name'] ) ) {
				return;
			}

			list( $name, $value ) = $this->field_common( $args );
			$value     = explode( ',', $value );
			$multiple  = isset( $args['multiple'] ) ? 'multiple' : '';
			$post_type = isset( $args['post_type'] ) ? $args['post_type'] : 'post';

			echo $this->field_before( $args );
			echo '<select name="' . $name . '" id="' . $args['id'] . '" class="select2 sp-input-text" ' . $multiple . '>';
			$posts = get_posts( array(
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				'posts_per_page' => - 1
			) );

			foreach ( $posts as $post ) {
				$selected = in_array( $post->ID, $value ) ? ' selected="selected"' : '';
				echo '<option value="' . $post->ID . '" ' . $selected . '>' . $post->post_title . '</option>';
			}
			echo '</select>';
			echo $this->field_after( $args );
		}

		/**
		 * Generate image gallery field
		 *
		 * @param $args
		 */
		public function images_gallery( $args ) {
			if ( ! isset( $args['id'], $args['name'] ) ) {
				return;
			}
			list( $name, $value ) = $this->field_common( $args );

			$btn_text = $value ? 'Edit Gallery' : 'Add Gallery';
			$value    = strip_tags( rtrim( $value, ',' ) );
			$output   = '';
			global $post;

			if ( $value ) {
				$thumbs = explode( ',', $value );
				foreach ( $thumbs as $thumb ) {
					$output .= '<li>' . wp_get_attachment_image( $thumb, array( 50, 50 ) ) . '</li>';
				}
			}

			$html = $this->field_before( $args );
			$html .= '<div class="carousel_slider_images">';
			$html .= sprintf( '<input type="hidden" value="%1$s" id="_carousel_slider_images_ids" name="%2$s">', $value, $name );
			$html .= sprintf(
				'<a href="#" id="%1$s" class="button" data-id="%2$s" data-ids="%3$s" data-create="%5$s" data-edit="%6$s" data-save="%7$s" data-progress="%8$s" data-insert="%9$s">%4$s</a>',
				'carousel_slider_gallery_btn',
				$post->ID,
				$value,
				$btn_text,
				esc_html__( 'Create Gallery', 'carousel-slider' ),
				esc_html__( 'Edit Gallery', 'carousel-slider' ),
				esc_html__( 'Save Gallery', 'carousel-slider' ),
				esc_html__( 'Saving...', 'carousel-slider' ),
				esc_html__( 'Insert', 'carousel-slider' )
			);
			$html .= sprintf( '<ul class="carousel_slider_gallery_list">%s</ul>', $output );
			$html .= '</div>';
			$html .= $this->field_after( $args );
			echo $html;
		}

		/**
		 * Generate image gallery list from images URL
		 *
		 * @param array $args
		 */
		public function images_url( array $args ) {
			if ( ! isset( $args['id'], $args['name'] ) ) {
				return;
			}

			global $post;

			$std_value = isset( $args['std'] ) ? $args['std'] : '';
			$meta      = get_post_meta( $post->ID, $args['id'], true );
			$value     = ! empty( $meta ) ? $meta : $std_value;

			$btn_text = $value ? __( 'Edit URLs', 'carousel-slider' ) : __( 'Add URLs', 'carousel-slider' );

			$html = $this->field_before( $args );
			$html .= sprintf( '<a id="_images_urls_btn" class="button" href="#">%s</a>', $btn_text );
			$html .= '<ul class="carousel_slider_url_images_list">';
			if ( is_array( $value ) && count( $value ) > 0 ) {
				foreach ( $value as $image ) {
					$html .= '<li><img src="' . $image['url'] . '" alt="' . $image['alt'] . '" width="75" height="75"></li>';
				}
			}
			$html .= '</ul>';
			$html .= $this->field_after( $args );
			echo $html;
		}

		/**
		 * @param array $args
		 */
		public function file( array $args ) {
			if ( ! isset( $args['id'], $args['name'] ) ) {
				return;
			}

			list( $name, $value ) = $this->field_common( $args );

			echo $this->field_before( $args );
			echo sprintf( '<input type="text" class="sp-input-text" value="%1$s" id="%2$s" name="%3$s">', $value, $args['id'], $name );
			echo sprintf( '<input type="button" class="button" id="carousel_slider_video_btn" value="%s">', __( 'Browse', 'carousel-slider' ) );
			echo $this->field_after( $args );
		}

		/**
		 * Generate image sizes dropdown from available image sizes
		 *
		 * @param array $args
		 */
		public function image_sizes( array $args ) {
			if ( ! isset( $args['id'], $args['name'] ) ) {
				return;
			}

			list( $name, $value ) = $this->field_common( $args );

			global $_wp_additional_image_sizes;

			$sizes = array();

			foreach ( get_intermediate_image_sizes() as $_size ) {
				if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {

					$width  = get_option( "{$_size}_size_w" );
					$height = get_option( "{$_size}_size_h" );
					$crop   = (bool) get_option( "{$_size}_crop" ) ? 'hard' : 'soft';

					$sizes[ $_size ] = "{$_size} - $crop:{$width}x{$height}";

				} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

					$width  = $_wp_additional_image_sizes[ $_size ]['width'];
					$height = $_wp_additional_image_sizes[ $_size ]['height'];
					$crop   = $_wp_additional_image_sizes[ $_size ]['crop'] ? 'hard' : 'soft';

					$sizes[ $_size ] = "{$_size} - $crop:{$width}x{$height}";
				}
			}

			$sizes = array_merge( $sizes, array( 'full' => 'original uploaded image' ) );


			echo $this->field_before( $args );
			echo '<select name="' . $name . '" id="' . $args['id'] . '" class="select2 sp-input-text">';
			foreach ( $sizes as $key => $option ) {
				$selected = ( $value == $key ) ? ' selected="selected"' : '';
				echo '<option value="' . $key . '" ' . $selected . '>' . $option . '</option>';
			}
			echo '</select>';
			echo $this->field_after( $args );
		}

		/**
		 * Get post terms drowdown list
		 *
		 * @param array $args
		 */
		public function post_terms( array $args ) {
			global $wp_version;

			if ( ! isset( $args['id'], $args['name'] ) ) {
				return;
			}
			list( $name, $value ) = $this->field_common( $args );

			$value    = explode( ',', strip_tags( rtrim( $value, ',' ) ) );
			$multiple = isset( $args['multiple'] ) ? 'multiple' : '';
			$taxonomy = isset( $args['taxonomy'] ) ? $args['taxonomy'] : 'category';

			if ( version_compare( $wp_version, '4.5.0', '>=' ) ) {
				$terms = get_terms( array( 'taxonomy' => $taxonomy ) );
			} else {
				$terms = get_terms( $taxonomy );
			}


			echo $this->field_before( $args );

			echo '<select name="' . $name . '" id="' . $args['id'] . '" class="select2 sp-input-text" ' . $multiple . '>';
			foreach ( $terms as $term ) {
				$title    = sprintf( '%s (%s)', $term->name, $term->count );
				$selected = in_array( $term->term_id, $value ) ? ' selected="selected"' : '';
				echo '<option value="' . $term->term_id . '" ' . $selected . '>' . $title . '</option>';
			}
			echo '</select>';

			echo $this->field_after( $args );
		}

		/**
		 * @param array $args
		 */
		public function spacing( array $args ) {
			list( $name, $value, $input_id ) = $this->field_common( $args );

			$default = isset( $args['std'] ) ? $args['std'] : array();

			echo $this->field_before( $args );

			// Top
			if ( isset( $default['top'] ) ) {
				$top_name  = $name . "[top]";
				$top_value = isset( $value['top'] ) ? esc_attr( $value['top'] ) : $default['top'];
				?>
                <div class="carousel-slider-dimension">
                    <span class="add-on"><i class="dashicons dashicons-arrow-up-alt"></i></span>
                    <input type="text" name="<?php echo $top_name; ?>" value="<?php echo $top_value; ?>">
                </div>
				<?php
			}

			// Right
			if ( isset( $default['right'] ) ) {
				$right_name  = $name . "[right]";
				$right_value = isset( $value['right'] ) ? esc_attr( $value['right'] ) : $default['right'];
				?>
                <div class="carousel-slider-dimension">
                    <span class="add-on"><i class="dashicons dashicons-arrow-right-alt"></i></span>
                    <input type="text" name="<?php echo $right_name; ?>" value="<?php echo $right_value; ?>">
                </div>
				<?php
			}
			// Bottom
			if ( isset( $default['bottom'] ) ) {
				$bottom_name  = $name . "[bottom]";
				$bottom_value = isset( $value['bottom'] ) ? esc_attr( $value['bottom'] ) : $default['bottom'];
				?>
                <div class="carousel-slider-dimension">
                    <span class="add-on"><i class="dashicons dashicons-arrow-down-alt"></i></span>
                    <input type="text" name="<?php echo $bottom_name; ?>" value="<?php echo $bottom_value; ?>">
                </div>
				<?php
			}
			// Bottom
			if ( isset( $default['left'] ) ) {
				$left_name  = $name . "[left]";
				$left_value = isset( $value['left'] ) ? esc_attr( $value['left'] ) : $default['left'];
				?>
                <div class="carousel-slider-dimension">
                    <span class="add-on"><i class="dashicons dashicons-arrow-left-alt"></i></span>
                    <input type="text" name="<?php echo $left_name; ?>" value="<?php echo $left_value; ?>">
                </div>
				<?php
			}

			echo $this->field_after( $args );
		}

		/**
		 * @param array $args
		 */
		public function slider( array $args ) {

			list( $name, $value, $input_id ) = $this->field_common( $args );

			$default = isset( $args['std'] ) ? intval( $args['std'] ) : 0;
			$min     = isset( $args['choices']['min'] ) ? intval( $args['choices']['min'] ) : 0;
			$max     = isset( $args['choices']['max'] ) ? intval( $args['choices']['max'] ) : 0;
			$step    = isset( $args['choices']['step'] ) ? intval( $args['choices']['step'] ) : 1;

			echo $this->field_before( $args );

			?>
            <div class="carousel-slider-range-wrapper">
                <input type="range"
                       id="<?php echo esc_attr( $input_id ); ?>"
                       name="<?php echo esc_attr( $name ); ?>"
                       min="<?php echo $min; ?>"
                       max="<?php echo $max ? $max : '' ?>"
                       step="<?php echo $step ? $step : '' ?>"
                       value="<?php echo $value; ?>"
                       data-reset_value="<?php echo $default; ?>"/>
                <div class="range-value">
                    <span class="value"><?php echo $value; ?></span>
					<?php
					if ( ! empty( $args['choices']['suffix'] ) ) {
						echo esc_attr( $args['choices']['suffix'] );
					}
					?>
                </div>
                <div class="carousel-slider-range-reset"
                     title="<?php esc_attr_e( 'Reset to default value', 'carousel-slider' ); ?>">
                    <span class="dashicons dashicons-image-rotate"></span>
                </div>
            </div>
			<?php

			echo $this->field_after( $args );
		}

		/**
		 * Generate field name and field value
		 *
		 * @param $args
		 *
		 * @return array
		 */
		private function field_common( $args ) {
			global $post;
			// Meta Name
			$group    = isset( $args['group'] ) ? $args['group'] : 'carousel_slider';
			$multiple = isset( $args['multiple'] ) ? '[]' : '';
			$name     = sprintf( '%s[%s]%s', $group, $args['id'], $multiple );

			// Meta Value
			$std_value = isset( $args['std'] ) ? $args['std'] : '';
			$meta      = get_post_meta( $post->ID, $args['id'], true );
			$value     = ! empty( $meta ) ? $meta : $std_value;

			// ID
			$id = sprintf( '%s_%s', $group, $args['id'] );

			if ( isset( $args['meta_key'] ) ) {
				$meta  = get_post_meta( $post->ID, $args['meta_key'], true );
				$value = ! empty( $meta[ $args['id'] ] ) ? $meta[ $args['id'] ] : $std_value;

				if ( isset( $args['position'] ) ) {
					$id    = sprintf( '%s_%s_%s', $group, $args['id'], $args['position'] );
					$name  = sprintf( '%s[%s][%s]', $group, $args['position'], $args['id'] );
					$value = ! empty( $meta[ $args['position'] ][ $args['id'] ] ) ? $meta[ $args['position'] ][ $args['id'] ] : $std_value;
				}
			}

			if ( $value == 'zero' ) {
				$value = 0;
			}

			return array( $name, $value, $id );
		}

		/**
		 * Generate field before template
		 *
		 * @param $args
		 *
		 * @return string
		 */
		private function field_before( $args ) {
			$group    = isset( $args['group'] ) ? $args['group'] : 'carousel_slider';
			$input_id = sprintf( '%s_%s', $group, $args['id'] );

			if ( isset( $args['position'], $args['meta_key'] ) ) {
				$input_id = sprintf( '%s_%s_%s', $group, $args['id'], $args['position'] );
			}

			$_normal = sprintf( '<div class="sp-input-group" id="field-%s">', $args['id'] );
			$_normal .= sprintf( '<div class="sp-input-label">' );
			$_normal .= sprintf( '<label for="%1$s">%2$s</label>', $input_id, $args['name'] );
			if ( ! empty( $args['desc'] ) ) {
				$_normal .= sprintf( '<p class="sp-input-desc">%s</p>', $args['desc'] );
			}
			$_normal .= '</div>';
			$_normal .= sprintf( '<div class="sp-input-field">' );

			if ( isset( $args['context'] ) && 'side' == $args['context'] ) {
				$_side = '<p id="field-' . $input_id . '">';
				$_side .= '<label for="' . $input_id . '"><strong>' . $args['name'] . '</strong></label>';

				if ( isset( $args['type'] ) && 'color' == $args['type'] ) {
					$_side .= '<span class="cs-tooltip" title="' . esc_attr( $args['desc'] ) . '"></span><br>';
				}

				return $_side;
			}

			return $_normal;
		}

		/**
		 * Generate field after template
		 *
		 * @param array $args
		 *
		 * @return string
		 */
		private function field_after( $args = array() ) {

			if ( isset( $args['context'] ) && 'side' == $args['context'] ) {
				$_side = '';
				if ( ! empty( $args['desc'] ) ) {
					$_side .= '<span class="cs-tooltip" title="' . esc_attr( $args['desc'] ) . '"></span>';
				}
				// For Color reset tooltip
				if ( isset( $args['type'] ) && 'color' == $args['type'] ) {
					$_side = '';
				}
				$_side .= '</p>';

				return $_side;
			}

			return '</div></div>';
		}
	}
}