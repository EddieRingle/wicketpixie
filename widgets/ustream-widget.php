<!-- ustream-widget -->
<div style="margin:0 auto;width:100%">
    <?php echo "<h1>".wp_get_option('sidebar_ustream_heading')."</h1>"; ?>
    <?php $key = "uzhqbxc7pqzqyvqze84swcer"; ?>
    <?php
        if (wp_get_option('sidebar_ustreamchannel') != false && wp_get_option('sidebar_ustreamchannel') != "") { $chan = wp_get_option('sidebar_ustreamchannel'); } else { $trip = true; }
        if (wp_get_option('sidebar_ustream_height') != false && wp_get_option('sidebar_ustream_height') != "") { $height = wp_get_option('sidebar_ustream_height'); } else { $trip = true; }
        if (wp_get_option('sidebar_ustream_width') != false && wp_get_option('sidebar_ustream_width') != "") { $width = wp_get_option('sidebar_ustream_width'); } else { $trip = true; }
        if (wp_get_option('sidebar_ustream_autoplay') == "1") { $autoplay = true; } else { $autoplay = false; }
        if ($trip == true) {
            $out = "<!-- Please go back to the Widget Page and set the settings for this widget. -->";
        } else {
            $url = "http://api.ustream.tv/php/channel/$chan/getCustomEmbedTag?key=$key&amp;params=autoplay:$autoplay;width:$width;height:$height;";
            $cl = curl_init($url);
            curl_setopt($cl,CURLOPT_HEADER,false);
            curl_setopt($cl,CURLOPT_RETURNTRANSFER,true);
            $resp = curl_exec($cl);
            curl_close($cl);
            $resultsArray = unserialize($resp);
            $out = $resultsArray['results'];
        }
        echo $out;
    ?>
</div>
<div style="clear:both"></div>
<!-- /ustream-widget -->
