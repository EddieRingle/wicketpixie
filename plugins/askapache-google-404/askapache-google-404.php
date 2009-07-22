<?php
/**
 * Plugin Name: AskApache Google 404
 * Short Name: AA Google 404
 * Description: Displays unbeatable information to site visitors arriving at a non-existant page (from a bad link).  Major SEO with Google AJAX, Google 404 Helper, Related Posts, Recent Posts, etc..
 * Author: AskApache
 * Version: 4.6.2.2
 * DB Version:	23
 * Requires at least: 2.7
 * Tested up to: 2.8-bleeding-edge
 * Tags: google, 404, errordocument, htaccess, error, notfound, ajax, search, seo, mistyped, urls, news, videos, images, blogs, optimized, askapache, post, admin, askapache, ajax, missing, admin, template, traffic
 * Contributors: AskApache, cduke250, Google
 * WordPress URI: http://wordpress.org/extend/plugins/askapache-google-404/
 * Author URI: http://www.askapache.com/
 * Donate URI: http://www.askapache.com/donate/
 * Plugin URI: http://www.askapache.com/seo/404-google-wordpress-plugin.html
 *
 *
 * AskApache Google 404 - Intelligent SEO-Based 404 Error Handling
 * Copyright (C) 2009	AskApache.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.	If not, see <http://www.gnu.org/licenses/>.
 */


!defined( 'ABSPATH' ) || !function_exists( 'add_options_page' ) || !function_exists( 'add_action' ) || !function_exists( 'wp_die' ) && die( 'death by askapache firing squad' );
!defined( 'COOKIEPATH' ) && define( 'COOKIEPATH', preg_replace('|https?://[^/]+|i', '', get_option('home') . '/') );
!defined( 'SITECOOKIEPATH' ) && define( 'SITECOOKIEPATH', preg_replace('|https?://[^/]+|i', '', get_option('siteurl') . '/') );
!defined( 'ADMIN_COOKIE_PATH' ) && define( 'ADMIN_COOKIE_PATH', SITECOOKIEPATH . 'wp-admin' );
!defined( 'PLUGINS_COOKIE_PATH' ) && define( 'PLUGINS_COOKIE_PATH', preg_replace('|https?://[^/]+|i', '', WP_PLUGIN_URL) );
!defined( 'WP_CONTENT_DIR' ) && define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
!defined( 'WP_PLUGIN_DIR' ) && define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
!defined( 'WP_CONTENT_URL' ) && define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content' );
!defined( 'WP_PLUGIN_URL' ) && define( 'WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins' );



if ( !in_array('AskApacheGoogle404', (array)get_declared_classes() ) && !class_exists( 'AskApacheGoogle404' ) ) :
/**
 * AskApacheGoogle404
 *
 * @package 
 * @author webmaster@askapache.com
 * @copyright AskApache
 * @version 2009
 * @access public
 */
class AskApacheGoogle404
{
	var $options;
	var $old_options;
	var $plugin;
	var $code;
	var $orig_code;


	/**
	 * AskApacheGoogle404::AskApacheGoogle404()
	 *
	 * @return
	 */
	function AskApacheGoogle404()
	{
		return $this->__construct();
	}
	
	function __construct()	
	{
		$this->plugin=$this->get_plugin_data();
		add_action( 'activate_' . $this->plugin['pb'], array(&$this, 'activate') );
		add_action( 'deactivate_' . $this->plugin['pb'], array(&$this, 'deactivate') );
		add_action( 'template_redirect', array(&$this, 'template_redirect') );
		return true;
	}


	function init()
	{
		$this->LoadOptions();
		//error_log(__FUNCTION__.':'.__LINE__);
//		add_action( 'admin_menu', array(&$this, 'admin_menu') );
		add_action( 'admin_notices', array(&$this, 'admin_notices') );
//		add_action( 'admin_print_styles-' . $this->plugin['hook'], array(&$this, 'admin_print_styles') );
//		add_action( 'admin_head-' . $this->plugin['hook'], array(&$this, 'admin_print_scripts') );
		add_action( 'admin_print_styles', array(&$this, 'admin_print_styles') );
		add_action( 'admin_head', array(&$this, 'admin_print_scripts') );
		add_action( 'wp_ajax_get_aa404_data', array(&$this, 'get_aa404_data') );
//		add_action( 'load-' . $this->plugin['hook'], array(&$this, 'load') );
		add_action( 'load', array(&$this, 'load') );
		add_action( 'admin_init', array(&$this, 'admin_init') );
		add_filter( 'plugin_action_links_' . $this->plugin['pb'], array(&$this, 'plugin_action_links') );
	}
	

	/**
	 * AskApacheGoogle404::activate()
	 *
	 * @return
	 */
	function activate()
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		global $wpdb;

