<?php
/*
Plugin Name: Chitika|Premium
Version: 1.3.4
Plugin URI: http://chitika.com/blog/wordpress/
Description: Enables you to quickly add and modify your use of Chitika|Premium within Wordpress! <a href="options-general.php?page=premium/premium.php">Edit your Chitika|Premium configuration settings</a>.
Author: Chitika Inc.
Author URI: http://chitika.com/blog/wordpress/
*/

/* These are defaults. Once the plugin is activated, these are no longer
 * used. You'll have to set the options in the Admin panel */

$PREMIUM_DEFAULTS['plugin-version'] =  '1.3.402192009';
$PREMIUM_DEFAULTS['client']     = 'lockergnome';
$PREMIUM_DEFAULTS['width']      = '468'; 
$PREMIUM_DEFAULTS['height']     = '120';
$PREMIUM_DEFAULTS['size']     = '468x120';
$PREMIUM_DEFAULTS['channel']    = 'Chris Pirillo';
$PREMIUM_DEFAULTS['background'] = 'ffffff'; 
$PREMIUM_DEFAULTS['border'] = '000000'; 
$PREMIUM_DEFAULTS['titlecolor'] = '0000CC'; 
$PREMIUM_DEFAULTS['textcolor'] = '000000';
$PREMIUM_DEFAULTS['placement'] = 'top'; 
$PREMIUM_DEFAULTS['single'] = 'true'; 
$PREMIUM_DEFAULTS['append'] = 'true'; 
$PREMIUM_DEFAULTS['font'] = '';

$PREMIUM_DEFAULTS['template'] ='<!-- Chitika|Premium - WordPress Plugin --><div class="chitika-adspace {%placement%}"><script type="text/javascript"><!--
ch_client = {%client%};
ch_type = "mpu";
ch_width = {%width%};
ch_height = {%height%};
ch_color_bg = {%background%};
ch_color_title = {%titlecolor%};
ch_color_site_link = {%titlecolor%};
ch_color_text = {%textcolor%};
ch_non_contextual = 4;
ch_vertical = "premium";
ch_font_title = {%font%};
ch_font_text = {%font%};
ch_sid = {%channel%};
var ch_queries = new Array( );
var ch_selected=Math.floor((Math.random()*ch_queries.length));
if ( ch_selected < ch_queries.length ) {
ch_query = ch_queries[ch_selected];
}
//--></script>
<script  src="http://scripts.chitika.net/eminimalls/amm.js" type="text/javascript"></script></div>';

class chitikaPremium {
	function chitikaPremium() {
		add_action('the_content', array(&$this, 'filter_content'), 500);
		add_action('admin_menu', array(&$this, 'add_options_page'));
		
		$this->install();
		$this->update();
		
		
		if ( ((!get_option('chitikap_client') || get_option('chitikap_client') == 'demo' ) && !isset($_POST['chitikap_update']) ) ||
			 ((empty($_POST['chitikap_client']) || $_POST['chitikap_client'] == 'demo') && isset($_POST['chitikap_update'])) ):
			add_action('admin_notices', array(&$this, 'chitika_premium_warning'));
		endif;
	}
	
	function chitika_premium_warning() {
		echo '
		<div id="chitika-warning" class="error"><p style="font-size:15px"><strong>The Chitika | Premium Plugin is almost ready to place ads on your site!</strong> You need to update your <a href="options-general.php?page=premium/premium.php#username">Chitika account username</a>.<br /><br />Don\'t have a Chitika account username? <a href="http://chitika.com/apply" target="_blank">Apply up for one</a>!</p></div>';
	}
	
	function install() {
		global $PREMIUM_DEFAULTS;
	
		$options = array('width', 'height', 'border', 'titlecolor', 'textcolor', 'client', 'channel', 'background', 'placement','font','size','single','append');
		foreach ($options as $option) {
			if ( !get_option("chitikap_{$option}".$option) ) {
				add_option("chitikap_{$option}", $PREMIUM_DEFAULTS[$option]);
			}
		}
	}
	
