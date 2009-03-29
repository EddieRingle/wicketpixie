<?php get_header(); ?>
			
			<!-- content -->
			<div id="content">
				
				<?php if (have_posts()) : ?>
					
					<?php $hit_count = $wp_query->found_posts; ?>
					<p style="margin:0 20px 1.8em;">Your search for "<span class="hilite"><?php the_search_query(); ?></span>" returned <?php echo $hit_count . ' results'; ?>.</p>
				
				<?php $adsense_counter = 0; ?>
				
				<?php while (have_posts()) : the_post(); ?>
				
				<!-- post -->
				<div class="post">					
					<h2 style="margin-bottom:0;"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
					<div class="post-author">						
						<p><strong><?php the_time('l, F jS, Y') ?></strong> at <?php the_time('g:ia') ?></p>												
					</div>
					<div class="clearer"></div>
					<div class="KonaBody">
					<?php the_excerpt(); ?>
					</div>					
				</div>
				<!-- /post -->
				
				<?php if ($adsense_counter == 0) { ?>
				<div align="center" style="margin: 15px 0 30px 0">
					<?php $adsense->wp_adsense('blog_post_bottom'); ?>
				</div>
			    <?php } ?>

			<?php $adsense_counter++; ?>
				
				<?php endwhile; ?>

				<div class="navigation">
					<div class="left"><?php next_posts_link('<span>&nbsp;</span>Older Posts'); ?> </div>
					<div class="right"><?php previous_posts_link('<span>&nbsp;</span>Newer Posts') ?></div>
				</div>

				<?php else : ?>
					
				<!-- post -->
				<div class="post">
					<h1>Search Results</h1>
					<?php $hit_count = $wp_query->found_posts; ?>
					<p>Your search for "<span class="hilite"><?php the_search_query(); ?></span>" returned no results.</p>				
				</div>
				<!-- /post -->

				<?php endif; ?>

			</div>
			<!-- content -->

			<!-- sidebar -->
			<?php get_sidebar(); ?>
			<!-- sidebar -->
			
<?php get_footer(); ?>
