<?php defined( 'ABSPATH' ) || exit; ?>
<!DOCTYPE html>
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
	<?php
	$slider_id = isset( $_GET['slider_id'] ) ? intval( $_GET['slider_id'] ) : 0;
	echo do_shortcode( '[carousel_slide id="' . $slider_id . '"]' );
	?>
</div>

<?php wp_footer(); ?>
<script type="text/javascript">
	(function () {
		if (window.frameElement) {
			window.frameElement.height = document.querySelector('.carousel-slider-preview-container').offsetHeight;
		}
	})();
</script>
</body>
</html>
