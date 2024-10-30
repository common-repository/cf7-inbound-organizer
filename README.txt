=== CF7 Inbound Organizer ===
Contributors: RobinLopulalan
Tags: lead form, contact form, email form, form, crm, lead tracker, lead tracking, cf7  
Requires at least: 6.0
Requires PHP: 7.3
Tested up to: 6.5.2
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Inbound messages from Contact Form 7 are organized on a board with 2 to 5 columns to track message processing. Depends on CF7 and Flamingo.

== Description ==

This plugin helps you to keep track of submitted CF7 forms that need to be processed by you or 
your team. Use it for example to track leads, support tickets or contact requests.

It is built on top of [Contact Form 7](https://wordpress.org/plugins/contact-form-7/) and [Flamingo](https://wordpress.org/plugins/flamingo/) (store form submissions).
To use the Inbound Organizer, users need to have the Flamingo permission to edit Inbound Messages.

The plugin adds a submenu in the WordPress Admin environment (Flamingo).
Messages are displayed visually in 2 to 5 columns representing the tracking status as you see fit.
1. Drag and drop a message to another column to reflect a status change.
1. Read message details by clicking on it.
1. Enrich messages by adding notes and colors.
1. Trash irrelevant ones.

== Installation ==

1. Make sure you have [Contact Form 7](https://wordpress.org/plugins/contact-form-7/) and [Flamingo](https://wordpress.org/plugins/flamingo/) installed and activated.
1. If needed, [configure your forms](https://contactform7.com/save-submitted-messages-with-flamingo/) so that data is correctly stored in Flamingo.
1. Download, install and activate CF7 Inbound Organizer just as any other plugin.
1. If installed and activated correctly, there will appear two new menu items in the Flamingo menu.

== Frequently Asked Questions ==

= Are there translations available? =

I am Dutch, so I have created a Dutch translation. Please contact me if you can provide support for additional language translations.

= Is this organizer also available for Gravity Forms or WP Forms? =

No, not yet. If you think it would be useful for your site, drop me a line and I will consider creating versions for Gravity Forms and/or WP Forms. 

== Screenshots ==

1. Inbound Organizer with 5 columns in use as a lead tracker.
2. Message details with notes and color picker.
3. Drag and drop a message to another column.
4. General Settings.
5. Columns Settings.
6. Add existing messages to the organizer.


== Changelog ==

= 1.0.0 =
* Initial release.

= 1.0.1 =
Fixed namespace conflict in AJAX object used for url and nonce.


