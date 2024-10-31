=== PostLists-Extension Navigation ===
Contributors: reneade
Donate link: http://www.rene-ade.de/stichwoerter/spenden
Tags: post, posts, page, categories, category, navigation, archive, placeholder, placeholders, lists, list, postlists, index, tags, navigation, navi, ajax
Stable tag: trunk
Requires at least: WordPress Plugin PostLists 2.0
Tested up to: 2.99999.WORDPRESS

With this extension for the WordPress plugin PostLists it is possible to add next-/prev- navigation links to a PostLists list. AJAX-Support.

== Description ==

With this extension for the WordPress plugin PostLists it is possible to add next-/prev- navigation links to a PostLists list.

The following placeholders can be used to create the links in the before- and after- fields of the list configuration:
%ple_navigation_next_link% will get replaced to the next-link you defined in the "navigation next link" field of your list (you need to configure that field and can use %navigation_next_url% there), if the configured number of posts to display was reached.
%ple_navigation_prev_link% will get replaced to the prev-link you defined in the "navigation prev link" field of your list if the prev-link is really needed (you need to configure that field and can use %navigation_prev_url% there). 
%ple_navigation_variable% will get replaced to the the name of the get-variable to overwrite the offset of this list.
%ple_navigation_next_offset% will get replaced to the offset to get the posts after the last post of this list.
%ple_navigation_next_url% will get replaced to the url to this list showing the posts after the last post of this list.
%ple_navigation_prev_offset% will get replaced to the offset to get the posts before the first post of this list.
%ple_navigation_prev_url% will get replaced to the url to this list showing the posts before the first post of this list.

Plugin Website: http://www.rene-ade.de/inhalte/wordpress-plugin-postlists-extension-navigation.html

Many thanks, to Marcel Goor (http://www.mediaculture.nl), for doing the AJAX-Part that is integrated in Version 3!

== Installation ==

Install and activate the WordPress plugin "PostLists" first if you have not already done!
http://www.rene-ade.de/inhalte/wordpress-plugin-postlists.html

Installation of this PostLists-Extension: Upload the folder "ple-navigation" (with all files and subfolders) to your plugins directory ("/wp-content/plugins/"). Then activate the plugin "PostLists-Extension Navigation" in your WordPress admin panel.

Usage: Select a list in the PostLists-AdminPanel for editing. Define the new link placeholders in the fields "Navigation next-link" and "Navigation prev-link" by using the other ple-navigation placeholders. For example "Navigation next-link" could be "<a href="%ple_navigation_next_url%">More &raquo;</a>". If the links are defined, you can use the placeholders %ple_navigation_next_link% and %ple_navigation_prev_link% within the html output fields for before and after the list, and within the html output field that is used if there are no more posts. If there are no prev posts %ple_navigation_prev_link% will not be replaced and be removed. If the maximum number of posts to display is not reached %ple_navigation_next_link% will also be only removed. This means, the links are only added if needed (if you use them via the placeholders). If you like to use AJAX to load the next posts of a list without refreshing the complete page, set "Navigation AJAX Support" to yes.

== PostLists ==

This Plugin "ple-navigation" is a PostLists-Extension: You need the WordPress plugin "PostLists" to use it!

You can download the WordPress Plugin "PostLists" here:
http://www.rene-ade.de/inhalte/wordpress-plugin-postlists.html

PostLists is a Plugin for WordPress to add configurable dynamic lists of posts via simple placeholders to a page, post or to your sidebar. PostLists itself can also be extended via other WordPress Plugins (called PostLists Extensions) to add further functionallity to PostLists. 

== PostLists Extensions ==

You will find a list of all know extensions for the WordPress Plugin PostLists here:
http://www.rene-ade.de/inhalte/wordpress-plugin-postlists-extensions.html
