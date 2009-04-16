<?php get_header(); ?>
<?php $wp_auth_credit= wp_get_option( 'auth_credit' ); ?>
			
			<!-- content -->
			<div id="content">
			<!-- google_ad_section_start -->	
				<?php if (have_posts()) : ?>	
				<?php while (have_posts()) : the_post(); ?>
                <?php $postid =  $post->ID; ?>
				
				<!-- post -->
				<div class="post" style="border-bottom:0;">
				
				    <?php if(wp_get_option('global_announcement') != false && wp_get_option('global_announcement') != ""): ?>
				    <div class="highlight">
				        <?php echo wp_get_option('global_announcement'); ?>
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
						<p><strong><?php the_time('l, F jS, Y') ?></strong><br/>
							by <?php the_author_posts_link(); ?><?php edit_post_link('Edit', ' - ', ''); ?></p>
						<?php } else { ?>
						<p><strong><?php the_time('l, F jS, Y') ?></strong><br/>
							at <?php the_time('g:ia') ?><?php edit_post_link('Edit', ' - ', ''); ?></p>
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
                    <div id="KonaBody">
					<?php the_content(); ?>
					</div>					
					
				</div>
				<!-- /post -->
				
				<!-- google_ad_section_end -->
				
				<!-- post-meta -->
				<div class="post-meta">
					
					<?php if(wp_get_option('plug_related-posts') == "1"):?>
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
							<p> <?php the_category(); ?></p>
						</div>
						<!-- /post-meta-categories -->
						
						<!-- post-bigbox -->
						<!-- <div class="post-bigbox">
							ad code goes here
						</div> -->
						<!-- /post-bigbox -->
						
					</div>
					<!-- /post-meta-right -->
					
					<div class="clearer"></div>
					
				</div>
				<!-- /post-meta -->
				
				<?php endwhile; ?>				

				<?php comments_template(); ?>

				<?php endif; ?>

			</div>
			<!-- content -->

			<!-- sidebar -->
			<?php get_sidebar(); ?>
			<!-- sidebar -->
			
<?php get_footer(); ?>
