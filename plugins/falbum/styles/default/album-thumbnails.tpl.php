<!-- Start of FAlbum -->
<div id='falbum' class='falbum'>

<h1 class='falbum-title'>
	<a href='<?php echo $url; ?>'><?php echo $photos_label; ?></a>&nbsp;&raquo;&nbsp;<?php echo $album_title; ?>
</h1>
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


