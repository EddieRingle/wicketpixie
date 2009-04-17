<?php
/**
* WicketPixie Home Editor
* Now you finally do not have to dig into the template files to
* modify your home page! Whee! \o/
**/

// Arrays that hold our options
$homeoptions = array(
    array(
    "name" => "Flickr Widget",
    "description" => "Display the Flickr widget on the home page.",
    "id" => "home_flickr",
    "std" => 1,
    "status" => "checked",
    "type" => "checkbox"),
    array(
    "name" => "Video Embed",
    "description" => "Enable the Video Embed Code entered below.",
    "id" => "home_video",
    "std" => 1,
    "status" => "checked",
    "type" => "checkbox"),
    array(
    "name" => "Video Embed Code",
    "description" => "Enter code for a video object. For example, a YouTube custom player.",
    "id" => "home_video_code",
    "std" => "No video embed code yet...",
    "type" => "textarea"),
    array(
    "name" => "Show 'My Videos' heading",
    "description" => "Show the 'My Videos' heading above the video embed.",
    "id" => "home_show_vid_heading",
    "std" => 1,
    "status" => "checked",
    "type" => "checkbox"),
    array(
    "name" => "Show 'Recent Photos' heading",
    "description" => "Show the 'Recent Photos' heading above the video embed.",
    "id" => "home_show_photo_heading",
    "std" => 1,
    "status" => "checked",
    "type" => "checkbox"),
    array(
    "name" => "Enable Social Buttons Widget",
    "description" => "Show the Social Buttons Widget in the homepage sidebar.",
    "id" => "home_sidebar_buttons",
    "std" => 1,
    "status" => "checked",
    "type" => "checkbox"),
    array(
    "name" => "Ustream Widget",
    "description" => "Check to enable the Ustream embed on the homepage sidebar.",
    "id" => "home_ustream",
    "std" => 1,
    "status" => "checked",
    "type" => "checkbox"),
    array(
    "name" => "Autoplay Ustream",
    "description" => "Check if you want the Ustream object to automatically play on page load.",
    "id" => "home_ustream_autoplay",
    "std" => 1,
    "status" => "checked",
    "type" => "checkbox"),
    array(
    "name" => "Ustream Object Heading",
    "description" => "The heading that will appear above the Ustream Object.",
    "id" => "home_ustream_heading",
    "std" => "Live Video",
    "type" => "textbox"),
    array(
    "name" => "Ustream Object Height",
    "description" => "Enter code for a video object. For example, a YouTube custom player.",
    "id" => "home_ustream_height",
    "std" => "293",
    "type" => "textbox"),
    array(
    "name" => "Ustream Object Width",
    "description" => "Enter code for a video object. For example, a YouTube custom player.",
    "id" => "home_ustream_width",
    "std" => "340",
    "type" => "textbox"),
    array(
    "name" => "Custom Code",
    "description" => "Content that is displayed after the post but before the Flickr Widget, Embedded Video, etc.",
    "id" => "home_custom",
    "std" => "No custom code yet...",
    "type" => "textarea")
);
class HomeAdmin {

	/**
	* Just calling WP's method to add a new menu to the design section.
	*/
	function addMenu()
	{
	    global $homeoptions;
        if ( 'save' == $_POST['action'] ) {
            foreach ( $homeoptions as $value ) {
	            if(wp_get_option($value['id'])) {
				    wp_update_option( $value['id'], $_POST[ $value['id'] ] );
				} else {
				    if(wp_option_isempty($value['id']) == true) {
				        wp_update_option($value['id'],$_POST[$value['id']]);
				    } else {
				        wp_add_option($value['id'],$_POST[$value['id']]);
				    }
				}
			}
            foreach ( $homeoptions as $value ) {
				if( isset( $_POST[ $value['id'] ] ) ) { 
					if( $value['type'] == 'checkbox' ) {
						if( $value['status'] == 'checked' ) {
						    if(wp_get_option($value['id'])) {
							    wp_update_option( $value['id'], '1' );
							} else {
							    if(wp_option_isempty($value['id']) == true) {
							        wp_update_option($value['id'],'1');
							    } else {
							        wp_add_option($value['id'], '1');
							    }
							}
						} else {
						    if(wp_get_option($value['id'])) {
							    wp_update_option( $value['id'], '0' );
							} else {
							    if(wp_option_isempty($value['id']) == true) {
							        wp_update_option($value['id'],'0');
							    } else {
							        wp_add_option($value['id'], '0');
							    }
							}
						}
					} elseif( $value['type'] != 'checkbox' ) {
					    if(wp_get_option($value['id'])) {
						    wp_update_option( $value['id'], $_POST[ $value['id'] ]  );
						} else {
						    wp_add_option($value['id'],$_POST[$value['id']]);
						}
					} else {
						if(wp_get_option($value['id'])) {
						    wp_update_option( $value['id'], $_POST[ $value['id'] ]  );
						} else {
						    wp_add_option($value['id'],$_POST[$value['id']]);
						}
					}
				}
			}
            wp_redirect($_SERVER['PHP_SELF'] ."?page=homeeditor.php&saved=true");
            die;
	    }
		add_submenu_page('wicketpixie-admin.php', __('WicketPixie Home Editor'), __('Home Editor'), 9, basename(__FILE__), array( 'HomeAdmin', 'homeMenu' ) );
	}

