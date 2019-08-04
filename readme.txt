=== Twitter Badge Widget ===
Contributors: Workshopshed, 2020media, visioniz
Tags: twitter, widget, multi-widget
Donate link: http://www.workshopshed.com/
Requires at least: 2.8
Tested up to: 3.4.1
Stable tag: 1.74
License: GPLv2 or later

Provides a twitter badge widget that you can place on your wordpress site to display tweets from a specific twitter user.

== Description ==

**WARNING**

As it stands this plugin will stop working in the near future. See http://wordpress.org/support/topic/plugin-twitter-badge-widget-twitter-breaking-changes


The widget takes a twitter username (screen name) and the number of tweets to display. 

**Features**

* The time that the tweet was made is formatted in a friendly style.
* The users, urls and hashtags are all linked from the tweets.
* The current version will display between 1 and 200 tweets.
* The title is optional and if entered will display in the usual style for titles, it is suppressed if not entered.
* There are two choices of layout, a narrow format for sidebars this is the default and a wide format for a banner shaped display which can be used on the top of pages.
* The follow can be a simple text link or an interactive button with count.
* Clientside cache with defaut of 1 minutes so that twitter rates are not exceeded.

== Installation ==

The installation is standard.

1. Upload all the files to a new directory in the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. From Appearance section of the dashboard select widgets
1. Drag the "Twitter Badge Widget" into your selected sidebar
1. Configure the twitter username and optionally the number of tweets and style. 

== Upgrade Notice ==

Improved javascript error handling to diagnose incompatibility issues with other jQuery plugins. Better support for Safari browser.

== Frequently Asked Questions ==

= I can't work out how to add the badge =

Your wordpress theme needs to support widgets, you can tell this from the themes page of the dashboard. You should see a link to widgets from there and also in the Appearance -> Widgets shortcut menu

= How do I change the graphics or appearance? =

This is handled by your style sheet, see TwitterBadge.css for details of the available styles, TBW_Wide is the wrapper for the wide style and TBW_Narrow for the default style.
If you want supress the default css file or totally replace the file then you can do that by hooking the filter "TBW_AlternateCSS" and either returning your own stylesheet or an empty string to turn off the default styles.

= How to hide the time? =

Change the ".TBW_Time" style to be display:none; 

= How to separate each tweet with a thin dotted line? =

Add to the bottom of the style ".TBW_Status ul li"

border-bottom: dotted;
border-bottom-width: thin;

= How can I see two users next to each other? =

The widget does not support multiple twitter accounts in one list but you could put two widgets next to each other and omit the title from the second one.

= It's not working.... =

If you see "loading..." and nothing is ever returned then you may have a clash with Jquery or a javascript error on your page that stops the document ready event being fired correctly.
If you see "loading..." and then it disappears then it's possible there is an issue with twitter or the username supplied is not valid.
If you wish to display the error message to the users then remove the style TBW_Error from the CSS file.

Feel free to add comments on the forum but be aware that I'm not always able to answer them quickly.

= It's still not working.... =

There is a know incompatibility with one of the settings in the WordPress-Amazon-Associate plugin which causes the jQuery and $ variables to be lost.
In the settings for "Enable Product Previews", turn off the "fix for some theme/plugin combinations" check box.

= It's not html4 / xhtml compliant =

That's true, it will report errors about invalid data-xxx attributes on some of the tags. Some of these are custom to the twitter badget widget and the others are prescribed by twitter for their interactive follow button. The advantage of this technique is that it will not cause any issues with rendering as unknown attributes are ignored and that it means there is less javascript to be run on the page.

= I want it to work in my language =

Although I've put some initial code in place to enable different languages I've not yet tested it with language files. If you want to have it working in your language then let me know and we can work together to make it work.

= It's too wide =

This is a known issue with the interactive twitter badge and I'm working to fix it. 
One work around is to add the following to your stylesheet, it won't necessarily solve the problem if your sidebars are very narrow but should stop the badge getting cropped.

`
.TBW_Wide .TBW_Follow {
    min-width: 250px;
}`

= It's not refreshing my latest tweets =

The tweets will only update when the user changes page, it does not live refresh. Additionally, there is a client side cache that means that the data will only update once a minute.

= Can I change the timeout for the cache? =

If you use the widget several times on the same page (perhaps with different names) then it's a good idea to change the timeout, you can do this by adding a javascript variable at the top of your page. For example to change the delay to 3 minutes, use the following:

`<script>
var TBWTimeout = 180000;
</script>`

== Screenshots ==

