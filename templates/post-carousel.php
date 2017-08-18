<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$_image_size       = get_post_meta( $id, '_image_size', true );
$_nav_color        = get_post_meta( $id, '_nav_color', true );
$_nav_active_color = get_post_meta( $id, '_nav_active_color', true );
$_lazy_load_image  = get_post_meta( $id, '_lazy_load_image', true );
$_post_height      = get_post_meta( $id, '_post_height', true );
?>
<style>
    #id-<?php echo $id; ?> .owl-dots .owl-dot span {
        background-color: <?php echo $_nav_color; ?>
    }

    #id-<?php echo $id; ?> .owl-dots .owl-dot.active span,
    #id-<?php echo $id; ?> .owl-dots .owl-dot:hover span {
        background-color: <?php echo $_nav_active_color; ?>
    }

    #id-<?php echo $id; ?> .carousel-slider-nav-icon {
        fill: <?php echo $_nav_color; ?>;
    }

    #id-<?php echo $id; ?> .carousel-slider-nav-icon:hover {
        fill: <?php echo $_nav_active_color; ?>;
    }

    #id-<?php echo $id; ?> .carousel-slider__post {
        height: <?php echo $_post_height; ?>px;
    }
</style>
<div <?php echo join( " ", $this->carousel_options( $id ) ); ?>>
	<?php
	$posts = carousel_slider_posts( $id );
	foreach ( $posts as $_post ):

		$_post    = get_post( $_post );
		$category = get_the_category( $_post->ID );

		do_action( 'carousel_slider_post_loop', $_post, $category );

		echo '<div class="carousel-slider__post">';
		echo '<div class="carousel-slider__post-content">';
		echo '<div class="carousel-slider__post-header">';
		// Post Thumbnail
		$_permalink = esc_url( get_permalink( $_post->ID ) );
		$_thumb_id  = get_post_thumbnail_id( $_post->ID );
		$_excerpt   = wp_trim_words( wp_strip_all_tags( $_post->post_content ), '20', ' ...' );

		if ( has_post_thumbnail( $_post ) ) {
			$image_src = wp_get_attachment_image_src( $_thumb_id, $_image_size );

			if ( $_lazy_load_image == 'on' ) {

				echo sprintf( '<a href="%s" class="carousel-slider__post-image owl-lazy" data-src="%s"></a>', $_permalink, $image_src[0] );
			} else {

				echo sprintf( '<a href="%s" class="carousel-slider__post-image" style="background-image: url(%s)"></a>', $_permalink, $image_src[0] );
			}

		} else {

			echo sprintf( '<a href="%s" class="carousel-slider__post-image"></a>', $_permalink );
		}

		// Post Title
		echo sprintf( '<a class="carousel-slider__post-title" href="%s"><h1>%s</h1></a>', $_permalink, $_post->post_title );
		echo '</div>'; // End Post Header
		echo '<div class="carousel-slider__post-excerpt">' . $_excerpt . '</div>';
		echo '<footer class="carousel-slider__post-meta">';
		echo '<div class="carousel-slider__post-excerpt-overlay"></div>';
		echo '<div class="carousel-slider__post-publication-meta">';
		echo '<div class="carousel-slider__post-details-info">';

		// Post author
		$_author_url  = esc_url( get_author_posts_url( intval( $_post->post_author ) ) );
		$_author_name = esc_html( get_the_author_meta( 'display_name', intval( $_post->post_author ) ) );

		echo sprintf( '<div class="carousel-slider__post-author"><a class="carousel-slider__post-author-link" href="%s">%s</a></div>',
			$_author_url,
			$_author_name
		);
		// Post date
		$_created  = strtotime( $_post->post_date );
		$_modified = strtotime( $_post->post_modified );

		if ( $_created !== $_modified ) {

			echo sprintf( '<time class="carousel-slider__post-publication-date" datetime="%s">%s</time>',
				date_i18n( 'c', $_modified ),
				date_i18n( get_option( 'date_format' ), $_modified )
			);

		} else {

			printf( '<time class="carousel-slider__post-publication-date" datetime="%s">%s</time>',
				date_i18n( 'c', $_created ),
				date_i18n( get_option( 'date_format' ), $_created )
			);
		}
		echo '</div>';
		echo '</div>';

		// Post catagory
		$cat_link  = isset( $category[0]->term_id ) ? esc_url( get_category_link( $category[0]->term_id ) ) : '';
		$cat_title = isset( $category[0]->name ) ? esc_html( $category[0]->name ) : '';
		echo '<div class="carousel-slider__post-category">';
		if ( isset( $cat_link ) ) {
			echo sprintf( '<a class="carousel-slider__post-category-link" href="%s">%s</a>',
				$cat_link,
				$cat_title
			);
		}
		echo '</div>';
		echo '</footer>';
		echo '</div>';
		echo '</div>';
	endforeach;
	?>
</div>