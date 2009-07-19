<!-- ustream-widget -->
<div id="sidebar1-inner">
    <div class="widget">
        <?php echo "<h3>".get_option($optpre.'sidebar_ustream_heading')."</h3>"; ?>
        <?php $key = "uzhqbxc7pqzqyvqze84swcer"; ?>
        <?php
            if (get_option($optpre.'sidebar_ustreamchannel') != false) { $chan = get_option($optpre.'sidebar_ustreamchannel'); } else { $trip = true; }
            if (get_option($optpre.'sidebar_ustream_height') != false) { $height = get_option($optpre.'sidebar_ustream_height'); } else { $trip = true; }
            if (get_option($optpre.'sidebar_ustream_width') != false) { $width = get_option($optpre.'sidebar_ustream_width'); } else { $trip = true; }
            if (get_option($optpre.'sidebar_ustream_autoplay') == "1") { $autoplay = 'true'; } else { $autoplay = 'false'; }
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
            echo '<!--[if !IE]> -->
  <object type="application/x-shockwave-flash" data="http://www.ustream.tv/flash/live/',$out['id'],'" width="',$width,'" height="',$height,'">
<!-- <![endif]-->
<!--[if IE]>
  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="',$width,'" height="',$height,'">
    <param name="movie" value="http://www.ustream.tv/flash/live/',$out['id'],'" />
<!--><!-- http://Validifier.com -->
    <param name="allowFullScreen" "value="true"/>
    <param value="always" name="allowScriptAccess" />
    <param value="transparent" name="wmode" />
    <param value="viewcount=true&amp;autoplay=',$autoplay,'" name="flashvars" />
  </object>
<!-- <![endif]-->';
        ?>
    </div>
</div>
<!-- /ustream-widget -->
