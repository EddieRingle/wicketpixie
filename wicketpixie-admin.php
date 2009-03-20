<?php

$services = array(
    array(
        "name" => "Flickr ID",
        "description" => "Flickr ID used to access Flickr photo stream.",
        "id" => "flickrid"
    ),
    array(
        "name" => "Ustream Channel",
        "description" => "Channel name of stream set for live video.",
        "id" => "ustreamchannel"
    )
);

function save($data) {
    global $services;
    foreach($services as $service) {
        $id = $service['id'];
        if($_POST[$id] != $service['name']) {
            $value = $_POST[$id];
            if(!wp_get_option("wp_$id")) {
                wp_add_option("wp_$id",$value);
            } else {
                wp_update_option("wp_$id",$value);
            }
        }
    }
}

function wicketpixie_toplevel_admin() {
add_menu_page('WicketPixie Admin', 'WicketPixie', 'edit_themes', 'wicketpixie-admin.php', 'wicketpixie_admin_index',get_template_directory_uri() .'/images/wicketsmall.png');
}

function wicketpixie_admin_index() {
    global $services;
    if($_POST['idform'] == 'true') {
        save($_POST);
    }
?>
			<div class="wrap">
				
				<div id="admin-options">
				
					<h2>WicketPixie Setup</h2>
                    <p>We will need a few things from you to enable some of WicketPixie's features.</p>
				    <form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=wicketpixie-admin.php" method="post">
                        <h3>IDs and Such</h3>
                        <p id="flickrid_tip">You can obtain your Flickr ID by using <a href="http://idgettr.com">idGettr</a>.</p>
                        <p id="ustreamchannel_tip">Your Ustream Channel is the name of the Ustream channel you'd like to stream from. For example, the channel 'Chris Pirillo Live' (url of which is http://ustream.tv/channel/chris-pirillo-live) would be entered as 'chris-pirillo-live'. (Like you'd see it in the Ustream URL.)</p>
                        <?php
                        foreach ( $services as $service ) {
                        $id = $service['id'];
                        $name = $service['name'];
                        $optdata = wp_get_option("wp_$id");
                        if(!$optdata || $optdata == "") {
                            $value = $name;
                        } else {
                            $value = $optdata;
                        }
                        ?>
                        <input type="text" name="<?php echo $id; ?>" value="<?php echo $value; ?>" onfocus="if(this.value=='<?php echo $value; ?>')value = '';document.getElementById('<?php echo $id; ?>_tip').style.font-weight = 'bold';" onblur="if(this.value=='')value='<?php echo $value; ?>';document.getElementById('<?php echo $id; ?>_tip').style.font-weight = 'normal';" onmouseover="document.getElementById('<?php echo $id; ?>_tip').style.font-weight = 'bold';" onmouseout="document.getElementById('<?php echo $id; ?>_tip').style.font-weight = 'normal';" /><br />
                        <?php
                        }
                        ?>
                        
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
