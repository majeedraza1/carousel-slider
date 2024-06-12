const previewMetaBox = document.querySelector<HTMLDivElement>('#carousel-slider-live-preview');
const btnUpdatePreview = document.querySelector<HTMLAnchorElement>('#carousel-slider-update-preview');
const btnHidePreview = document.querySelector<HTMLAnchorElement>('#carousel-slider-hide-preview');
const btnShowPreview = document.querySelector<HTMLAnchorElement>('#carousel-slider-show-preview');
const formPost = document.body.querySelector<HTMLFormElement>('form#post');

const dispatchRefreshPreviewEvent = () => {
  document.body.dispatchEvent(new CustomEvent('CarouselSlider.refresh.preview'))
}

const getPreviewHtml = () => {
  return new Promise((resolve, reject) => {
    const form = document.body.querySelector<HTMLFormElement>('form#post');
    if (form) {
      const data = new FormData(form);

      const url = new URL(window.CarouselSliderL10n.ajaxUrl);
      url.searchParams.set('action', 'carousel_slider_preview_meta_box');
      url.searchParams.set('cs_nonce', window.CarouselSliderL10n.nonce)
    }
    reject(false);
  })
}

if (formPost && previewMetaBox && btnShowPreview && btnHidePreview && btnUpdatePreview) {
  let previewStatus: 'hidden' | 'showing' | 'updatable' = 'hidden';

  btnShowPreview.addEventListener('click', () => {
    previewStatus = 'showing';
    btnShowPreview.classList.add('hidden');
    btnUpdatePreview.classList.add('hidden');
    btnHidePreview.classList.remove('hidden');
    previewMetaBox.style.display = 'block';
  })
  btnHidePreview.addEventListener('click', () => {
    previewStatus = 'hidden';
    btnHidePreview.classList.add('hidden');
    btnUpdatePreview.classList.add('hidden');
    btnShowPreview.classList.remove('hidden');
    previewMetaBox.style.display = 'none';
  })

  formPost.addEventListener('change', () => dispatchRefreshPreviewEvent());

  document.body.addEventListener('CarouselSlider.refresh.preview', () => {
    previewStatus = 'updatable';
    btnHidePreview.classList.add('hidden');
    btnShowPreview.classList.add('hidden');
    btnUpdatePreview.classList.remove('hidden');
    previewMetaBox.style.display = 'block';
  })
}

