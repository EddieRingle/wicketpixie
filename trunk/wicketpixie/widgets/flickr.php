<?php
$total= 5;
echo '<div class="widget">';
echo '<h3><img src="http://localhost:8888/wordpress_beta/wp-content/themes/wicketpixie/images/favicache/d0f493aa41438dab99dcd78a16917ac0.ico" alt="Flickr" />Flickr</h3>';
echo '<ul>';
$items= SourceAdmin::get_feed( "feed://api.flickr.com/services/feeds/photos_public.gne?id=45643934@N00&lang=en-us&format=rss_200" );
		foreach( $items as $item ) {
			if( $i != $total ) {
				echo '<li><a href="' . $item['link'] . '" title="' . $item['title'] . '">' . $item['title'] . '</a></li>';
$i++;
}
}
echo '</ul>';
echo '</div>';
