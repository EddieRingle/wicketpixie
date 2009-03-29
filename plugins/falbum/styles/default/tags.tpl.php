<!-- Start of FAlbum -->
<div id='falbum' class='falbum'>

<h1 class='falbum-title'>
	<a href='<?php echo $home_url; ?>'><?php echo $home_label; ?></a>&nbsp;&raquo;&nbsp;<?php echo $tags_label; ?>	
</h1>

<div class='falbum-meta'>
</div>

<div class='falbum-cloud'>
	<?php foreach ($tags as $tag): ?>
		<a href='<?php echo $tag['url']; ?>' class='<?php echo $tag['class']; ?>' title='<?php echo $tag['title']; ?>'><?php echo $tag['name']; ?></a>&nbsp;
	<?php endforeach; ?>	
</div>

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


