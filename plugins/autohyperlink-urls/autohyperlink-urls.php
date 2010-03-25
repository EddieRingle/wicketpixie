<?php
/*
Plugin Name: Auto-hyperlink URLs
Version: 3.0
Plugin URI: http://coffee2code.com/wp-plugins/autohyperlink-urls
Author: Scott Reilly
Author URI: http://coffee2code.com
Description: Automatically hyperlink text URLs and email addresses that appear in plaintext in post content and comments.

This plugin seeks to address certain shortcomings with WordPress's default auto-hyperlinking function.  This tweaks the 
pattern matching expressions to prevent inappropriate adjacent characters from becoming part of the link (such as a 
trailing period when a link ends a sentence, links that are parenthesized or braced, comma-separated, etc) and it prevents
invalid text from becoming a mailto: link (i.e. smart@ss) or for invalid URIs (i.e. http://blah) from becoming links.  In 
addition, this plugin adds configurability to the auto-hyperlinker such that you can configure:

- If you want text URLs to only show the hostname
- If you want text URLs truncated after N characters
- If you want auto-hyperlinked URLs to open in new browser window or not
- The text to come before and after the link text for truncated links
- If you want nofollow to be supported
- If you wish to support additional domain extensions not already configured into the plugin

This plugin will recognize any protocol-specified URI (http|https|ftp|news)://, etc, as well as e-mail addresses.  
It also adds the new ability to recognize Class B domain references (i.e. "somesite.net", not just domains prepended 
with "www.") as valid links (i.e. "wordpress.org" would now get auto-hyperlinked)

Known issues:
	Currently the plugin hyperlinks URLs that appear embedded within the middle of a longer string used as tag attribute value.
	i.e. 
	<a href="http://example.com" title="I go to http://example.com often">example.com</a>
	comes out as:
	<a href="http://example.com" title="I go to <a href="http://example.com" class="autohyperlink">http://example.com</a> often">example.com</a>
	
	It will also not hyperlink URLs that are immediately single- or double-quoted, i.e. 'http://example.com' or "http://example.com"
	
Compatible with WordPress 2.0+, 2.1+, 2.2+, 2.3+, and 2.5.

=>> Read the accompanying readme.txt file for more information.  Also, visit the plugin's homepage
=>> for more information and the latest updates

Installation:

1. Download the file http://coffee2code.com/wp-plugins/autohyperlink-urls.zip and unzip it into your 
/wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. (optional) Modify any configuration options for the plugin by going to its admin configuration page at
Options -> Autohyperlink (or in WP 2.5: Settings -> Autohyperlink)


Example (when running with default configuration):

"wordpress.org"
=> <a href="http://wordpress.org" title="http://wordpress.org" target="_blank" class="autohyperlink">wordpress.org</a>

"http://www.cnn.com"
=> <a href="http://www.cnn.com" title"http://www.cnn.com" target="_blank" class="autohyperlink">www.cnn.com</a>

"person@example.com"
=> <a href="mailto:person@example.com" title="mailto:person@example.com" class="autohyperlink">person@example.com</a>

*/