		$sql = "ALTER TABLE $wpdb->posts DROP INDEX `post_related` , ADD FULLTEXT `post_related` ( `post_name` , `post_content` ) ";
		$results = $wpdb->query( $wpdb->prepare($sql) );
		foreach ( array('options', 'code', 'run_data') as $pn ) delete_option( "askapache_google_404_{$pn}" );
		$this->InitOptions();
	}



	/**
	 * AskApacheGoogle404::deactivate()
	 *
	 * @return
	 */
	function deactivate()
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		foreach ( array('options', 'code', 'run_data', 'plugin') as $pn ) delete_option( "askapache_google_404_{$pn}" );
	}



	/**
	 * AskApacheGoogle404::LoadOptions()
	 *
	 * @return
	 */
	function LoadOptions()
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		$this->plugin = $this->get_plugin_data();
		$this->options = get_option( 'askapache_google_404_options' );
		$this->old_options = get_option( 'askapache_google_404_plugin' );
		$this->code = get_option( 'askapache_google_404_code' );
	}



	/**
	 * AskApacheGoogle404::InitOptions()
	 *
	 * @return
	 */
	function InitOptions()
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		$this->options = array( 'rel' => '1', 'rec' => '1', 'google_404' => '1', 'google_ajax' => '1', 'rel_num' => 20, 'rel_len' => 240, 'rec_num' => 10 );
$this->code=array( 'html' =>
'<h1>%error_title%</h1>

<h3>Were You Looking for One of These Posts?</h3>
%related_posts%

<h3>Recent Posts</h3>
%recent_posts%

<!-- Google404Helper-->
%google_helper%
<!-- Google404Helper-->
',



'css' =>
'#lDiv {width:140px;}
#lDiv .gsc-control .gsc-ad-box {padding-top:100px;}
#rDiv {width:400px; min-height:400px;}
#rDiv .gsc-control .gsc-ad-box,#gDiv {height:120px; margin-bottom:20px; padding:1em; width:99%; background-color:#EAF9EA;overflow:hidden;}
#gDiv .gsc-control .gsc-ad-box {width:50%;}
#gDiv .gsc-control .gsc-resultsHeader {border-bottom-width:0;color:#2B2D2B;font-weight:bold;}
#gDiv .gsc-control .gsc-results {position:relative; display:block; margin:0; overflow:hidden;}
#gDiv .gsc-control .gsc-results .gsc-trailing-more-results {display:none;}
#gDiv .gsc-control .gsc-results .gsc-expansionArea {display:none;}
.gs-videoResult .gs-text-box,.gs-imageResult .gs-text-box {display:none;}
.gsc-control {width:100% !important;margin:0 auto;position:relative;overflow:hidden;}
.gsc-control form {width:96%;}
.gsc-control table {margin:0;padding:0;}
.gsc-control, .gsc-control * {font-family:"trebuchet ms", verdana, sans-serif;font-size:13px;}',




'js' =>
'function OnLoad() {new cse();};
google.setOnLoadCallback(OnLoad);
google.load("search", "1");
function cse() {
var sDiv = document.getElementById("sDiv");
var gDiv = document.getElementById("gDiv");
var lDiv = document.getElementById("lDiv");
var rDiv = document.getElementById("rDiv");
this.gCT = new GSearchControl();
this.lCT = new GSearchControl();
this.rCT = new GSearchControl();
this.sForm = new GSearchForm(true, sDiv);
this.sForm.setOnSubmitCallback(this, cse.prototype.onSubmit);
this.sForm.setOnClearCallback(this, cse.prototype.onClear);
this.lCT.setResultSetSize(GSearch.SMALL_RESULTSET);
this.lCT.setLinkTarget(GSearch.LINK_TARGET_SELF);
this.rCT.setResultSetSize(GSearch.LARGE_RESULTSET);
this.rCT.setLinkTarget(GSearch.LINK_TARGET_SELF);
this.gCT.setResultSetSize(GSearch.SMALL_RESULTSET);
this.gCT.setLinkTarget(GSearch.LINK_TARGET_SELF);
var sOPT;
sOPT = new GsearcherOptions();
sOPT.setExpandMode(GSearchControl.EXPAND_MODE_PARTIAL);
var drawOptions;
drawOptions = new GdrawOptions();
drawOptions.setSearchFormRoot(sDiv);
drawOptions.setDrawMode(GSearchControl.DRAW_MODE_LINEAR);
var srBST = new GwebSearch();
srBST.setUserDefinedLabel("Googles Best Guess");
srBST.setSiteRestriction(aa_MYSITE);
srBST.setQueryAddition(aa_BGLABEL);
this.gCT.addSearcher(srBST,sOPT);
this.gCT.draw(gDiv, drawOptions);
drawOptions = new GdrawOptions();
sOPT = new GsearcherOptions();
drawOptions.setSearchFormRoot(sDiv);
drawOptions.setDrawMode(GSearchControl.DRAW_MODE_LINEAR);
sOPT.setExpandMode(GSearchControl.EXPAND_MODE_OPEN);
var srVID = new GvideoSearch();
srVID.setQueryAddition(aa_LABEL);
this.lCT.addSearcher(srVID,sOPT);
sOPT = new GsearcherOptions();
sOPT.setExpandMode(GSearchControl.EXPAND_MODE_OPEN);
var srIMG = new GimageSearch();
srIMG.setRestriction(GSearch.RESTRICT_SAFESEARCH, GSearch.SAFESEARCH_OFF);
srIMG.setQueryAddition(aa_LABEL);
this.lCT.addSearcher(srIMG,sOPT);
this.lCT.draw(lDiv, drawOptions);
var srSITE = new GwebSearch();
srSITE.setUserDefinedLabel(aa_LABEL);
srSITE.setSiteRestriction(aa_MYSITE);
srSITE.setQueryAddition(aa_LABEL);
srSITE.setRestriction(GSearch.RESTRICT_SAFESEARCH, GSearch.SAFESEARCH_OFF);
var srBLOG = new GblogSearch();
srBLOG.setQueryAddition(aa_LABEL);
srBLOG.setResultOrder(GSearch.ORDER_BY_DATE);
var srWEB = new GwebSearch();
srWEB.setQueryAddition(aa_LABEL);
srWEB.setRestriction(GSearch.RESTRICT_SAFESEARCH, GSearch.SAFESEARCH_OFF);
var srNEW = new GnewsSearch();
var srCSE = new GwebSearch();
srCSE.setQueryAddition("askapache");
srCSE.setUserDefinedLabel("CSE");
srCSE.setRestriction(GSearch.RESTRICT_SAFESEARCH, GSearch.SAFESEARCH_OFF);
srCSE.setSiteRestriction("002660089121042511758:kk7rwc2gx0i", null);
var srLOC = new GlocalSearch();
this.rCT.addSearcher(srSITE);
this.rCT.addSearcher(srBLOG);
this.rCT.addSearcher(srWEB);
this.rCT.addSearcher(srNEW);
this.rCT.addSearcher(srCSE);
this.rCT.addSearcher(srLOC);
drawOptions.setDrawMode(GSearchControl.DRAW_MODE_TABBED);
this.rCT.draw(rDiv, drawOptions);
this.sForm.execute(aa_XX);};
cse.prototype.onSubmit = function(form) { var q = form.input.value; if (q && q!= "") { this.gCT.execute(q); this.lCT.execute(q); this.rCT.execute(q);}; return false; };
cse.prototype.onClear = function(form) { this.gCT.clearAllResults(); this.lCT.clearAllResults(); this.rCT.clearAllResults(); form.input.value = ""; return false; };',
);


		$old_api_key = get_option( 'aa_google_404_api_key' );
        if (empty($old_api_key) || $old_api_key ===false) {
            $old_api_key = 'ABQIAAAAxsJ-1gfQyQ2Fwg4FjosmsBRbVmYmTc4GolLk1Px_P_CFsIwipxTDB8pa1FbgWALwXhm6z52pc1KwEA';
        }
		if ( $old_api_key !== false && strlen($old_api_key) > 5 )
		{
		//error_log(__FUNCTION__.':'.__LINE__);
			$this->options['api_key'] = $old_api_key;
			$search_replace = array( 'aalabel' => 'aa_LABEL', 'aamysite' => 'aa_MYSITE', 'aaexecute' => 'aa_XX' );
			foreach ( array('api_key', 'html', 'css', 'js') as $k )
			{
				$v = get_option( "aa_google_404_{$k}" );
				if ( $v && !empty($v) ) $old_options[$k] = str_replace( array_keys($search_replace), array_values($search_replace), $v );
				elseif ( array_key_exists("old_{$k}", $this->options) && !empty($this->options["old_{$k}"]) ) $old_options[$k] = $this->options["old_{$k}"];
				delete_option( "aa_google_404_{$k}" );
			}
		}

		$old_options = get_option( 'askapache_google_404_old_options' );
		if ( $old_options !== false && is_array($old_options['options']) ) $this->options['api_key'] = $old_options['options']['api_key'];

		update_option( 'askapache_google_404_options', $this->options );
		update_option( 'askapache_google_404_plugin', $this->plugin );
		update_option( 'askapache_google_404_code', $this->code );
	}



	/**
	 * AskApacheGoogle404::SaveOptions()
	 *
	 * @return
	 */
	function SaveOptions()
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		delete_option( 'askapache_google_404_old_options' );
		
		$this->old_options = array( 'options' => array(), 'code' => array() );
		$this->old_options['options'] = get_option( 'askapache_google_404_options' );
		$this->old_options['code'] = get_option( 'askapache_google_404_code' );

		update_option( 'askapache_google_404_options', $this->options );
		update_option( 'askapache_google_404_plugin', $this->plugin );
		update_option( 'askapache_google_404_code', $this->code );
		update_option( 'askapache_google_404_old_options', $this->old_options );
	}


	function admin_init()
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_aa404_main_settings']) ) $this->handle_post();	
	}


	/**
	 * AskApacheGoogle404::admin_menu()
	 *
	 * @return
	 */
	function admin_menu()
	{
		//error_log(__FUNCTION__.':'.__LINE__);
//		add_management_page( $this->plugin['Plugin Name'], $this->plugin['Short Name'], 'level_8', $this->plugin['page'], array(&$this, 'options_page') );

        add_submenu_page('wipi-plugins.php', __("AskApache Google 404"), __("AskApache Google 404"), 'manage_options', basename(__FILE__), array(&$this,'options_page'));
	}



	/**
	 * AskApacheGoogle404::admin_notices()
	 *
	 * @return
	 */
	function admin_notices()
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		if ( strlen($this->options['api_key']) > 5 ) return;
        
		printf('<div class="fad"><p><a href="%3$s?KeepThis=true&TB_iframe=true&height=500&width=950" title="Provided by AskApache" class="thickbox">Get a Google API Key!</a>  -  Then add it to your settings <a href="%1$s">here</a></p></div>',
		$this->plugin['action'], $this->plugin['Plugin Name'], 'http://code.google.com/apis/ajaxsearch/signup.html');
	}



	/**
	 * AskApacheGoogle404::plugin_action_links()
	 *
	 * @param mixed $links
	 * @return
	 */
	function plugin_action_links( $links )
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		return array_merge( array('<a href="' . admin_url($this->plugin['action']) . '">Settings</a>'), $links );
	}



	/**
	 * AskApacheGoogle404::template_redirect()
	 *
	 * @return
	 */
	function template_redirect()
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		global $wp_query, $AAGoogle404Handler;

		if ( is_404() )
		{
		//error_log(__FUNCTION__.':'.__LINE__);
			$AAGoogle404Handler = new AAGoogle404Handler();
			$AAGoogle404Handler->status_code = (( isset($_SERVER['REDIRECT_STATUS']) && $_SERVER['REDIRECT_STATUS'] != 200 ) ? $_SERVER['REDIRECT_STATUS'] : ( !isset($_REQUEST['error']) ) ? 404 : ( int )$_REQUEST['error']);
			$AAGoogle404Handler->handle_404();
		}
	}



	/**
	 * AskApacheGoogle404::load()
	 *
	 * @return
	 */
	function load()
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		add_thickbox();
		wp_enqueue_script( 'jquery' );
	}



	/**
	 * AskApacheGoogle404::get_aa404_data()
	 *
	 * @return
	 */
	function get_aa404_data()
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		check_ajax_referer( "get_aa404_data" );

		if(isset($_GET['whcode'])){
			
			

		$orig_code=array( 'html' =>
'<div id="content">
<h2>%error_title%</h2>

<!-- GoogleAjaxSearchResults-->
<div style="width:750px;overflow:hidden;margin-left:0;">
<div id="sDiv"></div><div id="gDiv"></div><div id="lDiv" style="float:right"></div><div id="rDiv"></div>
<br style="clear:both;" />
</div>
<!-- GoogleAjaxSearchResults-->

<h2>Related Posts</h2>
%related_posts%

<h2>Recent Posts</h2>
%recent_posts%

<!-- Google404Helper-->
%google_helper%
<!-- Google404Helper-->
</div>',



'css' =>
'#lDiv {width:140px;}
#lDiv .gsc-control .gsc-ad-box {padding-top:100px;}
#rDiv {width:500px; min-height:400px;}
#rDiv .gsc-control .gsc-ad-box,#gDiv {height:120px; margin-bottom:20px; padding:1em; width:99%; background-color:#EAF9EA;overflow:hidden;}
#gDiv .gsc-control .gsc-ad-box {width:50%;}
#gDiv .gsc-control .gsc-resultsHeader {border-bottom-width:0;color:#2B2D2B;font-weight:bold;}
#gDiv .gsc-control .gsc-results {position:relative; display:block; margin:0; overflow:hidden;}
#gDiv .gsc-control .gsc-results .gsc-trailing-more-results {display:none;}
#gDiv .gsc-control .gsc-results .gsc-expansionArea {display:none;}
.gs-videoResult .gs-text-box,.gs-imageResult .gs-text-box {display:none;}
.gsc-control {width:100% !important;margin:0 auto;position:relative;overflow:hidden;}
.gsc-control form {width:96%;}
.gsc-control table {margin:0;padding:0;}
.gsc-control, .gsc-control * {font-family:"trebuchet ms", verdana, sans-serif;font-size:13px;}',




'js' =>
'function OnLoad() {new cse();};
google.setOnLoadCallback(OnLoad);
google.load("search", "1");
function cse() {
var sDiv = document.getElementById("sDiv");
var gDiv = document.getElementById("gDiv");
var lDiv = document.getElementById("lDiv");
var rDiv = document.getElementById("rDiv");
this.gCT = new GSearchControl();
this.lCT = new GSearchControl();
this.rCT = new GSearchControl();
this.sForm = new GSearchForm(true, sDiv);
this.sForm.setOnSubmitCallback(this, cse.prototype.onSubmit);
this.sForm.setOnClearCallback(this, cse.prototype.onClear);
this.lCT.setResultSetSize(GSearch.SMALL_RESULTSET);
this.lCT.setLinkTarget(GSearch.LINK_TARGET_SELF);
this.rCT.setResultSetSize(GSearch.LARGE_RESULTSET);
this.rCT.setLinkTarget(GSearch.LINK_TARGET_SELF);
this.gCT.setResultSetSize(GSearch.SMALL_RESULTSET);
this.gCT.setLinkTarget(GSearch.LINK_TARGET_SELF);
var sOPT;
sOPT = new GsearcherOptions();
sOPT.setExpandMode(GSearchControl.EXPAND_MODE_PARTIAL);
var drawOptions;
drawOptions = new GdrawOptions();
drawOptions.setSearchFormRoot(sDiv);
drawOptions.setDrawMode(GSearchControl.DRAW_MODE_LINEAR);
var srBST = new GwebSearch();
srBST.setUserDefinedLabel("Googles Best Guess");
srBST.setSiteRestriction(aa_MYSITE);
srBST.setQueryAddition(aa_BGLABEL);
this.gCT.addSearcher(srBST,sOPT);
this.gCT.draw(gDiv, drawOptions);
drawOptions = new GdrawOptions();
sOPT = new GsearcherOptions();
drawOptions.setSearchFormRoot(sDiv);
drawOptions.setDrawMode(GSearchControl.DRAW_MODE_LINEAR);
sOPT.setExpandMode(GSearchControl.EXPAND_MODE_OPEN);
var srVID = new GvideoSearch();
srVID.setQueryAddition(aa_LABEL);
this.lCT.addSearcher(srVID,sOPT);
sOPT = new GsearcherOptions();
sOPT.setExpandMode(GSearchControl.EXPAND_MODE_OPEN);
var srIMG = new GimageSearch();
srIMG.setRestriction(GSearch.RESTRICT_SAFESEARCH, GSearch.SAFESEARCH_OFF);
srIMG.setQueryAddition(aa_LABEL);
this.lCT.addSearcher(srIMG,sOPT);
this.lCT.draw(lDiv, drawOptions);
var srSITE = new GwebSearch();
srSITE.setUserDefinedLabel(aa_LABEL);
srSITE.setSiteRestriction(aa_MYSITE);
srSITE.setQueryAddition(aa_LABEL);
srSITE.setRestriction(GSearch.RESTRICT_SAFESEARCH, GSearch.SAFESEARCH_OFF);
var srBLOG = new GblogSearch();
srBLOG.setQueryAddition(aa_LABEL);
srBLOG.setResultOrder(GSearch.ORDER_BY_DATE);
var srWEB = new GwebSearch();
srWEB.setQueryAddition(aa_LABEL);
srWEB.setRestriction(GSearch.RESTRICT_SAFESEARCH, GSearch.SAFESEARCH_OFF);
var srNEW = new GnewsSearch();
var srCSE = new GwebSearch();
srCSE.setQueryAddition("askapache");
srCSE.setUserDefinedLabel("CSE");
srCSE.setRestriction(GSearch.RESTRICT_SAFESEARCH, GSearch.SAFESEARCH_OFF);
srCSE.setSiteRestriction("002660089121042511758:kk7rwc2gx0i", null);
var srLOC = new GlocalSearch();
this.rCT.addSearcher(srSITE);
this.rCT.addSearcher(srBLOG);
this.rCT.addSearcher(srWEB);
this.rCT.addSearcher(srNEW);
this.rCT.addSearcher(srCSE);
this.rCT.addSearcher(srLOC);
drawOptions.setDrawMode(GSearchControl.DRAW_MODE_TABBED);
this.rCT.draw(rDiv, drawOptions);
this.sForm.execute(aa_XX);};
cse.prototype.onSubmit = function(form) { var q = form.input.value; if (q && q!= "") { this.gCT.execute(q); this.lCT.execute(q); this.rCT.execute(q);}; return false; };
cse.prototype.onClear = function(form) { this.gCT.clearAllResults(); this.lCT.clearAllResults(); this.rCT.clearAllResults(); form.input.value = ""; return false; };',
);

$old_options = get_option( 'askapache_google_404_old_options' );
$orig_code['old_html']=$old_options['code']['html'];
$orig_code['old_css']=$old_options['code']['css'];
$orig_code['old_js']=$old_options['code']['js'];

			$k=$_GET['whcode'];
			if ( array_key_exists($k, $orig_code )) print($orig_code[$k]);
		}
		die();
	}



	/**
	 * AskApacheGoogle404::admin_print_scripts()
	 *
	 * @return
	 */
	function admin_print_scripts()
	{		
	
	$nonce = wp_create_nonce( 'get_aa404_data' );

		?>
<script type="text/javascript"><!--
var ajax1url='<?php echo admin_url('admin-ajax.php');?>';
jQuery(document).ready(function($){
jQuery(".fad").animate({backgroundColor:"#cceaff"},600)
.animate({backgroundColor:"#e0f0ff"},300).animate({backgroundColor:"#cceaff"},600).animate({backgroundColor:"#e0f0ff"},300).animate({backgroundColor:"#cceaff"},600).animate({backgroundColor:"#e0f0ff"},300).animate({backgroundColor:"#cceaff"},600).animate({backgroundColor:"#e0f0ff"},300).animate({backgroundColor:"#cceaff"},600).animate({backgroundColor:"#e0f0ff"},300).animate({backgroundColor:"#cceaff"},600).animate({backgroundColor:"#e0f0ff"},300).animate({backgroundColor:"#cceaff"},600).animate({backgroundColor:"#e0f0ff"},2300);

jQuery('#aa404_hide_css').click(function(){jQuery("#aa404_css").toggle();return false;});
jQuery('#aa404_hide_js').click(function(){jQuery("#aa404_js").toggle();return false;});
jQuery('#aa404_hide_html').click(function(){jQuery("#aa404_html").toggle();return false;});
jQuery('#aa404_hide_0').click(function(){jQuery("#aa404_opt0").toggle();return false;});
jQuery('#aa404_hide_1').click(function(){jQuery("#aa404_opt1").toggle();return false;});
jQuery('#aa404_hide_2').click(function(){jQuery("#aa404_opt2").toggle();return false;});
jQuery('#aa404_hide_3').click(function(){jQuery("#aa404_opt3").toggle();return false;});
jQuery('#aa404_hide_4').click(function(){jQuery("#aa404_opt4").toggle();return false;});
jQuery('#aa404_load_css').click(function(){jQuery.ajax({ type: "get", url: ajax1url, data: {action:'get_aa404_data', whcode:'css', _ajax_nonce:'<?php echo $nonce; ?>'},
	beforeSend: function(){jQuery("#aa404_css").val('Loading...').fadeIn('slow');},success: function(html){jQuery("#aa404_css").val( html );}});return false;});
jQuery('#aa404_load_prev_css').click(function(){jQuery.ajax({ type: "get", url: ajax1url, data: {action:'get_aa404_data', whcode:'old_css', _ajax_nonce:'<?php echo $nonce; ?>'},
	beforeSend: function(){jQuery("#aa404_css").val('Loading...').fadeIn('slow');},success: function(html){jQuery("#aa404_css").val( html );}});return false;});

jQuery('#aa404_load_js').click(function(){jQuery.ajax({ type: "get", url: ajax1url, data: {action:'get_aa404_data', whcode:'js', _ajax_nonce:'<?php echo $nonce; ?>'},
	beforeSend: function(){jQuery("#aa404_js").val('Loading...').fadeIn('slow');},success: function(html){jQuery("#aa404_js").val( html );}});return false;});
jQuery('#aa404_load_prev_js').click(function(){jQuery.ajax({ type: "get", url: ajax1url, data: {action:'get_aa404_data', whcode:'old_js', _ajax_nonce:'<?php echo $nonce; ?>'},
	beforeSend: function(){jQuery("#aa404_js").val('Loading...').fadeIn('slow');},success: function(html){jQuery("#aa404_js").val( html );}});return false;});

jQuery('#aa404_load_html').click(function(){jQuery.ajax({ type: "get", url: ajax1url, data: {action:'get_aa404_data', whcode:'html', _ajax_nonce:'<?php echo $nonce; ?>'},
	beforeSend: function(){jQuery("#aa404_html").val('Loading...').fadeIn('slow');},success: function(html){jQuery("#aa404_html").val( html );}});return false;});
jQuery('#aa404_load_prev_html').click(function(){jQuery.ajax({ type: "get", url: ajax1url, data: {action:'get_aa404_data', whcode:'old_html', _ajax_nonce:'<?php echo $nonce; ?>'},
	beforeSend: function(){jQuery("#aa404_html").val('Loading...').fadeIn('slow');},success: function(html){jQuery("#aa404_html").val( html );}});return false;});
})
-->
</script>
		<?php
	}



	/**
	 * AskApacheGoogle404::admin_print_styles()
	 *
	 * @return
	 */
	function admin_print_styles()
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		?>
<style tyle="text/css" media="screen">h4.cl5 {margin:3.5em 0 .5em 0;padding-bottom:.25em;border-bottom:1px solid #b1b1b1;}h4.cl5 a {font-weight:normal;font-size:12px;}p.c4r{padding:5px 20px 5px 10px;margin-bottom:3px;margin-top:.25em;}
p.c4r label {display:block;float:left;width:20em;line-height:20px;}.wrap .updated p {line-height:1.3em;}.fad {background-color: #cceaff;border-color:#55abe6;border-width:1px;border-style:solid;padding: 0 0.6em;margin: 5px 15px 2px;	-moz-border-radius: 3px;	-khtml-border-radius: 3px;	-webkit-border-radius: 3px;	border-radius: 3px;}</style>
		<?php
	}



	/**
	 * AskApacheGoogle404::handle_post()
	 *
	 * @return
	 */
	function handle_post()
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		if ( current_user_can('level_8') || current_user_can('level_10') )
		{
		//error_log(__FUNCTION__.':'.__LINE__);
			$this->LoadOptions();
			$code=$options=array();
			
			foreach($this->options as $k=>$v)$options[$k]=$v;
			foreach($this->code as $k=>$v)$code[$k]=$v;
			
			if ( !wp_verify_nonce($_POST['_wpnonce'], 'aa404_google_ajax_search_form') ) wp_die( '<strong>ERROR</strong>: Incorrect Form Submission, please try again.' );
			foreach ( array('api_key', 'rel_num', 'rel_len', 'rec_num') as $k ) $options[$k] = (( isset($_POST["aa404_{$k}"]) ) ? $_POST["aa404_{$k}"] : $options[$k]);
			foreach ( array('google_404', 'rel', 'rec', 'google_ajax') as $k ) $options[$k] = ( (!isset($_POST["aa404_{$k}"])) ? '0' : '1' );
			foreach ( array('css', 'html', 'js', '404') as $k ) {
				if ( isset($_POST["aa404_{$k}"]) ) {
					$code[$k] = stripslashes( $_POST["aa404_{$k}"] );
				}
			}
			
			$this->code=$code;
			$this->options=$options;
			$this->SaveOptions();
		}
	}



	/**
	 * AskApacheGoogle404::options_page()
	 *
	 * @return
	 */
	function options_page()
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		?>
		<div class="wrap" style="max-width:1400px;">
		<h3>AskApache Google 404 Options - <a style="font-size:12px;" href="http://feeds.askapache.com/apache/htaccess">News/Updates</a></h3>
		
		<?php		
		/*
		 * Removed - there is an if in the 404 now
		 */
