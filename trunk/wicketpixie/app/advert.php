<div id="admin-affiliates">
	<h3>Donate to WicketPixie</h3>					
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
	<input type="hidden" name="cmd" value="_donations">
	<input type="hidden" name="business" value="affiliate@lockergnome.com">
	<input type="hidden" name="lc" value="US">
	<input type="hidden" name="item_name" value="WicketPixie 2.0">
	<input type="hidden" name="no_shipping" value="2">
	<input type="hidden" name="currency_code" value="USD">
	<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHosted">
	<input type="image" src="https://www.paypalobjects.com/WEBSCR-550-20081223-1/en_US/i/bnr/vertical_solution_PPeCheck.gif" border="0" name="submit" alt="">
	<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
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
	require_once('simplepie.php');
	foreach( $feeds as $affiliate ) {
		$feed_path= $affiliate['feed'];
		$feed= new SimplePie( (string) $feed_path, ABSPATH . (string) 'wp-content/uploads/activity/' );
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