	function update() {
		global $PREMIUM_DEFAULTS;
		// Updates the default variables in the wordpress database if the version number 
		// in the database doesn't match the one in the plugin.
		if (get_option('chitikap_plugin-version') != $PREMIUM_DEFAULTS['plugin-version']){
			$options = array('width', 'height', 'border', 'titlecolor', 'textcolor', 'client', 'channel', 'background','placement','font','size','single','append');
			if(get_option('chitikap_channel') == 'default' || get_option('chitikap_channel') == ''){
				// only change for previous default variables
				update_option('chitikap_channel', $PREMIUM_DEFAULTS['channel']);
			}
		}
		update_option('chitikap_plugin-version', $PREMIUM_DEFAULTS['plugin-version']);
	}
	
	function filter_content($text)
	{
		global $PREMIUM_DEFAULTS;
		$textContainsTag = preg_match_all("/(<\!--NO-ChitikaPremium-->)/is", $text, $matches);
		
		
		if($textContainsTag || (get_option('chitikap_single') == 'true' && !is_single()) ) {
			return $text;
		}
			
		// Get user defined name value pairs from their tag				
		$attributes = array('width', 'height', 'border', 'titlecolor', 'textcolor', 'client', 'channel', 'background','font','size','single','append');
		$vars = array();
		foreach ($attributes as $att) {
			$vars[$att] = $this->_get_attribute($att, $tag);
		}

		// Get the chitikaPremium javascript template
		
		$template = $PREMIUM_DEFAULTS['template'];
		list($vars['width'], $vars['height']) = explode('x', get_option('chitikap_size'));	
					
		// Put the chitikaPremium template into the post, replacing the user's tag	
		
		$_placement = get_option('chitikap_placement');
		
		
		if($_placement == 'bottom'){
			$text = $text . "\n" . $this->_apply_template($template, $vars, 'below');
		} elseif($_placement == 'both') {
			$text = $this->_apply_template($template, $vars, 'above - both') . "\n" . $text. "\n" . $this->_apply_template($template, $vars, 'below - both');
		} else {
			$text = $this->_apply_template($template, $vars, 'above') . "\n" . $text;
		}

		return $text;
	}
	
