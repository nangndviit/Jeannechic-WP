=== Header Footer Code Manager Pro ===
Contributors: 99robots, charliepatel, DraftPress
Tags: header, footer, code manager, snippet, functions.php, tracking, google analytics, adsense, verification, pixel
Requires at least: 4.9
Requires PHP: 5.6.20
Tested up to: 6.2.2
Stable tag: 1.0.15
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://draftpress.com

Easily add tracking code snippets, conversion pixels, or other scripts required by third party services for analytics, marketing, or chat features.

== Description ==
Header Footer Code Manager Pro by 99 Robots is an easy interface to add snippets to the header or footer or above or below the content of your page.

= BENEFITS =
* Never have to worry about inadvertently breaking your site by adding code
* Avoid inadvertently placing snippets in the wrong place
* Eliminate the need for a dozen or more silly plugins just to add a small code snippet - Less plugins is always better!
* Never lose your code snippets when switching or changing themes
* Know exactly which snippets are loading on your site, where they display, and who added them

= FEATURES =
* Add an unlimited number of scripts and styles anywhere and on any post / page
* Manage which posts or pages the script loads
* Supports custom post types
* Supports ability to load only on a specific post or page, or latest posts
* Control where exactly on the page the script is loaded - head, footer, before content, or after content
* Script can load only on desktops or mobile. Enable or disable one or the other.
* Use shortcodes to manually place the code anywhere
* Label every snippet for easy reference
* Plugin logs which user added and last edited the snippet, and when

= PAGE DISPLAY OPTIONS =
1. Site wide on every post / page
2. Specific post
3. Specific page
4. Specific category
5. Specific tag
6. Specific custom post type
7. Latest posts only (you choose how many)
8. Manually place using shortcodes

= INJECTION LOCATIONS =
1. Head section
2. Footer
3. Top of content
4. Bottom of content

= DEVICE OPTIONS =
* Show on All Devices
* Only Desktop
* Only Mobile Devices

= SUPPORTED SERVICES =
* Google Analytics
* Google Adsense
* Google Tag Manager
* Clicky Web Analytics or other analytics tracking scripts
* Chat modules such as Olark, Drip, or
* Pinterest site verification
* Facebook Pixels, Facebook Scripts, Facebook og:image Tag
* Google Conversion Pixels
* Twitter
* Heatmaps from Crazy Egg, notification bars Hello Bar, etc.
* It can accept ANY code snippet (HTML / Javascript / CSS) from any service
* and the list goes on and on...

== MULTISITE NOTE ==
If using this plugin on a multisite network, please make sure that the plugin is activated on a subsite level only.

