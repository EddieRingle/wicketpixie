<?php
/**
 * WicketPixie v2.0
 * (c) 2006-2009 Eddie Ringle,
 *               Chris J. Davis,
 *               Dave Bates
 * Provided by Chris Pirillo
 *
 * Licensed under the New BSD License.
 */
require_once(TEMPLATEPATH .'/functions.php');
$debug = DEBUG;
$plugins = array(
    'name' => '',
    array(
        "name"  => "All In One SEO Pack",
        "description"   => "It's filled with SEO goodies.",
        "id"    => 'wicketpixie_plugin_all_in_one_seo_pack',
        'path' => TEMPLATEPATH .'/plugins/all-in-one-seo-pack/all_in_one_seo_pack.php',
        "std"   => 'false',
        "type"  => 'checkbox'),
    array(
        "name" => "AskApache Google 404",
        "description" => "Displays unbeatable information to site visitors arriving at a non-existant page (from a bad link).  Major SEO with Google AJAX, Google 404 Helper, Related Posts, Recent Posts, etc..",
        "id"    => 'wicketpixie_plugin_aagoog404',
        'path'  => TEMPLATEPATH .'/plugins/askapache-google-404/askapache-google-404.php',
        "std"    => 'false',
        "type"    => "checkbox"),
    array(
        "name"  => "Automatically Hyperlink URLs",
        "description"   => "Automatically hyperlinks URLs in post contents.",
        "id"    => 'wicketpixie_plugin_autohyperlink-urls',
        'path'  => TEMPLATEPATH .'/plugins/autohyperlink-urls/autohyperlink-urls.php',
        "std"   => 'false',
        "type"  => 'checkbox'),
    array(
        "name" => "Chitika",
        "description" => "Use Chitika for Search Targeted Advertising",
        "id" => 'wicketpixie_plugins_chitika',
        'path' => TEMPLATEPATH .'/plugins/chitika-premium/premium.php',
        "std" => 'false',
        "type" => 'checkbox'),
    array(
        "name"  => "FAlbum",
        "description"   => "Integrate Flickr albums into your blog.",
        "id"    => 'wicketpixie_plugin_falbum',
        'path'  => TEMPLATEPATH .'/plugins/falbum/wordpress-falbum-plugin.php',
        "std"   => 'false',
        "type"  => 'checkbox'),
    array(
        "name" => "Kontera",
        "description" => "Enable Kontera Advertising.",
        "id"    => 'wicketpixie_plugin_kontera',
        'path'  => TEMPLATEPATH .'/plugins/kontera/kontera.php',
        "std"   => 'false',
        "type"  => 'checkbox'),
    array(
        "name"  => "NoFollow Navigation",
        "description"   => "Adds nofollow to the generated page links.",
        "id"    => 'wicketpixie_plugin_nofollow_navigation',
        'path'  => TEMPLATEPATH .'/plugins/nofollow-navi/nofollow-navi.php',
        "std"   => 'false',
        "type"  => 'checkbox'),
    array(
        "name"  => "Obfuscate Email",
        "description"   => "Modifies email addresses to prevent email harvesting.",
        "id"    => 'wicketpixie_plugin_obfuscate-email',
        'path'  => TEMPLATEPATH .'/plugins/obfuscate-email/obfuscate-email.php',
        "std"   => 'false',
        "type"  => 'checkbox'),
    array(
        "name"  => "WP PageNavi",
        "description"   => "Adds a more advanced paging navigation to your WordPress blog.",
        "id"    => 'wicketpixie_plugin_pagenavi',
        'path'  => TEMPLATEPATH .'/plugins/wp-pagenavi/wp-pagenavi.php',
        "std"   => 'false',
        "type"   => 'checkbox'),
    array(
        "name"  => "StatPress Reloaded",
        "description"   => "A really nifty stats plugin.",
        "id"    => 'wicketpixie_plugin_statpress-reloaded',
        'path'  => TEMPLATEPATH .'/plugins/statpress-reloaded/statpress.php',
        "std"   => 'false',
        "type"  => 'checkbox'),
    array(
        "name"  => "WP Related Posts",
        "description"   => "Generates a list of related posts. Deselect if you prefer a different related posts plugin (that works with the commands we use!).",
        "id"    => 'wicketpixie_plugin_related-posts',
        'path'  => TEMPLATEPATH .'/plugins/related-posts.php',
        "std"   => 'false',
        "type"  => 'checkbox')        
);

function add_plugins()
{
    global $plugins;
    global $debug;
    error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

    foreach($plugins as $plugin) {
        if (is_array($plugin)) {
            if(get_option($plugin['id']) == 'true' || $plugin['std'] == 'true') {
                require_once $plugin['path'];
            }
        }
    }
    if ($debug == true) {
        error_reporting(E_ALL & E_NOTICE & _STRICT);
    }
}

class WiPiPlugins extends AdminPage
{
    function __construct()
    {
        parent::__construct('WiPi Plugins','wipi-plugins.php',null,array($GLOBALS['plugins']));
        $this->page_title = 'WiPi Plugins';
    }
    
    function __destruct()
    {
        unset($GLOBALS['plugins']);
        parent::__destruct();
    }
    
    function save()
    {
        global $plugins;
        
        //Special considerations for the Google 404
        $aa404 = false;

        foreach ( $plugins as $value ) {
            if (isset($value['id']) && isset($_POST[$value['id']]) && !empty($_POST[$value['id']])) {
                 if (strpos($_POST[$value['id']], "aagoog404") !== false) $aa404 = true;
            }
        }
        
        if ($aa404) {
            if (!class_exists('AskApacheGoogle404')) {
                require_once(TEMPLATEPATH . "/plugins/askapache-google-404/askapache-google-404.php");
            }
            $tmp = new AskApacheGoogle404();
            $tmp->activate();
        } else {
            if (!class_exists('AskApacheGoogle404')) {
                require_once(TEMPLATEPATH . "/plugins/askapache-google-404/askapache-google-404.php");
            }
            $tmp = new AskApacheGoogle404();
            $tmp->deactivate();
        }
        
        parent::save();
    }
}
