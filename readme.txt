=== Plugin Name ===
Contributors: Matthhew Schwartz
Tags: google maps, maps
Requires at least: 2.9.0
Tested up to: 2.9.2
Stable tag: 0.1

Point, Click, Map. Insert Google Maps in your posts using just a web form inside Wordpress. All WYSIWYG: no JavaScript, no XML, no coding at all.

== Description ==

WPGMappity is a free and open source Wordpress plugin that allows you to easily insert Google Maps into your posts.  Unlike other plug-ins that require the use of XML data or JavaScript coding, building your map is accomplished completely in a WYSIWYG web form inside of Wordpress.  The form functions just like the media upload forms where you add images and attachments to your posts.

Features that are supported in the current release of WPGMappity include:

* Map Size
* Zoom Level
* Map Centre
* Map Type (normal, satellite, hybrid)
* Map Alignment in your post
* Add Markers to you map
* Allow clicking markers to open a Google Maps HTML window with text and links

After the plugin is installed, your can build Google Maps and display them in your Wordpress posts just by popping open the “edit Post” window like normal. There will be a new item in the Media Upload row at the top of your post (a “G” inside a grey box). After clicking the button you will be presented with the WPGMappity interface.

Summary of WPGMappity functionality – the numbers in red in the image above line up with the list of explanations below:

1. This is a preview of the map that you are building.  Any changes that you make in your configuration will be displayed in this preview.  Note: moving the map around does not effect how the map will be displayed.  Instead use the “Center Point” option below.
1. Size: Select the size of your map.  Much like choosing the size of an image in Wordpress, you can choose from predefined selections of small, medium, and large or pick a custom size.  Make sure the size you choose will fit comfortably in your blog’s template.
1. Center Point: Enter an address that will be the center of your map.  After you have entered an address the plugin will ask Google Maps to geolocate the position. If Google can’t find an exact match you will be offered choices that best fit the address you entered.  Click any of those to choose them.  The functionality in this section is much like entering an address or location on Google Maps.
1. Zoom Level: Adjust the zoom level by dragging the slider right to left. Levels go from 1 – 20.  1= Zoomed all the way out, 20 = zoomed all the way in.
1. Add Marker: To add a marker to a point on your map click “Add Marker.”  You will be asked to enter an address and if it is found by Google Maps it will be added to your map.  If not you will be presented with a choice of more addresses just as in “Center Point.”
1. Configure / Delete Markers: For each marker you have added you can delete it by selecting “Remove” or you can add a pop up window that will display text when a viewer clicks your marker.  To make the text displayed function as a link, check the box and enter a URL.
1. Map Type: Choose the type of map that you would like to display: normal, satellite, or a hybrid.
1. Alignment: Just like adding an image to your post you can have the map float left or right, be centered, or have no alignment specified.
1. Insert Map: When you have your map just how you like it hit “Insert Map.”  This will save off you map info to the database and insert a Wordpress “shortcode” into your post.  Wherever that shortcode is, the map will be displayed when you view your post.


== Installation ==

Installing WPGMappity is quick and simple: upload the plug-in, enter a Google Maps API key, and you are good to go.

If you do not already have a Google Maps API key you will need to obtain one from Google in order to display their maps on your web site. The API key is completely free of charge.

You can read more about it and view a FAQ on the [Google Maps API sign up page](http://code.google.com/apis/maps/signup.html).

Installation Instructions:

* Upload the full directory into your wp-content/plugins directory.
* Activate the plugin on the plugin administration page.
* A yellow box at the top of the admin panel will inform you that you need to enter a Google Maps API key.  Click the link offered to visit your WPGMappity Configuration page.
* Cut and Paste your API key in the box and click “Update API Key”.
* Thats it! The plugin is now available for use.

== Frequently Asked Questions ==

= X doesn't work right.  What's up with that? =

Visit the plugin's page if you encounter any problems!

= Does WPGMappity work with versions of Wordpress earlier than 2.9.1? =

The plugin was developed against 2.9.1, but is in the process of being tested against earlier versions.  


== Screenshots ==

/tags/0.1/wpgmappity-interface.png