> #### Plugin Information
> * [Plugin Site](https://www.draftpress.com/header-footer-code-manager)
> * [Plugin Documentation](https://www.draftpress.com/docs/header-footer-code-manager)
> * [Free Plugins on WordPress.org](https://profiles.wordpress.org/99robots#content-plugins)
> * [Premium Plugins](https://www.draftpress.com/products)

== Installation ==

1. Upload `99robots-header-footer-code-manager` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to plugins page to see instructions for shortcode and php template tags

NOTE: If using this plugin on a multisite network, please make sure that the plugin is activated on a subsite level only.

== Screenshots ==

1. HFCM Settings
2. Dashboard - All Snippets
3. Add New Snippet - Read the documentation at:
http://www.draftpress.com/docs/header-footer-code-manager
4. Choose where you want your snippet to be displayed

== Frequently Asked Questions ==

= Q. Why do my scripts appear as text on the website? =
A. Please make sure to enclose your script within script tags - <<script>> Insert Script Here <</script>>.

= Q. Where are this plugin’s Settings located? =
A. After activating the plugin, you can click on settings link under the plugin name OR you can click the HFCM tab on the left side navigation. From there, you can add, edit, remove, and review code snippets.

= Q. How do I add code snippets to all my posts/pages? =
A. With the version 1.1.4 of the HFCM plugin, we have replaced the Specific Custom Post Types with the ability to add code to All Post Types which include posts, pages, attachments and custom post types.

= Q. I have a question =
A. Since this is a free plugin, please ask all questions on the support forum here on WordPress.org. We will try to respond to every question within 48 hours.

= Q. How can I request a feature or encourage future development? =
A. Free plugins rely on user feedback. Therefore, the best thing you can do for us is to leave a review to encourage others to try the plugin. The more users, the more likely newer features will be added. That's a very small thing to ask for in exchange for a FREE plugin.

= Q. Do you support X or Y tracking scripts? =
A. If your script is not supported, just let us know and we'll look into it immediately. We will do our best to ensure all reputable services are supported. When requesting support for a particular script, it would be nice to get a sample of the script so that we can see its structure.

== Changelog ==
= 1.0.15 = 2023-07-04
* ADDED: WordPress nonce checks while performing bulk actions on the snippets

= 1.0.14 = 2023-06-27
* FIXED: Check if Woocommerce installed before using its functions

= 1.0.13 = 2023-06-26
* FIXED: Snippets not showing up on Woocommerce product categories and tags
* UPDATED: Compatibility with WordPress 6.2.2
* UPDATED: Compatibility with PHP 8.2

= 1.0.12 = 2022-12-20
* UPDATED: Compatibility with WordPress 6.1.1
* FIXED: Snippet including in case of rest api in some cases
* FIXED: Proper checks for user access and capabilities

= 1.0.11 = 2022-09-27
* FIXED: All the PHP snippets will now execute when the plugin is initialized.
* FIXED: RSS feed showing snippets
* UPDATED: Compatibility with WordPress 6.0.2

= 1.0.10 = 2022-07-21
* UPDATED: Code improvements as per WordPress standards
* FIXED: XSS Security Vulnerability fix
* FIXED: Internationalization support for PO Translation files.  Plugin now supports translation to additional languages in addition to the base language, English.
* ADDED: 1 Translation for Hindi.
* UPDATED: Compatibility with WordPress 6.0.1

= 1.0.9 = 2022-06-23
* ADDED: Ability to add snippets in Admin (WP Backend)
* FIXED: PHP warnings when adding/editing snippets
* UPDATED: Add confirmation before deleting snippets
* UPDATED: Copy shortcode button

= 1.0.8 = 2022-06-10
* UPDATED: Compatibility with WordPress 6.0

= 1.0.7 = 2022-05-02
* ADDED: Copy shortcode to clipboard buttons on the edit snippet page and on the snippet list page
* UPDATED: Compatibility with WordPress 5.9.3
* UPDATED: Included Custom Taxonomies for snippets
* UPDATED: Snippet code editor size
* FIXED: Increased Number of Allowed Posts/Page Exclusions to 200K+ posts.

= 1.0.6 = 2022-03-30
* ADDED: Ability to add tags to snippets
* ADDED: Ability to manage tags
* ADDED: Ability to filter by tags
* FIXED: MultiSite slow query issue
* UPDATED: Compatibility with WordPress 5.9.2

= 1.0.5 = 2022-03-05
* ADDED: Ability to apply snippets to search, home, archive page only
* ADDED: Snippet search functionality
* ADDED: Snippet type filter
* ADDED: Snippet sort by location
* ADDED: Delete snippet button on edit snippet page
* UPDATED: Snippet column length
* FIXED: XSS vulnerability with request parameter page in the HFCM snippet listing screen

= 1.0.4 = 2022-02-03
* ADDED: Snippet Priority
* UPDATED: Compatibility with WordPress 5.9
* FIXED: Bug with the session variable

= 1.0.3 = 2022-01-17
* ADDED: Kill switch - Protection for site from losing control due to bad/invalid snippet
* ADDED: Setting for enable/disable snippet validation
* ADDED: Support for private posts & pages
* FIXED: Author not showing on Add/Edit snippet screen
* UPDATED: Text & Plugin icon

= 1.0.2 = 2021-11-10
* FIXED - Plugin update issue
* UPDATED: Code as per WordPress Coding Standards

= 1.0.1 = 2021-09-09
* FIXED - non-logged in user snippets display issue
* UPDATED: Minimum required PHP version
* UPDATED: Lowest WordPress version that the plugin will work on

= 1.0.0 = 2021-08-12
* Initial release - HFCM Pro is born! :)
