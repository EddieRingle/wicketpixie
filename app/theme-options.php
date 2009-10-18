<?php
/**
 * WicketPixie v1.3
 * (c) 2006-2009 Eddie Ringle,
 *               Chris J. Davis,
 *               Dave Bates
 * Provided by Chris Pirillo
 *
 * Licensed under the New BSD License.
 */
$theme_options = array (
	array(  
		"name" => "Background Color",
		"description" => "The color that fills the screen around the content area.",
		"id" => 'wicketpixie_theme_body_bg_color',
		"std" => "#270b05",
		"type" => "text"),
		
	array(  
		"name" => "Background Image",
		"description" => "Optional background image.",
		"id" => 'wicketpixie_theme_body_bg_image',
		"std" => "solidwood-dark.jpg",
		"type" => "file"),
		
	array(  
		"name" => "Background Image Repeat",
		"description" => "Specify how you would like the background image positioned.",		
		"id" => 'wicketpixie_theme_body_bg_repeat',
		"std" => "repeat-x",
		"type" => "select",
		"options" => array("no-repeat", "repeat", "repeat-x", "repeat-y")),	
		
	array(  
		"name" => "Background Image Position",
		"description" => "Have the background scroll with the page, or stay in one place.",		
		"id" => 'wicketpixie_theme_body_bg_position',
		"std" => "fixed",
		"type" => "select",
		"options" => array("fixed", "scroll")),

	array(  
		"name" => "Body Font Family",
		"description" => "The main font used through-out the content areas.",
		"id" => 'wicketpixie_theme_body_font',
		"std" => "Lucida Grande, Arial, Verdana, sans-serif",
		"type" => "select",
		"options" => array("Lucida Grande, Arial, Verdana, sans-serif", "Helvetica, Arial, Verdana, sans-serif", "Arial, Verdana, sans-serif", "Verdana, Arial sans-serif", "Georgia, Times New Roman, Times, serif", "Times New Roman, Georgia, Times, serif", "Times, Times New Roman, Georgia, serif")),

	array(  
		"name" => "Headings Font Family",
		"description" => "The font used for post titles, section headings and the logo.",
		"id" => 'wicketpixie_theme_headings_font',
		"std" => "Georgia, Times New Roman, Times, serif",
		"type" => "select",
		"options" => array("Georgia, Times New Roman, Times, serif", "Times New Roman, Georgia, Times, serif", "Times, Times New Roman, Georgia, serif", "Lucida Grande, Arial, Verdana, sans-serif", "Helvetica, Arial, Verdana, sans-serif", "Arial, Verdana, sans-serif", "Verdana, Arial sans-serif")),

    array(  
		"name" => "Header Font Size",
		"description" => "The font size of the header logo, in px.",
		"id" => 'wicketpixie_theme_header_size',
		"std" => "40",
		"type" => "text"),

	array(  
		"name" => "Logo Text Color",
		"description" => "The color of the logo text.",
		"id" => 'wicketpixie_theme_logo_color',
		"std" => "#fff0a5",
		"type" => "text"),
		
	array(  
		"name" => "Status/Description Text Color",
		"description" => "The color of the status update or description text in the header.",
		"id" => 'wicketpixie_theme_description_color',
		"std" => "#9e6839",
		"type" => "text"),
		
	array(  
		"name" => "Titles/Content Headings Color",
		"description" => "The color of post titles and headings in the content area.",
		"id" => 'wicketpixie_theme_titles_color',
		"std" => "#b64926",
		"type" => "text"),	
		
	array(  
		"name" => "Sidebar Headings Color",
		"description" => "The color of headings in the sidebar.",
		"id" => 'wicketpixie_theme_sidebar_headings_color',
		"std" => "#8e2800",
		"type" => "text"),

	array(  
		"name" => "Content Links Color",
		"description" => "The color of links in the content area (main column).",
		"id" => 'wicketpixie_theme_content_links_color',
		"std" => "#8e2800",
		"type" => "text"),

	array(  
		"name" => "Sidebar Links Color",
		"description" => "The color of links in the sidebar.",
		"id" => 'wicketpixie_theme_sidebar_links_color',
		"std" => "#333",
		"type" => "text"),
	array(
	    "name" => "Max Image Width in Posts",
	    "description" => "Set the maximum width (in pixels) of images in post contents.",
	    "id" => 'wicketpixie_theme_post_max_image_width',
	    "std" => "340",
	    "type" => "text")
);

class ThemeOptions extends AdminPage
{
    function __construct()
    {
        parent::__construct('Theme Options','theme-options.php','wicketpixie-admin.php',array($GLOBALS['theme_options']));
    }
    
    function after_form()
    {
        ?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo $this->filename; ?>" style="padding-bottom:40px;">
            <?php wp_nonce_field('wicketpixie-settings'); ?>
			<input name="reset" type="submit" value="Reset Options" class="button-secondary" />
			<input type="hidden" name="action" value="reset" />
		</form>
        <?php
    }
    
