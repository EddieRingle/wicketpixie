=== Auto-hyperlink URLs ===
Contributors: coffee2code
Donate link: http://coffee2code.com
Tags: links, URLs, auto-link 
Requires at least: 2.0.2
Tested up to: 2.5
Stable tag: trunk
Version: 3.0

Automatically hyperlink text URLs and email addresses that appear in plaintext in post content and comments.

== Description ==

Automatically hyperlink text URLs and email addresses that appear in plaintext in post content and comments.

This plugin seeks to address certain shortcomings with WordPress's default auto-hyperlinking function.  This tweaks the pattern matching expressions to prevent inappropriate adjacent characters from becoming part of the link (such as a trailing period when a link ends a sentence, links that are parenthesized or braced, comma-separated, etc) and it prevents invalid text from becoming a mailto: link (i.e. smart@ss) or for invalid URIs (i.e. http://blah) from becoming links.  In addition, this plugin adds configurability to the auto-hyperlinker such that you can configure:

* If you want text URLs to only show the hostname
* If you want text URLs truncated after N characters
* If you want auto-hyperlinked URLs to open in new browser window or not
* The text to come before and after the link text for truncated links
* If you want nofollow to be supported
* If you wish to support additional domain extensions not already configured into the plugin

This plugin will recognize any protocol-specified URI (http|https|ftp|news)://, etc, as well as e-mail addresses.  It also adds the new ability to recognize Class B domain references (i.e. "somesite.net", not just domains prepended with "www.") as valid links (i.e. "wordpress.org" would now get auto-hyperlinked)

The following domain extensions (aka TLDs, Top-Level Domains) are recognized by the plugin: com, org, net, gov, edu, mil, us, info, biz, ws, name, mobi, cc, tv.  Knowing these only comes into play when you have a plaintext URL that does not have an explicit protocol specified.  If you need support for additional TLDs, you can add more via the plugin's admin options page.

== Installation ==

1. Unzip `autohyperlink-urls.zip` inside the `/wp-content/plugins/` directory, or upload `autohyperlink-urls.php` to `/wp-content/plugins/`
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. (optional) Modify any configuration options for the plugin by going to its admin configuration page at `Options` -> `Autohyperlink` (or in WP 2.5: `Settings` -> `Autohyperlink`)

== Examples ==

(when running with default configuration):

* "wordpress.org"
<a href="http://wordpress.org" title="http://wordpress.org" target="_blank" class="autohyperlink">wordpress.org</a>

* "http://www.cnn.com"
<a href="http://www.cnn.com" title"http://www.cnn.com" target="_blank" class="autohyperlink">www.cnn.com</a>

* "person@example.com"
<a href="mailto:person@example.com" title="mailto:person@example.com" class="autohyperlink">person@example.com</a>

To better illustrate what results you might get using the various settings above, here are examples.
	
For the following, assume the following URL is appearing as plaintext in a post: `www.somelonghost.com/with/some/long/URL/that/might/mess/up/your/theme/and/is/unsightly.php`
	
And unless explicitly stated, the results are using default values (nofollow is false, hyperlink emails is true, Hyperlink Mode is 0)
	
* By default:
<a href="http://www.somelonghost.com/with/some/long/URL/that/might/mess/up/your/theme/and/is/unsightly.php" title="http://www.somelonghost.com/with/some/long/URL/that/might/mess/up/your/theme/and/is/unsightly.php"  class="autohyperlink" target="_blank">www.somelonghost.com/with/some/long/URL/that/might/mess/up/your/theme/and/is/unsightly.php</a>

* With Hyperlink Mode set to 1
<a href="http://www.somelonghost.com/with/some/long/URL/that/might/mess/up/your/theme/and/is/unsightly.php" title="http://www.somelonghost.com/with/some/long/URL/that/might/mess/up/your/theme/and/is/unsightly.php" class="autohyperlink" target="_blank">www.somelonghost.com</a>

* With Hyperlink Mode set to 15
<a href="http://www.somelonghost.com/with/some/long/URL/that/might/mess/up/your/theme/and/is/unsightly.php" title="http://www.somelonghost.com/with/some/long/URL/that/might/mess/up/your/theme/and/is/unsightly.php" class="autohyperlink"target="_blank">www.somelonghos...</a>

* With Hyperlink Mode set to 15, nofollow set to true, open in new window set to false, truncation before of "[", truncation after of "...]"
<a href="http://www.somelonghost.com/with/some/long/URL/that/might/mess/up/your/theme/and/is/unsightly.php" title="http://www.somelonghost.com/with/some/long/URL/that/might/mess/up/your/theme/and/is/unsightly.php" class="autohyperlink" rel="nofollow">[www.somelonghos...]</a>

== Known Shortcomings ==

* Currently the plugin hyperlinks URLs that appear embedded within the middle of a longer string used as tag attribute value, i.e.
`<a href="http://example.com" title="I go to http://example.com often">example.com</a>`
comes out as:
`<a href="http://example.com" title="I go to <a href="http://example.com" class="autohyperlink">http://example.com</a> often">example.com</a>`
  
* It will also not auto-hyperlink URLs that are immediately single- or double-quoted, i.e. `'http://example.com'` or `"http://example.com"`

== Screenshots ==

1. A screenshot of the plugin's admin options page.
