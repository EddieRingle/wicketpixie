<!-- Start of FAlbum - tags.tpl -->
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


<!-- JS Start -->
<script type='text/javascript'>
//<!--

falbum.page_title('<?php echo $page_title; ?>'); 

falbum.set_remote_url('<?php echo $remote_url; ?>');
falbum.set_url_root('<?php echo $url_root; ?>');
	
falbum.ajax_init();
 
//-->
</script>
<!-- JS End -->

</div>




<!-- End of Falbum -->


