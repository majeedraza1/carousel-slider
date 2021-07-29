<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div data-id="open" id="section_video_settings" class="shapla-toggle shapla-toggle--stroke"
     style="display: <?php echo $slide_type != 'video-carousel' ? 'none' : 'block'; ?>">
	<span class="shapla-toggle-title">
		<?php esc_html_e( 'Video Settings', 'carousel-slider' ); ?>
	</span>
    <div class="shapla-toggle-inner">
        <div class="shapla-toggle-content">
			<?php
			$this->form->textarea( array(
				'id'   => '_video_url',
				'name' => esc_html__( 'Video URLs', 'carousel-slider' ),
				'desc' => sprintf(
					'%s<br><br>Example: %s',
					esc_html__( 'Only support youtube and vimeo. Enter video URL from youtube or vimeo separating each by comma', 'carousel-slider' ),
					'https://www.youtube.com/watch?v=O4-EM32h7b4,https://www.youtube.com/watch?v=72IO4gzB8mU,https://vimeo.com/193773669,https://vimeo.com/193517656'
				),
			) );
			?>
        </div>
    </div>
</div>