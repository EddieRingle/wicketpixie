<div id="admin-affiliates">
	<h3>Donate to WicketPixie</h3>					
	Waiting for new Paypal form code.
	<?php
	$feeds= array(
		array( 'feed' => 'http://feeds.pirillo.com/WicketPixieRecommendations',
			   'title' => 'Chris Pirillo Uses and Recommends...' ),			
		array( 'feed' => 'http://feeds.pirillo.com/WicketPixieAnnouncements',
			   'title' => 'WicketPixie Announcements' ),
		array( 'feed' => 'http://feeds.pirillo.com/WicketPixieSocial',
			   'title' => 'Follow Chris Pirillo on...' ),
		); 
	require('simplepie.php');
	foreach( $feeds as $affiliate ) {
		$feed_path= $affiliate['feed'];
		$feed= new SimplePie( (string) $feed_path, ABSPATH . (string) 'wp-content/uploads/activity' );
		$feed->handle_content_type();
			if( $feed->data ) {
				echo '<h3>' . $affiliate['title'] .'</h3>';
				echo '<ul>';
				foreach( $feed->get_items() as $entry ) {
					echo '<li><a href="' . $entry->get_permalink(). '" rel="nofollow">' . $entry->get_title() . '</a></li>';
				}
				echo '</ul>';
			}
	}
	?>
</div>