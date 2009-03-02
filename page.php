<?php get_header(); ?>
			
			<!-- content -->
			<div id="content">
				
				<?php if (have_posts()) : ?>	
				<?php while (have_posts()) : the_post(); ?>
				<?php  $postid =  $post->ID; ?>
				<!-- page -->
				<div class="page">					
					<h1><?php the_title(); ?></h1>
					<?php the_content('Continue reading &raquo;'); ?>					
				</div>
				<!-- /page -->
				
				<?php endwhile; ?>
				<?php endif; ?>

			</div>
			<!-- content -->

			<!-- sidebar -->
			<div id="sidebar">
				<?php get_sidebar(); ?>
			</div>
			<!-- sidebar -->
			
<?php get_footer(); ?>