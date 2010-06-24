<?php get_header(); ?>

<?php //get_sidebar('1'); ?>

			<div id="content">
				<?php
				if (have_posts()) : while (have_posts()) : the_post();
				?>
				<div class="post" id="post-<?php the_ID(); ?>">
					<h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
					<div class="meta">
						<?php the_date(); ?> at <?php the_time(); ?> | <?php comments_popup_link(__('0 Comments'), __('1 Comment'), __('% Comments')); ?>
					</div>
					<div class="post-content">
						<?php the_content(__('Read on...')); ?>
					</div>
				</div>
				<?php
				endwhile; endif;
				?>
			</div>

<?php get_sidebar('2'); ?>

<?php get_footer(); ?>
