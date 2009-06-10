<!-- ustream-widget -->
<div id="sidebar1-inner">
    <div class="widget">
        <?php echo "<h3>".wp_get_option('sidebar_ustream_heading')."</h3>"; ?>
        <?php $key = "uzhqbxc7pqzqyvqze84swcer"; ?>
        <?php
            if (wp_get_option('sidebar_ustreamchannel') != false && wp_get_option('sidebar_ustreamchannel') != "") { $chan = wp_get_option('sidebar_ustreamchannel'); } else { $trip = true; }
            if (wp_get_option('sidebar_ustream_height') != false && wp_get_option('sidebar_ustream_height') != "") { $height = wp_get_option('sidebar_ustream_height'); } else { $trip = true; }
            if (wp_get_option('sidebar_ustream_width') != false && wp_get_option('sidebar_ustream_width') != "") { $width = wp_get_option('sidebar_ustream_width'); } else { $trip = true; }
            if (wp_get_option('sidebar_ustream_autoplay') == "1") { $autoplay = 'true'; } else { $autoplay = 'false'; }
            if ($trip == true) {
                $out = "<!-- Please go back to the Widget Page and set the settings for this widget. -->";
            } else {
                $url = "http://api.ustream.tv/php/channel/$chan/getInfo?key=$key";
                $cl = curl_init($url);
                curl_setopt($cl,CURLOPT_HEADER,false);
                curl_setopt($cl,CURLOPT_RETURNTRANSFER,true);
                $resp = curl_exec($cl);
                curl_close($cl);
                $resultsArray = unserialize($resp);
                $out = $resultsArray['results'];
            }
            echo '<object id="utv_o_'.$out['id'].'" height="'.$height.'" width="'.$width.'" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"><param value="http://www.ustream.tv/flash/live/'.$out['id'].'" name="movie" /><param value="true" name="allowFullScreen" /><param value="always" name="allowScriptAccess" /><param value="transparent" name="wmode" /><param value="viewcount=true&amp;autoplay='.$autoplay.'" name="flashvars" /><embed name="utv_e_'.$out['id'].'" id="utv_e_'.$out['id'].'" flashvars="viewcount=true&amp;autoplay='.$autoplay.'" height="'.$height.'" width="'.$width.'" allowfullscreen="true" allowscriptaccess="always" wmode="transparent" src="http://www.ustream.tv/flash/live/'.$out['id'].'" type="application/x-shockwave-flash" /></object>';
        ?>
    </div>
</div>
<!-- /ustream-widget -->
