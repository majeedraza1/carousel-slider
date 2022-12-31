=== Carousel Slider ===
Contributors: sayful, majeedraza
Donate link: https://www.buymeacoffee.com/sayful1
Tags: carousel, carousel slider, image carousel, product carousel, woocommerce, slider
Requires at least: 5.6
Tested up to: 6.1
Requires PHP: 7.0
Stable tag: 2.2.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt

Create SEO friendly Image, Logo, Video, Post, WooCommerce Product Carousel and Slider.

== Description ==

https://www.youtube.com/watch?v=UOYK79yVrJ4&list=PL9GiQPpTzMv6aMEq449a7RPzjSVRf1bsc&index=1

= Overview =

**Create SEO friendly Image, Logo, Video, Post, WooCommerce Product Carousel and Slider.**

Carousel Slider is a touch enabled WordPress plugin that lets you create highly customizable,
stylish responsive carousel slider. With Carousel Slider, you can create image carousel using media gallery or
custom url, post carousel, video carousel.

**If you like this plugin, please give us [5 star](https://wordpress.org/support/plugin/carousel-slider/reviews/?rate=5#new-post) to encourage for future improvement.**

= Key Features List =

* **Support major website/page builder**, including Gutenberg (WordPress core), Elementor, Visual Composer, SiteOrigin, Divi Builder
* **Multiple types carousel**, images from media gallery, images from URL, videos from youtube and vimeo, posts, and WooCommerce products carousel slider
* **Hero slider** with background image, title, description, call to action buttons and more
* **Posts carousel**, support Specific posts, Post Categories, Post Tags, Posts per page, Date range query and ordering
* **Video carousel**, support custom height and width (Currently only support video from Youtube and Vimeo)
* **WooCommerce Product carousel**, support Product Categories, Product Tags, Specific Products, Featured Products, Recent Products, Sale Products, Best-Selling Products, Top Rated Products
* Options to hide/show product Title, Rating, Price, Cart Button, Sale Tag, Wishlist Button, Quick View button and options to change color for Title, Button Background, Button text
* **Fully responsive**, configure the number of items to display for desktop, small desktop, tablet and mobile devices
* **Lightweight**, only loads stuff when carousel is used
* **Navigation and pagination**, choose what type of navigation is displayed for your carousel with unlimited colors option
* **Works great in touch devices**, Touch and Grab enabled
* Supported in all major browsers
* CSS3 3D Acceleration
* Multiple carousel on same page
* Lazy load images
* Support image title, caption, link url
* and more options

= How to Videos =

Images Carousel: [https://www.youtube.com/watch?v=UOYK79yVrJ4](https://www.youtube.com/watch?v=UOYK79yVrJ4)
Hero Slider: [https://www.youtube.com/watch?v=_hVsamgr1k4](https://www.youtube.com/watch?v=_hVsamgr1k4)
Posts Carousel: [https://www.youtube.com/watch?v=ImJB946azy0](https://www.youtube.com/watch?v=ImJB946azy0)
WooCommerce Products Carousel: [https://www.youtube.com/watch?v=yiAkvXyfakg](https://www.youtube.com/watch?v=yiAkvXyfakg)

== Installation ==

* From your WordPress dashboard go to **Plugins > Add New**.
* Search for **Carousel Slider** in **Search Plugins** box.
* Find the WordPress Plugin named **Carousel Slider** by **Sayful Islam**.
* Click **Install Now** to install the **Carousel Slider** Plugin.
* The plugin will begin to download and install.
* Now just click **Activate** to activate the plugin.

If you still need help. visit [WordPress codex](https://codex.wordpress.org/Managing_Plugins#Installing_Plugins)

== Screenshots ==

1. Carousel slider admin page
2. Front-end example of posts carousel slider.
3. Front-end example of videos carousel slider.
4. Front-end example of images carousel slider.
5. Front-end example of products carousel slider.
6. Front-end example of products Quick View.
7. Front-end example of image lightbox.

== Upgrade Notice ==
Update to get new features and better security.

== Frequently Asked Questions ==

= Will Carousel Slider work with my theme? =
Carousel Slider works with any WordPress theme if themes are developed according to WordPress standard.

= Is Carousel Slider responsive? =
Yes, Carousel Slider is fully responsive, mobile and touch-friendly.

= Can I add Carousel Slider anywhere on my website?=
Yes, you can add carousel inside your blog posts, pages, widgets, and anywhere else on your WordPress website with a shortcode.

= Can I have multiple carousels on same post/page?
Yes. You can add multiple carousel on same post/page.

= How to use Carousel Slider in Gutenberg Block Editor (WordPress 5.0 or later) =
Carousel Slider is first class citizen in Gutenberg Block Editor. Just search 'carousel slider'. There is
a dedicated 'carousel slider' block with live preview for Gutenberg Block Editor.

== Changelog ==

= version 2.2.0 - 2022-12-31 =
* Feature - Add template to overwrite design from theme.
* Feature - Add basic dialog to replace "Magnific Popup" library.
* Feature - Add "Swiper" for replacement of "Owl Carousel 2" for slider library.
* Dev - Re-design responsive setting functionality.
* Dev - Add SliderSettingInterface class.
* Dev - Add MetaBoxConfig class to make metabox configuration shareable.
* Dev - Add multi checkbox setting field.
* Fix - Hero carousel delete button not working.
* Fix - Hero carousel index is not correct.

= version 2.1.0 - 2022-05-27 =
* Feature - Add new javaScript dialog to create carousel slider with title and slider type.
* Feature - Update metabox functionality separating create and edit functionality.
* Feature - Update slider type metabox design.
* Fix - Video Content is not saving.
* Fix - Content slider animation is too fast and not considering autoplay speed.
* Dev - Add typescript support.
* Dev - Add new meta box fields (Radio, Switch, ButtonGroup, Breakpoint, Html, Switch).
* Dev - Update metabox hiding slider change on edit mode.
* Dev - Add REST endpoint to create slider.
* Dev - Add admin feedback ui for plugin de-activation feedback.
* Dev - Add admin ui to take user confirmation to send non-sensitive data.
* Dev - Add sanitize method to sanitize array of integer.
* Dev - Add 'image_size' attribute on SliderSetting class.
* Dev - Add TemplateParserInterface class.
* Dev - Fix `ReturnTypeWillChange` warning on PHP 8.0 onward
* Dev - Add filter to modify slider css classes.
* Dev - Add functionality to modify responsive breakpoint.
* Dev - Group color metabox setting together.
* Dev - Fix section setting and description is not showing.
* Dev - Add SliderSetting::lazy_load_image() to get lazy load setting.
* Dev - Add filter to modify hero and video carousel item html.

= version 2.0.10 - 2022-03-26 =
* Fix - Fix image carousel image edit button is not working for single image.

= version 2.0.9 - 2022-02-27 =
* Fix - Fix multi select field is showing as single select.

= version 2.0.8 - 2022-01-07 =
* Dev - Remove return type declaration from admin footer text.

= version 2.0.7 - 2022-01-07 =
* Fix - Fix null value issue on admin footer text.

= version 2.0.6 - 2022-01-03 =
* Add - Add functionality to add css file link dynamically if it is not added yet
* Add - Add custom module for "Divi Builder"
* Fix - Showing data update message on admin area.
* Dev - Add unit testing for helpers classes.
* Dev - Update code according to WordPress Coding Standards

= version 2.0.5 - 2021-11-20 =
* Fix - Product carousel button style is not working for 'read more' button

= version 2.0.4 - 2021-11-20 =
* Tweak - Update carousel preview link on edit page

= version 2.0.3 - 2021-11-04 =
* Fix - Fix error on image carousel structure data if image is deleted.

= version 2.0.2 - 2021-11-03 =
* Fix - Fix hero carousel height is not working as before.

= version 2.0.1 - 2021-11-02 =
* Fix - Fix issue with version 1 compatibility template for WooCommerce product loop item
* Feature - Add random order for images carousel

= version 2.0.0 - 2021-10-31 =

* Dev - Add CLI(Command Line Interface) to create test sliders.
* Dev - Update owl setting functionality to data attribute.
* Dev - Add CSS variable to handle inline style.
* Dev - Separate sliders code to modules
* Dev - Add Upgrader class to handle upgrade functionality.
* Dev - Update gutenberg block script.

[See changelog for all versions](https://raw.githubusercontent.com/sayful1/carousel-slider/main/changelog.md).