/*		$four_file = TEMPLATEPATH . '/404.php';
		$four_exists = ( ((file_exists($four_file)) === false && (@realpath($four_file)) === false) || (@stat($four_file)) === false ) ? false : true;
		if ( $four_exists ) {
			echo '<p><strong>Found 404.php</strong> template file at '. str_replace(WP_CONTENT_DIR,'',$four_file);
			echo ' - add the following in that file where you want this plugins output to go.  <a href="http://www.askapache.com/search/404-errordocuments">Learn more...</a><br />
			<code style="background-color:transparent;">&lt;?php if(function_exists("aa_google_404"))aa_google_404();&gt;</code></p>';
		}
		else echo '<p><strong>No <a href="http://www.askapache.com/search/404.php">404.php</a> file found</strong>, so this plugin will be the 404.php, which is a good thing..  Note that for more customization use a 404.php template file.</p>';*/

		$htaccess_file = ABSPATH . '.htaccess';
		$htaccess_exists = ( ((file_exists($htaccess_file)) === false && (@realpath($htaccess_file)) === false) || (@stat($htaccess_file)) === false ) ? false : true;
		if ( $htaccess_exists ) {
			echo '<p><strong>Found .htaccess</strong> config file at '. str_replace(ABSPATH, '/', $htaccess_file).
			' - to help WordPress handle your ErrorDocuments add the below to it.  <a href="http://www.askapache.com/htaccess/apache-htaccess.html">Learn more...</a><br />'.
			'<code style="background-color:transparent;">ErrorDocument 404 '.$r.'index.php?error=404</code><br /><code style="background-color:transparent;">Redirect 404 '.$r.'index.php?error=404</code></p>';
		}
		?>
		


		<div id="live_error_preview" style="margin-top:1.5em;padding-top:.5em;border-top:1px solid #666;">
		<p><a title="AskApache Google 404 Preview" href="../wordpress-google-plugin-<?php echo get_bloginfo('name');?>/USA/rocks?askapache=plugin&amp;missing-9027435972345+this-post&TB_iframe=true&height=800&width=1280" class="thickbox"><strong>VIEW LARGE PREVIEW</strong></a><br /><small>test url: /wordpress-google-plugin-<?php echo get_bloginfo('name');?>/USA/rocks?askapache=plugin&amp;missing-9027435972345+this-post</small></p>
		<iframe src="../wordpress-google-plugin-<?php echo get_bloginfo('name');?>/USA/rocks?askapache=plugin&amp;missing-9027435972345+this-post" width="99%" height="400" frameborder="0" id="lerror_preview"></iframe>
		</div>

		
		<?php $this->google_ajax_search_form(); ?>



		<div style="width:300px;float:left;">
		<p><br class="clear" /></p>
		<h3>Articles from AskApache</h3>
		<ul>
		<li><a href="http://www.askapache.com/seo/seo-secrets.html">SEO Secrets of AskApache.com</a></li>
		<li><a href="http://www.askapache.com/seo/seo-advanced-pagerank-indexing.html">Controlling Pagerank and Indexing</a></li>
		<li><a href="http://www.askapache.com/htaccess/apache-htaccess.html">Ultimate .htaccess Tutorial</a></li>
		<li><a href="http://www.askapache.com/seo/updated-robotstxt-for-wordpress.html">Robots.txt Info for WordPress</a></li>
		</ul>
		</div>
		<div style="width:400px;float:left;">
		<h3>More Info from Google</h3>
		<ul>
		<li><a href="http://code.google.com/apis/ajaxsearch/wizards.html">AJAX Search Wizards</a></li>
		<li><a href="http://code.google.com/apis/ajaxsearch/documentation/">Developer Guide</a></li>
		<li><a href="http://code.google.com/apis/ajaxsearch/samples.html">Code Samples</a></li>
		<li><a href="http://code.google.com/apis/ajaxsearch/community-samples.html">Community Samples</a></li>
		<li><a href="http://code.google.com/support/bin/topic.py?topic=10021">Knowledge Base</a></li>
		<li><a href="http://googleajaxsearchapi.blogspot.com/">AJAX APIs Blog</a></li>
		<li><a href="http://groups.google.com/group/Google-AJAX-Search-API">Developer Forum</a></li>
		</ul>
		<p><br class="clear" /></p>
		</div>
		</div>
		<?php
	}



	/**
	 * AskApacheGoogle404::google_ajax_search_form()
	 *
	 * @return
	 */
	function google_ajax_search_form()
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		?>


		<form id="aa404_main_settings" method="post" action="<?php echo $this->plugin['action']; ?>">
		<?php wp_original_referer_field( true, 'previous' ); wp_nonce_field( 'aa404_google_ajax_search_form' ); ?>


		<h4 class="cl5">HTML Code - <a id="aa404_load_html" href="#">Load Default</a> | <a id="aa404_load_prev_html" href="#">Load Previous</a> | <a id="aa404_hide_html" href="">S/H</a></h4>
		<label for="aa404_html">This controls the output of the plugin.  Move stuff around, change what you want, and load the default if you mess up too much.<br /></label>
		<p><code style="font-weight:bold;">%error_title%</code> - replaced with the status code and error phrase - 404 Not Found<br />
		<code style="font-weight:bold;">%related_posts%</code> - replaced with your related posts html if enabled<br />
		<code style="font-weight:bold;">%recent_posts%</code> - replaced with the recent posts html if enabled<br />
		<code style="font-weight:bold;">%google_helper%</code> - replaced with the Google Fixurl Help box.</p>
		<textarea name="aa404_html" id="aa404_html" style="border-size:2px;font-size:12px; width:95%;" cols="30" rows="20"><?php echo htmlspecialchars( $this->code['html'] ); ?></textarea>


		<h4 class="cl5">Google Options - <a id="aa404_hide_1" href="#">S/H</a></h4>
		<div id="aa404_opt1"><?php
		      // API Key stuff was here
			$this->form_field( 1, 'Show Google AJAX Search', 'google_ajax', 'Displays Google AJAX Search Results' );
			$this->form_field( 1, 'Show Google 404 Helper', 'google_404', 'Displays Google New 404 Helper' );
		?></div>


		<h4 class="cl5">Related Posts Options - <a id="aa404_hide_2" href="#">S/H</a></h4>
		<div id="aa404_opt2"><?php
			$this->form_field( 1, 'Show Related Posts', 'rel', 'Displays List of Posts similar to the query' );
			$this->form_field( 3, 'Related Posts # to Show', 'rel_num', 'How many related posts to show..' );
			$this->form_field( 3, 'Related Posts Excerpt Length', 'rel_len', 'How many related posts to show..' );
		?></div>


		<h4 class="cl5">Recent Posts Options - <a id="aa404_hide_3" href="#">S/H</a></h4>
		<div id="aa404_opt3"><?php
			$this->form_field( 1, 'Show Recent Posts', 'rec', 'Displays List of Recent Posts' );
			$this->form_field( 3, 'Recent Posts # to Show', 'rec_num', 'How many recent posts to show..' );
		?></div>


		<?php
			$this->form_field( 4, 'CSS Code', 'css', 'The css that controls the google ajax search results.. (and anything else on the page)' );
			$this->form_field( 4, 'JavaScript Code', 'js', 'The javscript that runs the google ajax search.. (and anything else on the page)' );
		?>


		<p class="submit"><input type="submit" class="button" id="submit_aa404_main_settings" name="submit_aa404_main_settings" value="Save Changes &raquo;" /></p>
		</form>

		<?php
	}



	/**
	 * AskApacheGoogle404::form_field()
	 *
	 * @param integer $w
	 * @param string $title
	 * @param string $id
	 * @param string $desc
	 * @return
	 */
	function form_field( $w = 1, $title = '', $id = '', $desc = '' )
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		
		switch($w):
			case 1: ?>
				<p class="c4r"><label title="<?php _e($desc);?>" for="aa404_<?php echo $id;?>"> <?php _e($title) ?>:</label>
				<input name="aa404_<?php echo $id;?>" type="checkbox" id="aa404_<?php echo $id;?>" value="<?php echo $this->options[$id];?>" <?php checked('1', $this->options[$id]); ?> /><br style="clear:both;" /></p>
			<?php break;

			case 2: ?>
				<p class="c4r"><label title="<?php _e($desc);?>" for="aa404_<?php echo $id;?>"> <?php _e($title) ?>:</label>
				<input style="float:left;width:60em;" name="aa404_<?php echo $id;?>" id="aa404_<?php echo $id;?>" type="text" value="<?php echo (isset($this->options[$id]) ? $this->options[$id] : ''); ?>" /><br style="clear:both;" /></p>
			<?php break;

			case 3: ?>
				<p class="c4r"><label title="<?php _e($desc);?>" for="aa404_<?php echo $id; ?>"> <?php _e($title) ?>:</label>
				<input style="float:left;width:4em;" name="aa404_<?php echo $id; ?>" size="10" id="aa404_<?php echo $id; ?>" type="text" value="<?php echo (isset($this->options[$id]) ? $this->options[$id] : ''); ?>" /><br style="clear:both;" /></p>
			<?php break;

			case 4: ?>
				<h4 class="cl5"><?php _e($title);?> - <a id="aa404_load_<?php echo $id;?>" href="#">Load Default</a> | <a id="aa404_load_prev_<?php echo $id;?>" href="#">Load Previous</a> | <a id="aa404_hide_<?php echo $id;?>" href="">S/H</a></h4>
				<label for="aa404_<?php echo $id;?>"><?php _e($desc); ?><br /></label><br style="clear:both;" />
				<textarea name="aa404_<?php echo $id; ?>" id="aa404_<?php echo $id; ?>" style="font-size:11px; width:95%;" cols="50" rows="6"><?php echo htmlspecialchars($this->code[$id]); ?></textarea>
			<?php break;
		endswitch;

	}



	/**
	 * AskApacheGoogle404::get_keywords()
	 *
	 * @param mixed $sep
	 * @param integer $num
	 * @return
	 */
	function get_keywords( $sep, $num = 6 )
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		$comp_words = $found_words = array();
		$n = preg_match_all( "/[\w]{3,15}/", strtolower(html_entity_decode(strip_tags($_SERVER['REQUEST_URI'], ' ' . $_SERVER['QUERY_STRING']))), $found_words );
		if ( $n < 1 ) return $_SERVER['HTTP_HOST'];

		foreach ( array_unique((array )$found_words[0]) as $key => $aa_word ) $comp_words[] = $aa_word;
		if ( sizeof((array )$comp_words) > 0 ) if ( sizeof($comp_words) > $num ) array_splice( $comp_words, $num + 1 );

		return ( (sizeof($comp_words) > 0) ? trim(implode($sep, $comp_words)) : $_SERVER['HTTP_HOST'] );
	}



	/**
	 * AskApacheGoogle404::get_plugin_data()
	 *
	 * @param mixed $find
	 * @return
	 */
	function get_plugin_data( $find = array('Description', 'Author', 'Version', 'DB Version', 'Requires at least', 'Tested up to', 'WordPress', 'Plugin', 'Plugin Name', 'Short Name', 'Domain Path', 'Text Domain', '(?:[a-z]{2,25})? URI') )
	{
	    //TODO: Rewrite this so that it will work with the WP_PLUGINS
		//error_log(__FUNCTION__.':'.__LINE__);
		$fp = fopen( __FILE__, 'r' );
		if ( !is_resource($fp) ) return false;
		$data = fread( $fp, 1000 );
		fclose( $fp );

		$mtx = $plugin = array();
		preg_match_all( '/(' . join('|', $find) . ')\:[\s\t]*(.+)/i', $data, $mtx, PREG_SET_ORDER );
		foreach ( $mtx as $m ) $plugin[trim( $m[1] )] = str_replace( array("\r", "\n", "\t"), '', trim($m[2]) );

		$plugin['pb'] = preg_replace( '|^' . preg_quote(WP_PLUGIN_DIR, '|') . '/|', '', __FILE__ );
		$plugin['Title'] = '<a href="' . $plugin['Plugin URI'] . '" title="' . __( 'Visit plugin homepage' ) . '">' . $plugin['Plugin Name'] . '</a>';
		$plugin['Author'] = '<a href="' . $plugin['Author URI'] . '" title="' . __( 'Visit author homepage' ) . '">' . $plugin['Author'] . '</a>';
		$plugin['page'] = basename( __FILE__ );
		$plugin['hook'] = 'tools_page_' . rtrim( $plugin['page'], '.php' );
		$plugin['action'] = 'admin.php?page=' . $plugin['page'];

		return $plugin;
	}

}
endif;