	function options_page() {
		if (isset($_POST['chitikap_update'])) {
			$options = array('width', 'height', 'border', 'titlecolor', 'textcolor', 'client', 'channel', 'background', 'placement','font','size','single','append');
			foreach ($options as $option) {
				update_option('chitikap_'.$option, stripslashes($_POST['chitikap_'.$option]));
			}
			echo '<div class="updated"><p><strong>Your Chitika|Premium settings  have been saved.</strong></p></div>';
		}
?>
<div class="wrap"><h2>Chitika | Premium Settings</h2>
	<fieldset class="options"> 
	<legend>Customize Your Chitika|Premium Ads Display Settings</legend>

	<div id="poststuff">
	<div class="submitbox" id="submitpost" style="width:320px; margin-left:40px; float:right; line-height:1.7em;">
		<div class="side-info">
			<h5>Chitika|Premium FAQs</h5>
			<ul>
			<li style="line-height:180%;"><strong>Can I use Chitika|Premium on the same page as Google AdSense?</strong><br />
			Yes! Chitika | Premium ads are not contextual, and do not look like Google AdSense ads. <a href="http://chitika.com/adsense-alternative.php" target="_blank">More info</a>.</li>
			<li style="line-height:180%;"><strong>When do I get paid?</strong><br />
			Payments are based on a 'net 30' schedule. That means you will receive January's payment at the end of February and February's at the end of March and so on. <a href="https://chitika.com/support/index.php?_m=knowledgebase&_a=viewarticle&kbarticleid=94" target="_blank">More info</a>.</li>
			<li style="line-height:180%;"><strong>So You're Using the Plugin?</strong><br />
			Let us know: <a href="http://wordpress.org/tags/chitika-premium?forum_id=10">WordPress Plugin Forum</a> / <a href="http://chitika.com/blog/">Chitika Blog</a></li>
			</ul>
			<p><a href="http://chitika.com/blog/chitika-premium-faqs/" target="_blank">More FAQs</a></p>
		</div>
	</div>
	</div>
	<h3>What is Chitika|Premium?</h3>
	<p>Chitika|Premium is a smart, personalized targeting ad solution that provides premium content. This ad unit is CPC and is designed to be your highest performing ad unit (eCPM).</p>
	<ul>
		<li>Chitika|Premium Targets Your Search Engine Traffic - <a href="https://chitika.com/how-does-chitika-premium-work.php" target="_blank">How it Works (video)</a></li>
		<li><a href="http://chitika.com/adsense-alternative.php" target="_blank">100% Compatible with Google AdSense</a></li>
	</ul>
<?php
	if((float)get_bloginfo('version') >= 2.2){
?>
	<div>
	<h3>How Do I Preview Chitika|Premium on my Blog?</h3>
	<p>Since Chitika | Premium ads will only display to your US and Canada search engine traffic, you can see how they look on your site using the tool below.<br />Just enter the URL of the page you want to preview it on, add the keyword to display ads for and click preview.</p>
	<p>For additional help please view the <a href="https://chitika.com/support/index.php?_m=knowledgebase&_a=viewarticle&kbarticleid=138&nav=0,13">preview support documentation</a></p>
	
	<div style="background-color:#EAF3FA; margin-left:10px; width:500px; padding:20px; line-height:1.6em;">
	<form name="previewtool" id="previewtool" method="get">
		<fieldset><legend style="font-size:1.3em; font-weight:bold;">Chitika|Premium Preview Tool</legend>
		<label for="chitikap_url"><strong>URL</strong>  (For preview purposes only)</label><br />
		<input name="chitikap_url" type="text" id="chitikap_url" value="<?php echo bloginfo('url') ?>" size="45" /><br />
		<label for="chitikap_keywords"><strong>Keyword(s)</strong>  (For preview purposes only)</label><br />
		<input name="chitikap_keywords" type="text" id="chitikap_keywords" value="hybrid car" size="45" />
		<p class="submit" style="border-top-width: 0pt; padding-top:0">
		  <input type="button" onclick="var uri = jQuery('#chitikap_url').val() + '#chitikatest=' + jQuery('#chitikap_keywords').val(); window.open( uri ,'chitikapreview','width=600,height=500,status=1,toolbar=1,resizable=1,location=1,scrollbars=1');" name="chitikap_preview" value="Preview (in new window)" />
		</p>
</fieldset>
	</form>
</div>
	</div>
<?php
	} else {
?>
	<h3>How Do I Preview Chitika|Premium on my Blog?</h3>
	<p>Since Chitika | Premium ads will only display to your US and Canada search engine traffic, you need to append `#chitikatest=keywords` to the end of your URL to preview Chitika|Premium in your blog. For additional help please view the <a href="https://chitika.com/support/index.php?_m=knowledgebase&_a=viewarticle&kbarticleid=138&nav=0,13">preview support documentation</a>.	
<?php
	}
?>
	
	<br style="clear:both;" />	

<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 
<h3>Settings</h3>

<table width="100%" cellspacing="2" cellpadding="5" class="form-table">
	<tr valign="top"> 
    	<th width="33%" scope="row">Placement</th> 
        <td>
		<?php
			$_placement = get_option('chitikap_placement');
			if( $_placement == 'bottom'){
				$chitikap_placement_put[1] = ' checked="checked"'; $chitikap_placement_put[0] = ''; $chitikap_placement_put[2] = '';
			} if( $_placement == 'both'){
				$chitikap_placement_put[1] = ''; $chitikap_placement_put[0] = ''; $chitikap_placement_put[2] = ' checked="checked"';
			} else {
				$chitikap_placement_put[1] = ''; $chitikap_placement_put[0] = ' checked="checked"'; $chitikap_placement_put[2] = '';
			}
		?>
		<input name="chitikap_placement" type="radio" id="chitikap_placement" value="top" <?php echo $chitikap_placement_put[0]; ?>/> Above Posts <m>(Recommended!)</em><br />
        <input name="chitikap_placement" type="radio" id="chitikap_placement" value="bottom" <?php echo $chitikap_placement_put[1]; ?>/> Below Posts<br />
        <input name="chitikap_placement" type="radio" id="chitikap_placement" value="both" <?php echo $chitikap_placement_put[2]; ?>/> Above and Below Posts<br />
		<p>Placing the code <code>&lt;!--no-chitikapremium--&gt;</code> within a post will stop Chitika|Premium from displaying with that specific post.</p>
	  </td>  
	</tr>
	
	<tr valign="top"> 
    	<th width="33%" scope="row">Permalink</th> 
        <td>	
			<?php
			$_single = get_option('chitikap_single');
			if( $_single == 'true'){
				$chitikap_single_put = ' checked="checked"';
			} else {
				$chitikap_single_put = '';
			}
		?>
		<input name="chitikap_single" type="checkbox" id="chitikap_single" <?php echo $chitikap_single_put; ?> value="true" /> Only display ads on permalink pages
		  </td>  
	</tr>	
		
		
		
		
	<tr valign="top"> 
    	<th width="33%" scope="row" id="username">Chitika Account Username</th> 
        <td>
		<?php
				if ( (!get_option('chitikap_client') || get_option('chitikap_client') == 'demo' ) && !isset($_POST['submit']) ) {
					$_style = 'style="background-color:#FFFBCC; border-color:#D54E21;"';
				}
		?><input name="chitikap_client" type="text" id="chitikap_client" value="<?php echo get_option('chitikap_client') ?>" <?php echo $_style; ?> size="50" /><br />
        <p>If you dont have a Chitika account, please <a target="_blank" href="http://chitika.com/publishers.php?refid=lockergnome&from=mm">sign up</a> for one.<br />This is the ID you sign into <a href="http://chitika.com/affiliate/" target="_blank">chitika.com</a> with to check your earnings.</p>
	  </td>  
	</tr>
    <tr valign="top"> 
    	<th width="33%" scope="row">Size</th> 
        <td>
		<fieldset><legend class="hidden">Chitika|Premium Size</legend>
		<?php
			$_font = get_option('chitikap_size');
			$put_chitikap_size = array_fill(0, 23, '');
			switch($_font){
				case '728x90' :
					$put_chitikap_size[0] = ' selected="selected"';		break;
				case '120x600' :
					$put_chitikap_size[1] = ' selected="selected"';		break;
				case '160x600' :
					$put_chitikap_size[2] = ' selected="selected"';		break;
				case '468x180' :
					$put_chitikap_size[3] = ' selected="selected"';		break;
				case '468x90' :
					$put_chitikap_size[5] = ' selected="selected"';		break;
				case '468x60' :
					$put_chitikap_size[6] = ' selected="selected"';		break;
				case '550x120' :
					$put_chitikap_size[7] = ' selected="selected"';		break;
				case '550x90' :
					$put_chitikap_size[8] = ' selected="selected"';		break;
				case '450x90' :
					$put_chitikap_size[9] = ' selected="selected"';		break;
				case '430x90' :
					$put_chitikap_size[10] = ' selected="selected"';	break;
				case '400x90' :
					$put_chitikap_size[11] = ' selected="selected"';	break;
				case '300x250' :
					$put_chitikap_size[12] = ' selected="selected"';	break;
				case '300x150' :
					$put_chitikap_size[13] = ' selected="selected"';	break;
				case '300x125' :
					$put_chitikap_size[14] = ' selected="selected"';	break;
				case '300x70' :
					$put_chitikap_size[15] = ' selected="selected"';	break;
				case '250x250' :
					$put_chitikap_size[16] = ' selected="selected"';	break;
				case '200x200' :
					$put_chitikap_size[17] = ' selected="selected"';	break;
				case '160x160' :
					$put_chitikap_size[18] = ' selected="selected"';	break;
				case '336x280' :
					$put_chitikap_size[19] = ' selected="selected"';	break;
				case '336x160' :
					$put_chitikap_size[20] = ' selected="selected"';	break;
				case '334x100' :
					$put_chitikap_size[21] = ' selected="selected"';	break;
				case '180x300' :
					$put_chitikap_size[22] = ' selected="selected"';	break;
				case '180x150' :
					$put_chitikap_size[23] = ' selected="selected"';	break;
				default:
					$put_chitikap_size[4] = ' selected="selected"';		break;
			}
		?>
			<select name="chitikap_size" id="chitikap_size">
			
				<option value="468x180"<?php echo $put_chitikap_size[3]; ?>>468 x 180 Blog Banner</option>
				<option value="468x120"<?php echo $put_chitikap_size[4]; ?>>468 x 120 Blog Banner</option>

				<option value="468x90"<?php echo $put_chitikap_size[5]; ?>>468 x 90 Small Blog Banner</option>
				<option value="468x60"<?php echo $put_chitikap_size[6]; ?>>468 x 60 Mini Blog Banner</option>
				<option value="" disabled="disabled"></option>
				<option value="728x90"<?php echo $put_chitikap_size[0]; ?>>728 x 90 Leaderboard</option>
				<option value="120x600"<?php echo $put_chitikap_size[1]; ?>>120 x 600 Skyscraper</option>
				<option value="160x600"<?php echo $put_chitikap_size[2]; ?>>160 x 600 Wide Skyscraper</option>
				<option value="" disabled="disabled"></option>
				<option value="550x120"<?php echo $put_chitikap_size[7]; ?>>550 x 120 Content Banner</option>
				<option value="550x90"<?php echo $put_chitikap_size[8]; ?>>550 x 90 Content Banner</option>
				<option value="450x90"<?php echo $put_chitikap_size[9]; ?>>450 x 90 Small Content Banner</option>

				<option value="430x90"<?php echo $put_chitikap_size[10]; ?>>430 x 90 Small Content Banner</option>
				<option value="400x90"<?php echo $put_chitikap_size[11]; ?>>400 x 90 Small Content Banner</option>
				<option value="" disabled="disabled"></option>
				<option value="300x250"<?php echo $put_chitikap_size[12]; ?>>300 x 250 Rectangle</option>
				<option value="300x150"<?php echo $put_chitikap_size[13]; ?>>300 x 150 Rectangle, Wide</option>
				<option value="300x125"<?php echo $put_chitikap_size[14]; ?>>300 x 125 Mini Rectangle, Wide</option>

				<option value="300x70"<?php echo $put_chitikap_size[15]; ?>>300 x 70 Mini Rectangle, Wide</option>
				<option value="" disabled="disabled"></option>
				<option value="250x250"<?php echo $put_chitikap_size[16]; ?>>250 x 250 Square</option>
				<option value="200x200"<?php echo $put_chitikap_size[17]; ?>>200 x 200 Small Square</option>
				<option value="160x160"<?php echo $put_chitikap_size[18]; ?>>160 x 160 Small Square</option>
				<option value="" disabled="disabled"></option>
				<option value="336x280"<?php echo $put_chitikap_size[19]; ?>>336 x 280 Rectangle</option>

				<option value="336x160"<?php echo $put_chitikap_size[20]; ?>>336 x 160 Rectangle, Wide</option>
				<option value="" disabled="disabled"></option>
				<option value="334x100"<?php echo $put_chitikap_size[21]; ?>>334 x 100 Small Rectangle, Wide</option>
				<option value="180x300"<?php echo $put_chitikap_size[22]; ?>>180 x 300 Small Rectangle, Tall</option>
				<option value="180x150"<?php echo $put_chitikap_size[23]; ?>>180 x 150 Small Rectangle</option>
			</select>
		</fieldset>
			<p>Preview all Chitika|Premium size options in <a target="_blank" href="https://chitika.com/premium_formats.php" target="_blank">this list</a>.<br />
		Recommended size is 468 wide x 120 high. This size fits well in most WordPress templates.</p>
	</td>  
    </tr>
    <tr valign="top"> 
        <th width="33%" scope="row">Font</th> 
        <td>
		<?php
			$_font = get_option('chitikap_font');
			$put_chitikap_font = array_fill(0, 7, '');
			switch($_font){
				case 'Arial' :
					$put_chitikap_font[1] = ' selected="selected"';		break;
				case 'Comic Sans MS' :
					$put_chitikap_font[2] = ' selected="selected"';		break;
				case 'Georgia' :
					$put_chitikap_font[3] = ' selected="selected"';		break;
				case 'Tahoma' :
					$put_chitikap_font[4] = ' selected="selected"';		break;
				case 'Times' :
					$put_chitikap_font[5] = ' selected="selected"';		break;
				case 'Verdana' :
					$put_chitikap_font[6] = ' selected="selected"';		break;
				case 'Courier' :
					$put_chitikap_font[7] = ' selected="selected"';		break;
				default:
					$put_chitikap_font[9] = ' selected="selected"';		break;
			}
		?>
			<select name="chitikap_font" id="chitikap_font">
				<option value="" <?php echo $put_chitikap_font[0]; ?>>-- Default Font --</option>
				<option value="Arial"<?php echo $put_chitikap_font[1]; ?>>Arial</option>
				<option value="Comic Sans MS"<?php echo $put_chitikap_font[2]; ?>>Comic Sans MS</option>
				<option value="Georgia"<?php echo $put_chitikap_font[3]; ?>>Georgia</option>
				<option value="Tahoma"<?php echo $put_chitikap_font[4]; ?>>Tahoma</option>
				<option value="Times"<?php echo $put_chitikap_font[5]; ?>>Times</option>
				<option value="Verdana"<?php echo $put_chitikap_font[6]; ?>>Verdana</option>
				<option value="Courier"<?php echo $put_chitikap_font[7]; ?>>Courier</option>
			</select>
		</td>  
    </tr> 
    <tr valign="top"> 
        <th width="33%" scope="row">Channel Tracking</th> 
        <td><input name="chitikap_channel" type="text" id="chitikap_channel" value="<?php echo get_option('chitikap_channel') ?>" size="50" /><br />
		<p><a href="https://chitika.com/support/index.php?_m=knowledgebase&_a=viewarticle&kbarticleid=130" target="_blank">What are Channels?</a></p>
		<?php
			$_append = get_option('chitikap_append');
			if( $_append == 'true'){
				$chitikap_append_put = ' checked="checked"';
			} else {
				$chitikap_append_put = '';
			}
		?>
		<input name="chitikap_append" type="checkbox" id="chitikap_append" <?php echo $chitikap_append_put; ?> value="true" /> Append top / bottom to channel name depending on ad placement?
		</td>  
    </tr> 
    <tr valign="top"> 
        <th width="33%" scope="row">Background Color</th>
        <td>#<input name="chitikap_background" type="text" id="chitikap_background" value="<?php echo get_option('chitikap_background') ?>" size="25" />
		</td>  
    </tr>
	<?php
	/*
    <tr valign="top"> 
        <th width="33%" scope="row">Border Color</th> 
        <td>#<input name="chitikap_border" type="text" id="chitikap_border" value="<?php echo get_option('chitikap_border') ?>" size="25" />
		</td>  
    </tr>
	*/
	?>
    <tr valign="top"> 
        <th width="33%" scope="row">Link Color</th> 
        <td>#<input name="chitikap_titlecolor" type="text" id="chitikap_titlecolor" value="<?php echo get_option('chitikap_titlecolor') ?>" size="25" />
		</td>  
    </tr>
    <tr valign="top"> 
        <th width="33%" scope="row">Text Color</th> 
        <td>#</strong><input name="chitikap_textcolor" type="text" id="chitikap_textcolor" value="<?php echo get_option('chitikap_textcolor') ?>" size="25" />
		</td>  
    </tr>
    </table>
    </fieldset>

    <p class="submit">
      <input type="submit" name="chitikap_update" id="chitikap_update" value="Update Settings &raquo;" style="font-weight:bold;" />
    </p>
</form>
<?php
	}
	
