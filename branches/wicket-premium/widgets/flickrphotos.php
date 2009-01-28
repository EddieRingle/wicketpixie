<?php
$total= 5;
echo '<div class="widget">';
echo '<h3><img src="http://www.google.com/s2/favicons?domain=flickr.com" alt="Flickr Photos" />Flickr Photos</h3>';
echo '<ul>';
$items= SourceAdmin::get_feed( "feed://api.flickr.com/services/feeds/photos_public.gne?id=45643934@N00&lang=en-us&format=atom" );
		foreach( $items as $item ) {
			if( $i != $total ) {
				echo '<li><a href="' . $item['link'] . '" title="' . $item['title'] . '">' . $item['title'] . '</a></li>';
$i++;
}
}
echo '</ul>';
echo '</div>';
