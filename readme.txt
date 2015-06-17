=== Easy Post Series ===
Contributors: maurisrx
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=D2ZLXZ8VQKPE2
Tags: series, group, organize, post,
Tested up to: 4.2.2
Stable tag: 1.1
License: GPLv2

Create series of posts easily.

== Description ==

This plugin helps you create a series of posts. It creates a new taxonomy named 'series' (can be modified via filter, see FAQ). There is no setting needed for this plugin. Once it is installed, assign posts you want to group as a series via post editor.

The plugin also creates a posts navigation on posts that belong to a certain series. See screenshots for the examples.

== Installation ==

= How to install this plugin: =

1. Download and extract the plugin.
2. Upload 'easy-post-series' to the '/wp-content/plugins/' directory.
3. Activate the plugin through the plugin dashboard.

or

1. Go to your plugin admin page on WP admin dashboard.
2. Click "Add New" and search the name of the plugin.
3. Install and activate the plugin.

== Frequently Asked Questions ==

= How do I change the style of post navigation? =

You can change the style by adding new CSS rules to your theme's style.css.

= What are available filter hooks? =

- wpeps_languages_directory
- wpeps_taxonomy_labels
- wpeps_taxonomy_args
- wpeps_taxonomy
- wpeps_html_before_nav_paragraph
- wpeps_html_after_nav_paragraph
- wpeps_html_after_nav_list
- wpeps_nav_post_order
- wpeps_nav_post_orderby
- wpeps_archive_page_post_order
- wpeps_archive_page_post_orderby

= How to change series archive page post order? =

Add the following code to your active theme's functions.php
`
add_filter( 'wpeps_archive_page_post_order', 'prfx_archive_page_post_order');
function prfx_archive_page_post_order( $order ) {
	return $order = 'ASC';
}
	
add_filter( 'wpeps_archive_page_post_orderby', 'prfx_archive_page_post_orderby');
function prfx_archive_page_post_orderby( $orderby ) {
	return $orderby = 'date';
}
`

== Screenshots ==

1. Add a series to a post
2. Posts navigation on a series post

== Changelog ==

= 1.0 =
* Initial release.

= 1.1 =
* Add new filter hooks so that user can modify the plugin (See FAQ for available hooks)