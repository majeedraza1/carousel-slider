<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if( ! class_exists('Carousel_Slider_Form') ):

class Carousel_Slider_Form
{
	public function text( array $args )
	{
		if( ! isset( $args['id'], $args['name'] ) ) return;

		list($name, $value)  = $this->field_common( $args );

		echo $this->field_before( $args );
		echo sprintf( '<input type="text" class="sp-input-text" value="%1$s" id="%2$s" name="%3$s">', $value, $args['id'], $name);
		echo $this->field_after();
	}
	public function textarea( array $args )
	{
		if( ! isset( $args['id'], $args['name'] ) ) return;

		list($name, $value)  = $this->field_common( $args );
		$cols = isset( $args['cols'] ) ? $args['cols'] : 35;
		$rows = isset( $args['rows'] ) ? $args['rows'] : 6;

		echo $this->field_before( $args );
		echo sprintf( '<textarea class="sp-input-textarea" id="%2$s" name="%3$s" cols="%4$d" rows="%5$d">%1$s</textarea>', esc_textarea($value), $args['id'], $name, $cols, $rows);
		echo $this->field_after();
	}

	public function color( array $args )
	{
		if( ! isset( $args['id'], $args['name'] ) ) return;

		list($name, $value)  = $this->field_common( $args );
		$std_value = isset($args['std']) ? $args['std'] : '';

		echo $this->field_before( $args );
		echo sprintf( '<input type="text" class="colorpicker" value="%1$s" id="%2$s" name="%3$s" data-default-color="%4$s">', $value, $args['id'], $name, $std_value);
		echo $this->field_after();
	}

	public function date( array $args )
	{
		if( ! isset( $args['id'], $args['name'] ) ) return;

		list($name, $value)  = $this->field_common( $args );
		$std_value = isset($args['std']) ? $args['std'] : '';

		echo $this->field_before( $args );
		echo sprintf( '<input type="text" class="sp-input-text datepicker" value="%1$s" id="%2$s" name="%3$s">', $value, $args['id'], $name, $std_value);
		echo $this->field_after();
	}

	public function number( array $args )
	{
		if( ! isset( $args['id'], $args['name'] ) ) return;

		list($name, $value)  = $this->field_common( $args );
		$min = isset( $args['min'] ) ? $args['min'] : null;
		$max = isset( $args['max'] ) ? $args['max'] : null;

		echo $this->field_before( $args );
		echo sprintf( '<input type="number" class="sp-input-text" value="%1$s" id="%2$s" name="%3$s">', $value, $args['id'], $name);
		echo $this->field_after();
	}

	public function checkbox( array $args )
	{
		if( ! isset( $args['id'], $args['name'] ) ) return;

		list($name, $value)  	= $this->field_common( $args );
        $checked 				= ( $value == 'on' ) ? ' checked' : '';
        $label 					= isset( $args['label'] ) ? $args['label'] : '';

		echo $this->field_before( $args );
        echo sprintf( '<input type="hidden" name="%1$s" value="off">', $name );
        echo sprintf('<label for="%2$s"><input type="checkbox" %4$s value="on" id="%2$s" name="%1$s">%3$s</label>',$name, $args['id'], $label, $checked);
		echo $this->field_after();
	}

	public function select( $args )
	{
		if( ! isset( $args['id'], $args['name'] ) ) return;

		list($name, $value)  	= $this->field_common( $args );
        $checked 				= ( $value == 'on' ) ? ' checked' : '';
        $multiple = isset($args['multiple']) ? 'multiple' : '';

        echo $this->field_before( $args );
		echo sprintf('<select name="%1$s" id="%2$s" class="select2 sp-input-text" %3$s>',$name, $args['id'], $multiple);
        foreach( $args['options'] as $key => $option ){
            $selected = ( $value == $key ) ? ' selected="selected"' : '';
            echo sprintf('<option value="%1$s" %3$s>%2$s</option>',$key, $option, $selected);
        }
        echo'</select>';
        echo $this->field_after();
	}

	public function posts_list( $args )
	{
		if( ! isset( $args['id'], $args['name'] ) ) return;

		list($name, $value)  	= $this->field_common( $args );
		$value = explode(',', $value);
        $multiple = isset($args['multiple']) ? 'multiple' : '';
        $post_type = isset($args['post_type']) ? $args['post_type'] : 'post';

        echo $this->field_before( $args );
		echo sprintf('<select name="%1$s" id="%2$s" class="select2 sp-input-text" %3$s>',$name, $args['id'], $multiple);
		$posts = get_posts( array( 'post_type' => $post_type, 'post_status' => 'publish', 'posts_per_page' => -1 ) );

        foreach( $posts as $post ){
            $selected = in_array($post->ID, $value) ? ' selected="selected"' : '';
            echo sprintf('<option value="%1$s" %3$s>%2$s</option>',$post->ID, $post->post_title, $selected);
        }
        echo'</select>';
        echo $this->field_after();
	}

	public function images_gallery( $args )
	{
		if( ! isset( $args['id'], $args['name'] ) ) return;
		list($name, $value)  	= $this->field_common( $args );

		$btn_text 	= $value ? 'Edit Gallery' : 'Add Gallery';
        $value 		= strip_tags(rtrim($value, ','));
		$output 	= '';

	    if( $value ) {
	        $thumbs = explode(',', $value);
	        foreach( $thumbs as $thumb ) {
	            $output .= '<li>' . wp_get_attachment_image( $thumb, array(50,50) ) . '</li>';
	        }
	    }

		$html  = $this->field_before( $args );
		$html .= '<div class="carousel_slider_images">';
		$html .= sprintf('<input type="hidden" value="%1$s" id="_carousel_slider_images_ids" name="%2$s">', $value, $name);
		$html .= sprintf('<a href="#" id="carousel_slider_gallery_btn" class="carousel_slider_gallery_btn">%s</a>', $btn_text);
		$html .= sprintf('<ul class="carousel_slider_gallery_list">%s</ul>', $output);
		$html .= '</div>';
		$html .= $this->field_after();
		echo $html;
	}

