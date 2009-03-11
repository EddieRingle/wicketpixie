<!-- Start of FAlbum -->
<div id='falbum' class='falbum'>

<?php if (isset($top_paging)): ?>
	<?php echo $top_paging; ?>
<?php endif; ?>

<?php foreach ($albums as $row): ?>	
	<div class='falbum-album'>
		<div class="falbum-tn-border-<?php echo $row['tsize']; ?>">
			<div class='falbum-thumbnail<?php echo $css_type_thumbnails ?>'>
				<a href='<?php echo $row['url']; ?>' title='<?php echo $row['title']; ?>'>
					<img src='<?php echo $row['thumbnail']; ?>' alt='' />
				</a>
			</div>
		</div>	
		<h3 class='falbum-title'>
			<a href='<?php echo $row['url']; ?>' title='<?php echo $row['title_d'] ?>'><?php echo $row['title']; ?></a>
			<?php if (isset($row['tags_url'])): ?>
				/&nbsp;<a href='<?php echo $row['tags_url']; ?>' title='<?php echo $row['tags_title_d'] ?>'><?php echo $row['tags_title']; ?></a>
			<?php endif; ?>
		</h3>	
		<div class='falbum-meta'>
			<?php echo $row['meta'] ?>
		</div>	
		<div class='falbum-album-description'>
			<?php echo $row['description'] ?>
		</div>	
	</div>
<?php endforeach; ?>


<?php if (isset($bottom_paging)): ?>
	<?php echo $bottom_paging;?>
<?php endif; ?>

</div>
<!-- End of Falbum -->