/*
Copyright (c) 2004-2008 by Scott Reilly (aka coffee2code)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation 
files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, 
modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the 
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

if ( !class_exists('AutoHyperlinkURLs') ) :

class AutoHyperlinkURLs {
	var $admin_options_name = 'c2c_autohyperlink_urls';
	var $nonce_field = 'update-autohyperlink_urls';
	var $show_admin = true;	// Change this to false if you don't want the plugin's admin page shown.
	var $config = array();

	function AutoHyperlinkURLs() {
		add_action('admin_menu', array(&$this, 'admin_menu'));
		add_filter('the_content', array(&$this, 'hyperlink_urls'), 9);
		
		$options = $this->get_options();
		if ($options['hyperlink_comments']) {
			remove_filter('comment_text', array(&$this, 'make_clickable'));
			add_filter('comment_text', array(&$this, 'hyperlink_urls'), 9);
		}
		
		$this->config = array(
			'hyperlink_comments' => array('input' => 'checkbox', 'default' => true,
					'label' => 'Auto-hyperlink comments?'),
			'hyperlink_emails' => array('input' => 'checkbox', 'default' => true, 
					'label' => 'Hyperlink E-mails?'),
			'open_in_new_window' => array('input' => 'checkbox', 'default' => true,
					'label' => 'Open auto-hyperlinked links in new window?'),
			'nofollow' => array('input' => 'checkbox', 'default' => false,
					'label' => 'Enable <a href="http://en.wikipedia.org/wiki/Nofollow">nofollow</a>?'),
			'truncation_before_text' => array('input' => 'text', 'default' => '',
					'label' => 'Text to show before link truncation'),
			'truncation_after_text' => array('input' => 'text', 'default' => '...',
					'label' => 'Text to show after link truncation'),
			'more_extensions' => array('input' => 'text', 'default' => '',
					'label' => 'Extra domain extensions.',
					'help' => 'Space and/or comma-separate list of extensions/<acronym title="Top-Level Domains">TLDs</acronym>.
								<br />These are already built-in: com, org, net, gov, edu, mil, us, info, biz, ws, name, mobi, cc, tv'),
			'hyperlink_mode' => array('input' => 'select', 'default' => 0,
					'label' => 'Hyperlink Mode/Truncation',
					'help' => 'This determines what text should appear as the link.  Use <code>0</code>
								to show the full URL, use <code>1</code> to show just the hostname, or
								use a value greater than <code>10</code> to indicate how many characters
								of the URL you want shown before it gets truncated.  <em>If</em> text
								gets truncated, the truncation before/after text values above will be used.')
		);
	}
	
	function install() {
		$options = $this->get_options();
		update_option($this->admin_options_name, $options);
	}

	function admin_menu() {
		if ($this->show_admin)
			// add_options_page('Auto-Hyperlink URLs', 'Autohyperlink', 9, basename(__FILE__), array(&$this, 'options_page'));
			add_submenu_page('wipi-plugins.php', 'Auto-Hyperlink URLs', 'Autohyperlink', 9, basename(__FILE__), array($this, 'options_page'));
	}

	function get_options() {
		// Derive options from the config
		$options = array();
		foreach (array_keys($this->config) as $opt) {
			$options[$opt] = $this->config[$opt]['default'];
		}
        $existing_options = get_option($this->admin_options_name);
        if (!empty($existing_options)) {
            foreach ($existing_options as $key => $option)
                $options[$key] = $option;
        }            
        return $options;
	}

	function options_page() {
		$options = $this->get_options();
		// See if user has submitted form
		if ( isset($_POST['submitted']) ) {
			check_admin_referer($this->nonce_field);

			foreach (array_keys($options) AS $opt) {
				$options[$opt] = $_POST[$opt];
			}
			// Remember to put all the other options into the array or they'll get lost!
			update_option($this->admin_options_name, $options);

			echo "<div class='updated'><p>Plugin settings saved.</p></div>";
		}

		$action_url = $_SERVER[PHP_SELF] . '?page=' . basename(__FILE__);

		echo <<<END
		<div class='wrap'>
			<h2>Auto-Hyperlink URLs Plugin Options</h2>
			<p>This plugin seeks to address certain shortcomings with WordPress's default auto-hyperlinking function.
			This tweaks the pattern matching expressions to prevent inappropriate adjacent characters from becoming 
			part of the link (such as a trailing period when a link ends a sentence, links that are parenthesized or 
			braced, comma-separated, etc) and it prevents invalid text from becoming a mailto: link (i.e. smart@ss) 
			or for invalid URIs (i.e. http://blah) from becoming links.</p>
			
			<p>This plugin will recognize any protocol-specified URI (http|https|ftp|news)://, etc, as well as e-mail addresses.  
			It also adds the new ability to recognize Class B domain references (i.e. "somesite.net", not just domains prepended 
			with "www.") as valid links (i.e. "wordpress.org" would now get auto-hyperlinked)</p>

			<p>See the examples at the bottom of this page.</p>
			
			<form name="autohyperlink_urls" action="$action_url" method="post">	
END;
				wp_nonce_field($this->nonce_field);
		echo '<table width="100%" cellspacing="2" cellpadding="5" class="optiontable editform form-table">';
				foreach (array_keys($options) as $opt) {
					$input = $this->config[$opt]['input'];
					$label = $this->config[$opt]['label'];
					$value = $options[$opt];
					if (($input == 'checkbox') && ($value == 1)) {
						$checked = 'checked=checked ';
						$value = 1;
					} else {
						$checked = '';
					};
					echo "<tr valign='top'><th scope='row'>$label</th>";
					echo "<td><input name='$opt' type='$input' id='$opt' value='$value' $checked/>";
					if ($this->config[$opt]['help']) {
						echo "<br /><span style='color:#777; font-size:x-small;'>";
						echo $this->config[$opt]['help'];
						echo "</span>";
					}
					echo "</td></tr>";
				}
		echo <<<END
			</table>
			<input type="hidden" name="submitted" value="1" />
			<div class="submit"><input type="submit" name="Submit" value="Save Changes" /></div>
		</form>
			</div>
END;
		$logo = TEMPLATEPATH .'/plugins/' . basename($_GET['page'], '.php') . '/c2c_minilogo.png';
		echo <<<END
		<style type="text/css">
			#c2c {
				text-align:center;
				color:#888;
				background-color:#ffffef;
				padding:5px 0 0;
				margin-top:12px;
				border-style:solid;
				border-color:#dadada;
				border-width:1px 0;
			}
			#c2c div {
				margin:0 auto;
				padding:5px 40px 0 0;
				width:45%;
				min-height:40px;
				background:url('$logo') no-repeat top right;
			}
			#c2c span {
				display:block;
				font-size:x-small;
			}
		</style>
		<div id='c2c' class='wrap'>
			<div>
			This plugin brought to you by <a href="http://coffee2code.com" title="coffee2code.com">Scott Reilly, aka coffee2code</a>.
			<span><a href="http://coffee2code.com/donate" title="Please consider a donation">Did you find this plugin useful?</a></span>
			</div>
		</div>
END;
		echo <<<END
			<div class='wrap'>
				<h2>Examples</h2>
				
				<p>To better illustrate what results you might get using the various settings above, here are examples.</p>
				
				<p>In all cases, assume the following URL is appearing as plaintext in a post:<br />
				<code>www.somelonghost.com/with/some/long/URL/that/might/mess/up/your/theme/and/is/unsightly.php</code></p>
				
				<p>And unless explicitly stated, the results are using default values (nofollow is false, hyperlink emails is true, Hyperlink Mode is 0)</p>
				
			<dl>
				<dt>By default</dt>
				<dd><a href="http://www.somelonghost.com/with/some/long/URL/that/might/mess/up/your/theme/and/is/unsightly.php"  class="autohyperlink" title="http://www.somelonghost.com/with/some/long/URL/that/might/mess/up/your/theme/and/is/unsightly.php" target="_blank">www.somelonghost.com/with/some/long/URL/that/might/mess/up/your/theme/and/is/unsightly.php</a></dd>
				<dt>With Hyperlink Mode set to 1</dt>
				<dd><a href="http://www.somelonghost.com/with/some/long/URL/that/might/mess/up/your/theme/and/is/unsightly.php" title="http://www.somelonghost.com/with/some/long/URL/that/might/mess/up/your/theme/and/is/unsightly.php" class="autohyperlink" target="_blank">www.somelonghost.com</a></dd>
				<dt>With Hyperlink Mode set to 15</dt>
				<dd><a href="http://www.somelonghost.com/with/some/long/URL/that/might/mess/up/your/theme/and/is/unsightly.php" title="http://www.somelonghost.com/with/some/long/URL/that/might/mess/up/your/theme/and/is/unsightly.php" class="autohyperlink" target="_blank">www.somelonghos...</a></dd>
				<dt>With Hyperlink Mode set to 15, nofollow set to true, open in new window set to false, truncation before of "[", truncation after of "...]"</dt>
				<dd><a href="http://www.somelonghost.com/with/some/long/URL/that/might/mess/up/your/theme/and/is/unsightly.php" title="http://www.somelonghost.com/with/some/long/URL/that/might/mess/up/your/theme/and/is/unsightly.php" class="autohyperlink" rel="nofollow">[www.somelonghos...]</a></dd>
			</dl>

			</div>
END;
	}
	
	function truncate_link($url) {
		$options = $this->get_options();
		$mode = $options['hyperlink_mode'];
		$trunc_before = $options['truncation_before_text'];
		$trunc_after = $options['truncation_after_text'];
		$more_extensions = $options['more_extensions'];
		return autohyperlink_truncate_link($url, $mode, $trunc_before, $trunc_after, $more_extensions);
	}

	function hyperlink_urls($text) {
		$options = $this->get_options();
		$mode = $options['hyperlink_mode'];
		$trunc_before = $options['truncation_before_text'];
		$trunc_after = $options['truncation_after_text'];
		$hyperlink_emails = $options['hyperlink_emails'];
		$open_in_new_window = $options['open_in_new_window'];
		$nofollow = $options['nofollow'];
		$more_extensions = $options['more_extensions'];
		return autohyperlink_link_urls($text, $mode, $trunc_before, $trunc_after, $hyperlink_emails, $open_in_new_window, $nofollow, $more_extensions);
	}
} // end AutoHyperlinkURLs

endif; // end if !class_exists()
if ( class_exists('AutoHyperlinkURLs') ) :
	// Get the ball rolling
	$autohyperlink_urls = new AutoHyperlinkURLs();
	// Actions and filters
	if (isset($autohyperlink_urls)) {
		register_activation_hook( __FILE__, array(&$autohyperlink_urls, 'install') );
	}
endif;

function autohyperlink_truncate_link ($url, $mode=0, $trunc_before='', $trunc_after='...', $more_extensions='') {
	if (1 == $mode) {
		$url = preg_replace("/(([a-z]+?):\\/\\/[a-z0-9\-\.]+).*/i", "$1", $url);
		if ($more_extensions)
		 	$more_extensions = '|' . implode('|', array_map('trim', explode('|', str_replace(array(', ', ' ', ','), '|', $more_extensions))));
		$url = $trunc_before . preg_replace("/([a-z0-9\-\.]+\.(com|org|net|gov|edu|mil|us|info|biz|ws|name|mobi|cc|tv$more_extensions)).*/i", "$1", $url) . $trunc_after;
	} elseif (($mode > 10) && (strlen($url) > $mode)) {
		$url = $trunc_before . substr($url, 0, $mode) . $trunc_after;
	}
	return $url;
}