	function add_options_page() {
		add_submenu_page('wp_plugins.php', 'Chitika | Premium Settings', 'Chitika | Premium', 10, 'premium/premium.php', array(&$this, 'options_page'));
	}
	
	/* Parses premium tag for attributes values.
	 * If not found, use get_option to get defaults */
	function _get_attribute($name, $tag)
	{
		// Look for the name value pair, parse it out. Backreferences here provides
		// flexibility so the user can use either single or double quotes, as
		// long as their balanced, this will parse. 
		$tag = str_replace(array("&#8243;", "&#8242;"), array('"', "'"), $tag);
		$hasAttribute = preg_match( "/$name=('|\")([^\\1]*?)\\1/i", $tag, $matches );
		if ( $hasAttribute ) {
			$quote = $matches[1];
			$value = "$quote$matches[2]$quote";
		} else {
			$value = '"' . get_option("chitikap_${name}") . '"';
		}

		return $value;
	}
	
	function _prepare_template_var(&$item, $key) {
		$item = '{%' . $item . '%}';
	}

	function _apply_template($str, $replace = 0, $position = 'top') {
		global $wp_query;
		
		if(get_option('chitikap_append') == 'true'){ 
			$replace['channel'] = '"' . trim($replace['channel'], '"') . ' ' .$position .'"';
		}
		$replace['placement'] = str_replace(array(' ', '-'),'', $position);
		
	    if ( is_array($replace) ) {
			$from = array_keys($replace);
			array_walk($from, array(&$this, '_prepare_template_var'));
			
			$to = array_values($replace);
			return str_replace($from, $to, $str);
		}
		return $str;
	}
}

$chitikapremiumad = new chitikaPremium();
?>
