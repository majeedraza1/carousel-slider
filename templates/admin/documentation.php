<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<style type="text/css">
    .carousel_slider_iframe {
        position: relative;
        padding-bottom: 56.25%; /* height / width * 100 */
        padding-top: 25px;
        height: 0;
    }

    .carousel_slider_iframe > iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .carousel_slider_columns {
        display: flex;
        flex-wrap: wrap;
        box-sizing: border-box;
    }

    .carousel_slider_column {
        flex: 0 0 100%;
        padding: 1rem;
        box-sizing: border-box;
    }

    @media screen and (min-width: 601px) {
        .carousel_slider_column {
            flex: 0 0 50%;
        }
    }

    @media screen and (min-width: 1025px) {
        .carousel_slider_column {
            flex: 0 0 33.333333%;
        }
    }

    @media screen and (min-width: 1400px) {
        .carousel_slider_column {
            flex: 0 0 25%;
        }
    }
</style>
<div class="wrap">
    <h1 class="wp-heading">
        <?php esc_html_e( 'Carousel Slider Documentation', 'carousel-slider' ); ?>
    </h1>
    <hr class="clear">
    <div class="postbox">
        <div class="inside">
            <div class="carousel_slider_columns">
                <div class="carousel_slider_column">
                    <div class="carousel_slider_iframe">
                        <iframe width="1280" height="720"
                                src="https://www.youtube.com/embed/ZzI1JhElrxc"
                                frameborder="0"
                                allowfullscreen></iframe>
                    </div>
                    <label><?php esc_html_e( 'Images Carousel', 'carousel-slider' ); ?></label>
                    <p class="description"><?php esc_html_e( 'Image carousel using gallery images', 'carousel-slider' ); ?></p>
                </div>
                <div class="carousel_slider_column">
                    <div class="carousel_slider_iframe">
                        <iframe width="1280" height="720"
                                src="https://www.youtube.com/embed/a7hqn1yNzwM" frameborder="0"
                                allowfullscreen></iframe>
                    </div>
                    <label><?php esc_html_e( 'Images Carousel', 'carousel-slider' ); ?></label>
                    <p class="description"><?php esc_html_e( 'Image carousel using custom URLs', 'carousel-slider' ); ?></p>
                </div>
                <div class="carousel_slider_column">
                    <div class="carousel_slider_iframe">
                        <iframe width="1280" height="720"
                                src="https://www.youtube.com/embed/ImJB946azy0" frameborder="0"
                                allowfullscreen></iframe>
                    </div>
                    <label><?php esc_html_e( 'Posts Carousel', 'carousel-slider' ); ?></label>
                </div>
                <div class="carousel_slider_column">
                    <div class="carousel_slider_iframe">
                        <iframe width="1280" height="720"
                                src="https://www.youtube.com/embed/yiAkvXyfakg" frameborder="0"
                                allowfullscreen></iframe>
                    </div>
                    <label><?php esc_html_e( 'WooCommerce Products Carousel', 'carousel-slider' ); ?></label>
                </div>
                <div class="carousel_slider_column">
                    <div class="carousel_slider_iframe">
                        <iframe width="1280" height="720"
                                src="https://www.youtube.com/embed/kYgp6wp27lM" frameborder="0"
                                allowfullscreen></iframe>
                    </div>
                    <label><?php esc_html_e( 'In Widget Areas', 'carousel-slider' ); ?></label>
                </div>
                <div class="carousel_slider_column">
                    <div class="carousel_slider_iframe">
                        <iframe width="1280" height="720"
                                src="https://www.youtube.com/embed/-OaYQZfr1RM" frameborder="0"
                                allowfullscreen></iframe>
                    </div>
                    <label><?php esc_html_e( 'With Page Builder by SiteOrigin', 'carousel-slider' ); ?></label>
                </div>
                <div class="carousel_slider_column">
                    <div class="carousel_slider_iframe">
                        <iframe width="1280" height="720"
                                src="https://www.youtube.com/embed/4LhDXH81whk" frameborder="0"
                                allowfullscreen></iframe>
                    </div>
                    <label><?php esc_html_e( 'With WPBakery Visual Composer', 'carousel-slider' ); ?></label>
                </div>
            </div>
        </div>
    </div>
    <br class="clear">
</div>