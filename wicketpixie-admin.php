<?php

$settings = array(
    array(
        "name" => "Blog Feed URL",
        "description" => "RSS feed URL of your blog.",
        "id" => "blog_feed_url",
        "std" => "http://feeds.pirillo.com/ChrisPirillo",
        "type" => "text"),
    array(
        "name" => "Flickr ID",
        "description" => "Flickr ID used to access Flickr photo stream.",
        "id" => "flickrid",
        "type" => "text"),
    array(
        "name" => "Podcast Feed URL",
        "description" => "URL of your podcast's feed.",
        "id" => "podcastfeed",
        "std" => "http://feeds.pirillo.com/ChrisPirilloShow",
        "type" => "text"),
    array(
        "name" => "Twitter Username",
        "description" => "Twitter Username",
        "id" => "twitterid",
        "std" => "chrispirillo",
        "type" => "text"),
    array(
        "name" => "Ustream Channel",
        "description" => "Channel name of stream set for live video.",
        "id" => "ustreamchannel",
        "std" => "chris-pirillo-live",
        "type" => "text"),
    array(
        "name" => "YouTube Username",
        "description" => "Your username for YouTube.",
        "id" => "youtubeid",
        "std" => "lockergnome",
        "type" => "text"),
    	array(
		"name"	=>	"Show author on posts",
		"description"	=>	"Whether or not to show who wrote a particular post.",
		"id"	=> "auth_credit",
		"std"	=>	1,
		"status" => 'checked',
		"type"	=>	'checkbox'),	
    array(
        "name"  =>  "Enable WicketPixie Notifications",
        "description"   => "Check this if you want WicketPixie to notify services like Ping.fm about your new blog posts, as configured on the WicketPixie Notifications page.",
		"id"    =>  "notify",
        "std"   =>  1,
        	"status" => 'checked',
        "type"  => 'checkbox')
);

function save($data,$array) {
    check_admin_referer('wicketpixie-settings');
            foreach ( $array as $value ) {
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
            foreach ( $array as $value ) {
                if( isset( $_POST[ $value['id'] ] ) ) { 
                    if( $value['type'] == 'checkbox' ) {
                        if( $value['status'] == 'checked' ) {
                            if(wp_get_option($value['id'])) {
				                wp_update_option( $value['id'], '1');
				            } else {
				                if(wp_option_isempty($value['id']) == true) {
				                    wp_update_option($value['id'],'1');
				                } else {
				                    wp_add_option($value['id'],'1');
				                }
				            }
                        } else {
                            if(wp_get_option($value['id'])) {
				                wp_update_option( $value['id'], '0');
				            } else {
				                if(wp_option_isempty($value['id']) == true) {
				                    wp_update_option($value['id'],'0');
				                } else {
				                    wp_add_option($value['id'],'0');
				                }
				            }
                        }	
                    } elseif( $value['type'] != 'checkbox' ) {
                        if(wp_get_option($value['id'])) {
				            wp_update_option( $value['id'], $_POST[ $value['id'] ] );
				        } else {
				        if(wp_option_isempty($value['id']) == true) {
				            wp_update_option($value['id'],$_POST[$value['id']]);
				        } else {
				            wp_add_option($value['id'],$_POST[$value['id']]);
				        }
				    }
                    } else {
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
                }
            }
            wp_redirect($_SERVER['PHP_SELF'] ."?page=".basename(__FILE__)."&saved=true");
            die;
}

function wicketpixie_toplevel_admin() {
    global $settings;
    if($_POST['action'] == 'save_settings') {
        save($_POST,$settings);
    }
    if ( 'ccode' == $_REQUEST['action'] ) {
        if('global_announcement' == $_POST['file']) {
            require_once(TEMPLATEPATH .'/app/customcode.php');
            writeto($_POST['code'],"global_announcement.php");
        }
    }
add_menu_page('WicketPixie Admin', 'WicketPixie', 'edit_themes', 'wicketpixie-admin.php', 'wicketpixie_admin_index',get_template_directory_uri() .'/images/wicketsmall.png');
}

function wicketpixie_admin_index() {
    global $settings;
?>
			<div class="wrap">
				
                <div id="admin-options">
				
                    <h2>WicketPixie Setup</h2>
                    <p>We will need a few things from you to enable some of WicketPixie's features.</p>
				    
                    <h3>IDs and URLs</h3>
                    <p id="flickrid_tip">You can obtain your Flickr ID by using <a href="http://idgettr.com">idGettr</a>.</p>
                    <p id="ustreamchannel_tip">Your Ustream Channel is the name of the Ustream channel you'd like to stream from. For example, the channel 'Chris Pirillo Live' (url of which is http://ustream.tv/channel/chris-pirillo-live) would be entered as 'chris-pirillo-live'. (Like you'd see it in the Ustream URL.)</p>
		            <form method="post" style="padding:20px 0 40px;" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=wicketpixie-admin.php">
		            <?php wp_nonce_field('wicketpixie-settings'); ?>
		            <table class="form-table">
			            <?php foreach( $settings as $value ) { ?>
			            <tr valign="top"> 
				<th scope="row" style="font-size:12px;text-align:left;padding-right:10px;">
					<acronym title="<?php echo $value['description']; ?>"><?php echo $value['name']; ?></acronym>
				</th>
				<td style="padding-right:10px;">
					<?php
						if (wp_get_option($value['id']) != false) {
							$optdata = wp_get_option($value['id']);
						} else { 
							$optdata = $value['std']; 
						}
					?>
					<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php echo $optdata; ?>" <?php if($optdata === '1') { echo "checked='checked'"; } ?> />
				</td>
			</tr>
			<?php } ?>
		</table>
			<p class="submit">
			<input name="save_settings" type="submit" value="Save changes" />    
			<input type="hidden" name="action" value="save_settings" />
			</p>
		</form>
		
		<h3>Global Announcement</h3>
		    <?php
		    require_once(TEMPLATEPATH .'/app/customcode.php');
		    if(function_exists(fetchcustomcode)) {
		        $glob = fetchcustomcode('global_announcement.php');
		        if($glob == fetchcustomcode('idontexist.no')) {
		            $glob = "";
		        }
		    } else {
		        $glob = "";
		    }
		    ?>
                <p>The text you enter here will appear on the home page and all your posts as a global announcement. HTML is allowed.</p>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=wicketpixie-admin.php&amp;ccode=true" class="form-table">
                    <p><textarea name="code" id="code" style="border: 1px solid #999999;" cols="80" rows="15" /><?php echo $glob; ?></textarea></p>
                    <p class="submit">
                        <input name="save" type="submit" value="Save Global Announcement" /> 
                        <input type="hidden" name="action" value="ccode" />
                        <input type="hidden" name="file" value="global_announcement" />
                    </p>
                </form>
                </div>
                <?php include_once('app/advert.php'); ?>
<?php
}
?>
