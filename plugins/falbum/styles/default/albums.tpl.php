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
		<h1 class='falbum-title'>
			<a href='<?php echo $row['url']; ?>' title='<?php echo $row['title_d'] ?>'><?php echo $row['title']; ?></a>
			<?php if (isset($row['tags_url'])): ?>
				/&nbsp;<a href='<?php echo $row['tags_url']; ?>' title='<?php echo $row['tags_title_d'] ?>'><?php echo $row['tags_title']; ?></a>
			<?php endif; ?>
		</h1>	
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

					<div style="padding-top: 30px" align="center">
						<script type="text/javascript"><!--
                                   google_ad_client = "pub-7561297527511227";
                                   google_ad_width = 300;
                                   google_ad_height = 250;
                                   google_ad_format = "300x250_as";
                                   google_ad_type = "text_image";
                                   google_color_border = "ffffff";
                                   google_color_bg = "ffffff";
                                   google_color_link = "0000FF";
                                   google_color_url = "0000FF";
                                   google_color_text = "000000";
                                   google_alternate_ad_url = 'http://chris.pirillo.com/wp-content/adsense_blocks/adsense_alt_300x250.php';
                              //--></script>
                                   <script type="text/javascript"
                                     src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                                   </script>
					</div>

</div>
<!-- End of Falbum -->

