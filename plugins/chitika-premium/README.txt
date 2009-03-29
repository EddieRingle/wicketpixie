=== Chitika | Premium ===
Author: chitikainc
Contributors: chitikainc
Donate link: http://chitika.com/blog/wordpress/
Tags: seo, google, adsense, adsense alternative, chitika, premium, eminimalls, advertising, ads, post, posts, ad, income
Requires at least: 1.5.1
Tested up to: 2.7.1
Stable tag: 1.3.4

This plugin will automate adding Chitika|Premium to your blog posts. Chitika|Premium is an AdSense alternative/compatible search targeted ad solution

== Description ==

Chitika|Premium is a CPC search-targeted advertising solution brought to you by [Chitika](http://chitika.com "Search Targeted Advertising").  It can be run on the same page as Google AdSense, or on its own as an [AdSense alternative](http://chitika.com/adsense-alternative.php "AdSense alternative: Chitika|Premium").  [See a video about how Chitika|Premium works](http://chitika.com/how-does-chitika-premium-work.php "How Chitika | Premium Works").

This plugin allows you to easily change the display of your Chitika|Premium ads through a settings page in the WordPress admin interface. It allows you to change any of the following features:

* Ad size (more than *20 sizes* to choose from)
* Link and text color
* Background color
* Display position (above or below your post)
* Channel ([What are channels?](https://chitika.com/support/index.php?_m=knowledgebase&_a=viewarticle&kbarticleid=130 "Whate Are Chitika|Premium Channels"))

= Updates =

* **New in 1.3** - Choose whether or not show ads only on permalink pages (an not just any page that displays the full post)        
New Option to append 'Above', 'Below' to channel names for advanced channel tracking
* **New in 1.3.1** - Style options expanded
* **New in 1.3.2** - Alert error bug fix
* **New in 1.3.4** - Username bug fix


= About Chitika|Premium =

* Chitika|Premium is 100% AdSense compatible and can be used on the **same page as AdSense**
* Chitika|Premium ads target your US and Canada search engine traffic
* Chitika offers more than 20 ad unit sizes to fit your needs
* You do need to [sign up with Chitika](https://chitika.com/application.php?type=mm "Sign Up for Chitika|Premium") in order to earn money through Chitika|Premium ads

== Installation ==

Installing and customization is easy and takes fewer than five minutes.

1. Upload `/chitika-premium/` directory to the `/wp-content/plugins/` directory
2. Activate the plugin *Chitika|Premium* through the 'Plugins' menu in WordPress
3. Go to 'Settings' > 'Chitika | Premium' to activate the display and add your username and change any display settings.    
*If you are using a version of WordPress earlier than 2.5 your configuration screen will be in 'Options' > 'Chitika|Premium'*

== Frequently Asked Questions ==

= Can I use Chitika|Premium on the same page as Google AdSense? =

Yes! But don't take our word for it - 20,000+ of our publishers are running our ads on the same page as Google AdSense (and of course, for further confirmation, you can always contact your Google AdSense Rep). [More info](http://chitika.com/adsense-alternative.php).

= When do I get paid? =

Payments are based on a 'net 30' schedule. That means you will receive January's payment at the end of February and February's at the end of March and so on. [More info](https://chitika.com/support/index.php?_m=knowledgebase&_a=viewarticle&kbarticleid=94).

= When Installing this Plugin do I need to Upload the Entire Directory? =

Yes, when installing the plugin it is recommended you upload the `/chitika-premium/` directory.  Your directory structure should look something like this:

    - wp-content/
        - plugins/
            - akismet/
            - chitika-premium/
                - README.txt
                - premium.php
                - index.php
                - screenshot1.png
                - screenshot2.png
                - screenshot3.png
                - screenshot4.png
                - screenshot5.png
            - hello.php
           

= Is it compatible with WP Cache and WP Super Cache? =

Chitika|Premium uses javascript to serve up ads so as long as the javascript call is on the page your ads will continue to display correctly for your search traffic and not at all to your non-search traffic.

= How can I preview Chitika|Premium ads once I sign up and install this plugin? =

Easy! There's a tool built into the settings page, for WordPress versions greater than 2.2, where you can enter the URL of the page you want to test and the keyword(s) you want to test and it will open up a new window displaying that page with the Chitika|Premium ad. If you need more information [check out our support documentation about previewing Chitika|Premium](https://chitika.com/support/index.php?_m=knowledgebase&_a=viewarticle&kbarticleid=138 "How Do I Preview Chitika|Premium Ads on My Site")

= How do I Stop Chitika Premium from Displaying on Specific Posts =

If you don't want Chitika Premium to display on specific posts just add `<!--NO-ChitikaPremium-->` anywhere in the body of the post.       
The `<!-- -->` means it's an HTML comment, so you don't need to worry about the `<!--NO-ChitikaPremium-->` ever displaying in your posts. You can put it at the top, bottom or anywhere in between. You can [read more about HTML comments here](http://www.w3schools.com/tags/tag_comment.asp "W3C HTML Comment Tags").

= It's not showing up on my posts?  Why!?!?! =

There's a few different things that could be going on:

1. Make sure you viewing you page with `#chitikatest=camera` at the end of the url. Example: `http://example.com/blog/i-like-camera-stuff/#chitikatest=camera`
2. The Chitika|Premium plugin will only display above/below (depending on your settings) of full posts, not excerpts. So be sure to check on a permalink page!
3. Have you upgraded to v 1.2?  We encountered some conflicts with other plugins prior to the 1.2 release that have since been resolved
4. Address your concerns using the [WP Plugin Forum](http://wordpress.org/tags/chitika-premium?forum_id=10) or the [official Chitika Publisher Blog](http://chitika.com/blog/2008/12/01/new-wordpress-plugin-for-chitika-premium/ "WordPress Plugin for Chitika | Premium")


= My username won't update - what gives? =

We've heard reports of this problem but have not been able to replicate it on our side.  If you do encounter this problem follow these steps:

1. Ensure you have the latest version of the Chitika | Premium plugin
2. Try and update your username again
2. Go to your dashboard and see if you still have the 'The Chitika | Premium Plugin is almost ready to place ads on your site!' alert at the top of the page.

If it still doesn't work, please contact our [support staff](https://chitika.com/support/) with the following information:
 - What OS (operating system) are you using?
 - What browser are you using, and what version number?
 - What version of WordPress are you using?
 - What is your website URL you are using the plugin on?
 - When you try and save the username and it doesn't work what shows in the username field? Is it highlighted yellow/red?
 - Is the plugin activated or deactivated?
 - Did you see the 'The Chitika | Premium Plugin is almost ready to place ads on your site!' alert on the dashboard after no username saved?

== Feedback ==

Want to show us how you are using the Chitika|Premium WordPress plugin or provide feedback about your user experience with it? Let us know about it!     
You can leave a comment on our blog post [New WordPress Plugin for Chitika | Premium](http://chitika.com/blog/2008/12/01/new-wordpress-plugin-for-chitika-premium/ "WordPress Plugin for Chitika | Premium") or drop a note here in the [Chitika|Premium WordPress Plugin Directory Forum](http://wordpress.org/tags/chitika-premium?forum_id=10).


== Screenshots ==

1. Chitika|Premium ads sample

2. This is how a default Chitika|Premium Ad will display on your homepage for visitors entering from a search engine having searched for the term 'WordPress'

3. This is how non-search traffic visitors will see your home page

4. This is how a default Chitika|Premium Ad will display on your interior/single posts for visitors entering from a search engine having searched for the term 'world'

5. This is how non-search traffic visitors will see your interior/single posts