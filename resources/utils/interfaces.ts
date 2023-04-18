interface SliderTypeInfoInterface {
  icon: string;
  slug: string;
  label: string;
  enabled: boolean;
  pro?: boolean;
}

type CONTENT_ANIMATION = 'fadeInDown' | 'fadeInUp' | 'fadeInRight' | 'fadeInLeft' | 'zoomIn';
type LINK_TARGET = '_self' | '_blank';
type BUTTON_TYPE = 'normal' | 'stroke';
type BUTTON_SIZE = 'large' | 'medium' | 'small';
type BG_SIZE = 'auto' | 'contain' | 'cover' | '100% 100%' | '100% width' | '100% height';
type BG_POSITION =
  'left top'
  | 'left center'
  | 'left bottom'
  | 'center top'
  | 'center center'
  | 'center bottom'
  | 'right top'
  | 'right center'
  | 'right bottom';

interface ButtonSettingInterface {
  text: string;
  url: string;
  target: LINK_TARGET;
  type: BUTTON_TYPE;
  size: BUTTON_SIZE;
  border_width: string;
  border_radius: string;
  bg_color: string;
  color: string;
}

interface HeroCarouselItemInterface {
  background_type: 'color' | 'image'
  bg_color: string,
  bg_image: {
    img_id: number,
    img_position: BG_POSITION,
    img_size: BG_SIZE,
    overlay_color: string,
    ken_burns_effect: 'zoom-in' | 'zoom-out' | '',
  },
  content_alignment: 'left' | 'center' | 'right';
  content_animation: '' | CONTENT_ANIMATION;
  heading: {
    text: string;
    font_size: string,
    margin_bottom: string,
    color: string,
  },
  description: {
    text: string;
    font_size: string,
    margin_bottom: string,
    color: string,
  },
  link_type: 'none' | 'full' | 'button',
  full_link?: { url: string, target: LINK_TARGET }
  button_link?: {
    primary?: ButtonSettingInterface;
    secondary?: ButtonSettingInterface;
  }
}

interface HeroCarouselSettingsInterface {
  items: HeroCarouselItemInterface[],
  slide_height: string | Record<string, string>;
  content_width: string | Record<string, string>;
  content_padding: { top: string; right: string; bottom: string; left: string };
  content_animation: '' | CONTENT_ANIMATION;
}

export {
  SliderTypeInfoInterface,
  HeroCarouselItemInterface,
  HeroCarouselSettingsInterface
}
