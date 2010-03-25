<?php
/**
 * UstreamWidget Class
 */
class UstreamWidget extends WP_Widget
{
    function UstreamWidget()
    {
        $widget_ops = array('classname' => 'widget_ustream','description' => __('Displays Ustream.tv object.'));
        $this->WP_Widget('ustream',__('Ustream'),$widget_ops,null);
    }
    
    function widget($args,$instance)
    {
        extract($args);
        $title = apply_filters('widget_title',empty($instance['title']) ? false : $instance['title']);
        $channel = empty($instance['channel']) ? get_option('wicketpixie_ustream_channel') : $instance['channel'];
        $autoplay = empty($instance['autoplay']) ? 'false' : $instance['autoplay'];
        
        echo $before_widget;
        
        if($title)
            echo $before_title, $title, $after_title;
            
        $key = "uzhqbxc7pqzqyvqze84swcer";
        
        $trip = ($channel == '') ? true : false;
        if ($trip == true) {
            $out = "<!-- Please go back to the Widget Page and set the settings for this widget. -->";
        } else {
            $url = "http://api.ustream.tv/php/channel/$channel/getInfo?key=$key";
            $cl = curl_init($url);
            curl_setopt($cl,CURLOPT_HEADER,false);
            curl_setopt($cl,CURLOPT_RETURNTRANSFER,true);
            $resp = curl_exec($cl);
            curl_close($cl);
            $resultsArray = unserialize($resp);
            $out = $resultsArray['results'];
            
        }
        if($trip == false) {
            echo '<!--[if !IE]> -->
  <object type="application/x-shockwave-flash" data="http://www.ustream.tv/flash/live/',$out['id'],'" width="300" height="240">
<!-- <![endif]-->
<!--[if IE]>
  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="300" height="240">
    <param name="movie" value="http://www.ustream.tv/flash/live/',$out['id'],'" />
<!--><!-- http://Validifier.com -->
    <param name="allowFullScreen" "value="true"/>
    <param value="always" name="allowScriptAccess" />
    <param value="transparent" name="wmode" />
    <param value="viewcount=true&amp;autoplay=',$autoplay,'" name="flashvars" />
  </object>
<!-- <![endif]-->';
        } else {
            echo $out;
        }
        ?>
        <?php
        echo $after_widget;
    }
    
    function update($new_instance,$old_instance)
    {
        $new_instance = (array)$new_instance;
        $instance = $old_instance;
        foreach($instance as $name => $val) {
		    if (isset($new_instance[$name])) {
				$instance[$name] = 1;
			} else {
			    $instance[$name] = 0;
			}
        }
        $instance['title'] = strip_tags(stripslashes($new_instance['title']));
        $instance['channel'] = strip_tags(stripslashes($new_instance['channel']));
        return $instance;
    }
    
    function form($instance)
    {
        $instance = wp_parse_args((array)$instance,array('title'=>'Live Video Stream','channel'=>get_option('wicketpixie_ustream_channel'),'autoplay'=>false));
        $title = htmlspecialchars($instance['title']);
        $channel = htmlspecialchars($instance['channel']);
        $autoplay = $instance['autoplay'];
        echo '<p style="text-align:left">
        <label for="',$this->get_field_name('title'),'">',__('Title:'),'<input style="width:150px" name="',$this->get_field_name('title'),'" id="',$this->get_field_id('title'),'" type="text" value="',$title,'" /></label><br />
        <label for="',$this->get_field_name('channel'),'">',__('Channel:'),'<input style="width:150px" name="',$this->get_field_name('channel'),'" id="',$this->get_field_id('channel'),'" type="text" value="',$channel,'" /></label>
        </p>';
        ?>
        <input class="checkbox" type="checkbox" <?php checked($instance['autoplay'],true); ?> id="<?php echo $this->get_field_id('autoplay'); ?>" name="<?php echo $this->get_field_name('autoplay'); ?>" />
        <label for="<?php echo $this->get_field_id('autoplay'); ?>"><?php _e('Autoplay on Page Load'); ?></label>
        </p>
        <?php
    }
}

function UstreamWidgetInit() {
    register_widget('UstreamWidget');
}