	/**
	* The admin page for our home editor.
	**/
    function homeMenu()
    {
        global $homeoptions;
        
        if ( isset( $_REQUEST['saved'] ) ) echo '<div id="message" class="updated fade"><p><strong>'.__('Options saved.').'</strong></p></div>';
    ?>
    <div class="wrap">
	
	    <div id="admin-options">
	
		    <h2>Home Editor</h2>
            
		    <form method="post" style="padding:20px 0 10px;" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=homeeditor.php&amp;saved=true">
			    <table class="form-table">

			    <?php foreach ($homeoptions as $value) {
        
			    if ($value['type'] == "textarea") {
                    if ($value['id'] == "home_video" || $value['id'] == "home_custom") {
                        if (wp_get_option($value['id']) != false && wp_get_option($value['id']) != '') {
                            $content = stripslashes(wp_get_option($value['id']));
                        } else {
                            $content = $value['std'];
                        }
                    } else {
                        if (wp_get_option($value['id']) != false && wp_get_option($value['id']) != '') {
                            $content = wp_get_option($value['id']);
                        } else {
                            $value['std'];
                        }
                    }
                ?>
			    <tr valign="top"> 
			        <th scope="row" style="font-size:12px; text-align:left; padding-right:10px;"><acronym title="<?php echo $value['description']; ?>"><?php echo $value['name']; ?></acronym></th>
			        <td style="padding-bottom:10px;">
			            <textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="<?php stripslashes($content); ?>"><?php echo stripslashes($content); ?></textarea>
			        </td>
			    </tr>

			    <?php } elseif ($value['type'] == "select") { ?>

			        <tr valign="top"> 
			            <th scope="row" style="font-size:12px; text-align:left; padding-right:10px;"><acronym title="<?php echo $value['description']; ?>"><?php echo $value['name']; ?></acronym></th>
			            <td style="padding-bottom:10px;">
			                <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
			                    <?php foreach ($value['options'] as $option) { ?>
			                    <option<?php
								    if ( $option == wp_get_option( $value['id'] ) ) { 
									    echo ' selected="selected"'; 
								    } elseif( $option == $value['std'] && !wp_get_option( $value['id'] ) ) {
									    echo ' selected="selected"';
								    } ?>
							    ><?php echo $option; ?></option>
			                    <?php } ?>
			                </select>
			            </td>
			        </tr>

			    <?php 
				    } elseif( $value['type'] == 'checkbox' ) { ?>
				    <tr valign="top">
				        <th scope="row" style="font-size:12px; text-align:left; padding-right:10px;"><acronym title="<?php echo $value['description']; ?>"><?php echo $value['name']; ?></acronym></th>
			            <td style="padding-right:10px;">
					        <?php
						        if (wp_get_option($value['id']) != false) {
							        $checked = wp_get_option($value['id']);
						        } else { 
							        $checked = $value['std']; 
						        }
					        ?>
					        <input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php echo $value['id']; ?>" <?php if($checked === '1') { echo "checked='checked'"; } ?> />
					</tr>
			    <?php
				    } elseif( $value['type'] == 'textbox' ) { ?>
				    <tr valign="top"> 
			        <th scope="row" style="font-size:12px; text-align:left; padding-right:10px;"><acronym title="<?php echo $value['description']; ?>"><?php echo $value['name']; ?></acronym></th>
			        <td style="padding-bottom:10px;">
			            <input type="<?php echo $value['type']; ?>" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="<?php if ( wp_get_option( $value['id'] ) != false && wp_get_option($value['id']) != '') { echo wp_get_option( $value['id'] ); } else { echo $value['std']; } ?>"><?php (wp_get_option($value['id']) != false && wp_get_option($value['id']) != '') ? wp_get_option($value['id']) : $value['std']; ?></input>
			        </td>
			    </tr>
			    <?php
				    }
			    }
			    ?>

			    </table>

			    <p class="submit">
				    <input name="save" type="submit" value="Save changes" class="button" />    
				    <input type="hidden" name="action" value="save" />
			    </p>

		    </form>
	    </div>
	    <?php include_once('advert.php'); ?>
    <?php
    }
}
?>
