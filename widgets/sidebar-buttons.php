<!-- social-buttons -->
<?php
    $blogfeed = wp_get_option("blog_feed_url");
    $podcastfeed = wp_get_option("podcastfeed");
    $twitter = wp_get_option("twitterid");
    $youtube = wp_get_option("youtubeid");
    $wcount = 0;
    if($blogfeed != false && $blogfeed != "") {
        $witem[$wcount] = "<a href='$blogfeed' target='_blank'><img src='".get_template_directory_uri()."/images/button-feed.png' style='float:left;padding:10px 10px 20px 14px' height='60' width='60' border='0' alt='Subscribe'/></a>\n";
        $wcount++;
    }
    if($podcastfeed != false && $podcastfeed != "") {
        $witem[$wcount] = "<a href='$podcastfeed' target='_blank'><img src='".get_template_directory_uri()."/images/button-podcast-feed.png' style='float:left;padding:10px 10px 20px 14px' height='60' width='60' border='0' alt='Podcast'/></a>\n";
        $wcount++;
    }
    if($twitter != false && $twitter != "") {
        $witem[$wcount] = "<a href='http://twitter.com/$twitter' target='_blank'><img src='".get_template_directory_uri()."/images/button-twitter.png' style='float:left;padding:10px 10px 20px 14px' height='60' width='60' border='0' alt='Twitter'/></a>\n";
        $wcount++;
    }
    if($youtube != false && $youtube != "") {
        $witem[$wcount] = "<a href='http://youtube.com/$youtube' target='_blank'><img src='".get_template_directory_uri()."/images/button-youtube.png' style='float:left;padding:10px 10px 20px 14px' height='60' width='60' border='0' alt='YouTube'/></a>\n";
        $wcount++;
    }
    $wwidget = ($wcount * 0.25) * 340;
    ?>
<?php echo "<div style='margin:0 auto 0 auto;width:".$wwidget."px'>"; ?>
<?php foreach($witem as $item) { echo $item; } ?>
</div>
<div style="clear:both"></div>
<!-- /social-buttons -->
