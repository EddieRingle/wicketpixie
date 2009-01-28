<?php
/*
Plugin Name: Random Posts
*/

function query_random_posts($query) {
	return query_posts($query . '&random=true');	
}

class RandomPosts {
	function orderby($orderby) {
		if ( get_query_var('random') == 'true' )
			return "RAND()";
		else
			return $orderby;
	}
	function register_query_var($vars) {
		$vars[] = 'random';
		return $vars;
	}
}
add_filter( 'posts_orderby', array('RandomPosts', 'orderby') );
add_filter( 'query_vars', array('RandomPosts', 'register_query_var') );
?>