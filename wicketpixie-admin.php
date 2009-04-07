<?php

$settings = array(
    array(
        "name" => "Blog Feed URL",
        "description" => "RSS feed URL of your blog.",
        "id" => "blog_feed_url",
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
        "type" => "text"),
    array(
        "name" => "Twitter Username",
        "description" => "Twitter Username",
        "id" => "twitterid",
        "type" => "text"),
    array(
        "name" => "Ustream Channel",
        "description" => "Channel name of stream set for live video.",
        "id" => "ustreamchannel",
        "type" => "text"),
    array(
        "name" => "YouTube Username",
        "description" => "Your username for YouTube.",
        "id" => "youtubeid",
        "type" => "text")
);

function save($data) {
    global $settings;
    foreach($settings as $setting) {
        $name = $setting['name'];
        $id = $setting['id'];
        $type = $setting['type'];
        if($type == "text") {
            if($_POST[$id] != $name) {
                $value = $_POST[$id];
                if(!wp_get_option("wp_$id")) {
                    wp_add_option("wp_$id",$value);
                } else {
                    wp_update_option("wp_$id",$value);
                }
            }
        } elseif($type == "checkbox") {
            // todo
        }
    }
}

function wicketpixie_toplevel_admin() {
add_menu_page('WicketPixie Admin', 'WicketPixie', 'edit_themes', 'wicketpixie-admin.php', 'wicketpixie_admin_index',get_template_directory_uri() .'/images/wicketsmall.png');
}

function wicketpixie_admin_index() {
    global $settings;
    if($_POST['idform'] == 'true') {
        save($_POST);
    }
?>
			<div class="wrap">
				
                <div id="admin-options">
				
                    <h2>WicketPixie Setup</h2>
                    <p>We will need a few things from you to enable some of WicketPixie's features.</p>
				    
                    <h3>IDs and URLs</h3>
                    <p id="flickrid_tip">You can obtain your Flickr ID by using <a href="http://idgettr.com">idGettr</a>.</p>
                    <p id="ustreamchannel_tip">Your Ustream Channel is the name of the Ustream channel you'd like to stream from. For example, the channel 'Chris Pirillo Live' (url of which is http://ustream.tv/channel/chris-pirillo-live) would be entered as 'chris-pirillo-live'. (Like you'd see it in the Ustream URL.)</p>
		            <form method="post" style="padding:20px 0 40px;" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=wicketpixie-admin.php">
		            <table class="form-table">
			            <?php foreach( $settings as $setting ) {
			            $id = $setting['id'];
                        $name = $setting['name'];
                        $desc = $setting['description'];
                        $optdata = wp_get_option("wp_$id");
                        if($optdata == false || $optdata == "") {
                            $value = "";
                        } else {
                            $value = $optdata;
                        }
                        ?>
			            <tr valign="top"> 
				            <th scope="row" style="font-size:12px;text-align:left;padding-right:10px;">
					            <acronym title="<?php echo $desc; ?>"><?php echo $name; ?></acronym>
				            </th>
				            <td style="padding-right:10px;">
					            <input name="<?php echo $id; ?>" id="<?php echo $id; ?>" type="text" value="<?php echo $value; ?>" />
				            </td>
                        </tr>
                        <?php } ?>
                    </table>
                        <p class="submit">
                            <input type="submit" name="submit" value="Save" />
                            <input type="hidden" name="idform" value="true" />
                        </p>
                    </form>
                </div>
                <?php include_once('app/advert.php'); ?>
<?php
}
?>
