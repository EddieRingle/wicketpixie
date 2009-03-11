<!-- Start of FAlbum - photo.tpl -->

<div id='falbum' class='falbum'>

<?php if ($can_edit == true): ?>
	<div id='falbum-post-helper-switch'><img src="<?php echo get_settings('siteurl'); ?>/wp-content/plugins/falbum/styles/default/images/wrench.png" alt="Post Helper" title="Post Helper" width="16" height="16" /></div>
	<div id='falbum-post-helper-block' style="display: none">
		<img id="falbum-post-helper-block-close" src="<?php echo get_settings('siteurl'); ?>/wp-content/plugins/falbum/styles/default/images/cross.png" alt="Close" title="Close" width="16" height="16" />
	    <div id='falbum-post-helper-block-rb'>
		<table>
		<tr><td valign="top"><?php echo fa__('Size'); ?>:</td><td>
		<?php foreach ($sizes as $size): ?>					
		<input type="radio" name="size" value="<?php echo $size['value']; ?>" id="size-<?php echo $size['value']; ?>"> <label for="size-<?php echo $size['value']; ?>"><?php echo $size['title']; ?></label><br />
		<?php endforeach; ?>	
		</td></tr>			
	    <tr><td valign="top"><?php echo fa__('Position'); ?>:</td><td>
	    <input type="radio" name="position" value="l" checked="checked" id="position-l"> <label for="position-l"><?php echo fa__('Float left'); ?></label><br />
		<input type="radio" name="position" value="r" id="position-r"> <label for="position-r"><?php echo fa__('Float right'); ?></label><br />
		<!--<input type="radio" name="position" value="c" id="position-c"> <label for="position-c"><?php echo fa__('Center'); ?></label><br />-->
		</td></tr>
		<tr><td valign="top"><?php echo fa__('Link to'); ?>:</td><td>
	    <input type="radio" name="linkto" value="p" checked="checked" id="linkto-photo"> <label for="linkto-photo"><?php echo fa__('Photo'); ?></label><br />
		<input type="radio" name="linkto" value="i" id="linkto-index"> <label for="linkto-index"><?php echo fa__('Index page'); ?></label><br />
		</td></tr>
		</table>
		</div>
	<br />
	<?php echo fa__('Copy and paste the following line into your post'); ?>:
	<div id='falbum-post-helper-value'><?php echo $post_value; ?></div> 
	</div> 
<?php endif; ?>	

<h3 class='falbum-title'>
	<a href='<?php echo $home_url; ?>'><?php echo $home_label; ?></a>&nbsp;&raquo;&nbsp;<a href='<?php echo $title_url; ?>'><?php echo $title_label; ?></a>&nbsp;&raquo;&nbsp;<span id="falbum-photo-title"><?php echo $title; ?></span>
</h3>

<div class='falbum-date-taken'><?php echo $date_taken; ?></div>

<?php if (isset($tags)): ?>
<div class='falbum-tags-block'>
	<span class="falbum-tags-label"><a href='<?php echo $tags_url; ?>'><?php echo $tags_label; ?></a>:&nbsp;</span><span class="falbum-tags"><?php while($tag = current($tags)): ?><a href='<?php echo $tag['url']; ?>'><?php echo $tag['tag']; ?></a><?php if ($this->has_next($tags)): ?>, <?php endif;?><?php next($tags); endwhile; ?></span>
</div>
<div class="falbum-clear-left"></div>
<?php endif; ?>

<?php if (isset($notes)): ?>
<map id='imgmap'>
	<?php foreach ($notes as $note): ?>
		<area alt='' title="<?php echo $note['title']; ?>" nohref='nohref' shape='rect' coords="<?php echo $note['coords']; ?>" />
	<?php endforeach; ?>
</map>
<?php endif; ?>

<div class='falbum-photo-block'>

	<div class='falbum-photo<?php echo $css_type_photo; ?>'>
		<a href='<?php echo $photo_url; ?>' title="<?php echo $photo_title_label; ?>" id="falbum_photo_link">
			<img src='<?php echo $image; ?>' alt='' <?php echo $usemap; ?> id='flickr-photo' class='annotated' width='<?php echo $photo_width; ?>'/>
		</a>
	</div> 

	<div class='falbum-nav'>
		<?php if (isset($prev_button)): ?>
			<?php echo $prev_button; ?>&nbsp;&nbsp;
		<?php endif; ?> 
		
		<?php echo $return_button; ?>
		
		<?php if (isset($next_button)): ?>
			&nbsp;&nbsp;<?php echo $next_button; ?>
		<?php endif; ?>
	</div> 	
</div> 

<div class='falbum-description'>
	<p id="falbum-photo-desc"><?php echo $description; ?></p>
</div>

<div class='falbum-meta'>

	<?php if (isset($sizes_label)): ?>
	<div class='falbum-photoSizesBlock'>
		<?php echo $sizes_label; ?>: 
		<?php foreach ($sizes as $size): ?>					
		<a href='<?php echo $size['image']; ?>' class='falbum-photoSizes' title="<?php echo $size['title']; ?>"><?php echo $size['display']; ?></a>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>

	<p>
		<a href='http://www.flickr.com/photos/<?php echo $nsid; ?>/<?php echo $photo; ?>'><?php echo $flickr_label; ?></a>
	</p>

	<?php if (isset($exif_label)): ?>
	<div id='exif' class='falbum-exif'>
		<a href="javascript:falbum.showExif('<?php echo $exif_data; ?>')"><?php echo $exif_label; ?></a>
	</div>
	<?php endif; ?>
	
	<?php if (isset($comments)): ?>
	<div class="falbum-comment-block">
	<span class="falbum-comment-title"><?php echo fa__('Comments'); ?>:</span>
	<?php foreach ($comments as $comment): ?>
		<div class="falbum-comment-author"><a href="<?php echo $comment['author_url']; ?>"><?php echo $comment['author_name']; ?></a></div>
		<div class="falbum-comment"><?php echo $comment['text']; ?></div>
	<?php endforeach; ?>
	</div>
	<?php endif; ?>	

	
</div> 


<!-- JS Start -->
	
<script type='text/javascript'>
//<!--
	
<?php if ($can_edit == true): ?>	
	falbum.set_photo_id('<?php echo $photo_id; ?>');
	falbum.set_title('<?php echo preg_replace('/[\n|\r]/','',htmlspecialchars($title, ENT_QUOTES)); ?>');
	falbum.set_desc('<?php echo preg_replace('/[\n|\r]/','',htmlspecialchars($description_orig, ENT_QUOTES)); ?>');
	falbum.set_nodesc('<?php echo $no_description_text; ?>');
	
	falbum.set_post_value('<?php echo $post_value; ?>');
	
	falbum.makeEditable('falbum-photo-desc');
	falbum.makeEditable('falbum-photo-title');   		
	falbum.enable_post_helper();
	
	
<?php endif; ?>

falbum.page_title('<?php echo $page_title; ?>'); 

falbum.prefetch('<?php echo $next_image; ?>');
	
falbum.set_remote_url('<?php echo $remote_url; ?>');
falbum.set_url_root('<?php echo $url_root; ?>');
	
falbum.ajax_init();
falbum.anno_init();
 
//-->
</script>
<!-- JS End -->

</div>
<!-- End of Falbum -->
