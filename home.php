<?php get_header(); ?>
<?php $wp_auth_credit= wp_get_option( 'wp_auth_credit' ); ?>

			<!-- content -->
			<div id="content">
		    
		    <!-- google_ad_section_start -->
				<?php query_posts('showposts=1'); ?>
				<?php if (have_posts()) : ?>	
				<?php while (have_posts()) : the_post(); ?>
				
				<!-- post -->
				<div class="post">
					
					<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

                
					<div class="post-comments">
						<ul>
						<?php
						if(wp_get_option('wp_plug_disqus')) {
						    if(wp_get_option('wp_plug_disqus') == 1) {
						        $disqus = 1;
						    } else {
						        $disqus = 0;
						    }
						} else {
						    $disqus = 0;
						}
						?>
							<li class="post-comments-count"><a href="<?php if ($disqus == 1) ? echo '#disqus_thread' : echo '#comments'; ?>" title="View all <?php comments_number('0', '1', '%'); ?> Comments"><?php comments_number('0', '1', '%'); ?></a></li>
							<li class="post-comments-add"><a href="<?php the_permalink(); if ($disqus == 1) ? echo '#disqus_thread' : echo '#respond'; ?>" title="Add a Comment"><span>&nbsp;</span>Add a Comment</a></li>
						</ul>
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
				</div>
				<!-- /post -->
				
				<!-- google_ad_section_end -->
				<?php endwhile; ?>
				<?php endif; ?>
				
				
				<?php
				if(is_enabled_adsense() == true) {
			    ?>
			        <!-- don't ask why we're using the youtube CSS id for this. -->
			        <div id="home-youtube-full">
			        <?php
			            $adsense->wp_adsense('blog_post_bottom');
			        ?>
			        </div>
			    <?php
				} elseif(is_enabled_adsense() == false) {
				?>
				    <!-- Enable Adsense on the WicketPixie Adsense admin page. -->
				<?php
				}
				?>
				
				<div id="home-categories">
				    <h2>My Videos</h2>
					<object width="500" height="285"><param name="movie" value="http://www.youtube.com/cp/vjVQa1PpcFOi2GvexXT8XYrvBOsPoeQUt32UxT-AJgI="></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/cp/vjVQa1PpcFOi2GvexXT8XYrvBOsPoeQUt32UxT-AJgI=" type="application/x-shockwave-flash" wmode="transparent" width="500" height="285"></embed></object>
			    </div>
			    
				<div class="clearer"></div>
				
				<!-- home-photos
					Replace the user= value with your own Flickr ID. It can be obtained from http://idgettr.com -->
				<div id="home-photos">
					<h2>Recent Photos</h2>
					<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=6&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=49503157467@N01"></script>
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
				
				<!-- youtube
					width = 340, height = 293 -->
				<div id="home-youtube">
					<h3>My Live Video</h3>
					<embed src="http://www.ustream.tv/flash/live/553" width="340" height="293" wmode="transparent" flashvars="autoplay=false&amp;brand=embed" type="application/x-shockwave-flash" allowfullscreen="true" bgcolor="#000000" />
                         <p align="center"><a href="http://live.pirillo.com/">Join the Live Chat Room</a></p>
				</div>
				<!-- /youtube -->
				
				<!-- recent-posts -->
				<div id="sidebar1">					
					<div id="recent-posts" class="widget">
						<h3>Subscribe to Chris Pirillo's Newsletter!</h3>
                        <form action="http://whatcounts.com/bin/listctrl" method="post">
                            <input type=hidden name="slid" value="4EB045FEF6973258752D42129F9F915C" />
                            <input type=hidden name="cmd" value="subscribe" />
                            <input type=hidden name="goto" value="http://chris.pirillo.com/" />
                            <input type=hidden name="key" value="slid" />
                            E-Mail: <input type=text name=email size=13 />
                            <input type=hidden name="format" value="plain" />
                            <input type=submit value="Subscribe" />
                        </form>

						<h3>Recent Posts</h3>
						<?php query_posts('showposts=5&offset=1'); ?>
						<?php while (have_posts()) : the_post(); ?>
						<!-- post -->
						<div class="post">								
							<h5><a href="<?php the_permalink() ?>" rel="bookmark" title="Continue reading <?php the_title(); ?>"><?php the_title(); ?></a></h5>
							<p><?php the_time('l, F jS') ?> | <?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></p>
							<?php the_excerpt(); ?>
							<p><a href="<?php the_permalink() ?>" rel="bookmark" title="Continue reading <?php the_title(); ?>">Continue reading &raquo;</a></p>
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
			<!-- sidebar -->
			
<?php get_footer(); ?>
