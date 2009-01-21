<?php get_header(); ?>
<?php $wp_auth_credit= get_option( 'wp_auth_credit' ); ?>

			<!-- content -->
			<div id="content">
				
				<div class="page">
					<h1 style="border-bottom:1px solid #ddd; padding-bottom:5px;"><?php wp_title('',true,''); ?></h1>
				</div>
				
				<?php if (have_posts()) : ?>	
				<?php while (have_posts()) : the_post(); ?>
				
				<!-- post -->
				<div class="post">
					
					<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

					<div class="post-author">
						<?php if( $wp_auth_credit == 1 ) { ?>
						<?php echo get_avatar( get_the_author_email(), $size = '36', $default = 'images/avatar.jpg' ); ?>
						<p><strong><?php the_time('l, F jS, Y') ?></strong><br/>
							by <?php the_author_posts_link(); ?></p>
						<?php } else { ?>
						<p><strong><?php the_time('l, F jS, Y') ?></strong><br/>
							at <?php the_time('g:ia') ?></p>
						<?php } ?>
					</div>
					<div class="post-comments">
						<ul>
							<li class="post-comments-count"><?php comments_popup_link('0', '1', '%'); ?></li>
							<li class="post-comments-add"><a href="<?php the_permalink() ?>#respond" title="Add a Comment"><span>&nbsp;</span>Add a Comment</a></li>
						</ul>
					</div>
					<div class="clearer"></div>

					<?php the_content('Continue reading &raquo;'); ?>
					
				</div>
				<!-- /post -->
				
				<?php endwhile; ?>

				<div class="navigation">
					<div class="left"><?php next_posts_link('<span>&nbsp;</span>Older Posts'); ?> </div>
					<div class="right"><?php previous_posts_link('<span>&nbsp;</span>Newer Posts') ?></div>
				</div>

				<?php endif; ?>

			</div>
			<!-- content -->

			<!-- sidebar -->
			<div id="sidebar">
				<?php get_sidebar(); ?>
			</div>
			<!-- sidebar -->
			
<?php get_footer(); ?>