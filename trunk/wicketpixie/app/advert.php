<div id="admin-affiliates">
	<h3>Donate to WicketPixie</h3>					
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">  
		<input type="hidden" name="cmd" value="_s-xclick"> 
		<input type="hidden" name="hosted_button_id" value="2767322"> 
		<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt=""> <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>
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