function autohyperlink_link_urls ($text, $mode=0, $trunc_before='', $trunc_after='...', $hyperlink_emails=true, $open_in_new_window=true, $nofollow=false, $more_extensions='') {
	$link_attributes = 'class="autohyperlink"';
	if ($open_in_new_window) $link_attributes .= ' target="_blank"';
 	if ($nofollow) $link_attributes .= ' rel="nofollow"';
	$text = ' ' . $text . ' ';
	$extensions = 'com|org|net|gov|edu|mil|us|info|biz|ws|name|mobi|cc|tv';
	if ($more_extensions)
	 	$extensions .= '|' . implode('|', array_map('trim', explode('|', str_replace(array(', ', ' ', ','), '|', $more_extensions))));

	$patterns = array(
		'#([\s{}\(\)\[\]])(([a-z]+?)://([a-z_0-9\-]+\.([^\s{}\(\)\[\]]+[^\s,\.\;{}\(\)\[\]])))#ie',
		"#([\s{}\(\)\[\]])([a-z0-9\-\.]+[a-z0-9\-])\.($extensions)((?:/[^\s{}\(\)\[\]]*[^\.,\s{}\(\)\[\]]?)?)#ie"
	);

	$replacements = array(
		"'$1<a href=\"$2\" title=\"$2\" $link_attributes>' . autohyperlink_truncate_link(\"$4\", \"$mode\", \"$trunc_before\", \"$trunc_after\") . '</a>'",
		"'$1<a href=\"http://$2.$3$4\" title=\"http://$2.$3$4\" $link_attributes>' . autohyperlink_truncate_link(\"$2.$3$4\", \"$mode\", \"$trunc_before\", \"$trunc_after\") . '</a>'"
	);
	
	if ($hyperlink_emails) {
		$patterns[] = '#([\s{}\(\)\[\]])([a-z0-9\-_\.]+?)@([^\s,{}\(\)\[\]]+\.[^\s.,{}\(\)\[\]]+)#ie';
		$replacements[] = "'$1<a class=\"autohyperlink\" href=\"mailto:$2@$3\" title=\"mailto:$2@$3\">' . autohyperlink_truncate_link(\"$2@$3\", \"$mode\", \"$trunc_before\", \"$trunc_after\") . '</a>'";
	}
	
	$text = preg_replace($patterns, $replacements, $text);

	// Remove links within links
	$text = preg_replace("#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i", "$1$3</a>", $text);

	return trim($text);
}

?>
