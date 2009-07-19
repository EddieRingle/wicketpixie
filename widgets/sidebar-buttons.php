<!-- social-buttons -->
<?php
    $blogfeed = get_option($optpre."blog_feed_url");
    $podcastfeed = get_option($optpre."podcastfeed");
    $twitter = get_option($optpre."twitterid");
    $youtube = get_option($optpre."youtubeid");
    $wcount = 0;
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
<?php echo "<div style='margin:0px auto 0px auto;width:",$wwidget,"px'>"; ?>
<?php foreach($witem as $item) { echo $item; } ?>
</div>
<div style="clear:both"></div>
<!-- /social-buttons -->
