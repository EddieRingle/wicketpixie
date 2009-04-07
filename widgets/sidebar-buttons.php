<!-- social-buttons -->
<div style="margin:0 auto;width:100%">
    <?php
    $blogfeed = wp_get_option("wp_blog_feed_url");
    $podcastfeed = wp_get_option("wp_podcastfeed");
    $twitter = wp_get_option("wp_twitterid");
    $youtube = wp_get_option("wp_youtubeid");
    if($blogfeed != false && $blogfeed != "") {
        echo "<a href='$blogfeed' target='_blank'><img src='".get_template_directory_uri()."/images/button-feed.png' style='float:left;padding:10px 10px 20px 14px' height='60' width='60' border='0' alt='Subscribe'/></a>";
    }
    if($podcastfeed != false && $podcastfeed != "") {
        echo "<a href='$podcastfeed' target='_blank'><img src='".get_template_directory_uri()."/images/button-podcast-feed.png' style='float:left;padding:10px 10px 20px 14px' height='60' width='60' border='0' alt='Podcast'/></a>";
    }
    if($twitter != false && $twitter != "") {
        echo "<a href='http://twitter.com/$twitter' target='_blank'><img src='".get_template_directory_uri()."/images/button-twitter.png' style='float:left;padding:10px 10px 20px 14px' height='60' width='60' border='0' alt='Twitter'/></a>";
    }
    if($youtube != false && $youtube != "") {
        echo "<a href='http://youtube.com/$youtube' target='_blank'><img src='".get_template_directory_uri()."/images/button-youtube.png' style='float:left;padding:10px 10px 20px 14px' height='60' width='60' border='0' alt='YouTube'/></a>";
    }
    ?>
</div>
<div style="clear:both"></div>
<!-- /social-buttons -->
