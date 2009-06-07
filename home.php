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
						    <ul style="margin: 15px 0 0 5px">
						        <p align="center">
						            <script type="text/javascript" src="http://tweetmeme.com/i/scripts/button.js"></script>
						        </p>
						        <?php if (wp_get_option('plug_related-posts')):?>
						        <?php wp_related_posts(5); ?>
						        <?php endif;?>
						    </ul>
					    </div>
					<?php
					} else {
					?>
					    <div id="post-ad">
					    <!-- Enable Adsense on the WicketPixie Adsense Ads admin page. -->
					        <ul style="margin: 15px 0 0 5px">
						        <?php if (wp_get_option('plug_related-posts')):?>
						        <?php wp_related_posts(5); ?>
						        <?php endif;?>
					        </ul>
					    </div>
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
						 <?php wp_related_posts(5); ?>
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
				
				<!-- Custom Code Area -->
				<?php
				if(wp_get_option('home_custom') != false && wp_get_option('home_custom') != "") {
				    echo stripslashes(wp_get_option('home_custom'));
				}
				?>
				<!-- /Custom Code Area -->
				
                <?php endwhile; ?>
                <?php endif; ?>
                
                <?php
				if(wp_get_option('home_video') != "0") { ?>
                <div id="home-categories">
				<?php
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
				    ?>
			    </div>
			    
				<div class="clearer"></div>
				<?php } ?>
				
				<?php
				if(wp_get_option('home_flickr') != "0") { ?>
				<!-- home-photos -->
				<div id="home-photos">
				<?php
				if(wp_get_option('flickrid') != false && wp_get_option('flickrid') != "") {
				    $flickrid = wp_get_option('flickrid');
				} else {
				    $flickrid = '49503157467@N01';
				}
			    if(wp_get_option('home_show_photo_heading') != "0") {
			        echo "<h2>Recent Photos</h2>";
			    }
				?>
				    <script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=6&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=<?php echo $flickrid; ?>"></script>
				</div>
				<!-- /home-photos -->
				
				<div class="clearer"></div>
				<?php
				}
				?>
				
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
                        echo '<object id="utv_o_'.$out['id'].'" height="'.$height.'" width="'.$width.'" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"><param value="http://www.ustream.tv/flash/live/'.$out['id'].'" name="movie" /><param value="true" name="allowFullScreen" /><param value="always" name="allowScriptAccess" /><param value="transparent" name="wmode" /><param value="viewcount=true&amp;autoplay='.$autoplay.'" name="flashvars" /><embed name="utv_e_'.$out['id'].'" id="utv_e_'.$out['id'].'" flashvars="viewcount=true&amp;autoplay='.$autoplay.'" height="'.$height.'" width="'.$width.'" allowfullscreen="true" allowscriptaccess="always" wmode="transparent" src="http://www.ustream.tv/flash/live/'.$out['id'].'" type="application/x-shockwave-flash" /></object>';
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
							<h5><a href="<?php the_permalink() ?>" rel="bookmark" title="Continue reading <?php the_title(); ?>"><?php the_title(); ?></a></h5>
							<p><?php the_time('l, F jS') ?> | <?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></p>
						<!-- /post -->						
						<?php endwhile; ?>
                                        </div>
                                        <div id="recent-posts" class="widget">
						
						<h3>Random Posts From the Archive</h3>
						<?php query_posts('showposts=5&random=true'); ?>
						<?php while (have_posts()) : the_post(); ?>						
						<h5><a href="<?php the_permalink() ?>" rel="bookmark" title="Continue reading <?php the_title(); ?>"><?php the_title(); ?></a></h5>
						<p><?php the_time('l, F jS') ?> | <?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></p>						
						<?php endwhile; ?>
					</div>
                                        <div id="recent-posts" class="widget">
                                        <h2>Popular Tags</h2>
					<ul>
						<?php wp_tag_cloud('orderby=count&order=DESC&unit=px&smallest=11&largest=11&format=list'); ?>
					</ul>
                                        </div>
					<!-- Custom Sidebar Code -->
				    <?php
				    require_once(TEMPLATEPATH .'/app/customcode.php');
				    fetchcustomcode('homesidebar.php');
				    ?>
				    <!-- /Custom Sidebar Code -->			
				</div>
				<!-- /recent-posts -->

			</div>
			<!-- sidebar -->
<?php get_footer(); ?>
