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

/* Useful functions */
require_once('app/functions.php');

/* Dynamic (Widget-enabled) Sidebar */
enable_widgetized_sidebar();

/* Admin Pages */
load_admin_pages();

/* Version number in admin footer */
show_version_in_admin_footer();

/* Status updates */
require_once( TEMPLATEPATH .'/app/update.php');

/* Widgets */
load_widgets();
?>
