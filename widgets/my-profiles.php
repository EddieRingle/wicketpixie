<?php
/**
 * MyProfilesWidget Class
 */
class MyProfilesWidget extends WP_Widget
{
    function MyProfilesWidget()
    {
        $widget_ops = array('classname' => 'widget_my_profiles','description' => __('Lists all the profiles added to the WicketPixie Social Me Manager'));
        $this->WP_Widget('myprofiles',__('My Profiles'),$widget_ops,null);
    }
    
    function widget($args,$instance)
    {
        extract($args);
        $title = apply_filters('widget_title',empty($instance['title']) ? false : $instance['title']);
        $sources = new SourceAdmin;
        
        echo $before_widget;
        
        if($title)
            echo $before_title, $title, $after_title;
        ?>
	        <ul id="myprofiles">
		        <?php foreach( $sources->legend_types() as $legend ) { ?>
			        <li><img src="<?php echo $legend->favicon; ?>" alt="<?php echo $legend->title; ?>" /><a href="<?php echo $legend->profile_url; ?>"><?php echo $legend->title; ?></a></li>
		        <?php } ?>
	        </ul>
        <?php
        echo $after_widget;
    }
    
    function update($new_instance,$old_instance)
    {
        $new_instance = (array)$new_instance;
        $instance = $old_instance;
        $instance['title'] = strip_tags(stripslashes($new_instance['title']));
        return $instance;
    }
    
    function form($instance)
    {
        $instance = wp_parse_args((array)$instance,array('title'=>'My Profiles'));
        $title = htmlspecialchars($instance['title']);
        echo '<p style="text-align:left"><label for="',$this->get_field_name('title'),'">',__('Title:'),'<input style="width:150px" name="',$this->get_field_name('title'),'" id="',$this->get_field_id('title'),'" type="text" value="',$title,'" /></label></p>';
    }
}

function MyProfilesInit() {
    register_widget('MyProfilesWidget');
}
