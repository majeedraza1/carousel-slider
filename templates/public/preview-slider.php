<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$slider_id = isset( $_GET['slider_id'] ) ? intval( $_GET['slider_id'] ) : 0;
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

        .carousel-slider-preview {
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

<div class="carousel-slider-preview">
	<?php echo do_shortcode( '[carousel_slide id=' . $slider_id . ']' ); ?>
</div>

<?php wp_footer(); ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        if (window.frameElement) {
            var sliderHeight = jQuery('.carousel-slider-preview')
                .find('.carousel-slider')
                .outerHeight(true);
            window.frameElement.height = sliderHeight;
        }
    });
</script>
</body>
</html>
