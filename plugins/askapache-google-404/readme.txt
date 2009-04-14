=== AskApache Google 404 ===
Contributors: AskApache, cduke250, Google
Donate link: http://www.askapache.com/donate/
Tags: google, 404, errordocument, htaccess, error, notfound, ajax, search, seo, mistyped, urls, news, videos, images, blogs, optimized, askapache, post, admin, askapache, ajax, missing, admin, template, traffic
Requires at least: 2.7
Tested up to: 2.8-bleeding-edge
Stable tag: 4.6.2.2


== Description ==

AskApache Google 404 is a sweet and simple plugin that takes over the handling of any HTTP Errors that your blog has from time to time.  The most common type of error is when a page cannot be found, due to a bad link, mistyped URL, etc.. So this plugin uses some AJAX code, Google Search API'S,  and a few tricks to display a very helpful and Search-Engine Optimized Error Page. The default displays Google Search Results for images, news, blogs, videos, web, custom search engine, and your own site. It also searches for part of the requested filename that was not found, but it attaches your domain to the search for SEO and greater results.

This new version also adds related posts, recent posts, and integrates thickbox for instant previews.

See it Live, http://www.askapache.com/search-askapache-robots-seo-wordpress?whatevers=clever


== Installation ==

This section describes how to install the plugin and get it working. http://www.askapache.com/seo/404-google-wordpress-plugin.html

1. Upload the zip file to the /wp-content/plugins/ directory and unzip. 
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to your Options Panel and open the "AA Google 404" submenu. /wp-admin/options-general.php?page=askapache-google-404.php
4. Enter in your Google Search API Key and configure your settings.
5. If you use a 404.php file, add <?php if(function_exists('aa_google_404'))aa_google_404();?> to the body.


== Frequently Asked Questions ==

Do I need a Google Account?
Yes.

Do I need a 404.php template file?
Nope

My 404.php page isn't being served for 404 Not Found errors!?
Add this to your .htaccess file.  Read [.htaccess Tutorial](http://www.askapache.com/htaccess/apache-htaccess.html "AskApache .htaccess File Tutorial") for more information.

 ErrorDocument 404 /index.php?error=404 
 Redirect 404 /index.php?error=404




== Screenshots ==

1. Basic AskApache 404 Look
2. Related Links Feature
3. Configuration Panel
4. New 404 Google Helper