1. Example of the default narrow style
2. Example of the wide style
3. Example of the interactive follow button
4. The admin UI of the widget

== Changelog ==

= 1.74 =

* Moved the load the regionalisation code in the footer
* Additional error handling in javascript
* Additional testing with Safari, found incompatibility with Amazon widget, see FAQ.

= 1.73 =

* Added if(!class_exists('AGC_Widget_Base') check to the widget base class AGC_Widget_Base
* Added jquery.json javascript library to improve caching on IE

= 1.72 =

* Fixed issue introduced in 1.71 where multiple twitter badge widgets on the same page all used the same cache key so they ended up displaying the same tweets on a page refresh.
* Minor fixes to .pot file + sent to first volunteer translator
* Fixed loading text domain to be load_plugin_textdomain rather than load_textdomain 

= 1.71 = 

* Fixed issue with caching of AJAX in IE
* Improved error handling in Javascript rendering function
* Improved element caching in Javascript, should give slight performance improvement
* Added Javascript caching via jstorage from www.jstorage.info

= 1.63 = 

* Added text domain to all of the localisations __('String','TBWidget')
* Added .pot file
* Added optional filter for the CSS file TBW_AlternateCSS
* Changed the higher level "span" elements to "div" as that seems to validate better than the other way around. The child elements remain as "span"

= 1.62 = 

* Fixed error in local time code, day vs days. Thanks to Rob Schade for spotting that issue.

= 1.61 =

* Fixed error in local time function with regards to radix location of parseint

= 1.6 =

* Added the &trim_user=false setting as that's needed to get user information back
* Added LocTime for calls to _() in locatisation
* Improved error handling for loc_relative_time.js
* Changed & for &amp;amp; in the json url
* Added microformat abbr wrapper around the time abbreviation to give tool tip for actual time.
* Put javascript through http://www.javascriptlint.com/online_lint.php, fixed couple of syntax issues.

= 1.5 =
* Small change to the rendering to make it work better with screen readers, tested with Fangs. http://www.standards-schmandards.com/projects/fangs/

= 1.4 =
* Made the loading of the twitter follow script asynchronous based on code example at http://dev.twitter.com/pages/follow_button which means for one less script to queue
* Ran page through xhtml validator and swapped the div for span
* Removed Jquery.data references and swapped to html5 style data-xxx attributes which means less code on the page.
* Simplified the rendering function with use of printf

= 1.3 =
* Put CSS through CSS lint at http://csslint.net
* Changed code and CSS prefixes from TB to TBW to avoid a potential clash with Thick Box
* Removed dependency on Jquery.ready function being successful.
* Removed explicit enqueuing of Jquery and include it as a dependency instead.
* Testing with Events widget
* Changed one of the styles so have more control 

= 1.2 =
* Simplified the rendering function
* Swaped the case of the filenames to lowercase so they work with the various minify plugins

= 1.1 =
* Refactored the rendering of the Widget UI to use functions rather than mixed php / html
* Fixed bug with the title section not rendering correctly.
* Testing on Twenty Ten and Twenty Eleven themes and CSS changes to reflect.

= 1.0 =
* Implemented interactive follow button

= 0.92 =
* Fixed typo in Localised regional time JScript

= 0.9 =
* Improved Jquery handling
* Improved Jscript handling

= 0.8 =
* Added a loading graphic using the stylesheet.
* Added localised version of the relative time function
* Also regionalisation support in the UI and widget form.
* Minor CSS fixes

= 0.7 =
* Put the inline JScript into a comment block (best practice) but I'm not actually aware of any browsers that would have an issue with it.
* Split the rendering out into a separate function and processed it using add_action/do_action 'TBW_RenderWidget', this should help if people want to extend or override the widget's rendering
* Minor CSS fix for position on Wide format

= 0.6 =
* Added the &trim_user=true and &include_entities=false parameters onto the JSON call, this means less data is returned and hence it should display faster

= 0.5 =
* Restructure Widget code to have all rendering code together. Will make easier to add shortcode support.
* Moved styling from JS to CSS where it belongs.
* Improved checking on parameters
* Support for more than 20 tweets

= 0.4 =
* First release version


== Rate limiting ==

Twitter will limit to 150 requests per hour, that means that each visitor to your site will be able to view 150 pages on your site each hour before the status will stop updating. Check your hosting stats to see if that's likely to be an issue.

"The REST API does user and IP-based rate limiting. Authenticated API calls are measured against the authenticating user's rate limit. Unauthenticated calls are deducted from the calling hosts IP address allowance."

See: http://dev.twitter.com/pages/rate-limiting

Practically this should work for most kinds of site, perhaps if you are hosting a forum or very interactive site where the user might be visiting many pages then you would be better of with the Wickett Twitter widget. That widget calls the API from your site and hence uses your quota, it does however cache the results to ensure that it's not making too many calls per hour.

== Regional Support ==

The plugin and javascript has been coded to support different regional languages, however the langage files have not been created yet. The interactive follow button is provided by Twitter and supports a reduced number of languages.

Let me know if you are interested and we can work together to make it work in your language.

== Technical section ==

It uses the Twitter API and as it's only accessing tweets and is read only it does not need authentication.

http://api.twitter.com/1/statuses/user_timeline.json

The widget uses client side javascript to load the tweets. It uses Jquery to delay a call the API so it should not slow the display of your page, once the page has loaded then the javascript will call out to twitter and retrieve the values, the javascript then formats the results including links for urls, peoples IDs and hashtags.

The widget supports multiple instances so you can display two different twitter streams on the same page.

The visual layout is controlled with CSS so can be customised if desired. Two default graphics are supplied from the twitter buttons page.

http://twitter.com/about/resources/buttons

If you want to override the rendering totally then you need to provide a function that does the same as Render and hook it to the action 'TBW_RenderWidget'

The interactive follow button is loaded asynchronously by following the guide at http://dev.twitter.com/pages/follow_button
A link is styled with various html5 style data-xxx attributes which are then used by the script to style the button

e.g.

`<a href="http://twitter.com/Workshopshed" class="twitter-follow-button" data-show-count="false" data-lang="de">Follow @Workshopshed</a>`

or 

`<a href="http://twitter.com/Workshopshed" class="twitter-follow-button" data-lang="fr">Follow @Workshopshed</a>`

In 1.4 version I made the widget render code use the same kind of technique, values are held in attributes against the DIV element.  The function TBW_ProcessWidgets is then run in the footer and scans through all the widgets to see which ones need to have an ajax call made for them.

The code and javascript has been localised using the relavent __() functions etc. Not actually tried changing the language yet though.

The caching of the tweets is handled by the jstorage library by Andris Reinman

== References ==

A selection of places I've found help for this plugin

= Wordpress =
* http://justintadlock.com/archives/2009/05/26/the-complete-guide-to-creating-widgets-in-wordpress-28
* http://planetozh.com/blog/2008/04/how-to-load-javascript-with-your-wordpress-plugin/
* http://www.garyc40.com/2010/03/5-tips-for-using-ajax-in-wordpress/#js-global
     
= Twitter =     
* http://dev.twitter.com/doc/get/statuses/user_timeline
* http://dev.twitter.com/pages/follow_button
* http://twitter.pbworks.com/w/page/1779897/RelativeTimeScripts
* Wickett Twitter Widget

= Javascript / Jquery =     
* http://www.diveintojavascript.com/projects/javascript-sprintf
* http://mytechworld.officeacuity.com/?p=393
* http://www.simonwhatley.co.uk/examples/twitter/prototype/
* http://drdobbs.com/web-development/229402114

= Localisation =
* http://codex.wordpress.org/I18n_for_WordPress_Developers    
* http://ryan.boren.me/2004/11/01/localizing-plugins-and-themes/

== Enhancements ==

Some ideas, let me know if you are specifically interested in any of these.

Support more of the parameters for the user timeline API?

Support for shortcode tags?

Support for other twitter APIs e.g. a user profile widget, search for a #tag or a twitter lists widget?

Include additional profile information next to the "follow" link e.g. numbers and location,  500 followers, London

Support narrow widgets with long twitter names

Look into making the cache timeout of 60s configurable so people with shared IPs or multiple widgets on the same page don't 

Anything else?


== Testing comments ==

Tested with IE9, Firefox 4 and the default Android browser on the HTC Wildfire.

Some testing done with Fangs Screenreader, some testing donw with the Windows version of Safari 4

The widget uses the widget classes that were introduced in Wordpress 2.8 so it should work with any version since then.

Tested on my own site with a variety of different widgets.

= Themes Tested =

* Twenty Ten
* Twenty Eleven 
* Emerald Stretch by Nikolaj Masnikov.

= Plugins Tested =

* Google + 1
* Contact Form 7
* Google XML Sitemaps
* Events Calendar 6.7.10
* BWP Minify
* Wordpress Amazon Associates

Thanks to 2020media for their help with testing.
Thanks to themc for testing on various IE versions.
