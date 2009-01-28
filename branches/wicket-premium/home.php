<?php $ads= new AdsService(); ?>
<?php $video= new Videos(); ?>
<?php get_header(); ?>
<?php $wp_auth_credit= get_option( 'wp_auth_credit' ); ?>

			<!-- content -->
			<div id="content">
				
				<?php query_posts('showposts=1'); ?>
				<?php if (have_posts()) : ?>	
				<?php while (have_posts()) : the_post(); ?>
				
				<!-- post -->
				<div class="post">
					
					<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

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
					<div class="post-comments">
						<ul>
							<li class="post-comments-count" title="View all 21 Comments"><?php comments_popup_link('0', '1', '%'); ?></li>
							<li class="post-comments-add" title="Add a Comment"><a href="<?php the_permalink() ?>#respond"><span>&nbsp;</span>Add a Comment</a></li>
						</ul>
					</div>
					<div class="clearer"></div>
					<?php if( $ads->home() != '' ) { ?>
						<div id="post-ad">
							<?php echo $ads->home(); ?>
						</div>
					<?php } ?>
					<div class="KonaBody">
					<?php the_content('Continue reading &raquo;'); ?>
					</div>
				</div>
				<!-- /post -->
				
				<?php endwhile; ?>
				<?php endif; ?>
				
				<!-- home-photos
					Replace the user= value with your own Flickr ID. It can be obtained from http://idgettr.com -->
				<div id="home-photos">
					<h2>Recent Photos</h2>
					<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=6&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=49503157467@N01"></script>
				</div>
				<!-- /home-photos -->
				
				<div class="clearer"></div>
				<?php if( $video->my_videos() != '' ) { ?>
				<!-- youtube
					width = 500, height = 285 -->
				<div id="home-youtube-full">
					<h2>My Videos</h2>
					<?php echo $video->my_videos(); ?>
				</div>
				<!-- /youtube -->
				<?php } ?>
				<!-- home-categories -->
				<div id="home-categories">					
					<h2>Categories</h2>
					<ul>
						<?php wp_list_categories('orderby=name&show_count=1&title_li='); ?>
					</ul>
					<div class="clearer"></div>					
				</div>
				<!-- /home-categories -->
				
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
				<?php if( $video->sidebar_top() != '' ) { ?>
				<!-- youtube
					width = 340, height = 293 -->
				<div id="home-youtube">
					<h3>My Live Video</h3>
					<?php echo $video->sidebar_top(); ?>
				</div>
				<!-- /youtube -->
				<?php } ?>
				<!-- recent-posts -->
				<div id="sidebar1">					
					<div id="recent-posts" class="widget">
						
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
				<?php if( $ads->sidebar_home() !='' ) { ?>
				<div align="center" style="margin: 30px 0 20px 0">
					<?php echo $ads->sidebar_home(); ?>
				</div>
				<?php } ?>
				<!-- home-mybloglog
					width = 340 -->
				<div id="home-mybloglog">

				</div>
				<!-- /home-mybloglog -->

			</div>
			<!-- sidebar -->
			
<?php get_footer(); ?>