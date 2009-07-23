<?php
/*
WicketPixie - A Social Media WordPress Theme
Licensed under the New BSD License.
Copyright (c) 2006-2009 Chris Pirillo <chris@pirillo.com>,
                        Eddie Ringle <eddie@eringle.net>,
                        Chris J. Davis <cjdavis@viewzi.com>,
                        Dave Bates <me@dave-bates.net>.
All rights reserved.
*/

$optpre = 'wicketpixie_';
include_once( TEMPLATEPATH . '/widgets/sources.php' );
define(SIMPLEPIEPATH,ABSPATH.'wp-includes/class-simplepie.php');

// No spaces in this constant please
/*
* a = alpha (unstable, most likely broken)
* b = beta (testing, works but may have bugs)
* rc = release candidate (stable testing, minor issues are left)
*/
define('WIK_VERSION',"1.2-a1");

function collect() {
	global $wpdb;
	$table= $wpdb->prefix . 'wik_sources';
	$sources= $wpdb->get_results( "SELECT * FROM $table" );
	if( is_array( $sources ) ) {
		return $sources;
	} else {
		return array();
	}
}

if ( function_exists('register_sidebar') )
    register_sidebar(array('name'=>'sidebar_top',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
    ));
	register_sidebar(array('name'=>'sidebar1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));	
	register_sidebar(array('name'=>'sidebar2',		
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	register_sidebar(array('name'=>'sidebar3',		
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	register_sidebar(array('name'=>'sidebar4',		
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	register_sidebar(array('name'=>'sidebar5',		
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	register_sidebar(array('name'=>'sidebar6',		
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	
if( function_exists( 'register_sidebar_widget' ) ) {
	register_sidebar_widget('WicketPixie: Recent Posts','wicketpixie_recent_posts');
	register_sidebar_widget('WicketPixie: My Profiles','wicketpixie_my_profiles');
	register_sidebar_widget('WicketPixie: Social Buttons','wicketpixie_social_buttons');
	register_sidebar_widget('WicketPixie: Ustream','wicketpixie_ustream_widget');
	register_widget_control('WicketPixie: Ustream','wicketpixie_ustream_widget_control');
	foreach( collect() as $widget ) {
		$cleaned= strtolower( $widget->title );
		$cleaned= preg_replace( '/\W/', ' ', $cleaned );
		$cleaned= str_replace( " ", "", $cleaned );
		register_sidebar_widget( 'WicketPixie: ' . $widget->title, 'wicketpixie_' . $cleaned );
	}
}

function wicketpixie_my_profiles()
{
	include( TEMPLATEPATH .'/widgets/my-profiles.php'); 
}

function wicketpixie_recent_posts()
{	
	include( TEMPLATEPATH .'/widgets/recent-posts.php'); 
}

function wicketpixie_social_buttons()
{
    include(TEMPLATEPATH .'/widgets/sidebar-buttons.php');
}

function wicketpixie_ustream_widget()
{
    include(TEMPLATEPATH .'/widgets/ustream-widget.php');
}

function wicketpixie_ustream_widget_control()
{
    if(get_option('wicketpixie_sidebar_ustream_heading') != false) {
        $heading = get_option('wicketpixie_sidebar_ustream_heading');
    }
    if(get_option('wicketpixie_sidebar_ustream_channel') != false) {
        $channelname = get_option('wicketpixie_sidebar_ustream_channel');
    } elseif(get_option('wicketpixie_ustream_channel') != false) {
        $channelname = get_option('wicketpixie_ustream_channel');
    }
    if(get_option('wicketpixie_sidebar_ustream_autoplay') != false) {
        $autoplay = get_option('wicketpixie_sidebar_ustream_autoplay');
    } elseif(get_option('wicketpixie_home_ustream_autoplay') != false) {
        $autoplay = get_option('wicketpixie_home_ustream_autoplay');
    }
    if(get_option('wicketpixie_sidebar_ustream_height') != false) {
        $height = get_option('wicketpixie_sidebar_ustream_height');
    } elseif(get_option('wicketpixie_home_ustream_height') != false) {
        $height = get_option('wicketpixie_home_ustream_height');
    }
    if(get_option('wicketpixie_sidebar_ustream_width') != false) {
        $width = get_option('wicketpixie_sidebar_ustream_width');
    } elseif(get_option('wicketpixie_home_ustream_width') != false) {
        $width = get_option('wicketpixie_home_ustream_width');
    }
    if($_POST['ustreamWidget-Submit']) {
        update_option('wicketpixie_sidebar_ustream_heading',$_POST['ustreamWidget-heading']);
        update_option('wicketpixie_sidebar_ustream_channel',$_POST['ustreamWidget-ChannelName']);
        if(isset($_POST['ustreamWidget-Autoplay'])) {
            update_option('wicketpixie_sidebar_ustream_autoplay','true');
        } else {
            update_option('wicketpixie_sidebar_ustream_autoplay','false');
        }
        update_option('wicketpixie_sidebar_ustream_height',$_POST['ustreamWidget-Height']);
        update_option('wicketpixie_sidebar_ustream_width',$_POST['ustreamWidget-Width']);
    }
    ?>
    <p>
    <label for="ustreamWidget-heading">Title: </label>
    <input type="text" id="ustreamWidget-heading" name="ustreamWidget-heading" value="<?php echo $heading; ?>" /><br/>
    <label for="ustreamWidget-ChannelName">Channel: </label>
    <input type="text" id="ustreamWidget-ChannelName" name="ustreamWidget-ChannelName" value="<?php echo $channelname; ?>" /><br/>
    <label for="ustreamWidget-Height">Height: </label>
    <input size="5" type="text" id="ustreamWidget-Height" name="ustreamWidget-Height" value="<?php echo $height; ?>" />px (240 recommended)<br/>
    <label for="ustreamWidget-Width">Width: </label>
    <input size="5" type="text" id="ustreamWidget-Width" name="ustreamWidget-Width" value="<?php echo $width; ?>" />px (300 recommended)<br/>
    <label for="ustreamWidget-Autoplay">Autoplay: </label>
    <input type="checkbox" id="ustreamWidget-Autoplay" name="ustreamWidget-Autoplay" <?php if ($autoplay === 'true') { echo "checked='checked'"; } ?> />
    <input type="hidden" id="ustreamWidget-Submit" name="ustreamWidget-Submit" value="1" />    
    </p>
    <?php
}

function wicketpixie_add_admin_footer() {
	echo "Thank you for using WicketPixie v".WIK_VERSION.", a free premium WordPress theme from <a href='http://chris.pirillo.com/'>Chris Pirillo</a>.<br/>";
}

// The parent AdminPage class
require_once(TEMPLATEPATH .'/app/admin-page.php');

// WicketPixie Admin page
require_once( TEMPLATEPATH .'/app/wicketpixie-admin.php');
$a = new WiPiAdmin();
add_action('admin_menu',array($a,'add_page_to_menu'));
unset($a);

// WiPi Plugins page
require_once( TEMPLATEPATH .'/app/wipi-plugins.php');
$a = new WiPiPlugins();
add_action('admin_menu',array($a,'add_page_to_menu'));
add_plugins();
unset($a);

// Adsense Settings page
require_once( TEMPLATEPATH .'/app/adsenseads.php');
$a = new AdsenseAdmin();
add_action('admin_menu',array($a,'add_page_to_menu'));
unset($a);
register_activation_hook('/app/adsenseads.php',array('AdsenseAdmin','install'));

// Custom Code page
require_once( TEMPLATEPATH .'/app/customcode.php');
$a = new CustomCodeAdmin();
add_action('admin_menu',array($a,'add_page_to_menu'));
unset($a);

// Faves Manager
require_once( TEMPLATEPATH .'/app/faves.php');
$a = new FavesAdmin();
add_action('admin_menu',array($a,'add_page_to_menu'));
unset($a);
register_activation_hook('/app/faves.php',array('FavesAdmin','install'));

// Home Editor
require_once( TEMPLATEPATH .'/app/homeeditor.php');
$a = new HomeAdmin();
add_action('admin_menu',array($a,'add_page_to_menu'));
unset($a);

// Social Me Manager
require_once( TEMPLATEPATH .'/app/sourcemanager.php' );
$a = new SourceAdmin();
add_action('admin_menu',array($a,'add_page_to_menu'));
unset($a);
register_activation_hook('/app/sourcemanager.php', array( 'SourceAdmin', 'install' ) );

// Theme Options
require_once(TEMPLATEPATH .'/app/theme-options.php');
$a = new ThemeOptions();
add_action('admin_menu',array($a,'add_page_to_menu'));
unset($a);
add_action('admin_head', 'wicketpixie_admin_head');
add_action('wp_head', 'wicketpixie_wp_head');

// WicketPixie Notifications page
require_once( TEMPLATEPATH .'/app/notify.php');
$a = new NotifyAdmin();
add_action('admin_menu',array($a,'add_page_to_menu'));
unset($a);
register_activation_hook('/app/notify.php',array('NotifyAdmin','install'));

// Version number in admin footer
add_action('in_admin_footer', 'wicketpixie_add_admin_footer');
// Status update bubble
require_once( TEMPLATEPATH .'/app/update.php');

?>
