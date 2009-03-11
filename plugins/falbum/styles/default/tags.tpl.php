<!-- Start of FAlbum -->
<div id='falbum' class='falbum'>

<h3 class='falbum-title'>
	<a href='<?php echo $home_url; ?>'><?php echo $home_label; ?></a>&nbsp;&raquo;&nbsp;<?php echo $tags_label; ?>	
</h3>

<div class='falbum-meta'>
</div>

<div class='falbum-cloud'>
	<?php foreach ($tags as $tag): ?>
		<a href='<?php echo $tag['url']; ?>' class='<?php echo $tag['class']; ?>' title='<?php echo $tag['title']; ?>'><?php echo $tag['name']; ?></a>&nbsp;
	<?php endforeach; ?>	
</div>

</div>
<!-- End of Falbum -->


