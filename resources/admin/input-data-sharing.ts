const sendGetRequest = (url: string) => {
  return new Promise(resolve => {
    let xhr = new XMLHttpRequest();
    // Call a function when the state changes.
    xhr.addEventListener('load', () => {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        resolve(xhr.responseText);
      }
    })
    xhr.open('GET', url);
    xhr.setRequestHeader("Accept", "application/json");
    xhr.send();
  })
}

const inputEl = document.querySelector<HTMLInputElement>('input[type="checkbox"][name="carousel_slider_allow_tracking"]');
if (inputEl) {
  window.console.log(window.ajaxurl);
  inputEl.addEventListener('change', (event) => {
    const url = new URL(window.CarouselSliderL10n.ajaxUrl);
    url.searchParams.set('action', 'carousel_slider_tracker_consent');
    url.searchParams.set('_token', inputEl.dataset.token as string);
    if ((event.target as HTMLInputElement).checked) {
      url.searchParams.set('carousel_slider_tracker_optin', 'true');
    } else {
      url.searchParams.set('carousel_slider_tracker_optout', 'true');
    }
    window.console.log(url.toString())
    sendGetRequest(url.toString()).then(() => {

    });
  })
}