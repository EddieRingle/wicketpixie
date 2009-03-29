<?php 

define('FALBUM', true);
define('FALBUM_STANDALONE', true);

require_once (dirname(__FILE__).'/../falbum.php');

if (file_exists(get_template_directory()."/falbum.php")) {
	
	include_once(get_template_directory()."/falbum.php");

} else { 

get_header(); 
?>

<script
	type="text/javascript"
	src="<?php echo get_settings('siteurl'); ?>/wp-content/themes/wicketpixie/plugins/falbum/res/overlib.js"></script>
<script
	type="text/javascript"
	src="<?php echo get_settings('siteurl'); ?>/wp-content/themes/wicketpixie/plugins/falbum/res/jquery-c.js"></script>
<script
	type="text/javascript"
	src="<?php echo get_settings('siteurl'); ?>/wp-content/themes/wicketpixie/plugins/falbum/res/falbum-c.js"></script>

<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

<div id="content" class="narrowcolumn">

	<div id="falbum-wrapper">
		 <?php 			 
		 $falbum->show_photos(); 			 
		 ?>
	 </div>
	 
</div>

<?php 

get_sidebar();

get_footer(); 

}
