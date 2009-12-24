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

$optpre = 'wicketpixie_';
define('SIMPLEPIEPATH',ABSPATH.'wp-includes/class-simplepie.php');

// No spaces in this constant please (use hyphens)
/*
* a = alpha (unstable, most likely broken)
* b = beta (testing, works but may have bugs)
* rc = release candidate (stable testing, minor issues are left)
*/
define('WIK_VERSION',"2.0-pre");

/* Debug settings */
define(DEBUG,true);
if (DEBUG == true) {
    error_reporting(E_ALL & E_NOTICE & E_STRICT);
}

/* Dynamic (Widget-enabled) Sidebar */
if ( function_exists('register_sidebar') ) {
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
}

/* Admin Pages */

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

// WicketPixie Notifications page
require_once( TEMPLATEPATH .'/app/notify.php');
$a = new NotifyAdmin();
add_action('admin_menu',array($a,'add_page_to_menu'));
unset($a);
register_activation_hook('/app/notify.php',array('NotifyAdmin','install'));

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

/* Version number in admin footer */
function wicketpixie_add_admin_footer() {
	echo "Thank you for using WicketPixie v".WIK_VERSION.", a free premium WordPress theme from <a href='http://chris.pirillo.com/'>Chris Pirillo</a>.<br/>";
}
add_action('in_admin_footer', 'wicketpixie_add_admin_footer');

/* Status updates */
require_once( TEMPLATEPATH .'/app/update.php');

/* jQuery 
function load_jQuery()
{
    wp_enqueue_script('jquery');
}
add_action('init','load_jQuery'); */

/* Widgets */

if(function_exists('register_widget')) {
    // My Profiles
    require_once(TEMPLATEPATH .'/widgets/my-profiles.php');
    add_action('widgets_init','MyProfilesInit');

    // Social Badges
    require_once(TEMPLATEPATH .'/widgets/social-badges.php');
    add_action('widgets_init','SocialBadgesInit');

    // Ustream
    require_once(TEMPLATEPATH .'/widgets/ustream-widget.php');
    add_action('widgets_init','UstreamWidgetInit');

    // Social Me Feed Widgets
    include_once(TEMPLATEPATH .'/widgets/sources.php');
    foreach( SourceAdmin::collect() as $widget ) {
        if(SourceAdmin::feed_check($widget->title) == 1) {
            $source_title = $widget->title;
            $t_title = str_replace(' ','',$source_title);
	        $cleaned= strtolower( $source_title );
	        $cleaned= preg_replace( '/\W/', ' ', $cleaned );
	        $cleaned= str_replace( " ", "", $cleaned );
            if(is_file(TEMPLATEPATH .'/widgets/'.$cleaned.'.php')) {
                add_action('widgets_init',"${t_title}Init");
            }
	    }
    }
}
?>