    function request_check()
    {
        parent::request_check();
        
        if (isset($_POST['action']) && $_POST['action'] == 'reset') {
		    check_admin_referer('wicketpixie-settings');
           	foreach ($GLOBALS['theme_options'] as $value) {
           	    if (get_option($this->$value['id'])) {
                   	delete_option( $this->$value['id'] );
                }
		    }
		    wp_redirect($_SERVER['PHP_SELF'] ."?page=".$this->filename."&reset=true");
        }
    }
    
    function save_hook()
    {
        if ( $_POST['completed'] == 'true' && $_FILES['wicketpixie_theme_body_bg_image']['tmp_name'] != '' ) {
			$new_name= $_FILES['wicketpixie_theme_body_bg_image']['name'];
			$new_home= TEMPLATEPATH . '/images/backgrounds/' . $new_name;
			if( move_uploaded_file( $_FILES['wicketpixie_theme_body_bg_image']['tmp_name'], $new_home ) ) {
                update_option('wicketpixie_theme_body_bg_image',$new_name);
			} else {
                error_log( 'No joy, no uploaded file' );
			}
		}
		
		if ( $_POST['saved_images'] != '' ) {
            update_option('wicketpixie_theme_body_bg_image',$_POST['saved_images']);
		}
    }
    
    function extra_types_html($value,$checkdata)
    {
        if( $value['type'] == 'file' ) { ?>
					<?php
						$image_check= get_option('wicketpixie_theme_body_bg_image');
						if( isset( $image_check ) && $image_check != '' ) {
							$image_check= get_option('wicketpixie_theme_body_bg_image');
						} else {
							$image_check= 'false';
						}
					?>
					<?php if( get_option($value['id'] ) ) { ?>
					<input type="hidden" name="<?php echo $value['id']; ?>" value="<?php echo get_option($value['id'] ); ?>">				
					<?php } ?>
                    <?php
                    $uploaded= opendir( TEMPLATEPATH .'/images/backgrounds/' );
                    $images= array();
                    while ( $file= readdir( $uploaded ) ) {
                        $pattern = "/[\"‘]?([^\"’]?.*(png|jpg|gif))[\"’]?/i";
                        if( preg_match($pattern, $file ) ) {
                            $images[]= $file;
                        }
                    }
                    ?>
					<select name="saved_images" id="saved_images">
						<option value="">Choose an image</option>
						<?php foreach( $images as $image ) { ?>
						<option value="<?php echo $image; ?>" <?php if(get_option('wicketpixie_theme_body_bg_image') == $image) { echo 'selected="selected"'; } ?>><?php echo $image; ?></option>
						<?php } ?>
					</select>	Current:
					<?php 
						if( $image_check== 'false' ) { 
							echo 'None';
						} elseif( $image_check != 'false' ) {
						?>
						<a href="<?php echo TEMPLATEPATH .'/images/backgrounds/'. get_option($value['id']); ?>" title="<?php echo get_option($value['id']); ?>"><?php echo get_option($value['id']); ?></a>
						<?php
						} else {
							echo 'None'; 
						} ?>
					<p><input type="file" id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>">
					<input type="hidden" name="MAX_FILE_SIZE" value="1500000">
					<input type="hidden" name="completed" value="true"></p>
					<p><input type="checkbox" value="<?php echo get_option('wicketpixie_theme_no_image'); ?>" name="wicketpixie_theme_no_image" <?php if(get_option('wicketpixie_theme_no_image') == 'true') { echo 'checked="checked"'; } else { echo ''; } ?>> No Background Image</p>
	<?php
		} else {
		    parent::extra_types_html($value,$checkdata);
		}
    }
    
    function __destruct()
    {
        parent::__destruct();
        unset($GLOBALS['theme_options']);
    }
}

