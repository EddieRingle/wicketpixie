<?php
/**
 * WicketPixie v2.0
 * (c) 2006-2009 Eddie Ringle,
 *               Chris J. Davis,
 *               Dave Bates
 * Provided by Chris Pirillo
 *
 * Licensed under the New BSD License.
 */

    get_header();
    $wp_auth_credit= get_option('wicketpixie_show_post_author'); ?>
			
			<!-- content -->
			<div id="content">
			<!-- google_ad_section_start -->	
				<?php if (have_posts()) : ?>	
				<?php while (have_posts()) : the_post(); ?>
                <?php $postid =  $post->ID; ?>
				
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
						<p><strong><?php the_time('l, F jS, Y') ?></strong><br/>
							by <?php the_author_posts_link(); ?><?php edit_post_link('Edit', ' - ', ''); ?></p>
						<?php } else { ?>
						<p><strong><?php the_time('l, F jS, Y') ?></strong><br/>
							at <?php the_time('g:ia') ?><?php edit_post_link('Edit', ' - ', ''); ?></p>
						<?php } ?>
					</div>
					
					<div class="clearer"></div>
					
					<?php if (get_option('wicketpixie_post_enable_aside') == 'true') { ?>
					<!-- post-ad -->
                        <div id="post-ad">
                            <?php if(is_enabled_adsense() == true) { $adsense->wp_adsense('blog_post_side'); } ?>
                            <div style="margin: 15px 0 0 5px">
                                <?php if(get_option('wicketpixie_tweetmeme_enable') == 'true') { ?>
						        <p style="margin: 0px auto;width: inherit;">
						            <script type="text/javascript" src="http://tweetmeme.com/i/scripts/button.js"></script>
						        </p>
						        <?php } ?>
						        <?php if (get_option('wicketpixie_plugin_related-posts') == 'true'):?>
						        <?php wp_related_posts(5); ?>
						        <?php endif;?>
						    </div>
                        </div>
                    <!-- /post-ad -->
                    <?php } ?>
                    
                    <div class="KonaBody">
                    <?php if(is_enabled_adsense() == true) { ?>
                    <span style="float:left;display:block;clear:none;margin-right:10px;">
                    <?php $adsense->wp_adsense('blog_post_front'); ?>
                    </span>
                    <?php } ?>
					<?php the_content(); ?>
					</div>					
					
				</div>
				<!-- /post -->
				
				<!-- google_ad_section_end -->
				
				<!-- post-meta -->
				<div class="post-meta">
					
					<?php if(get_option('wicketpixie_plugin_related-posts') == 'true' && function_exists(wp_related_posts)):?>
					<!-- related-posts -->
					<div id="related-posts">
						<h3>You might also be interested in...</h3>
							<?php wp_related_posts(); ?>
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
							<?php the_category(); ?>
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
