=== Site Counter ===
Contributors: Sam Williams
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BSM7RW9DWDZPW
Tags: counter, counter visitors, visitors, widget counter, widget
Requires at least: 4.5.3
Tested up to: 4.5.3
Stable tag: 4.5.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Site Counter - is a simple count of visitors. It is will bring the number of visitors since the establishment of the counter on the site.

== Description ==

Site Counter - is a simple count of visitors. It is will bring the number of visitors since the establishment of the counter on the site. <br />
= The plugin has a configuration: =
* The number from which the report will be carried out visitors
* Cookie storage period for the visitors
* Now you need not call the function `**<php sc_sitecounter (); ?>**`, To counter worked
* Added a widget to the Home Screen page "Console"
* Added a widget - Site Counter to widget page

== Installation ==

1. Upload `site-counter.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use Settings -> Site Counter to configure the plugin
4. If you want to display the counter of visitors, you need to insert the code: `**<?php sc_sitecounter();?>** ` in your template.

== Frequently asked questions ==

= How do I display a counter on the website? =

If you want to display the counter of visitors, you need to insert the code: `<?php sc_sitecounter(); ?>` in your template.

= When remove the plug, the data will remain? =
No, after removal of the plug-in, its data is completely removed.

== Screenshots ==

1. Settings page
2. Add code `**<php sc_sitecounter (); ?>**` in your template
3. Displays the number of visitors
4. Statistics Widget
5. Widget Site Counter
6. Widget Site Counter worked

== Changelog ==

= 1.0 = Plugin Release
= 1.1 =
* Added a widget to the Home Screen page "Console"
* Now you need not call the function `**<php sc_sitecounter (); ?>**`, To counter worked.
= 1.2 =
* Added a widget

== Upgrade notice ==

If you deactivate the plugin, you need to remove the line - `<?php sc_sitecounter();?>`