function wicketpixie_wp_head() { ?>
	<?php
	global $theme_options;
	foreach ( $theme_options as $value ) {
	    if ( get_option($value['id']) === FALSE ) { 
			$$value['id'] = $value['std']; 
		} else { 
			$$value['id'] = get_option($value['id']); 
		} 
	}
	
	$image_check= get_option('wicketpixie_theme_body_bg_image');
	if( isset( $image_check ) && $image_check != '' ) {
		$image_check= get_option('wicketpixie_theme_body_bg_image');
	} else {
		$image_check= 'false';
	}
	
	?>

	<style type="text/css">
		body { font-family: <?php echo $wicketpixie_theme_body_font; ?>; background: <?php echo $wicketpixie_theme_body_bg_color; ?> <?php if( get_option('wicketpixie_theme_no_image') != 'true' ) { ?>url("<?php bloginfo('template_directory'); ?>/images/backgrounds/<?php echo $wicketpixie_theme_body_bg_image; ?>") <?php echo $wicketpixie_theme_body_bg_position; ?> <?php echo $wicketpixie_theme_body_bg_repeat; ?> 50% 0<?php } ?>; }
		#logo { font-family: <?php echo $wicketpixie_theme_headings_font; ?>; color: <?php echo $wicketpixie_theme_logo_color; ?>; }
		#logo a:link, #logo a:visited, #logo a:active { color: <?php echo $wicketpixie_theme_logo_color; ?>; }
		#logo a:hover { color: #fff; }
		#description, #status p, #status a:link, #status a:active, #status a:visited { color: <?php echo $wicketpixie_theme_description_color; ?>; }
		.content a:link, .content a:visited, .content a:active { color: <?php echo $wicketpixie_theme_content_links_color; ?>; }
		.content a:hover { color: #000; border-bottom: 1px solid <?php echo $wicketpixie_theme_content_links_color; ?>; }
		.content h1, .content h2, .content h3, .content h4, .content h5, .content h6 { color: <?php echo $wicketpixie_theme_titles_color; ?>; font-family: <?php echo $wicketpixie_theme_headings_font; ?>; font-weight: bold; }
		.content h1 a:link, .content h1 a:visited, .content h1 a:active, .content h2 a:link, .content h2 a:visited, .content h2 a:active, .content h3 a:link, .content h3 a:visited, .content h3 a:active, .content h4 a:link, .content h4 a:visited, .content h4 a:active, .content h5 a:link, .content h5 a:visited, .content h5 a:active, .content h6 a:link, .content h6 a:visited, .content h6 a:active { color: <?php echo $wicketpixie_theme_titles_color; ?>; }
		.content h1 a:hover, .content h2 a:hover, .content h3 a:hover, .content h4 a:hover, .content h5 a:hover, .content h6 a:hover { color: #000; }
		#content .comment h3 a:link, #content .comment h3 a:active, #content .comment h3 a:visited { color: <?php echo $wicketpixie_theme_content_links_color; ?>; }
		#content .comment h3 a:hover { color: #000; border-bottom: 1px solid <?php echo $wicketpixie_theme_content_links_color; ?>; }
		#content .comment h5 { font-family: <?php echo $wicketpixie_theme_body_font; ?>; }
        #content img { max-width: <?php echo $wicketpixie_theme_post_max_image_width; ?>; }
		#comment-form input, #comment-form textarea { font-family: <?php echo $wicketpixie_theme_body_font; ?>; }
		#sidebar a:link, #sidebar a:visited, #sidebar a:active { color: <?php echo $wicketpixie_theme_sidebar_links_color; ?>; }
		#sidebar a:hover { color: #000; }
		#sidebar h1, #sidebar h2, #sidebar h3, #sidebar h3 a:link, #sidebar h3 a:visited, #sidebar h3 a:active, #sidebar h4, #sidebar h5, #sidebar h6 { color: <?php echo $wicketpixie_theme_sidebar_headings_color; ?>; font-family: <?php echo $wicketpixie_theme_headings_font; ?>; font-weight: bold; }
		#sidebar h5 { font-family: <?php echo $wicketpixie_theme_body_font; ?>; }
	</style>
<?php }

function wicketpixie_admin_head() {
	$path= get_bloginfo('template_directory');
	echo '<script type="text/javascript" src="' . $path . '/js/colorpicker.js"></script>';
	echo '<link rel="stylesheet" href="' . $path . '/css/admin.css" type="text/css" media="screen, projection" />';
?>
    <?php ?>
    <script src="<?php echo get_bloginfo('template_directory'), '/contrib/iphone-style-checkboxes/iphone-style-checkboxes.js'; ?>" type="text/javascript" charset="utf-8"></script>
    <link rel="stylesheet" href="<?php echo get_bloginfo('template_directory'), '/contrib/iphone-style-checkboxes/style.css'; ?>" type="text/css" media="screen" charset="utf-8"> <?php ?>
	<script type="text/javascript">
		jQuery(function($) {
            $("#wicketpixie_theme_logo_color").attachColorPicker();
            $("#wicketpixie_theme_body_bg_color").attachColorPicker();
            $("#wicketpixie_theme_description_color").attachColorPicker();
            $("#wicketpixie_theme_titles_color").attachColorPicker();
            $("#wicketpixie_theme_sidebar_headings_color").attachColorPicker();
            $("#wicketpixie_theme_content_links_color").attachColorPicker();
            $("#wicketpixie_theme_sidebar_links_color").attachColorPicker();
        });
	</script>
	<script>
	jQuery(function($) {
		$('#explaintext').click(function(){
			$('#explain').toggle();
			return false;
		});
		$(document).ready(function() {
		    $('#admin-options :checkbox').iphoneStyle();
		});
	});
	</script>
	<style type="text/css">
	#ColorPickerDiv 
	{
	    display: block;
	    display: none;
	    position: relative;
	    border: 1px solid #777;
	    background: #fff
	}

	#ColorPickerDiv TD.color
	{
		cursor: pointer;
		font-size: xx-small;
		font-family: 'Arial' , 'Microsoft Sans Serif';
	}
	#ColorPickerDiv TD.color label
	{
		cursor: pointer;
	}

	.ColorPickerDivSample
	{
		margin: 0 0 0 4px;
		border: solid 1px #000;
		padding: 0 10px;	
		position: relative;
		cursor: pointer;
	}
	
	#explain
	{
		display:none;
		background: #eee;
		padding: 5px;
	}
	</style>
<?php
}
?>
