<?php get_header(); ?>
<?php $wp_auth_credit= get_option('wicketpixie_show_post_author'); ?>

            <!-- content -->
            <div id="content">
            
            <!-- google_ad_section_start -->
                <?php query_posts('showposts=1'); ?>
                <?php if (have_posts()) : ?>    
                <?php while (have_posts()) : the_post(); ?>
                
                <!-- post -->
                <div class="post" style="border-bottom:0;">
                
                    <?php
                    require_once(TEMPLATEPATH .'/app/customcode.php');
                    $glob = fetchcustomcode('global_announcement.php',true);
                    if($glob != "" && $glob != fetchcustomcode('idontexist.no')): ?>
                    <div class="highlight">
                    <?php
                        echo $glob;
                    ?>
                    </div>
                    <?php endif; ?>
                    
                    <h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>" style="text-decoration:none;"><?php the_title(); ?></a></h1>

                    <div class="post-comments">
                        <div class="post-comments">
                        <ul>
                        <?php
                        $addlink="#respond";
                        $countlink="#comments";
                        ?>
                            <li class="post-comments-count"><a href="<?php the_permalink(); echo $countlink; ?>" title="View all <?php comments_number('0', '1', '%'); ?> Comments"><?php comments_number('0', '1', '%'); ?></a></li>
                            <li class="post-comments-add"><a href="<?php the_permalink(); echo $addlink; ?>" title="Add a Comment"><span>&nbsp;</span>Add a Comment</a></li>
                        </ul>
                        </div>
                    </div>

                    <div class="post-author">
                        <?php if( $wp_auth_credit == 'true' ) { ?>
                        <?php echo get_avatar( get_the_author_email(), $size = '36', $default = 'images/avatar.jpg' ); ?>
                        <p><strong><?php the_time('l, F jS') ?></strong><br/>
                            by <?php the_author_posts_link(); ?></p>
                        <?php } else { ?>
                        <p><strong><?php the_time('l, F jS') ?></strong><br/>
                            at <?php the_time('g:ia') ?></p>
                        <?php } ?>
                    </div>
                    <div class="clearer"></div>
                    
                    <!-- post-ad -->
                        <div id="post-ad">
                            <?php if(is_enabled_adsense() == true) { $adsense->wp_adsense('blog_post_side'); } ?>
                            <div style="margin: 15px 0 0 5px">
						        <?php if (get_option('wicketpixie_plugin_related-posts') == 'true'):?>
						        <?php wp_related_posts(5); ?>
						        <?php endif;?>
						    </div>
                        </div>
                    <!-- /post-ad -->
                    
                    <div class="KonaBody">
                    <?php the_content('Continue reading &raquo;'); ?>
                    </div>
                    <?php wp_after_home_post_code(); ?>
                </div>
                <!-- /post -->
                <!-- google_ad_section_end -->
                
                <!-- post-meta -->
                <div class="post-meta">
                    
                    <?php if(get_option('wicketpixie_plugin_related-posts') == 'true' && function_exists(wp_related_posts)):?>
                    <!-- related-posts -->
                    <div id="related-posts">
                        <h3>You might also be interested in...</h3>
                         <?php wp_related_posts(5); ?>
                    </div>
                    <!-- /related-posts -->
                    <?php endif; ?>
                    
                    <!-- post-meta-right -->
                    <div class="post-meta-right">
                        
                        
                        <!-- post-meta-tags -->
                        <div class="post-meta-tags">
                            <h6>Tags</h6>
                            <?php the_tags('<ul><li>','</li><li>','</li></ul>'); ?>
                        </div>
                        <!-- /post-meta-tags -->

                        <!-- post-meta-categories -->
                        <div class="post-meta-categories">
                            <h6>Categories</h6>
                            <?php the_category(); ?>
                        </div>
                        <!-- /post-meta-categories -->
                        
                        <!-- post-bigbox -->
                        <div class="post-bigbox">
                        <?php
                        if(is_enabled_adsense() == true) {
                            $adsense->wp_adsense('blog_post_bottom');
                        } elseif(is_enabled_adsense() == false) {
                        ?>
                            <!-- Enable Adsense on the WicketPixie Adsense admin page. -->
                        <?php
                        }
                        ?>
                        </div>
                        <!-- /post-bigbox -->
                        
                    </div>
                    <!-- /post-meta-right -->
                    
                    <div class="clearer"></div>
                    
                </div>
                <!-- /post-meta -->
                
                <!-- Custom Code Area -->
                <?php
                if(get_option('wicketpixie_home_custom_code') != false && get_option('wicketpixie_home_custom_code') != '') {
                    echo stripslashes(get_option('wicketpixie_home_custom_code'));
                }
                ?>
                <!-- /Custom Code Area -->
                
                <?php endwhile; ?>
                <?php endif; ?>
                
                <?php
                if(get_option('wicketpixie_home_video_enable') == 'true') { ?>
                <div id="home-categories">
                <?php
                    if(get_option('wicketpixie_home_show_video_heading') == 'true') {
                        echo "<h2>My Videos</h2>";
                    }
                    if(get_option('wicketpixie_home_video_code') != false && get_option('wicketpixie_home_video_code') != '') {
                                echo stripslashes(get_option('wicketpixie_home_video_code'));
                    } else {
                    ?>
                    <!-- Add video object code in the WicketPixie Home Editor -->
                    <!-- Here's Chris Pirillo's YouTube object as an example: -->
                    <!--[if !IE]> -->
                      <object type="application/x-shockwave-flash" data="http://www.youtube.com/cp/vjVQa1PpcFOi2GvexXT8XYrvBOsPoeQUt32UxT-AJgI=" width="500" height="285">
                    <!-- <![endif]-->
                    <!--[if IE]>
                      <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="500" height="285">
                        <param name="movie" value="http://www.youtube.com/cp/vjVQa1PpcFOi2GvexXT8XYrvBOsPoeQUt32UxT-AJgI=" />
                    <!--><!-- http://Validifier.com -->
                      </object>
                    <!-- <![endif]-->
                    <?php
                    }
                    ?>
                </div>
                <?php } ?>
                
                <?php
                if(get_option('wicketpixie_home_flickr_enable') == 'true') { ?>
                <!-- home-photos -->
                <div id="home-photos">
                <?php                
                if(get_option('wicketpixie_flickr_id') != false && get_option('wicketpixie_flickr_id') != 'false') {
                    $flickrid = get_option('wicketpixie_flickr_id');
                } else {
                    $flickrid = '49503157467@N01';
                }
                if(get_option('wicketpixie_home_flickr_number') != false) {
                    $num = get_option('wicketpixie_home_flickr_number');
                } else {
                    $num = '5';
                }
                if(get_option('wicketpixie_home_show_photo_heading') == 'true') {
                    echo "<h2>Recent Photos</h2>";
                }
                ?>
                    <script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=<?php echo $num; ?>&amp;display=latest&amp;size=s&amp;layout=h&amp;source=user&amp;user=<?php echo $flickrid; ?>"></script>
                </div>
                <!-- /home-photos -->
                
                <div class="clearer"></div>
                <?php
                }
                ?>             

            </div>
            <!-- content -->

            <!-- sidebar -->
            <div id="sidebar">
                <?php
                if(get_option('wicketpixie_home_social_buttons_enable') == 'true') {
                ?>
                    <!-- social-buttons -->
                    <?php
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
                    <?php echo "<div style='margin:0px auto 0px auto;width:",$wwidget,"px'>"; ?>
                    <?php foreach($witem as $item) { echo $item; } ?>
                    </div>
                    <div style="clear:both"></div>
                    <!-- /social-buttons -->
                <?php
                }
                ?>
                <!-- width = 340, height = 240 -->
                <?php if (get_option('wicketpixie_home_ustream_enable') == 'true'):?>
                <div id="home-youtube">
                    <?php echo "<h3>".get_option('wicketpixie_home_ustream_heading')."</h3>"; ?>
                    <?php $key = "uzhqbxc7pqzqyvqze84swcer"; ?>
                    <?php
                        $ustream_channel = get_option('wicketpixie_ustream_channel');
                        if ($ustream_channel != false && $ustream_channel != "") { $chan = $ustream_channel; } else { $trip = true; }
                        $ustream_height = get_option('wicketpixie_home_ustream_height');
                        if ($ustream_height != false && $ustream_height != "") { $height = $ustream_height; } else { $trip = true; }
                        $ustream_width = get_option('wicketpixie_home_ustream_width');
                        if ($ustream_width != false && $ustream_width != "") { $width = $ustream_width; } else { $trip = true; }
                        if (get_option($optpre.'home_ustream_autoplay') == 'true') { $autoplay = true; } else { $autoplay = false; }
                        if ($trip == true) {
                            $out = "<!-- Please go back to the Home Editor and set the settings for this widget. -->";
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
                <?php endif; ?>
                <!-- /youtube -->
                
                <!-- recent-posts -->
                <div id="sidebar1">                    
                    <div class="widget">
                        <h3>What else is new?</h3>
                        <?php query_posts('showposts=5&offset=1'); ?>
                        <?php while (have_posts()) : the_post(); ?>
                        <!-- post -->                                
                            <h5><a href="<?php the_permalink() ?>" rel="bookmark" title="Continue reading <?php the_title(); ?>"><?php the_title(); ?></a></h5>
                            <p style="font-size:1em"><?php the_time('l, F jS') ?> | <?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></p>
                        <!-- /post -->                        
                        <?php endwhile; ?>
                        <div style="padding-bottom:15px"></div>

                        <h3>Recent Comments</h3>
                        <ul class="recentcomments">
                        <?php
                        if(!$comments = wp_cache_get('recent_comments','widget')) {
                            $comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_approved = '1' ORDER BY comment_date_gmt DESC LIMIT 5");
                            wp_cache_add('recent_comments',$comments,'widget');
                        }
                        if($comments) : foreach((array)$comments as $comment) :
                            echo '<li class="recentcomments">',sprintf(_x('%1$s on %2$s','widgets'),get_comment_author_link(),'<a href="'.esc_url(get_comment_link($comment->comment_ID)).'">'.get_the_title($comment->comment_post_ID).'</a>'),'</li>';
                        endforeach; endif;
                        ?>
                        </ul>
                        <div style="padding-bottom:15px"></div>

                        <h3>Random Posts From the Archive</h3>
                        <?php query_posts('showposts=5&random=true'); ?>
                        <?php while (have_posts()) : the_post(); ?>                        
                        <h5><a href="<?php the_permalink() ?>" rel="bookmark" title="Continue reading <?php the_title(); ?>"><?php the_title(); ?></a></h5>
                        <p style="font-size:1em"><?php the_time('l, F jS') ?> | <?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></p>
                        <?php endwhile; ?>
                        <div style="padding-bottom:15px"></div>

                        <h3>Popular Tags</h3>
                        <span style="line-height:1.3em;">
                        <?php wp_tag_cloud('orderby=count&order=DESC&unit=px&smallest=10&largest=16&format=flat'); ?>
                        </span>
                        <div style="padding-bottom:15px"></div>

                        <!-- Custom Sidebar Code -->
                        <?php
                        require_once(TEMPLATEPATH .'/app/customcode.php');
                        fetchcustomcode('homesidebar.php');
                        ?>
                        <!-- /Custom Sidebar Code -->
                        <div style="padding-bottom:15px"></div>
                    </div>
                </div>
                <!-- /recent-posts -->

            </div>
            <!-- sidebar -->
<?php get_footer(); ?>
