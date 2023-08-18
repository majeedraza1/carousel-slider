#### Version 2.2.3 - 2023-08-18

* Fix - Fix a security issue related to plugin data tracking consent option.

#### Version 2.2.2 - 2023-08-08

* Dev - Tested with WordPress 6.3 and WooCommerce 7.9
* Dev - Add REST API functionality to create/update hero carousel (Coming UI improvement for hero carousel).

#### Version 2.2.1 - 2023-03-31

* Dev - Tested with WordPress 6.2 and WooCommerce 7.5
* Dev - Update Swiper javaScript library to version 9.1
* Dev - Update others JavaScript dependencies to the latest version.

#### Version 2.2.0 - 2022-12-31

* Feature - Add template to overwrite design from theme.
* Feature - Add basic dialog to replace "Magnific Popup" library.
* Feature - Add "Swiper" for replacement of "Owl Carousel 2" for slider library.
* Dev - Re-design responsive setting functionality.
* Dev - Add SliderSettingInterface class.
* Dev - Add MetaBoxConfig class to make metabox configuration shareable.
* Dev - Add multi checkbox setting field.
* Fix - Hero carousel delete button not working.
* Fix - Hero carousel index is not correct.

#### Version 2.1.1 - 2022-10-04

* Fix - Fix error for WooCommerce product carousel error.

#### version 2.1.0 - 2022-05-27

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

#### version 2.0.10 - 2022-03-26

* Fix - Fix image carousel image edit button is not working for single image.

#### version 2.0.9 - 2022-02-27

* Fix - Fix multi select field is showing as single select.

#### version 2.0.8 - 2022-01-07

* Dev - Remove return type declaration from admin footer text.

#### version 2.0.7 - 2022-01-07

* Fix - Fix null value issue on admin footer text.

#### version 2.0.6 - 2022-01-03

* Add - Add custom module for "Divi Builder"
* Add - Add functionality to add css file link dynamically if it is not added yet
* Fix - Showing data update message on admin area.
* Dev - Add unit testing for helpers classes.
* Dev - Update code according to WordPress Coding Standards

#### version 2.0.5 - 2021-11-20

* Fix - Product carousel button style is not working for 'read more' button

#### version 2.0.4 - 2021-11-20

* Tweak - Update carousel preview link on edit page

#### version 2.0.3 - 2021-11-04

* Fix - Fix error on image carousel structure data if image is deleted.

#### version 2.0.2 - 2021-11-03

* Fix - Fix hero carousel height is not working as before.

#### version 2.0.1 - 2021-11-02

* Fix - Fix issue with version 1 compatibility template for WooCommerce product loop item
* Feature - Add random order for images carousel

#### version 2.0.0 - 2021-09-09

* Dev - Update preview slider core code.
* Dev - Add CLI(Command Line Interface) to create test sliders.
* Dev - Update owl setting functionality to data attribute.
* Dev - Add CSS variable to handle inline style.
* Dev - Separate sliders code to modules
* Dev - Add Upgrader class to handle upgrade functionality.
* Dev - Update gutenberg block script.

#### version 1.10.0 - 2021-03-17

* Dev - Tested with WordPress 5.7 and WooCommerce 5.1
* Dev - Update core code with namespace

#### version 1.9.5 - 2020-12-19

* Dev - Update javaScript dependencies to latest version.
* Dev - Tested with WordPress 5.6 and WooCommerce 4.8

#### version 1.9.4 - 2020-07-05

* Fix - in settings "Pause On Hover" looses it's value.
* Fix - Carousel Displaying code from Divi Builder on post thumbnails.
* Fix - Bug Store XSS with profile image.
* Tweak - Checked version compatibility with WooCommerce 4.2.*
* Dev - Replace module bundler from gulp to webpack.

#### version 1.9.3 - 2019-11-17

* Tweak - Checked version compatibility with WooCommerce 3.8.*
* Tweak - Checked version compatibility with WordPress 5.3.*
* Add - Add settings to enable/disable loading scrips and styles to all pages.
* Tweak - Rename "WPBakery Visual Composer" to "Visual Composer Website Builder"

#### version 1.9.2 - 2019-05-26

* Fix - Fix Carousel slider showing hidden products
* Tweak - Checked version compatibility with WooCommerce 3.6.*
* Tweak - Checked version compatibility with WordPress 5.2.*

#### version 1.9.1 - 2019-05-07

* Tweak - Add WP_Post class as first params on `carousel_slider_post_read_more` filter hook.

#### version 1.9.0 - 2019-01-20

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

#### version 1.8.9 - 2018-07-12

* Tweak - Update Owl Carousel version to v2.3.4 as fix rewind issue.
* Tweak - Transfer inline script to external file so that it should not conflict when combine scripts.
* Fixed - PHP notice of terms if WooCommerce is not installed.
* Dev - Tested with WooCommerce version 3.4

#### version 1.8.8 - 2018-04-18

* Fixed - Roll back to Owl Carousel version 2.2.1 as new version has a bug relating to rewind feature that creates
  conflict with previous version.

#### version 1.8.7 - 2018-03-26

* Fixed - Navigation is now showing when set always.

#### version 1.8.6 - 2018-03-17

* Dev - Update Owl Carousel from version 2.2.1 to version 2.3.2
* Tweak - Update Owl Carousel style for new version.
* Added - Added video description for hero carousel on readme.txt file and Documentation admin menu.

#### version 1.8.5 - 2018-01-31

* Fixed - Fixed syntax error for short array syntax on PHP 5.3

#### version 1.8.4 - 2018-01-25

