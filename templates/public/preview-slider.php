<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$slider_id = isset( $_GET['slider_id'] ) ? intval( $_GET['slider_id'] ) : 0;
add_filter( 'carousel_slider_load_scripts', '__return_true' );
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
    <style type="text/css" media="screen">
        html {
            margin-top: 0 !important;
        }

        * html body {
            margin-top: 0 !important;
        }

        #wpadminbar {
            display: none !important;
        }

        .carousel-slider-preview-container {
            max-width: 1024px;
            margin-left: auto;
            margin-right: auto;
        }

        @media screen and ( max-width: 782px ) {
            html {
                margin-top: 0 !important;
            }

            * html body {
                margin-top: 0 !important;
            }
        }
    </style>
</head>
<body>
<div class="carousel-slider-preview-container">
	<?php echo do_shortcode( '[carousel_slide id="' . $slider_id . '"]' ); ?>
</div>

<script type="text/javascript">
    jQuery(document).ready(function () {
        setTimeout(function () {
            var frameEl = window.frameElement;
            // get the form element
            var height = jQuery('.carousel-slider-preview-container').outerHeight(true);

            if (frameEl) {
                frameEl.height = height;
            }
        }, 500);
    });
</script>
<?php wp_footer(); ?>
</body>
</html>