if ( !in_array('AAGoogle404Handler', (array)get_declared_classes() ) && !class_exists( 'AAGoogle404Handler' ) ) :
/**
 * AAGoogle404Handler
 *
 * @package
 * @author webmaster@askapache.com
 * @copyright AskApache
 * @version 2009
 * @access public
 */
class AAGoogle404Handler
{
	var $options = false;
	var $code = false;
	var $status_code = 404;

	var $errors = false;
	var $messages = false;
	var $message = false;
	var $reason = false;

	var $ASC = array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		226 => 'IM Used',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => 'Reserved',
		307 => 'Temporary Redirect',
		400 => array('Bad Request', "Your browser sent a request that this server could not understand."),
		401 => array('Unauthorized', "This server could not verify that you are authorized to access the document requested."),
		402 => array('Payment Required', "%I_ERR%"),
		403 => array('Forbidden', "You don't have permission to access %R_URI% on this server."),
		404 => array('Not Found', "We couldn't find <acronym title='%R_URI%'>that uri</acronym> on our server, though it's most certainly not your fault."),
		405 => array('Method Not Allowed', "The requested method %R_METH% is not allowed for the URL %R_URI%."),
		406 => array('Not Acceptable', "An appropriate representation of the requested resource %R_URI% could not be found on this server."),
		407 => array('Proxy Authentication Required', "An appropriate representation of the requested resource %R_URI% could not be found on this server."),
		408 => array('Request Timeout', "Server timeout waiting for the HTTP request from the client."),
		409 => array('Conflict', "%I_ERR%"),
		410 => array('Gone', "The requested resource%R_URI%is no longer available on this server and there is no forwarding address. Please remove all references to this resource."),
		411 => array('Length Required', "A request of the requested method GET requires a valid Content-length."),
		412 => array('Precondition Failed', "The precondition on the request for the URL %R_URI% evaluated to false."),
		413 => array('Request Entity Too Large', "The requested resource %R_URI% does not allow request data with GET requests, or the amount of data provided in the request exceeds the capacity limit."),
		414 => array('Request-URI Too Long', "The requested URL's length exceeds the capacity limit for this server."),
		415 => array('Unsupported Media Type', "The supplied request data is not in a format acceptable for processing by this resource."),
		416 => array('Requested Range Not Satisfiable', ""),
		417 => array('Expectation Failed', "The expectation given in the Expect request-header field could not be met by this server. The client sent <code>Expect:</code>"),
		422 => array('Unprocessable Entity', "The server understands the media type of the request entity, but was unable to process the contained instructions."),
		423 => array('Locked', "The requested resource is currently locked. The lock must be released or proper identification given before the method can be applied."),
		424 => array('Failed Dependency', "The method could not be performed on the resource because the requested action depended on another action and that other action failed."),
		425 => array('No Code', "%I_ERR%"),
		426 => array('Upgrade Required', "The requested resource can only be retrieved using SSL. Either upgrade your client, or try requesting the page using https://"),
		500 => array('Internal Server Error', "%I_ERR%"),
		501 => array('Not Implemented', " %R_METH% to %R_URI% not supported."),
		502 => array('Bad Gateway', "The proxy server received an invalid response from an upstream server."),
		503 => array('Service Unavailable', "The server is temporarily unable to service your request due to maintenance downtime or capacity problems. Please try again later."),
		504 => array('Gateway Timeout', "The proxy server did not receive a timely response from the upstream server."),
		505 => array('HTTP Version Not Supported', "%I_ERR%"),
		506 => array('Variant Also Negotiates', "A variant for the requested resource <code>%R_URI%</code> is itself a negotiable resource. This indicates a configuration error."),
		507 => array('Insufficient Storage', "The method could not be performed.	There is insufficient free space left in your storage allocation."),
		510 => array('Not Extended', "A mandatory extension policy in the request is not accepted by the server for this resource.")
	);



	/**
	 * AAGoogle404Handler::AAGoogle404Handler()
	 *
	 * @return
	 */
	function AAGoogle404Handler()
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		global $wpdb;
		add_action( 'wp_head', array(&$this, 'wp_header') );
		add_filter( 'wp_title', array(&$this, 'title_fix') );

		$this->options = get_option( 'askapache_google_404_options' );
		$this->code = get_option( 'askapache_google_404_code' );
		$this->status_code = ( isset($_SERVER['REDIRECT_STATUS']) && $_SERVER['REDIRECT_STATUS'] != 200 ) ? $_SERVER['REDIRECT_STATUS'] : ( !isset($_REQUEST['error']) ) ? 404 : ( int )$_REQUEST['error'];

		$sr = array( '%I_ERR%' => 'The server encountered an internal error or misconfiguration and was unable to complete your request.', '%R_URI%' => attribute_escape(stripslashes($_SERVER['REQUEST_URI'])), '%R_METH%' => $_SERVER['REQUEST_METHOD'] );

		$this->message = ( isset($this->ASC[$this->status_code][1]) ) ? str_replace( array_keys($sr), array_values($sr), $this->ASC[$this->status_code][1] ) : '';
		$this->reason = $this->ASC[$this->status_code][0];

	}



	/**
	 * AAGoogle404Handler::handle_404()
	 *
	 * @return
	 */
	function handle_404()
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		global $wpdb, $posts, $post, $wp_did_header, $wp_did_template_redirect, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;
		if ( is_array($wp_query->query_vars) ) extract( $wp_query->query_vars, EXTR_SKIP );

		ob_start();
		@header( "HTTP/1.1 {$this->status_code} {$this->reason}", 1 );
		@header( "Status: {$this->status_code} {$this->reason}", 1 );

		if ( $this->status_code == 400 || $this->status_code == 403 || $this->status_code == 405 || (string )$this->status_code[0] == '5' ) return $this->handle_non_404();
		if ( file_exists(TEMPLATEPATH . '/404.php') && is_file(TEMPLATEPATH . '/404.php') ) load_template( TEMPLATEPATH . '/404.php' );
		else {
			get_header();

			$this->handle_it();

			get_sidebar();

			get_footer();

		}
		
		ob_flush(); flush(); exit; exit();
	}



	/**
	 * AAGoogle404Handler::handle_it()
	 *
	 * @return
	 */
	function handle_it()
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		global $wpdb, $posts, $post, $wp_did_header, $wp_did_template_redirect, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;
		if ( is_array($wp_query->query_vars) ) extract( $wp_query->query_vars, EXTR_SKIP );
	
		$recent = $related = $google_help = $title = '';

		$title = $this->status_code . ' ' . $this->reason;

		if ( $this->options['google_404'] == '1' ) $google_helper = '<script type="text/javascript" src="http://linkhelp.clients.google.com/tbproxy/lh/wm/fixurl.js"></script>' . "\n";


		if ( $this->options['rec'] == '1' )
		{
		//error_log(__FUNCTION__.':'.__LINE__);
			ob_start();
			echo '<ul>';
			wp_get_archives( 'type=postbypost&limit=' . $this->options['rec_num'] );
			echo '</ul>';
			$recent = ob_get_clean();
		}


		if ( $this->options['rel'] == '1' )
		{
		//error_log(__FUNCTION__.':'.__LINE__);
			ob_start();
			$this->related_posts( (int)$this->options['rel_num'], (int)$this->options['rel_len'] );
			$related = ob_get_clean();
		}


		$sr = array( '%error_title%' => $title, '%related_posts%' => $related, '%recent_posts%' => $recent, '%google_helper%' => $google_helper );
		if ( $this->options['google_ajax'] == '1' ) echo str_replace( array_keys($sr), array_values($sr), $this->code['html'] );
	}



	/**
	 * AAGoogle404Handler::handle_non_404()
	 *
	 * @return
	 */
	function handle_non_404()
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		if ( $this->status_code == 405 ) @header( 'Allow: GET,HEAD,POST,OPTIONS,TRACE' );
		@header( "Connection: close", 1 );
		echo "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">\n<html><head>\n<title>{$this->status_code} {$this->reason}</title>\n";
		echo "<h1>{$this->reason}</h1>\n<p>{$this->message}<br />\n</p>\n</body></html>";
		ob_flush(); flush(); exit; exit();
	}



	/**
	 * AAGoogle404Handler::wp_header()
	 *
	 * @return
	 */
	function wp_header()
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		if ( !is_404() ) return;
		$sr = array( '/\0+/' => '', '/(\\\\0)+/' => '', '/\s\s+/' => ' ', "/(\r\n|\n|\r)/" => "\n", '/\/\*(.*?)\*\//' => '', '/(:|,|;) /' => "\\1",
						 '# +{#' => '{', '#{ +#' => '{', '#} +#' => '}', '# +}#' => '}', '#;}#' => '}', '#, +#' => ',', '# +,#' => ',' );

		$css = preg_replace( array_keys($sr), array_values($sr), $this->code['css'] );
		$jss = preg_replace( array_keys($sr), array_values($sr), $this->code['js'] );

		printf( '%9$s%1$s<style type="text/css">%11$s</style><script src="%8$s" type="text/javascript"></script>' .
				  '<script type="text/javascript">//<![CDATA[%1$svar aa_LABEL="%2$s";var aa_MYSITE="%3$s";var aa_XX="%4$s";' .
				  'var aa_BGLABEL="%5$s";var GOOG_FIXURL_LANG="%6$s";var GOOG_FIXURL_SITE="%7$s";try{%10$s}catch(err){}%1$s//]]></script>%1$s%9$s', "\n", get_option('blogname'),
				  str_replace('www.', '', $_SERVER['HTTP_HOST']), $this->get_keywords('|', 6), 'OR allinurl:' . $this->get_keywords(' ', 2), get_bloginfo('language'),
				  get_bloginfo('wpurl'), 'http://www.google.com/jsapi?key=' . $this->options['api_key'], "<!-- Google 404 Plugin by www.AskApache.com -->", str_replace('}', "};", $jss), $css );
	}



	/**
	 * AAGoogle404Handler::title_fix()
	 *
	 * @param mixed $title
	 * @return
	 */
	function title_fix( $title )
	{
		return ( !is_404() ) ? $title : $this->status_code . ' ' . $this->reason;
	}




	/**
	 * AAGoogle404Handler::get_keywords()
	 *
	 * @param mixed $sep
	 * @param integer $num
	 * @return
	 */
	function get_keywords( $sep, $num = 6 )
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		$comp_words = $found_words = array();

		$n = preg_match_all( "/[\w]{3,15}/", strtolower(html_entity_decode(strip_tags($_SERVER['REQUEST_URI'], ' ' . $_SERVER['QUERY_STRING']))), $found_words );
		if ( $n < 1 ) return $_SERVER['HTTP_HOST'];

		foreach ( array_unique((array )$found_words[0]) as $key => $aa_word ) $comp_words[] = $aa_word;
		if ( sizeof((array )$comp_words) > 0 ) if ( sizeof($comp_words) > $num ) array_splice( $comp_words, $num + 1 );

		return ( (sizeof($comp_words) > 0) ? trim(implode($sep, $comp_words)) : $_SERVER['HTTP_HOST'] );
	}



	/**
	 * AAGoogle404Handler::related_posts()
	 *
	 * @param integer $limit
	 * @param integer $len
	 * @return
	 */
	function related_posts( $limit = 15, $len = 120 )
	{
		//error_log(__FUNCTION__.':'.__LINE__);
		global $wpdb;
		$terms = $this->get_keywords( ' ' );
		if ( strlen($terms) < 3 ) return;

		$sql = "SELECT ID, post_title, post_name, post_content, MATCH (post_name, post_content) AGAINST ('$terms') AS `score` FROM $wpdb->posts WHERE MATCH (post_name, post_content) AGAINST ('$terms') ".
		"AND post_type = 'post' AND post_status = 'publish' AND post_password = '' AND post_date < '" . current_time( 'mysql' ) . "' ORDER BY score DESC LIMIT $limit";

		$results = $wpdb->get_results( $wpdb->prepare($sql) );

		if ( $results )
		{
		//error_log(__FUNCTION__.':'.__LINE__);
			foreach ( $results as $result ) printf( '%4$s<h4><a href="%1$s" title="%2$s">%2$s</a></h4>%4$s<blockquote cite="%1$s">%4$s<p>%3$s...</p>%4$s</blockquote>%4$s',
																get_permalink($result->ID), attribute_escape(stripslashes(apply_filters('the_title', $result->post_title))),
																substr(wp_trim_excerpt(stripslashes(strip_tags($result->post_content))), 0, $len), "\n" );
		}
	}

}
endif;

$AskApacheGoogle404 = new AskApacheGoogle404();

add_action('init',array(&$AskApacheGoogle404, 'init'));
add_action( 'admin_menu', array(&$AskApacheGoogle404, 'admin_menu') );

function options_page() {
	global $AskApacheGoogle404;
	
	$AskApacheGoogle404->options_page();
}

if ( !function_exists('aa_google_404') ):
	/**
	 * aa_google_404()
	 *
	 * @return
	 */
	function aa_google_404()
	{
		error_log(__FUNCTION__.':'.__LINE__);
		global $AAGoogle404Handler;
		$AAGoogle404Handler =& new AAGoogle404Handler();
		$AAGoogle404Handler->handle_it();
	}
endif;

?>
