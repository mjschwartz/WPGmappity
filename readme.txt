=== Plugin Name ===
Contributors: Matthew Schwartz
Tags: google maps, maps, google, mapping
Requires at least: 3.4
Tested up to: 3.4.1
Stable tag: 0.6

Point, Click, Map. Build and insert Google Maps just as easily as you insert images.  All WYSIWYG: no JavaScript, no XML, no coding at all.

== Description ==

Google Maps made Simple by WP-G-Mappity

WPGMappity allows you to easily insert Google Maps into your posts.  No XML or JavaScript needed, building a Google Map is done completely in a WYSIWYG form inside your blog.  

Versions 0.5 and above use version 3 of the Google Maps API.  For you this means:

* No API key needed
* New features
* Fast downloads: visitors to your blog only download one extra file from Google - nothing from you.

Features include:

* Markers and custom icons
* Routing
* Map Controls
* Completely customizable size and alignment

Project Home, full instructions, and help: [http://matthewschwartz.me/wordpress/wpgmappity/](http://matthewschwartz.me/wordpress/wpgmappity/)




== Installation ==

WPGMappity no longer requires a Google API key to operate.  Version 3 of the Google Maps API has removed this requirement.

Installation Instructions:

* Upload the full directory into your wp-content/plugins directory.
* Activate the plugin on the plugin administration page.
* Thats it!

More Installation Info: [http://matthewschwartz.me/wordpress/wpgmappity/wpgmappity-installation/](http://matthewschwartz.me/wordpress/wpgmappity/wpgmappity-installation/)

== Screenshots ==

1. WPGMappity Map Builder
1. WPGMappity button added to the Edit Post screen.

== Changelog ==

=0.6=

Bug fixes:

Allow library functionality to work with newer WordPress releases.

New Features:

Added a option to disable scrollwheel zooming.

=0.5.6=

Bug fixes:

Changed the "edit marker" screen to load markers with an asynchronous delay.  Loading more than 5 markers too quicly was hitting the Google Maps API geocaching rate limit.  Google should be happier about this.

"Unwedged" broken databases - People that upgraded with the first 0.5 versions find their DBs in an usable format.

=0.5.4=

Major release.

Now using version 3 of the Google Maps API.  This means all the goodies of v3 come that with it:

* API key no longer needed.
* Large speed increase.
* Significantly smaller code footprint.
* WPGMappity has been updated to reflect new map types and new map control types.

Custom marker images.  The UI is pretty basic at this point:  When creating a marker you can specify a URL that a marker icon can be loaded from.

Routing available.

Bugbusting.

Bug fix: Changed method for updating database

=0.5.3=

Bug Fix: Added versioning to the JS calls to prevent cache from previous versions.


=0.5.2=

Bug Fix: Marker import was being done in a stateful manner which blew up in faster JS interpreters.

=0.5.1=

Major release.

Now using version 3 of the Google Maps API.  This means all the goodies of v3 come that with it:

* API key no longer needed.
* Large speed increase.
* Significantly smaller code footprint.
* WPGMappity has been updated to reflect new map types and new map control types.

Custom marker images.  The UI is pretty basic at this point:  When creating a marker you can specify a URL that a marker icon can be loaded from.

Routing available.

Bugbusting.

Bug fix: edit code to try an protect old maps from damage with non-direct install.

=0.5=

Major release.

Now using version 3 of the Google Maps API.  This means all the goodies of v3 come that with it:

* API key no longer needed.
* Large speed increase.
* Significantly smaller code footprint.
* WPGMappity has been updated to reflect new map types and new map control types.

Custom marker images.  The UI is pretty basic at this point:  When creating a marker you can specify a URL that a marker icon can be loaded from.

Routing available.

Bugbusting.


= 0.4.4 =

Corrected a JS error.  WP 3.1 removed a JS var definition from core.

= 0.4.2 =

Important bug fix: WordPress 3.1's update to version 1.8 of jQuery UI broke the input screen.  
Several pieces of jQuery functionality that were needed were broken out into additional files
and needed to be included in the plugin's scripts manually.

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

