class CarouselSliderPreviewMetaBox {
  private _previewContainer: HTMLDivElement;
  private readonly _formPost: HTMLFormElement;
  private readonly _previewMetaBox: HTMLDivElement;
  private readonly _iframeContainer: HTMLDivElement;
  private readonly _btnUpdatePreview: HTMLAnchorElement;
  private readonly _btnHidePreview: HTMLAnchorElement;
  private _btnShowPreview: HTMLAnchorElement;
  private _previewStatus: 'hidden' | 'showing' | 'updatable' | 'loading' = 'hidden';
  private _postId: number = 0;

  constructor() {
    this._iframeContainer = document.querySelector<HTMLDivElement>('#carousel_slider_preview_iframe_container');
    this._previewMetaBox = document.querySelector<HTMLDivElement>('#carousel-slider-live-preview');
    this._btnUpdatePreview = document.querySelector<HTMLAnchorElement>('#carousel-slider-update-preview');
    this._btnShowPreview = document.querySelector<HTMLAnchorElement>('#carousel-slider-show-preview');
    this._btnHidePreview = document.querySelector<HTMLAnchorElement>('#carousel-slider-hide-preview');
    this._previewContainer = document.querySelector<HTMLAnchorElement>('#carousel_slider_preview_meta_box');
    this._formPost = document.body.querySelector<HTMLFormElement>('form#post');
    if (this._iframeContainer && this._btnUpdatePreview && this._btnHidePreview && this._formPost) {
      this._postId = parseInt(this._btnShowPreview.dataset.id as string)
      this._btnShowPreview.addEventListener('click', () => this.onClickShowPreviewButton())
      this._btnHidePreview.addEventListener('click', () => this.onClickHidePreviewButton())
      this._btnUpdatePreview.addEventListener('click', () => this.onClickUpdatePreviewButton())
      this._formPost.addEventListener('change', () => this.dispatchRefreshPreviewEvent());
      document.body.addEventListener('CarouselSlider.refresh.preview', () => this.onRefreshPreview())
    }
  }

  getIframeUrl(): string {
    const url = new URL(window.CarouselSliderL10n.homeUrl)
    url.searchParams.set('carousel_slider_preview', '1');
    url.searchParams.set('carousel_slider_iframe', '1');
    url.searchParams.set('slider_id', this._postId.toString());
    return url.toString();
  }

  getPreviewIframe(): HTMLIFrameElement {
    const iframe = document.createElement('iframe');
    iframe.setAttribute('id', 'carousel_slider_preview_iframe')
    iframe.setAttribute('frameborder', '0');
    iframe.setAttribute('src', this.getIframeUrl());
    return iframe;
  }

  refreshIframe() {
    const iframeHeight = this._iframeContainer.offsetHeight;
    this._iframeContainer.style.height = iframeHeight + 'px';
    this._iframeContainer.innerHTML = '';
    this._iframeContainer.appendChild(this.getPreviewIframe());
    this._iframeContainer.style.height = '';
  }

  onRefreshPreview() {
    this._previewStatus = 'updatable';
    this._btnHidePreview.classList.add('hidden');
    this._btnShowPreview.classList.add('hidden');
    this._btnUpdatePreview.classList.remove('hidden');
    this._previewMetaBox.style.display = 'block';
  }

  onClickShowPreviewButton() {
    this._btnShowPreview.classList.add('hidden');
    this._btnUpdatePreview.classList.add('hidden');
    this._btnHidePreview.classList.remove('hidden');
    this._previewMetaBox.style.display = 'block';
    this.getPreviewHtml().then(() => {
      this._previewStatus = 'showing';
    })
  }

  onClickHidePreviewButton() {
    this._previewStatus = 'hidden';
    this._btnHidePreview.classList.add('hidden');
    this._btnUpdatePreview.classList.add('hidden');
    this._btnShowPreview.classList.remove('hidden');
    this._previewMetaBox.style.display = 'none';
  }

  onClickUpdatePreviewButton() {
    if (this._previewStatus !== 'hidden') {
      this.getPreviewHtml().then(() => {
        this._previewStatus = 'showing';
        this._btnShowPreview.classList.add('hidden');
        this._btnUpdatePreview.classList.add('hidden');
        this._btnHidePreview.classList.remove('hidden');
        this._previewMetaBox.style.display = 'block';
      });
    }
  }

  dispatchRefreshPreviewEvent() {
    document.body.dispatchEvent(new CustomEvent('CarouselSlider.refresh.preview'))
  }

  getPreviewHtml() {
    return new Promise((resolve, reject) => {
      const form = document.body.querySelector<HTMLFormElement>('form#post');
      if (form) {
        const formData = new FormData(form);
        formData.set('action', 'carousel_slider_preview_meta_box');

        const url = new URL(window.CarouselSliderL10n.ajaxUrl);
        url.searchParams.set('action', 'carousel_slider_preview_meta_box');
        url.searchParams.set('cs_nonce', window.CarouselSliderL10n.nonce)

        fetch(url.toString(), {
          method: 'POST',
          headers: {'X-WP-Nonce': window.CarouselSliderL10n.restNonce},
          body: formData,
        })
          .then(response => response.json())
          .then(response => {
            resolve(response.data);
            // this._previewContainer.innerHTML = response.data.html;
            this.refreshIframe();
          })
          .catch((error) => {
            console.error('Error:', error);
            reject(false);
          });
      }
    })
  }
}

new CarouselSliderPreviewMetaBox();
