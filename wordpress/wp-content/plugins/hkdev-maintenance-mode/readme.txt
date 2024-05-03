=== Maintenance Mode ===
Contributors: helderk, jfinch3, petervandoorn
Tags: maintenance,redirect,developer,coming soon,under construction
Requires at least: 6.1
Tested up to: 6.4.3
Stable tag: 2.4
Requires PHP: 7.4
Text Domain: hkdev-maintenance-mode
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin is intended primarily for developers that need to allow clients to preview sites before being available to the general public or to temporarily hide your WordPress site while undergoing major updates.


== Description ==
This plugin is based on an older version of the "Maintenance Redirect" plugin developed by Jack Finch and Peter Hardy-vanDoorn.

Allows you to specify a maintenance mode message or HTML page for your site as well as configure settings to allow specific users to bypass the maintenance mode functionality in order to preview the site prior to public launch, etc.

Any logged in user with WordPress administrator privileges will be allowed to view the site regardless of the settings in the plugin. The exact privilege can be set using a filter hook - see FAQs.

The behaviour of this can be enabled or disabled at any time without losing any of settings configured in its settings pane. However, deactivating the plugin is recommended versus having it activated while disabled.

Functionality to exclude pages from maintenance mode, so only the selected pages will be visible.

When redirect is enabled it can send 2 different header types. “200 OK” is best used for when the site is under development and “503 Service Temporarily Unavailable” is best for when the site is temporarily taken offline for small amendments. If used for a long period of time, 503 can damage your Google ranking.

A list of IP addresses can be set up to completely bypass maintenance mode. This option is useful when needing to allow a client’s entire office to access the site while in maintenance mode without needing to maintain individual access keys.

Access keys work by creating a key on the user’s computer that will be checked against when maintenance mode is active. When a new key is created, a link to create the access key cookie will be emailed to the email address provided. Access can then be revoked either by disabling or deleting the key.

This plugin allows four methods of notifying users that a site is undergoing maintenance:

  1. They can be presented with a message using WordPress’s wp_die() function which is core function of WordPress, which makes this plugin feel and work as a part of WordPress core.
  2. They can be presented with a message on a page created with the style of the current template.
  3. They can be presented with a custom HMTL page.
  4. They can be redirected to a static page or external URL.


== Installation ==
1. Upload the `hkdev-maintenance-mode` folder to your plugins directory (usually `/wp-content/plugins/`).
2. Activate the plugin through the `Plugins` menu in WordPress.
3. Configure the settings through the `Maintenance Mode` Settings panel.


== Frequently Asked Questions ==

= How can I bypass the redirect programatically? =

There is a filter which allow you to programatically bypass the redirection block:

**`hkdev_matches`**

This allows you to run pretty much any test you like, although be aware that the whole redirection thing runs *before* the `$post` global is set up, so WordPress conditionals such as `is_post()` and `is_tax()` are not available. 

This example looks in the `$_SERVER` global to see if any part of the URL contains "demo"

	function my_hkdev_matches( $hkdev_matches ) {
		if ( stristr( $_SERVER['REQUEST_URI'], 'demo' ) ) 
			$hkdev_matches[] = "<!-- Demo -->";
		return $hkdev_matches;
	}
	add_filter( "hkdev_matches", "my_hkdev_matches" );`

*Props to @brianhenryie for this!*

= How can I let my logged-in user see the front end? =

By default, Maintenance Mode uses the `manage_options` cap, but that is normally only applied to administrators. As it stands, a user with a lesser permissions level, such as editor, is able to view the admin side of the site, but not the front end. You can change this using this filter:

**`hkdev_user_can`**

This filter is used to pass a different WordPress capability to check if the logged-in user has permission to view the site and thus bypass the redirection, such as `edit_posts`. Note that this is run before `$post` is set up, so WordPress conditionals such as `is_post()` and `is_tax()` are not available. However, it's not really meant for programatically determining whether a user should have access, but rather just changing the default capability to be tested, so you don't really need to do anything other than the example below.

	function my_hkdev_user_can( $capability ) {
		return "edit_posts";
	}
	add_filter( "hkdev_user_can", "my_hkdev_user_can" );


== Changelog ==

= 3.0.2 =
* Security improvements

= 3.0.1 =
* Code completely revised and modernized to increase possible risks and vulnerabilities
* Implementation of various performance and security improvements

= 2.6.0 =
* Bug fix - Bypass Vulnerability

= 2.5.0 =
* Bug fix - Updated for WordPress WordPress 6.4.1
* Minor improvements and fixes

= 2.4.5 =
* Bug fix - conflict with other plugins using Select2

= 2.4.4 =
* Minor fixes

= 2.4.1 =
* Updated for WordPress 6.2.2
* Added new attributes to the html editor
* Minor improvements and fixes

= 2.3.2 =
* Minor fixes

= 2.3.1 =
* Minor fixes

= 2.3.0 =
* Bug fix
* Minor fixes

= 2.2.6 =
* Updated translations

= 2.2.5 =
* Updated for WordPress 6.1.1
* Added functionality to resend an access key
* Added functionality to copy access keys.
* Added functionality to facilitate insertion of IPs
* Minor fixes

= 2.2.4 =
* Updated for WordPress 6.0
* Minor fixes

= 2.2.3 =
* Updated for WordPress 5.8

= 2.2.2 =
* Bug fix

= 2.2.1 =
* Minor fixes

= 2.2.0 =
* Added functionality to exclude pages from maintenance mode.
* Firefox fix

= 2.1.4 =
* Minor fixes

= 2.1.3 =
* Added toggle switch for activating Maintenance Mode

= 2.1.2 =
* Added new allowed html Tags
* Minor improvements

= 2.1.1 =
* Added new icon status on admin bar menu
* Improve the uninstall function

= 2.1 =
* New settings panel interface for better usability.
* Added status indicator on the admin bar menu.
* Plugins ready for translation.
* Added Portuguese translation (pt-PT)
* Security fixes.

= 2.0 =
* First release of the new adaptation of the plugins based on the version created by petervandoorn and modified by jfinch3.
