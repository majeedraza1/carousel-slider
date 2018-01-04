<#
	var isRtl           = <?php echo is_rtl() ? 'true' : 'false'; ?>,
		direction       = isRtl ? 'rtl' : 'ltr',
		navi            = settings.navigation,
		showDots        = ( 'dots' === navi || 'both' === navi ),
		showArrows      = ( 'arrows' === navi || 'both' === navi ),
		autoplay        = ( 'yes' === settings.autoplay ),
		infinite        = ( 'yes' === settings.infinite ),
		speed           = Math.abs( settings.transition_speed ),
		autoplaySpeed   = Math.abs( settings.autoplay_speed ),
		fade            = ( 'fade' === settings.transition ),
		buttonSize      = settings.button_size,
		sliderOptions = {
			"initialSlide": Math.max( 0, editSettings.activeItemIndex-1 ),
			"slidesToShow": 1,
			"autoplaySpeed": autoplaySpeed,
			"autoplay": false,
			"infinite": infinite,
			"pauseOnHover":true,
			"pauseOnFocus":true,
			"speed": speed,
			"arrows": showArrows,
			"dots": showDots,
			"rtl": isRtl,
			"fade": fade
		}
		sliderOptionsStr = JSON.stringify( sliderOptions );
	if ( showArrows ) {
		var arrowsClass = 'slick-arrows-' + settings.arrows_position;
	}

	if ( showDots ) {
		var dotsClass = 'slick-dots-' + settings.dots_position;
	}

#>
<div class="elementor-slides-wrapper elementor-slick-slider" dir="{{ direction }}">
	<div data-slider_options="{{ sliderOptionsStr }}" class="elementor-slides {{ dotsClass }} {{ arrowsClass }}" data-animation="{{ settings.content_animation }}">
		<# _.each( settings.slides, function( slide ) { #>
			<div class="elementor-repeater-item-{{ slide._id }}">
				<#
				var kenClass = '';

				if ( '' != slide.background_ken_burns ) {
					kenClass = ' elementor-ken-' + slide.zoom_direction;
				}
				#>
				<div class="slick-slide-bg{{ kenClass }}"></div>
				<div class="slick-slide-inner">
						<# if ( 'yes' === slide.background_overlay ) { #>
					<div class="elementor-background-overlay"></div>
						<# } #>
					<div class="elementor-slide-content">
						<# if ( slide.heading ) { #>
							<div class="elementor-slide-heading">{{{ slide.heading }}}</div>
						<# }
						if ( slide.description ) { #>
							<div class="elementor-slide-description">{{{ slide.description }}}</div>
						<# }
						if ( slide.button_text ) { #>
							<div class="elementor-button elementor-slide-button elementor-size-{{ buttonSize }}">{{{ slide.button_text }}}</div>
						<# } #>
					</div>
				</div>
			</div>
		<# } ); #>
	</div>
</div>