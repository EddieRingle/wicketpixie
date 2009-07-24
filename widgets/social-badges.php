<?php
/**
 * SocialBadgesWidget Class
 */
class SocialBadgesWidget extends WP_Widget
{
    function SocialBadgesWidget()
    {
        $widget_ops = array('classname' => 'widget_social_badges','description' => __('Displays badges with links to four different social sites as defined in WicketPixie Admin.'));
        $this->WP_Widget('socialbadges',__('Social Badges'),$widget_ops,null);
    }
    
    function widget($args,$instance)
    {
        extract($args);        
        $blogfeed = get_option('wicketpixie_blog_feed_url');
        $podcastfeed = get_option('wicketpixie_podcast_feed_url');
        $twitter = get_option('wicketpixie_twitter_id');
        $youtube = get_option('wicketpixie_youtube_id');
        $wcount = 0;
        $witem = array();
        if($blogfeed != false && $blogfeed != "") {
            $witem[$wcount] = "<a href='$blogfeed'><img src='".get_template_directory_uri()."/images/button-feed.png' style='float:left;padding:10px 10px 20px 14px;border:0px;' height='60' width='60' alt='Subscribe'/></a>\n";
            $wcount++;
        }
        if($podcastfeed != false && $podcastfeed != "") {
            $witem[$wcount] = "<a href='$podcastfeed'><img src='".get_template_directory_uri()."/images/button-podcast-feed.png' style='float:left;padding:10px 10px 20px 14px;border:0px;' height='60' width='60' alt='Podcast'/></a>\n";
            $wcount++;
        }
        if($twitter != false && $twitter != "") {
            $witem[$wcount] = "<a href='http://twitter.com/$twitter'><img src='".get_template_directory_uri()."/images/button-twitter.png' style='float:left;padding:10px 10px 20px 14px;border:0px;' height='60' width='60' alt='Twitter'/></a>\n";
            $wcount++;
        }
        if($youtube != false && $youtube != "") {
            $witem[$wcount] = "<a href='http://youtube.com/$youtube'><img src='".get_template_directory_uri()."/images/button-youtube.png' style='float:left;padding:10px 10px 20px 14px;border:0px;' height='60' width='60' alt='YouTube'/></a>\n";
            $wcount++;
        }
        $wwidget = ($wcount * 0.25) * 340;
        ?>
	        <?php echo '<div style="margin:0px auto 0px auto;width:',$wwidget,'px">'; ?>
            <?php foreach($witem as $item) { echo $item; } ?>
            </div>
            <div style="clear:both"></div>
        <?php
    }
    
    function update($new_instance,$old_instance)
    {
        return $old_instance;
    }
    
    function form($instance)
    {
    }
}

function SocialBadgesInit() {
    register_widget('SocialBadgesWidget');
}
