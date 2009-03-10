=== Obfuscate E-mail ===
Contributors: coffee2code
Donate link: http://coffee2code.com
Tags: email, obfuscation, security, spam
Requires at least: 2.0.2
Tested up to: 2.5
Stable tag: trunk
Version: 2.0

Obfuscate e-mail addresses that appear as text in your blog in an effort to deter e-mail harvesting spammers while retaining the appearance and functionality of hyperlinks.

== Description ==

Obfuscate e-mail addresses that appear as text in your blog in an effort to deter e-mail harvesting spammers while retaining the appearance and functionality of hyperlinks.

Any textual occurrence of an e-mail address in a post body, excerpt, or comment (and when admin and user emails are retrieved for display) will be obfuscated.

By default, the entire e-mail address will be obfuscated using hexadecimal HTML entity substitutions.  For example, 

`<a href="mailto:person@example.com">person@example.com</a>`

Would be translated into something like this:

`<a href="mailto:&#x70;&#x65;&#x72;&#x73;&#x6f;&#x6e;&#x40;&#x65;&#x78;&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;om" title="mailto:&#x70;&#x65;&#x72;&#x73;&#x6f;&#x6e;&#x40;&#x65;&#x78;&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;om">&#x70;&#x65;&#x72;&#x73;&#x6f;&#x6e;&#x40;&#x65;&#x78;&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;om</a>`

However, in your browser it would appear to you as it does prior to obfuscation, and the link for the e-mail would still work.  Theoretically, however, spammers would have a somewhat more difficult time harvesting the e-mails you display or link to in your posts.

Via the plugin's admin options page located at `Options` -> `Obfuscate E-mail` (or in WP 2.5: `Settings` -> `Obfuscate E-mail`) you can opt to only partially obfuscate an e-mail address.  By defining custom replacements for the `@` and `.` characters, you can have e-mails display like:

`<a href="mailto:person[at]example[dot]com">person[at]example[dot]com</a>`

or

`<a href="mailto:person@DELETETHIS.com">person@DELETETHISexample.com</a>`

(Only when using the custom replacement feature will visitors need to modify the e-mail address for use in their e-mail program.)

== Installation ==

1. Unzip `obfuscate-email.zip` inside the `/wp-content/plugins/` directory, or upload `obfuscate-email.php` to `/wp-content/plugins/`
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. Go to the new `Options` -> `Obfuscate E-mail` (or in WP 2.5: `Settings` -> `Obfuscate E-mail`) admin options page.  Optionally customize the settings.

== Frequently Asked Questions ==

= So it'll be impossible for spammers to harvest my site for e-mail addresses? =

Nothing is fool-proof and nothing is guaranteed.  By its very definition, "obfuscate" means "to make obscure or unclear", and that's all it's really doing.  It's some degree of basic protection, which is better than nothing.  Much as how locks in real-life at best provide some measure of deterrent for a would-be criminal rather than absolute security from a determined and capable individual.

= Aren't there better methods of e-mail obfuscation? =

Some would argue yes.  But nothing short of not actually displaying e-mail addresses can guarantee that e-mail addresses can't get harvested.  Some methods are more aggressive and therefore have compatibility and/or usability issues.  This plugin should be very compatible and usable by all visitors to your site.

= Does this plugin make use of JavaScript as other e-mail obfuscators do?

No.  This makes this plugin's implementation of obfuscation more compatible and usable by more visitors, at the expense of perhaps not being as aggressively protective.

== Screenshots ==

1. A screenshot of the plugin's admin options page.
