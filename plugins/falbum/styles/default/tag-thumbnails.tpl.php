<!-- Start of FAlbum -->
<div id='falbum' class='falbum'>

<h3 class='falbum-title'>
	<?php if (isset($recent_label)): ?>
	<a href='<?php echo $url; ?>'><?php echo $photos_label; ?></a>&nbsp;&raquo;&nbsp;<?php echo $recent_label; ?>
	<?php else: ?>
	<a href='<?php echo $url; ?>'><?php echo $photos_label; ?></a>&nbsp;&raquo;&nbsp;<a href='<?php echo $tag_url; ?>'><?php echo $tags_label; ?></a>:&nbsp;<?php echo $tags; ?>
	<?php endif; ?>
</h3>
<div class='falbum-meta'>
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


