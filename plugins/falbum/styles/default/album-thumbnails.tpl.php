<!-- Start of FAlbum -->
<div id='falbum' class='falbum'>

<h3 class='falbum-title'>
	<a href='<?php echo $url; ?>'><?php echo $photos_label; ?></a>&nbsp;&raquo;&nbsp;<?php echo $album_title; ?>
</h3>
<div class='falbum-meta'>
	<div class='falbum-slideshowlink'>
		<a href='#' onclick="window.open('http://www.flickr.com/slideShow/index.gne?set_id=<?php echo $album_id; ?>','slideShowWin','width=500,height=500,top=150,left=70,scrollbars=no, status=no, resizable=no')"><?php echo $slide_show_label; ?></a>
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

</div>
<!-- End of Falbum -->


