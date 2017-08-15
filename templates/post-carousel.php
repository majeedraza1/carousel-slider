<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$args = array(
	'post_type' 		=> 'post',
	'post_status' 		=> 'publish',
	'order'   			=> get_post_meta( $id, '_post_order', true ),
	'orderby' 			=> get_post_meta( $id, '_post_orderby', true ),
	'posts_per_page'    => intval(get_post_meta( $id, '_posts_per_page', true ))
);

$query_type = get_post_meta( $id, '_post_query_type', true );
$query_type = empty($query_type) ? 'latest_posts' : $query_type;

// Get posts by post IDs
if ( $query_type == 'specific_posts' ) {
	$post_in = explode(',', get_post_meta( $id, '_post_in', true ));
	$post_in = array_map(function($value){ return intval($value);}, $post_in);
	unset($args['posts_per_page']);
	$args = array_merge($args, array('post__in' => $post_in ));
}

// Get posts by post catagories IDs
if ( $query_type == 'post_categories' ) {
	$post_categories 	= get_post_meta( $id, '_post_categories', true );
	$args = array_merge($args, array('cat' => $post_categories));
}

// Get posts by post tags IDs
if ( $query_type == 'post_tags' ) {
	$post_tags 	= get_post_meta( $id, '_post_tags', true );
	$post_tags 	= array_map('intval', explode(',', $post_tags));
	$args 		= array_merge( $args, array( 'tag__in' => $post_tags ) );
}

// Get posts by date range
if( $query_type == 'date_range' ){

	$post_date_after 	= get_post_meta( $id, '_post_date_after', true );
	$post_date_before 	= get_post_meta( $id, '_post_date_before', true );

	if ( $post_date_after && $post_date_before ) {
		$args = array_merge($args, array(
			'date_query' => array(
				array(
					'after'     => $post_date_after,
					'before'    => $post_date_before,
					'inclusive' => true,
				),
			),
		));
	} elseif ($post_date_after ) {
		$args = array_merge($args, array(
			'date_query' => array(
				array(
					'before'    => $post_date_before,
					'inclusive' => true,
				),
			),
		));
	} elseif ($post_date_before ) {
		$args = array_merge($args, array(
			'date_query' => array(
				array(
					'before'    => $post_date_before,
					'inclusive' => true,
				),
			),
		));
	}
}

$posts 				= get_posts( $args );
$carousels 			= $this->filter_posts( $posts );

$_image_size 		= get_post_meta( $id, '_image_size', true );
$_nav_color         = get_post_meta( $id, '_nav_color', true );
$_nav_active_color  = get_post_meta( $id, '_nav_active_color', true );
$_lazy_load_image 	= get_post_meta( $id, '_lazy_load_image', true );
$_post_height 		= get_post_meta( $id, '_post_height', true );
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
<div <?php echo join(" ", $this->carousel_options($id)); ?>>
	<?php
		foreach ( $carousels as $query ):
			echo '<div class="carousel-slider__post">';
				echo '<div class="carousel-slider__post-content">';
		            echo '<div class="carousel-slider__post-header">';
		            	// Post Thumbnail
			            if( $query->thumbnail_id ) {
							$image_src = wp_get_attachment_image_src( $query->thumbnail_id, $_image_size );

							if ( $_lazy_load_image == 'on' ) {

				            	echo sprintf('<a href="%s" class="carousel-slider__post-image owl-lazy" data-src="%s"></a>', $query->permalink, $image_src[0]);
							} else {
								
				            	echo sprintf('<a href="%s" class="carousel-slider__post-image" style="background-image: url(%s)"></a>', $query->permalink, $image_src[0]);
							}

			            } else {
			            	
			            	echo sprintf('<a href="%s" class="carousel-slider__post-image"></a>', $query->permalink );
			            }

			            // Post Title
		            	echo sprintf('<a class="carousel-slider__post-title" href="%s"><h1>%s</h1></a>', $query->permalink, $query->title );
		            echo '</div>'; // End Post Header
		            echo '<div class="carousel-slider__post-excerpt">' . $query->excerpt . '</div>';
		            echo '<footer class="carousel-slider__post-meta">';
		                echo '<div class="carousel-slider__post-excerpt-overlay"></div>';
		                echo '<div class="carousel-slider__post-publication-meta">';
		                    echo '<div class="carousel-slider__post-details-info">';

	                        	// Post author
	                        	echo sprintf('<div class="carousel-slider__post-author"><a class="carousel-slider__post-author-link" href="%s">%s</a></div>',
	                        		$query->author->posts_url,
	                        		$query->author->display_name
	                        	);
	                        	// Post date
		                        if ( $query->created !== $query->modified ) {

		                        	echo sprintf('<time class="carousel-slider__post-publication-date" datetime="%s">%s</time>',
		                        		date_i18n( 'c', $query->modified ),
		                        		date_i18n( get_option( 'date_format' ), $query->modified )
		                        	);

		                        } else {

		                        	echo sprintf('<time class="carousel-slider__post-publication-date" datetime="%s">%s</time>',
		                        		date_i18n( 'c', $query->created ),
		                        		date_i18n( get_option( 'date_format' ), $query->created )
		                        	);
		                        }
			                echo '</div>';
		                echo '</div>';

                		// Post catagory
		                echo '<div class="carousel-slider__post-category">';
	                	if ( isset($query->category->link) ) {
	                		echo sprintf('<a class="carousel-slider__post-category-link" href="%s">%s</a>',
	                			$query->category->link,
	                			$query->category->title
	                		);
	                	}
		                echo '</div>';
		            echo '</footer>';
	            echo '</div>';
			echo '</div>';
		endforeach;
	?>
</div>