	public function images_url( array $args )
	{
		if( ! isset( $args['id'], $args['name'] ) ) return;
		list($name, $value)  	= $this->field_common( $args );

		$btn_text = $value ? __('Edit URLs', 'carousel-slider') : __('Add URLs', 'carousel-slider');

		$html  = $this->field_before( $args );
		$html .= sprintf('<a id="_images_urls_btn" class="button button-primary" href="#">%s</a>', $btn_text);
		$html .= '<ul class="carousel_slider_url_images_list">';
		if ( is_array($value) && count($value) > 0){
			foreach ($value as $image ) {
				$html .= sprintf('<li><img src="%s" alt="%s" width="75" height="75"></li>', $image['url'], $image['alt']);
			}
		}
		$html .= '</ul>';
		$html .= $this->field_after();
		echo $html;
	}

	public function file( array $args )
	{
		if( ! isset( $args['id'], $args['name'] ) ) return;

		list($name, $value)  = $this->field_common( $args );

		echo $this->field_before( $args );
		echo sprintf( '<input type="text" class="sp-input-text" value="%1$s" id="%2$s" name="%3$s">', $value, $args['id'], $name);
		echo sprintf('<input type="button" class="button" id="carousel_slider_video_btn" value="%s">', __('Browse', 'carousel-slider'));
		echo $this->field_after();
	}

	public function image_sizes( array $args )
	{
		if( ! isset( $args['id'], $args['name'] ) ) return;

		list($name, $value)  	= $this->field_common( $args );

		global $_wp_additional_image_sizes;

		$sizes = array();

		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {

				$width 		= get_option( "{$_size}_size_w" );
				$height 	= get_option( "{$_size}_size_h" );
				$crop 		= (bool) get_option( "{$_size}_crop" ) ? 'hard' : 'soft';

				$sizes[$_size]   = "{$_size} - {$width}x{$height}";

			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

				$width 		= $_wp_additional_image_sizes[ $_size ]['width'];
				$height 	= $_wp_additional_image_sizes[ $_size ]['height'];
				$crop 		= $_wp_additional_image_sizes[ $_size ]['crop'] ? 'hard' : 'soft';

				$sizes[$_size]   = "{$_size} - {$width}x{$height}";
			}
		}

		$sizes = array_merge($sizes, array('full' => 'original uploaded image'));


		echo $this->field_before( $args );
		echo sprintf('<select name="%1$s" id="%2$s" class="select2 sp-input-text">',$name, $args['id']);
        foreach( $sizes as $key => $option ){
            $selected = ( $value == $key ) ? ' selected="selected"' : '';
            echo sprintf('<option value="%1$s" %3$s>%2$s</option>',$key, $option, $selected);
        }
        echo'</select>';
		echo $this->field_after();
	}

	public function post_terms( array $args )
	{
		global $wp_version;

		if( ! isset( $args['id'], $args['name'] ) ) return;
		list($name, $value) = $this->field_common( $args );

		$value 				= explode(',', strip_tags(rtrim($value, ',') ) );
        $multiple 			= isset($args['multiple']) ? 'multiple' : '';
        $taxonomy 			= isset($args['taxonomy']) ? $args['taxonomy'] : 'category';

		if ( version_compare( $wp_version, '4.5.0', '>=' ) ) {
			$terms = get_terms( array( 'taxonomy' => $taxonomy ) );
		} else {
			$terms = get_terms( $taxonomy );
		}

		// var_dump($terms);

		echo $this->field_before( $args );

		echo sprintf('<select name="%1$s" id="%2$s" class="select2 sp-input-text" %3$s>',$name, $args['id'], $multiple);

        foreach( $terms as $term ){
        	$title = sprintf('%s (%s)', $term->name, $term->count);
            $selected = in_array($term->term_id, $value) ? ' selected="selected"' : '';
            echo sprintf('<option value="%1$s" %3$s>%2$s</option>',$term->term_id, $title, $selected);
        }
        echo'</select>';

		echo $this->field_after();
	}

	private function field_common( $args )
	{
		global $post;
		// Meta Name
		$group 		= isset($args['group']) ? $args['group'] : 'carousel_slider';
		$multiple 	= isset($args['multiple']) ? '[]' : '';
		$name 		= sprintf('%s[%s]%s', $group, $args['id'], $multiple);

		// Meta Value
		$std_value 	= isset($args['std']) ? $args['std'] : '';
		$meta 		= get_post_meta( $post->ID, $args['id'], true );
		$value 		= ! empty($meta) ? $meta : $std_value;

        if ($value == 'zero') {
            $value = 0;
        }

		return array( $name, $value );
	}

	private function field_before( $args )
	{
		$table  = sprintf( '<div class="sp-input-group" id="field-%s">', $args['id'] );
		$table .= sprintf( '<div class="sp-input-label">' );
		$table .= sprintf( '<label for="%1$s">%2$s</label>', $args['id'], $args['name'] );
		if ( ! empty( $args['desc'] ) ) {
			$table .= sprintf( '<p class="sp-input-desc">%s</p>', $args['desc'] );
		}
		$table .= '</div>';
		$table .= sprintf( '<div class="sp-input-field">' );
		return $table;
	}

	private function field_after()
	{
		return '</div></div>';
	}
}

endif;