<?php
/*
Plugin Name: Nofollow Navigation
Plugin URI: http://chris.pirillo.com/wicketpixie
Description: Add nofollow to the generated page links.
Version: 0.0.1
Author: Eddie Ringle, based off of Page List Plus by Tim Holt
Author URI: http://eddieringle.com
*/
// The Hook
add_filter('wp_list_pages', 'add_nofollow');
// Replacement time \o/
function add_nofollow($output) {	
	global $wpdb;
	$posts_table = $wpdb->prefix . 'posts';
	$no_follow_link_data = mysql_query("SELECT post_title FROM " . $posts_table . " WHERE post_status = 'publish'");
	while ($row = mysql_fetch_assoc($no_follow_link_data)) {
		extract($row);
		$post_title = wptexturize($post_title);
		$output = str_replace('>' . $post_title . '<', ' rel="nofollow">' . $post_title . '<', $output);
	}
	return $output;
}
?>
