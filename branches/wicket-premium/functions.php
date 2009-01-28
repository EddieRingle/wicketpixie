<?php
include_once( ABSPATH . 'wp-content/themes/wicketpixie/widgets/sources.php' );

define('WIK_VERSION','1.0.4');

function collect() {
	global $wpdb;
	$table= $wpdb->prefix . 'wik_sources';
	$sources= $wpdb->get_results( "SELECT * FROM $table" );
	if( is_array( $sources ) ) {
		return $sources;
	} else {
		return array();
	}
}

if ( function_exists('register_sidebar') )
	register_sidebar(array('name'=>'sidebar1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));	
	register_sidebar(array('name'=>'sidebar2',		
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	register_sidebar(array('name'=>'sidebar3',		
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	register_sidebar(array('name'=>'sidebar4',		
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	register_sidebar(array('name'=>'sidebar5',		
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	register_sidebar(array('name'=>'sidebar6',		
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	
if( function_exists( 'register_sidebar_widget' ) ) {
	register_sidebar_widget('WicketPixie: Recent Posts','wicketpixie_recent_posts');
	register_sidebar_widget('WicketPixie: My Profiles','wicketpixie_my_profiles');
	foreach( collect() as $widget ) {
		$cleaned= strtolower( $widget->title );
		$cleaned= preg_replace( '/\W/', ' ', $cleaned );
		$cleaned= str_replace( " ", "", $cleaned );
		register_sidebar_widget( 'WicketPixie: ' . $widget->title, 'wicketpixie_' . $cleaned );
	}
}

function wicketpixie_recent_posts() {	
	include( ABSPATH . 'wp-content/themes/wicketpixie/widgets/recent-posts.php'); 
}

function wicketpixie_my_profiles() {
	include( ABSPATH . 'wp-content/themes/wicketpixie/widgets/my-profiles.php'); 
}

$themename = "WicketPixie";
$shortname = "wp";
$options = array (
    
	array(  
		"name" => "Background Color",
		"description" => "The color that fills the screen around the content area.",
		"id" => $shortname."_body_bg_color",
		"std" => "#270b05",
		"type" => "text"),
		
	array(  
		"name" => "Background Image",
		"description" => "Optional background image.",
		"id" => $shortname."_body_bg_image",
		"std" => "solidwood-dark.jpg",
		"type" => "file"),
		
	array(  
		"name" => "Background Image Repeat",
		"description" => "Specify how you would like the background image positioned.",		
		"id" => $shortname."_body_bg_repeat",
		"std" => "repeat-x",
		"type" => "select",
		"options" => array("no-repeat", "repeat", "repeat-x", "repeat-y")),	
		
	array(  
		"name" => "Background Image Position",
		"description" => "Have the background scroll with the page, or stay in one place.",		
		"id" => $shortname."_body_bg_position",
		"std" => "fixed",
		"type" => "select",
		"options" => array("fixed", "scroll")),

	array(  
		"name" => "Body Font Family",
		"description" => "The main font used through-out the content areas.",
		"id" => $shortname."_body_font",
		"std" => "Lucida Grande, Arial, Verdana, sans-serif",
		"type" => "select",
		"options" => array("Lucida Grande, Arial, Verdana, sans-serif", "Helvetica, Arial, Verdana, sans-serif", "Arial, Verdana, sans-serif", "Verdana, Arial sans-serif", "Georgia, Times New Roman, Times, serif", "Times New Roman, Georgia, Times, serif", "Times, Times New Roman, Georgia, serif")),

	array(  
		"name" => "Headings Font Family",
		"description" => "The font used for post titles, section headings and the logo.",
		"id" => $shortname."_headings_font",
		"std" => "Georgia, Times New Roman, Times, serif",
		"type" => "select",
		"options" => array("Georgia, Times New Roman, Times, serif", "Times New Roman, Georgia, Times, serif", "Times, Times New Roman, Georgia, serif", "Lucida Grande, Arial, Verdana, sans-serif", "Helvetica, Arial, Verdana, sans-serif", "Arial, Verdana, sans-serif", "Verdana, Arial sans-serif")),

	array(  
		"name" => "Logo Text Color",
		"description" => "The color of the logo text.",
		"id" => $shortname."_color_logo",
		"std" => "#fff0a5",
		"type" => "text"),
		
	array(  
		"name" => "Status/Description Text Color",
		"description" => "The color of the status update or description text in the header.",
		"id" => $shortname."_color_description",
		"std" => "#9e6839",
		"type" => "text"),
		
	array(  
		"name" => "Titles/Content Headings Color",
		"description" => "The color of post titles and headings in the content area.",
		"id" => $shortname."_color_titles",
		"std" => "#b64926",
		"type" => "text"),	
		
	array(  
		"name" => "Sidebar Headings Color",
		"description" => "The color of headings in the sidebar.",
		"id" => $shortname."_color_headings",
		"std" => "#8e2800",
		"type" => "text"),

	array(  
		"name" => "Content Links Color",
		"description" => "The color of links in the content area (main column).",
		"id" => $shortname."_color_links_content",
		"std" => "#8e2800",
		"type" => "text"),

	array(  
		"name" => "Sidebar Links Color",
		"description" => "The color of links in the sidebar.",
		"id" => $shortname."_color_links_sidebar",
		"std" => "#333",
		"type" => "text")
);

$settings= array(
			
	array(
		"name"	=>	"Show author on posts",
		"description"	=>	"Whether or not to show who wrote a particular post.",
		"id"	=>	$shortname . "_auth_credit",
		"std"	=>	1,
		"status" => 'checked',
		"type"	=>	'checkbox'),	
    array(
        "name"  =>  "Enable WicketPixie Notifications",
        "description"   => "Check this if you want WicketPixie to notify services like Ping.fm about your new blog posts, as configured on the WicketPixie Notifications page.",
		"id"    =>  $shortname."_notify",
        "std"   =>  1,
        "status"    => 'checked',
        "type"  => 'checkbox')
);

function wicketpixie_add_admin_footer() {
	echo "Thank you for using WicketPixie v".WIK_VERSION.", a free premium WordPress theme from <a href='http://chris.pirillo.com/'>Chris Pirillo</a>.<br/>";
}

function wicketpixie_add_admin() {
    global $themename, $shortname, $options, $settings;
	if ( isset( $_GET['page'] ) && $_GET['page'] == basename(__FILE__) ) {
        if ( 'save' == $_REQUEST['action'] ) {
            check_admin_referer('wicketpixie-settings');
            foreach ( $options as $value ) {
				update_option( $value['id'], $_REQUEST[ $value['id'] ] ); 
			}

            foreach ( $options as $value ) {
				if( isset( $_REQUEST[ $value['id'] ] ) ) { 
					update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); 
				} else { 
					delete_option( $value['id'] ); 
				} 
			}
			
			if( $_REQUEST['no_image'] ) {
				update_option('wp_body_bg_image', '0' );
			}
			
			if ( $_REQUEST['completed'] == 1 && $_FILES['wp_body_bg_image']['tmp_name'] != '' ) {
				$new_name= $_FILES['wp_body_bg_image']['name'];
				$new_home= ABSPATH . '/wp-content/themes/wicketpixie/images/backgrounds/' . $new_name;
				if( move_uploaded_file( $_FILES['wp_body_bg_image']['tmp_name'], $new_home ) ) {
					if ( get_option( 'wp_body_bg_image' ) ) {
						update_option( 'wp_body_bg_image', $new_name );
					} else {
						$deprecated= '';
					    $autoload= 'no';
						add_option( 'wp_body_bg_image', $new_name, $deprecated, $autoload );
					}
				} else {
					error_log( 'No joy, no uploaded file' );
				}
			}
						
			if( $_REQUEST['saved_images'] != '' ) {
				update_option( 'wp_body_bg_image', $_REQUEST['saved_images'] );
			}
			
			wp_redirect("themes.php?page=functions.php&saved=true");
			die;
		
		} elseif ( 'save_settings' == $_REQUEST['action'] ) {
			check_admin_referer('wicketpixie-settings');
	        foreach ( $settings as $value ) {
				update_option( $value['id'], $_REQUEST[ $value['id'] ] );
			}
			foreach ( $settings as $value ) {
				if( isset( $_REQUEST[ $value['id'] ] ) ) { 
					if( $value['type'] == 'checkbox' ) {
						if( $value['status'] == 'checked' ) {
							update_option( $value['id'], 1 );
						} else { 
							update_option( $value['id'], 0 ); 
						}	
					} elseif( $value['type'] != 'checkbox' ) {
						update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); 
					} else { 
						update_option( $value['id'], $_REQUEST[ $value['id'] ] ); 
					}
				}
			}

			wp_redirect("themes.php?page=functions.php&saved=true");
			die;

        } elseif( 'reset' == $_REQUEST['action'] ) {
			check_admin_referer('wicketpixie-settings');
           	foreach( $options as $value ) {
               	delete_option( $value['id'] ); 
			}
			wp_redirect("themes.php?page=functions.php&saved=true");
			die;
        }
    }

    add_theme_page($themename." Options", "WicketPixie Options", 'edit_themes', basename(__FILE__), 'wicketpixie_admin');

}

function wicketpixie_admin() {
    global $themename, $shortname, $options, $settings;

	$uploaded= opendir( ABSPATH . '/wp-content/themes/wicketpixie/images/backgrounds/' ); 
	$images= array();
		while ( $file= readdir( $uploaded ) ) { 
			$pattern = "/[\"‘]?([^\"’]?.*(png|jpg|gif))[\"’]?/i";
			if( preg_match($pattern, $file ) ) {
				$images[]= $file;
		}
	}

    if ( isset( $_REQUEST['saved'] ) ) echo '<div id="message" class="updated fade"><p><strong>'.__('Options saved.').'</strong></p></div>';
    if ( isset( $_REQUEST['reset'] ) ) echo '<div id="message" class="updated fade"><p><strong>'.__('Options reset.').'</strong></p></div>';
    
?>
<div class="wrap">
	
	<div id="admin-options">        
		<?php include_once('app/options-style.php'); ?>
		<hr />
		<?php include_once('app/options-settings.php'); ?>
	</div>
	<?php include_once('app/advert.php'); ?>
<?php
}

function wicketpixie_wp_head() { ?>
	<?php
	global $options;
	foreach ( $options as $value ) {
	    if ( get_settings( $value['id'] ) === FALSE ) { 
			$$value['id'] = $value['std']; 
		} else { 
			$$value['id'] = get_settings( $value['id'] ); 
		} 
	}
	
	$image_check= get_option( 'wp_body_bg_image' );
	if( isset( $image_check ) && $image_check != '' ) {
		$image_check= get_option('wp_body_bg_image');
	} else {
		$image_check= 'false';
	}
	
	?>
	
	<style type="text/css">
		body { font-family: <?php echo $wp_body_font; ?>; background: <?php echo $wp_body_bg_color; ?> <?php if( get_option('wp_body_bg_image') != 'false' ) { ?>url("<?php bloginfo('template_directory'); ?>/images/backgrounds/<?php echo $wp_body_bg_image; ?>") <?php echo $wp_body_bg_position; ?> <?php echo $wp_body_bg_repeat; ?> 50% 0<?php } ?>; }
		#logo { font-family: <?php echo $wp_headings_font; ?>; color: <?php echo $wp_color_logo; ?>; }
		#logo a:link, #logo a:visited, #logo a:active { color: <?php echo $wp_color_logo; ?>; }
		#logo a:hover { color: #fff; }
		#description, #status p, #status a:link, #status a:active, #status a:visited { color: <?php echo $wp_color_description; ?>; }
		.content a:link, .content a:visited, .content a:active { color: <?php echo $wp_color_links_content; ?>; }
		.content a:hover { color: #000; border-bottom: 1px solid <?php echo $wp_color_links_content; ?>; }
		.content h1, .content h2, .content h3, .content h4, .content h5, .content h6 { color: <?php echo $wp_color_titles; ?>; font-family: <?php echo $wp_headings_font; ?>; font-weight: bold; }
		.content h1 a:link, .content h1 a:visited, .content h1 a:active, .content h2 a:link, .content h2 a:visited, .content h2 a:active, .content h3 a:link, .content h3 a:visited, .content h3 a:active, .content h4 a:link, .content h4 a:visited, .content h4 a:active, .content h5 a:link, .content h5 a:visited, .content h5 a:active, .content h6 a:link, .content h6 a:visited, .content h6 a:active { color: <?php echo $wp_color_titles; ?>; }
		.content h1 a:hover, .content h2 a:hover, .content h3 a:hover, .content h4 a:hover, .content h5 a:hover, .content h6 a:hover { color: #000; }
		#content .comment h3 a:link, #content .comment h3 a:active, #content .comment h3 a:visited { color: <?php echo $wp_color_links_content; ?>; }
		#content .comment h3 a:hover { color: #000; border-bottom: 1px solid <?php echo $wp_color_links; ?>; }
		#content .comment h5 { font-family: <?php echo $wp_body_font; ?>; }
		#comment-form input, #comment-form textarea { font-family: <?php echo $wp_body_font; ?>; }
		#sidebar a:link, #sidebar a:visited, #sidebar a:active { color: <?php echo $wp_color_links_sidebar; ?>; }
		#sidebar a:hover { color: #000; }
		#sidebar h1, #sidebar h2, #sidebar h3, #sidebar h3 a:link, #sidebar h3 a:visited, #sidebar h3 a:active, #sidebar h4, #sidebar h5, #sidebar h6 { color: <?php echo $wp_color_headings; ?>; font-family: <?php echo $wp_headings_font; ?>; font-weight: bold; }
		#sidebar h5 { font-family: <?php echo $wp_body_font; ?>; }
	</style>
<?php }

function wicketpixie_admin_head() {
	$path= get_bloginfo('template_directory');
	echo '<script type="text/javascript" src="' . $path . '/js/colorpicker.js"></script>';
	echo '<link rel="stylesheet" href="' . $path . '/css/admin.css" type="text/css" media="screen, projection" />';
?>
	<script type="text/javascript">
		jQuery(function($) {
            $("#wp_color_logo").attachColorPicker();
            $("#wp_body_bg_color").attachColorPicker();
            $("#wp_color_description").attachColorPicker();
            $("#wp_color_titles").attachColorPicker();
            $("#wp_color_headings").attachColorPicker();
            $("#wp_color_links_content").attachColorPicker();
            $("#wp_color_links_sidebar").attachColorPicker();
        });
	</script>
	<script>
	jQuery(function($) {
		$('#explaintext').click(function(){
			$('#explain').toggle();
			return false;
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

add_action('admin_head', 'wicketpixie_admin_head');
add_action('wp_head', 'wicketpixie_wp_head');
add_action('admin_menu', 'wicketpixie_add_admin');

require( TEMPLATEPATH .'/app/sourcemanager.php' );
add_action ('admin_menu', array( 'SourceAdmin', 'addMenu' ) );
register_activation_hook('app/sourcemanager.php', array( 'SourceAdmin', 'install' ) );
add_action('in_admin_footer', 'wicketpixie_add_admin_footer');

require( TEMPLATEPATH .'/app/faves.php');
require( TEMPLATEPATH .'/app/notify.php');
require( TEMPLATEPATH .'/app/update.php');
require( TEMPLATEPATH .'/app/videos.php');
require( TEMPLATEPATH . '/app/adsservice.php');
?>