<?php get_header(); ?>
<?php $wp_auth_credit= wp_get_option( 'auth_credit' ); ?>

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
				    $glob = fetchcustomcode('global_announcement.php');
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
						if(wp_get_option('plug_disqus')) {
						    if(wp_get_option('plug_disqus') == "1") {
						        $countlink="#disqus_thread";
						    } else {
						        $countlink="#comments";
						    }
						} else {
						    $countlink="#comments";
						}
						?>
							<li class="post-comments-count"><a href="<?php the_permalink(); echo $countlink; ?>" title="View all <?php comments_number('0', '1', '%'); ?> Comments"><?php comments_number('0', '1', '%'); ?></a></li>
							<li class="post-comments-add"><a href="<?php the_permalink(); echo $addlink; ?>" title="Add a Comment"><span>&nbsp;</span>Add a Comment</a></li>
						</ul>
						</div>
					</div>

					<div class="post-author">
						<?php if( $wp_auth_credit == 1 ) { ?>
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
					<?php
					if(is_enabled_adsense() == true) {
					?>
					    <div id="post-ad">
						    <?php $adsense->wp_adsense('blog_post_side'); ?>
					    </div>
					<?php
					} else {
					?>
					    <!-- Enable Adsense on the WicketPixie Adsense Ads admin page. -->
					<?php
					}
					?>
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
					
					<?php if(wp_get_option('plug_related-posts') && function_exists(wp_related_posts)):?>
					<!-- related-posts -->
					<div id="related-posts">
						<h3>You might also be interested in...</h3>
						<ul>
						 <?php wp_related_posts(); ?>
						</ul>
					</div>
					<!-- /related-posts -->
					<?php endif;?>
					
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
							<p><?php the_category(); ?></p>
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
				
				<div id="home-categories">
				<?php
				if(wp_get_option('home_video') != "0") {
				    if(wp_get_option('home_show_vid_heading') != "0") {
				        echo "<h2>My Videos</h2>";
				    }
				    if(wp_get_option('home_video_code') != false && wp_get_option('home_video_code') != "") {
        	    	            echo stripslashes(wp_get_option('home_video_code'));
				    } else {
				    ?>
				    <object width="500" height="285"><param name="movie" value="http://www.youtube.com/cp/vjVQa1PpcFOi2GvexXT8XYrvBOsPoeQUt32UxT-AJgI="></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/cp/vjVQa1PpcFOi2GvexXT8XYrvBOsPoeQUt32UxT-AJgI=" type="application/x-shockwave-flash" wmode="transparent" width="500" height="285"></embed></object>
				    <?php
				    }
				}
				?>
			    </div>
                <?php endwhile; ?>
                <?php endif; ?>
			    
				<div class="clearer"></div>
				
				<!-- home-photos -->
				<div id="home-photos">
				<?php
				if(wp_get_option('flickrid') != false && wp_get_option('flickrid') != "") {
				    $flickrid = wp_get_option('flickrid');
				} else {
				    $flickrid = '49503157467@N01';
				}
				if(wp_get_option('home_flickr') != "0") {
				    if(wp_get_option('home_show_photo_heading') != "0") {
				        echo "<h2>Recent Photos</h2>";
				    }
				?>
				    <script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=6&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=<?php echo $flickrid; ?>"></script>
				<?php
				}
				?>
				</div>
				<!-- /home-photos -->
				
				<div class="clearer"></div>
				
				<!-- home-tags -->
				<div id="home-tags">					
					<h2>Popular Tags</h2>
					<ul>
						<?php wp_tag_cloud('orderby=count&order=DESC&unit=px&smallest=11&largest=11&format=list'); ?>
					</ul>
					<div class="clearer"></div>					
				</div>
				<!-- /home-tags -->				

			</div>
			<!-- content -->

			<!-- sidebar -->
			<div id="sidebar">
				<?php
				if(wp_get_option('home_sidebar_buttons') != "0") {
				    include TEMPLATEPATH .'/widgets/sidebar-buttons.php';
				}
				?>
				<!-- width = 340, height = 240 -->
				<?php if (wp_get_option('home_ustream')):?>
				<div id="home-youtube">
					<?php echo "<h3>".wp_get_option('home_ustream_heading')."</h3>"; ?>
					<?php $key = "uzhqbxc7pqzqyvqze84swcer"; ?>
                    <?php
                        if (wp_get_option('ustreamchannel') != false && wp_get_option('ustreamchannel') != "") { $chan = wp_get_option('ustreamchannel'); } else { $trip = true; }
                        if (wp_get_option('home_ustream_height') != false && wp_get_option('home_ustream_height') != "") { $height = wp_get_option('home_ustream_height'); } else { $trip = true; }
                        if (wp_get_option('home_ustream_width') != false && wp_get_option('home_ustream_width') != "") { $width = wp_get_option('home_ustream_width'); } else { $trip = true; }
                        if (wp_get_option('home_ustream_autoplay') == "1") { $autoplay = true; } else { $autoplay = false; }
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
                        echo '<embed src="http://www.ustream.tv/flash/live/'.$out['id'].'" width="'.$width.'" height="'.$height.'" flashvars="autoplay='.$autoplay.'&amp;brand=embed" type="application/x-shockwave-flash" allowfullscreen="true" bgcolor="#000000" />';
                    ?>
                </div>
				<?php endif; ?>
				<!-- /youtube -->
				
				<!-- recent-posts -->
				<div id="sidebar1">					
					<div id="recent-posts" class="widget">
						<h3>What else is new?</h3>
						<?php query_posts('showposts=5&offset=1'); ?>
						<?php while (have_posts()) : the_post(); ?>
						<!-- post -->
						<div class="post">								
							<h5><a href="<?php the_permalink() ?>" rel="bookmark" title="Continue reading <?php the_title(); ?>"><?php the_title(); ?></a></h5>
							<p><?php the_time('l, F jS') ?> | <?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></p>
						</div>
						<!-- /post -->						
						<?php endwhile; ?>
						
						<h3>Random Posts From the Archive</h3>
						<?php query_posts('showposts=5&random=true'); ?>
						<?php while (have_posts()) : the_post(); ?>						
						<h5><a href="<?php the_permalink() ?>" rel="bookmark" title="Continue reading <?php the_title(); ?>"><?php the_title(); ?></a></h5>
						<p><?php the_time('l, F jS') ?> | <?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></p>						
						<?php endwhile; ?>
					</div>					
				</div>
				<!-- /recent-posts -->
				
				<!-- home-mybloglog
					width = 340 -->
				<div id="home-mybloglog">
				</div>
				<!-- /home-mybloglog -->

			</div>
			<!-- sidebar -->\
<?php get_footer(); ?>
