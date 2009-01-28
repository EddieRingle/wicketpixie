<?php
$total= 5;
echo '<div class="widget">';
echo '<h3><img src="http://www.google.com/s2/favicons?domain=chrisjdavis.org" alt="Sillyness, werd." />Sillyness, werd.</h3>';
echo '<ul>';
$items= SourceAdmin::get_feed( "http://chrisjdavis.org/atom/1" );
		foreach( $items as $item ) {
			if( $i != $total ) {
				echo '<li><a href="' . $item['link'] . '" title="' . $item['title'] . '">' . $item['title'] . '</a></li>';
$i++;
}
}
echo '</ul>';
echo '</div>';