* Added - Add content animation for hero carouse slider.
* Fixed - Open Slide Link In New Window issue
* Removed - Fixed Width and Height for video carousel.
* Added - Add lightbox support for video carousel

#### version 1.8.3 - 2017-12-01

* Added - Background Overlay color for hero carousel.
* Added - Ken Burns Effect for hero carousel.
* Tweak - Update hero carousel style.
* Removed - Heading and Description background color.
* Dev - Load non-minified version when script debug is enabled.
* Dev - Update core code.

#### version 1.8.2 - 2017-11-03

* Fixed - Fixed color overlapping issue on WordPress 3.9

#### version 1.8.1 - 2017-11-03

* Added - Auto Width: set item width according to its content width.
* Added - Stage Padding: Stage padding option adds left and right padding style (in pixels) onto stage-wrapper.
* Fixed - Carousel Slider widget only show latest five slider.
* Fixed - arrows is showing on mobile device.

#### version 1.8.0 - 2017-09-30

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

#### version 1.7.4 - 2017-08-26

* Fixed - Fixed syntax error for short array syntax on PHP 5.3

#### version 1.7.3 - 2017-08-25

* Added - JSON-LD structured data for post carousel.
* Added - Added uninstall.php file to remove data on uninstall.
* Added - Added admin only notice for deprecated shortcode.
* Fixed - get_product() deprecated notice on WooCommerce version 3
* Fixed - Fixed error on WooCommerce version 2.6.* and 2.5.*
* Tweak - Remove dependency over jquery.livequery.js and update admin javascript.
* Tweak - Refactor code with WordPress coding style.

#### version 1.7.2 - 2017-04-07

* New - Structured data generator using JSON-LD format for Product Carousel and Gallery Image Carousel.

#### version 1.7.1 - 2017-04-05

* New - WooCommerce 3.0.0 compatibility
* Fixed - get_product() has been replaced with wc_get_product()
* Fixed - get_rating_html() has been replaced with wc_get_rating_html()
* Fixed - Fixed id was called incorrectly (Product properties should not be accessed directly) notice.

#### version 1.7.0 - 2017-03-12

* Added - WooCommerce Product carousel.
* Added - WooCommerce Product Quick View button.
* Added - WooCommerce Product wishlist button (Required YITH WooCommerce Wishlist).
* Added - Unlimited colors for Product button and title.
* Added - Lightbox support for images carousel.

#### version 1.6.3 - 2017-01-31

* Added - Added title option for carousel slider widget.
* Tweak - Tweak at post carousel admin interface.
* Tweak - Added new carousel_slider_load_scripts filter hook for modifying scripts load conditions.
* And some other tweak for coding improvement.

#### version 1.6.2 - 2017-01-27

* Fixed - Removed PHP trait to add support to PHP 5.3 as some users still use PHP 5.3
* Fixed - Fixed issue to make all carousel slider type to **Image Carousel** on activation.

#### version 1.6.1 - 2017-01-12

* Added - Show posts by Post Categories, Post Tags.
* Added - Option to choose query type - Latest Posts, Date Range, Post Categories, Post Tags or Specific Posts.
* Added - Added option to set link target for image carousel when click on image.
* Fixed - Fixed issue for not saving value from multiple select when no/empty value.
* Updated - Pre and Next Navigation buttons has been changed by inline SVG images.

#### version 1.6.0 - 2016-12-22

* Added - "Link to URL" field at WordPress media uploader for linking carousel image.
* Added - Video carousel slider with custom height and width.
* Added - Posts carousel slider supporting Specific posts, Posts per page, Date range query and ordering posts.
* Added - Images carousel using custom URL with sorting, custom title, custom caption and link to URL features.
* Added - New breakpoint for Extra Large Desktop Layout
* Updated - Owl Carousel v2.2.0 javaScript library
* Shortcode **[carousel]** and **[item]** has been deprecated but backup up for previous versions.
* Documentation enhancement and improvements.

#### version 1.5.3 - 2016-12-08

* Fixed - Issue for not saving zero value for margin.

#### version 1.5.2 - 2016-12-03

* Added - Added options to show title and caption on carousel item.
* Added - Lazy Load of images.
* Added - Support multiple carousel slide at same page.
* Added - New Carousel Slider Widget to add carousel at widget. Especially helpful for page builder that use widget
  like "SiteOrigin Page Builder".
* Added - New Element added for Visual Composer Website Builder. If you are using Visual Composer Website Builder.
* Merged - All CSS styles in on file.

#### version 1.5.1 - 2016-08-11

* Version compatibility check and some bug fix.

#### version 1.5.0 - 2016-02-05

* Added graphical interface to add carousel
* Added shortcode attributes 'inifnity_loop', 'autoplay_timeout', 'autoplay_speed', 'slide_by', 'nav_color', '
  nav_active_color', 'margin_right'
* Removed shortcode 'carousel_slider' and 'all-carousels'
* Removed shortcode attributes 'pagination_speed', 'rewind_speed', 'scroll_per_page', 'pagination_numbers', '
  auto_height', 'single_item'

#### version 1.4.0

* Added option to add custom image size
* Added option to link each slide to a URL
* Added option to open link in the same frame as it was clicked or in a new window or tab.
* Added feature to add multiple slider at page, post or theme by custom post category slug
* Re-write with Object-oriented programming (OOP)

#### version 1.3.0

* Tested with WordPress version 4.1

#### version 1.2.0

* Fixed bugs regarding shortcode.
* Added href="" to add link to post, page or media
* Translation ready

#### version 1.1.0 - 2014-07-15

* Fixed some bugs.

#### version 1.0.0 - 2014-06-30

* Initial release.
