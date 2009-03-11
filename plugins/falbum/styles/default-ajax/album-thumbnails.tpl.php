<!-- Start of FAlbum - album-thumbnails.tpl -->
<div id='falbum' class='falbum'>

<h3 class='falbum-title'>
	<a href='<?php echo $url; ?>'><?php echo $photos_label; ?></a>&nbsp;&raquo;&nbsp;<?php echo $album_title; ?>
</h3>
<div class='falbum-meta'>
	<div class='falbum-slideshowlink'>
		<a href='http://www.flickr.com/photos/???/set/<?php echo $album_id; ?>' target="_new"><?php echo $slide_show_label; ?></a>
	</div>
</div>

<?php if (isset($top_paging)): ?>
	<?php echo $top_paging; ?>
<?php endif; ?>

<?php foreach ($thumbnails as $row): ?>	
	<div class="falbum-tn-border-<?php echo $row['tsize']; ?>">
		<div class='falbum-thumbnail<?php echo $css_type_thumbnails ?>'>
			<a href='<?php echo $row['url']; ?>'>
				<img src='<?php echo $row['thumbnail']; ?>' alt='<?php echo $row['title']; ?>' title='<?php echo $row['title']; ?>' />
			</a>
		</div>
	</div>
<?php endforeach; ?>

<?php if (isset($bottom_paging)): ?>
	<?php echo $bottom_paging;?>
<?php endif; ?>



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


