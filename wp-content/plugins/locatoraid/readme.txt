=== Locatoraid - Store Locator Plugin ===

Contributors: HitCode
Tags: business locations, dealer locator, dealer locater, store locator, post, store locater, mapping, mapper, google, google maps, ajax, shop locator, shop finder, shortcode, location finder, places, widget, stores, plugin, maps, coordinates, latitude, longitude, posts, geo, geocoding, jquery, shops, page, zipcode, zip code, zip code search, store finder, address map, address location map, map maker, map creator, mapping software, map tools, mapping tools, locator maps, map of addresses, map multiple locations, wordpress locator, store locator map
License: GPLv2 or later

Stable tag: trunk
Requires at least: 3.3
Tested up to: 4.0

Add a store locator map to your site and get your visitors to start finding you faster!

== Description ==

Locatoraid is WordPress store locator, zip code locator plugin for any business that needs to let customers search online for their stores, dealers, hotels, restaurants, ATMs, products, locations, etc. 

This locator plugin takes the address or zip code input from your customer and returns nearest matches sorted by distance, complete with online Google map, driving directions, contact info, links and more. 

###Lite Version Features###
* Automatic geocoding to quickly locate your stores
* Insert a store locator map with a shortcode
* Driving directions
* Sort results by distance, state or city
* Configurable search radius
* Auto detect customer position
* Customizable options

###Pro Version Features###

* __CSV Import / Export__ to bulk add and update your locations
* __Custom Fields__    to show more information
* __Location Priority__ to highlight and move higher important entries
* __Search Stats__ to keep track of what are your visitors searching for

[Get the Pro version now!](http://www.locatoraid.com/order/)

== Support ==
Please contact us at http://www.locatoraid.com/contact/

Author: HitCode
Author URI: http://www.locatoraid.com

== Installation ==

1. After unzipping, upload everything in the `locatoraid` folder to your `/wp-content/plugins/` directory (preserving directory structure).

2. Activate the plugin through the 'Plugins' menu in WordPress.

3. To make it full width on default WP 2014 theme add the following code to the post with Locatoraid shortcode:
<style>
.site-content .entry-content
{
max-width: 100%;
}
#lpr-results
{
width: 100%;
overflow: auto;
}
#lpr-map {
width: 60%;
float: left;
}
#lpr-locations
{
width: 35%;
float: right;
}
</style>

== Upgrade Notice ==
The upgrade is simply - upload everything up again to your `/wp-content/plugins/` directory, then go to the Locatoraid menu item in the admin panel. It will automatically start the upgrade process if any needed.

== Changelog ==

= 2.4.0 =
* Now it can recognize shortcode options. Currently there are 2: "search" for the search address, and "search2" for the product option if you have any.
For example: [locatoraid search2="Pizza"]

= 2.3.9 =
* Added options to configure all other labels in the front end search form so now it can be easily translated into any language.

= 2.3.8 =
* Added an option to configure the search form label: the "Address or zip code" text.

= 2.3.7 =
* Loading Google maps JavaScript libraries with "//" rather than "http://" that will fix the error on https websites

= 2.3.6 =
* Fixed the empty label for website address in the admin panel

= 2.3.5 =
* Fixed compatibility issue with AutoChimp plugin
* Modified the CSV import code that may have failed then loading UTF-8 encoded CSV files (applies to the Pro version).

= 2.3.4 =
* Added a dropdown input to choose a country if you have locations in several countries
* Added a configuration for the location website label. If no label is given then the location's website URL is displayed. Applies to the Pro version.
* BUG: fatal error when Locatoraid front end was appeared on a post in the blog posts list rather on a page of its own.

= 2.3.3 =
* A fix for front end view for sites that implement page caching for example WPEngine

= 2.3.2 =
* BUG: when submitting the search by hitting the Enter button rather than a click, the auto-detect location input was appearing.

= 2.3.1 =
* Added an option to hide the user autodetect button
* Added an option to view locations in alphabetical order (in Settings > Group Output)
* BUG: the admin area in multi site installation was redirecting to the default site
* Added the data-id attribute in the location wrapping div (.lpr-location) in the front-end for a possible developer use

= 2.3.0 =
* Admin panel restyled for a closer match to the latest WordPress version.
* Front end JavaScript placed in a separate file to optimize loading.
* Cleaned and optimized many files thus greatly reducing the package size.
* The Pro version now features automatic updates option too.

= 2.2.2 =
* Redesigned the front end search form.
* Minor updates and fixes.

= 2.2.1 =
* Fixed a bug if you are using several instances (like locatoraid2.php and [locatoraid2] shortcode), it was showing the first instance for all the shortcodes.
* Added a wrapping CSS classes for location view in front end like .lpr-location-distance, .lpr-location-address, .lpr-location-name

= 2.2.0 =
* Added an option to set a limit on the number of locations that are shown following a search. For example, even though there may be 10 locations near AB10 C78, the locator only shows 3.

= 2.1.9 =
* Added a search form widget

= 2.1.8 =
* Making the plugin admin area accessible by only Editors or higher

= 2.1.7 =
* a small fix in the front end view when both "append search" and "start with all locations listing" options were enabled

= 2.1.6 =
* jQuery live() deprecated calls replaced

= 2.1.5 =
* When using auto search (auto detecting the current location), and switching the distance or the product selection, the search results were reverted back to the default search rather than current location.
* Language file fix

= 2.1.4 =
* Failed setup procedure in some WP configurations 

= 2.1.3 =
* Error in location count when prompting a next radius search
* Failed shortcode with some WP configurations 

= 2.1.2 =
* Enabled native languages interface

= 2.1.1 =
* Cleared jQuery dependency, making use of the built-in WP version

= 2.1.0 =
* Initial plugin version release


Thank You.

 
