/**
 * Carousel Slider Gallery from URL
 */

import $ from 'jquery';

const _l10n = window.CarouselSliderAdminL10n.videoCarousel;
const loopItemContainerId = 'carousel-slider-video-carousel-urls';
const loopItemClassname = 'carousel_slider-fields--video-urls';

const template = `<div class="carousel_slider-fields--video-urls shapla-column is-6">
    <div class="carousel_slider-fields media-url-form-field">
      <div class="media-url-form-field__content">
          <label class="setting media-url-form-field__item">
              <span class="name">${_l10n.YoutubeOrVimeoURL}</span>
              <input type="url" name="_video_urls[]" value="" autocomplete="off" 
              placeholder="https://www.youtube.com/watch?v=UOYK79yVrJ4">
          </label>
      </div>
      <div class="media-url-form-field__actions flex-direction-row">
          <span class="sort_row"><span class="dashicons dashicons-move"></span></span>
          <span class="add_video_url_row"><span class="dashicons dashicons-plus-alt"></span></span>
          <span class="delete_video_url_row"><span class="dashicons dashicons-trash"></span></span>
      </div>
    </div>
</div>`;

// Append new row
$(document).on('click', '.add_video_url_row', function (event) {
  event.preventDefault();
  let loopItemContainer = $(`#${loopItemContainerId}`),
    currentColumn = $(this).closest(`.${loopItemClassname}`);
  if (currentColumn.length) {
    currentColumn.after(template);
  } else {
    let columns = loopItemContainer.find(`.${loopItemClassname}`);
    if (columns.length) {
      columns.last().after(template);
    } else {
      loopItemContainer.prepend(template);
    }
  }
});

// Delete current row
$(document).on('click', '.delete_video_url_row', function () {
  if (confirm(`${_l10n.AreYouSureToDelete}`)) {
    $(this).closest(`.${loopItemClassname}`).remove();
  }
});

// Make fields sortable
if ($.fn.sortable) {
  $('#carousel-slider-video-carousel-urls').sortable();
}
