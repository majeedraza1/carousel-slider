=== Carousel Slider ===
Contributors: sayful, stackonet, majeedraza
Tags: woocommerce, shortcode, images, carousel, carousel slider, image carousel, product carousel, slider, owl carousel
Requires at least: 4.8
Tested up to: 5.4
Requires PHP: 5.6
Stable tag: 1.9.4
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt

Create SEO friendly Image, Logo, Video, Post, WooCommerce Product Carousel and Slider.

== Description ==

**Create SEO friendly Image, Logo, Video, Post, WooCommerce Product Carousel and Slider.**
Carousel Slider is a touch enabled WordPress plugin that lets you create highly customizable, stylish responsive carousel slider. With Carousel Slider, you can create image carousel using media gallery or custom url, post carousel, video carousel. We have integrated [Owl Carousel 2](http://www.owlcarousel.owlgraphic.com/) into our plugin for the ultimate device support.

> Looking for a free minimal WordPress theme. Try [Shapla](https://wordpress.org/themes/shapla)


**If you like this plugin, please give us [5 star](https://wordpress.org/support/plugin/carousel-slider/reviews/?rate=5#new-post) to encourage for future improvement.**

= Full Feature Set =

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

= Hero Image Slider =
https://www.youtube.com/watch?v=_hVsamgr1k4

= Images Carousel using gallery images =
https://www.youtube.com/watch?v=ZzI1JhElrxc

= Images Carousel using custom URLs =
https://www.youtube.com/watch?v=a7hqn1yNzwM

= Posts Carousel =
https://www.youtube.com/watch?v=ImJB946azy0

= WooCommerce Products Carousel =
https://www.youtube.com/watch?v=yiAkvXyfakg

= With Page Builder by SiteOrigin =
https://www.youtube.com/watch?v=-OaYQZfr1RM

= With Visual Composer Website Builder =
https://www.youtube.com/watch?v=4LhDXH81whk

= Using as a Widget =
https://www.youtube.com/watch?v=kYgp6wp27lM

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

== Changelog ==

= version 1.9.4 - 2020-07-05 =
* Fix - in settings "Pause On Hover" looses it's value.
* Fix - Carousel Displaying code from Divi Builder on post thumbnails.
* Fix - Bug Store XSS with profile image.
* Tweak - Checked version compatibility with WooCommerce 4.2.*
* Dev - Replace module bundler from gulp to webpack.

= version 1.9.3 - 2019-11-17 =
* Tweak - Checked version compatibility with WooCommerce 3.8.*
* Tweak - Checked version compatibility with WordPress 5.3.*
* Add - Add settings to enable/disable loading scrips and styles to all pages.
* Tweak - Rename "WPBakery Visual Composer" to "Visual Composer Website Builder"

= version 1.9.2 - 2019-05-26 =
* Fix - Fix Carousel slider showing hidden products
* Tweak - Checked version compatibility with WooCommerce 3.6.*
* Tweak - Checked version compatibility with WordPress 5.2.*

= version 1.9.1 - 2019-05-07 =
* Tweak - Add WP_Post class as first params on `carousel_slider_post_read_more` filter hook.

= version 1.9.0 - 2019-01-20 =
* Tweak - Checked version compatibility with WooCommerce 3.5.*
* Tweak - Checked version compatibility with WordPress 5.0.*
* Tweak - Add Gutenberg block for carousel slider.
* Fixed - Hero banner slider heading and description style is not working.
* Fixed - Disable magnific popup counter as it show wrong count.
* Fixed - Fix "Link to URL" value is not deleting.
* Fixed - Post carousel border does not show properly.
* Tweak - Update post carousel style.
* Tweak - Update product carousel style with equal item width.
* Fixed - Product carousel star rating color does not work.
* Tweak - Removed "Columns Height" field from post carousel as it no longer required.
* Tweak - Slider item equal height for post and product carousel (issue #5)
* Dev - Two new filter `carousel_slider_post_read_more` and `carousel_slider_post_excerpt_length` has been added.

= version 1.8.9 - 2018-07-12 =
* Tweak - Update Owl Carousel version to v2.3.4 as fix rewind issue.
* Tweak - Transfer inline script to external file so that it should not conflict when combine scripts.
* Fixed - PHP notice of terms if WooCommerce is not installed.
* Dev - Tested with WooCommerce version 3.4

= version 1.8.8 - 2018-04-18 =
* Fixed - Roll back to Owl Carousel version 2.2.1 as new version has a bug relating to rewind feature that creates conflict with previous version.

= version 1.8.7 - 2018-03-26 =
* Fixed - Navigation is now showing when set always.

= version 1.8.6 - 2018-03-17 =
* Dev - Update Owl Carousel from version 2.2.1 to version 2.3.2
* Tweak - Update Owl Carousel style for new version.
* Added - Added video description for hero carousel on readme.txt file and Documentation admin menu.

= version 1.8.5 - 2018-01-31 =
* Fixed - Fixed syntax error for short array syntax on PHP 5.3

= version 1.8.4 - 2018-01-25 =
* Added - Add content animation for hero carouse slider.
* Fixed - Open Slide Link In New Window issue
* Removed - Fixed Width and Height for video carousel.
* Added - Add lightbox support for video carousel

= version 1.8.3 - 2017-12-01 =
* Added - Background Overlay color for hero carousel.
* Added - Ken Burns Effect for hero carousel.
* Tweak - Update hero carousel style.
* Removed - Heading and Description background color.
* Dev - Load non-minified version when script debug is enabled.
* Dev - Update core code.

= version 1.8.2 - 2017-11-03 =
* Fixed - Fixed color overlapping issue on WordPress 3.9

= version 1.8.1 - 2017-11-03 =
* Added - Auto Width: set item width according to its content width.
* Added - Stage Padding: Stage padding option adds left and right padding style (in pixels) onto stage-wrapper.
* Fixed - Carousel Slider widget only show latest five slider.
* Fixed - arrows is showing on mobile device.

= version 1.8.0 - 2017-09-30 =
* Added - Hero banner slider(beta)
* Added - Hero banner slider: Add unlimited number of slide
* Added - Hero banner slider: Delete and sort(up, down, top, bottom) slide
* Added - Hero banner slider: Slide background with color, image and more
* Added - Hero banner slider: Slide title and description
* Added - Hero banner slider: Up to two call to action buttons with full style
* Added - Arrow Nav: Position (Inside or Outside)
* Added - Arrow Nav: Custom Size
* Tweak - Arrow Nav: Always visibility
* Added - Bullet Nav: Position (Left, Center or Right)
* Added - Bullet Nav: Custom Size
* Added - Bullet Nav: Square or Circle Shape
* Tweak - Arrow Nav: Option to enable visibility only on hover
* Tweak - Alpha color picker for choosing color
* Added - WooCommerce product categories list carousel.
* Fixed - Fix featured and top rated products on WooCommerce 3.x.x
* Fixed - Fix popup/modal not working always
* Tweak - Update owlCarousel version 2.2.1

= version 1.7.4 - 2017-08-26 =
* Fixed - Fixed syntax error for short array syntax on PHP 5.3

= version 1.7.3 - 2017-08-25 =
* Added - JSON-LD structured data for post carousel.
* Added - Added uninstall.php file to remove data on uninstall.
* Added - Added admin only notice for deprecated shortcode.
* Fixed - get_product() deprecated notice on WooCommerce version 3
* Fixed - Fixed error on WooCommerce version 2.6.* and 2.5.*
* Tweak - Remove dependency over jquery.livequery.js and update admin javascript.
* Tweak - Refactor code with WordPress coding style.

= version 1.7.2 - 2017-04-07 =
* New   - Structured data generator using JSON-LD format for Product Carousel and Gallery Image Carousel.

= version 1.7.1 - 2017-04-05 =
* New   - WooCommerce 3.0.0 compatibility
* Fixed - get_product() has been replaced with wc_get_product()
* Fixed - get_rating_html() has been replaced with wc_get_rating_html()
* Fixed - Fixed id was called incorrectly (Product properties should not be accessed directly) notice.

= version 1.7.0 - 2017-03-12 =
* Added - WooCommerce Product carousel.
* Added - WooCommerce Product Quick View button.
* Added - WooCommerce Product wishlist button (Required YITH WooCommerce Wishlist).
* Added - Unlimited colors for Product button and title.
* Added - Lightbox support for images carousel.

= version 1.6.3 - 2017-01-31 =
* Added - Added title option for carousel slider widget.
* Tweak - Tweak at post carousel admin interface.
* Tweak - Added new carousel_slider_load_scripts filter hook for modifying scripts load conditions.
* And some other tweak for coding improvement.

= version 1.6.2 - 2017-01-27 =
* Fixed  - Removed PHP trait to add support to PHP 5.3 as some users still use PHP 5.3
* Fixed  - Fixed issue to make all carousel slider type to **Image Carousel** on activation.

= version 1.6.1 - 2017-01-12 =
* Added   - Show posts by Post Categories, Post Tags.
* Added   - Option to choose query type - Latest Posts, Date Range, Post Categories, Post Tags or Specific Posts.
* Added   - Added option to set link target for image carousel when click on image.
* Fixed   - Fixed issue for not saving value from multiple select when no/empty value.
* Updated - Pre and Next Navigation buttons has been changed by inline SVG images.

= version 1.6.0 - 2016-12-22 =
* Added   - "Link to URL" field at WordPress media uploader for linking carousel image.
* Added   - Video carousel slider with custom height and width.
* Added   - Posts carousel slider supporting Specific posts, Posts per page, Date range query and ordering posts.
* Added   - Images carousel using custom URL with sorting, custom title, custom caption and link to URL features.
* Added   - New breakpoint for Extra Large Desktop Layout
* Updated - Owl Carousel v2.2.0 javaScript library
* Shortcode **[carousel]** and **[item]** has been deprecated but backup up for previous versions.
* Documentation enhancement and improvements.

= version 1.5.3 - 2016-12-08 =
* Fixed   - Issue for not saving zero value for margin.

= version 1.5.2 - 2016-12-03 =
* Added   - Added options to show title and caption on carousel item.
* Added   - Lazy Load of images.
* Added   - Support multiple carousel slide at same page.
* Added   - New Carousel Slider Widget to add carousel at widget. Especially helpful for page builder that use widget like "SiteOrigin Page Builder".
* Added   - New Element added for Visual Composer Website Builder. If you are using Visual Composer Website Builder.
* Merged  - All CSS styles in on file.

= version 1.5.1 - 2016-08-11 =
* Version compatibility check and some bug fix.

= version 1.5.0 - 2016-02-05 =

* Added graphical interface to add carousel
* Added shortcode attributes 'inifnity_loop', 'autoplay_timeout', 'autoplay_speed', 'slide_by', 'nav_color', 'nav_active_color', 'margin_right'
* Removed shortcode 'carousel_slider' and 'all-carousels'
* Removed shortcode attributes 'pagination_speed', 'rewind_speed', 'scroll_per_page', 'pagination_numbers', 'auto_height', 'single_item'

= version 1.4.0 =

* Added option to add custom image size
* Added option to link each slide to a URL
* Added option to open link in the same frame as it was clicked or in a new window or tab.
* Added feature to add multiple slider at page, post or theme by custom post category slug
* Re-write with Object-oriented programming (OOP)

= version 1.3.0 =

* Tested with WordPress version 4.1

= version 1.2.0 =

* Fixed bugs regarding shortcode.
* Added href="" to add link to post, page or media
* Translation ready

= version 1.1.0 - 2014-07-15 =

* Fixed some bugs.

= version 1.0.0 - 2014-06-30 =
* Initial release.

== Upgrade Notice ==
Update to get exciting new features and better security.
