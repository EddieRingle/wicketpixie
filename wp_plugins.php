<?php

add_action('admin_menu','wp_plugins_toplevel_admin');

/* List of the plugins included in WicketPixie which should be activated.
Make sure you add an option in functions.php as well, in the $plugins array.
Example:
    if(wp_get_option("wp_plug_pluginname")) {
        include "plugins/pluginname.php";
    }
"pluginname" is the name of the plugin.
*/

if(wp_get_option("wp_plug_all_in_one_seo_pack") == "1") {
    include TEMPLATEPATH ."/plugins/all-in-one-seo-pack/all_in_one_seo_pack.php";
}
if(wp_get_option("wp_plug_autohyperlink-urls") == "1") {
    include TEMPLATEPATH ."/plugins/autohyperlink-urls/autohyperlink-urls.php";
}
if(wp_get_option("wp_plug_disqus") == "1") {
    include TEMPLATEPATH ."/plugins/disqus-comment-system/disqus.php";
}
if(wp_get_option("wp_plug_falbum") == "1") {
    include TEMPLATEPATH ."/plugins/falbum/wordpress-falbum-plugin.php";
}
if(wp_get_option("wp_plug_kontera") == "1") {
    include TEMPLATEPATH ."/plugins/kontera/kontera.php";
}
if(wp_get_option("wp_plug_nofollow_navigation") == "1" || wp_get_option("wp_plug_nofollow_navigation") != "0") {
    include TEMPLATEPATH ."/plugins/nofollow-navi/nofollow-navi.php";
}
if(wp_get_option("wp_plug_obfuscate-email") == "1") {
    include TEMPLATEPATH ."/plugins/obfuscate-email/obfuscate-email.php";
}
if(wp_get_option("wp_plug_statpress-reloaded") == "1") {
    include TEMPLATEPATH ."/plugins/statpress-reloaded/statpress.php";
}

$plugins = array(
    array(
        "name" => "DISQUS Comment System",
        "description" => "Use DISQUS for comments.",
        "id"    => $shortname."_plug_disqus",
        "std"   => 1,
        "status"    => 'checked',
        "type"  => 'checkbox'),
    array(
        "name" => "Kontera",
        "description" => "Enable Kontera Advertising.",
        "id"    => $shortname."_plug_kontera",
        "std"   => 1,
        "status"    => 'checked',
        "type"  => 'checkbox'),
    array(
        "name"  => "Automatically Hyperlink URLs",
        "description"   => "Automatically hyperlinks URLs in post contents.",
        "id"    => $shortname."_plug_autohyperlink-urls",
        "std"   => 1,
        "status"    => 'checked',
        "type"  => 'checkbox'),
    array(
        "name"  => "FAlbum",
        "description"   => "Integrate Flickr albums into your blog.",
        "id"    => $shortname."_plug_falbum",
        "std"   => 1,
        "status"    => 'checked',
        "type"  => 'checkbox'),
    array(
        "name"  => "StatPress Reloaded",
        "description"   => "A really nifty stats plugin.",
        "id"    => $shortname."_plug_statpress-reloaded",
        "std"   => 1,
        "status"    => 'checked',
        "type"  => 'checkbox'),
    array(
        "name"  => "All In One SEO Pack",
        "description"   => "It's filled with SEO goodies.",
        "id"    => $shortname."_plug_all_in_one_seo_pack",
        "std"   => 1,
        "status"    => 'checked',
        "type"  => 'checkbox'),
    array(
        "name"  => "Obfuscate Email",
        "description"   => "Modifies email addresses to prevent email harvesting.",
        "id"    => $shortname."_plug_obfuscate-email",
        "std"   => 1,
        "status"    => 'checked',
        "type"  => 'checkbox'),
    array(
        "name"  => "NoFollow Navigation",
        "description"   => "Adds nofollow to the generated page links.",
        "id"    => $shortname."_plug_nofollow_navigation",
        "std"   => 1,
        "status"    => 'checked',
        "type"  => 'checkbox'),
    array(
        "name"  => "WP Related Posts",
        "description"   => "Generates a list of related posts. Deselect if you prefer a different related posts plugin. (that works with the commands we use!)",
        "id"    => $shortname."_plug_related-posts",
        "std"   => 1,
        "status"    => 'checked',
        "type"  => 'checkbox'),
    array(
        "name"  => "Search Highlight",
        "description"   => "Highlights the keywords you searched for in the search results.",
        "id"    => $shortname."_plug_search_highlight",
        "std"   => 1,
        "status"    => 'checked',
        "type"  => 'checkbox')
);

function wp_plugins_toplevel_admin() {
global $plugins;
        if ( 'save_plugins' == $_POST['action'] ) {
            check_admin_referer('wicketpixie-settings');
            foreach ( $plugins as $value ) {
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
            foreach ( $plugins as $value ) {
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
            wp_redirect($_SERVER['PHP_SELF'] ."?page=wp_plugins.php&saved=true");
            die;
        }
add_menu_page('WiPi Plugins', 'WiPi Plugins', 'edit_themes', 'wp_plugins.php', 'wp_plugins_admin_index',get_template_directory_uri() .'/images/wicketsmall.png');
}

function wp_plugins_admin_index() {
global $plugins;
?>
            <?php
            if ( isset( $_REQUEST['saved'] ) ) echo '<div id="message" class="updated fade"><p><strong>'.__('Plugin preferences saved.').'</strong></p></div>';
            ?>
			<div class="wrap">
				
                <div id="admin-options">
				    <h2><?php echo $themename; ?> Plugins</h2>
		<form method="post" style="padding:20px 0 40px;" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=wp_plugins.php&amp;saved=true">
        <?php wp_nonce_field('wicketpixie-settings'); ?>
		<table class="form-table">
			<?php foreach( $plugins as $value ) { ?>
			<tr valign="top"> 
				<th scope="row" style="font-size:12px;text-align:left;padding-right:10px;">
					<acronym title="<?php echo $value['description']; ?>"><?php echo $value['name']; ?></acronym>
				</th>
				<td style="padding-right:10px;">
					<?php
						if (wp_get_option($value['id']) != false) {
							$checked = wp_get_option($value['id']);
						} else { 
							$checked = $value['std']; 
						}
					?>
					<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php echo $value['id']; ?>" <?php if($checked === '1') { echo "checked='checked'"; } ?> />
				</td>
			</tr>
			<?php } ?>
		</table>
			<p class="submit">
			<input name="save_plugins" type="submit" value="Save changes" />    
			<input type="hidden" name="action" value="save_plugins" />
			</p>
		</form>
                </div>
                <?php include_once('app/advert.php'); ?>
<?php
}
?>
