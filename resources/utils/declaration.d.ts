import {SliderTypeInfoInterface} from "./interfaces";

declare global {
  interface Window {
    ajaxurl: string;
    carouselSliderCssUrl: string;
    csDivi: {
      site_url: string;
    };
    CarouselSliderL10n: {
      sliderTypes: SliderTypeInfoInterface[];
      restRoot: string;
      restNonce: string;
      ajaxUrl: string;
    };
    CarouselSliderAdminL10n: {
      videoCarousel: {
        YoutubeOrVimeoURL: string;
        AreYouSureToDelete: string;
      }
    },
    i18nCarouselSliderBlock: {
      sliders: (Record<string, any>)[],
      site_url: string;
      block_logo: string;
      block_title: string;
      select_slider: string;
    }
  }
}
