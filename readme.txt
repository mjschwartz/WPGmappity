=== Plugin Name ===
Contributors: Matthew Schwartz
Tags: google maps, maps, google, mapping
Requires at least: 2.9.0
Tested up to: 3.0.1
Stable tag: 0.4.1

Point, Click, Map. Build and insert Google Maps in your posts just as easily as you insert images.  All WYSIWYG: no JavaScript, no XML, no coding at all.

== Description ==

Google Maps made Simple by WP-G-Mappity

WPGMappity allows you to easily insert Google Maps into your posts.  No XML or JavaScript needed, building a Google Map is done completely in a WYSIWYG form inside your blog.  

Project Home, full instructions, and help: [http://www.wordpresspluginfu.com/wpgmappity/](http://www.wordpresspluginfu.com/wpgmappity/)




== Installation ==

You will need a free Google Maps API key to display Google maps on your web site.

Installation Instructions:

* Upload the full directory into your wp-content/plugins directory.
* Activate the plugin on the plugin administration page.
* Cut and Paste your Google Map API key in the G-Mappity admin page and click “Update API Key”.
* Thats it!

More Installation Info: [http://www.wordpresspluginfu.com/wpgmappity/wpgmappity-installation/](http://www.wordpresspluginfu.com/wpgmappity/wpgmappity-installation/)

== Screenshots ==

1. WPGMappity Map Builder
1. WPGMappity button added to the Edit Post screen.

== Changelog ==

= 0.4.1 =

Bug fix to allow multi-line marker text

= 0.4.0 =

Added a CSS rule that prevents WP 3.0 default screen from clipping maker windows

Changed the JSON functionality to be more unicode friendly.

Markers:
* Added option to mark by latitude / longitude
* Corrected a handful of bugs that were overwriting edit marker dialog

= 0.3.5 =
Put the json_decode() support both places it was supposed to be.

= 0.3.4 =
Added support for json_decode() for PHP4 systems

= 0.3.3 = 
Added a stylesheet and a single style to prevent themes from setting an image background inside a map's div.

= 0.3.1 =
Corrected a CSS error in alignment = center

= 0.3 =

* Added large and small Google Map controls.
* Added a class ("wpgmappity_container") to all WPGMappity map div's to allow CSS styling as necessary.
* Added "Map Library" in the build a map section.  The map allows the user to view saved maps and add, alter, or delete the records.

= 0.2.1 =
Corrected a pathing issue that was effecting some servers.

= 0.2 =
* Added a selection in settings to preserve all the map info in the database when the plugin is uninstalled.

= 0.1 =
* Initial release.

== Upgrade Notice ==

= 0.3 = 

Copy new files over existing.  There is a database migration that is managed by the plugin.

= 0.2 =
Users should upgrade by directly copying the new files over the old.

