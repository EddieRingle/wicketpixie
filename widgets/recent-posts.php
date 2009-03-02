<div class="widget">
	<h3>Recent Posts</h3>
	<?php query_posts('showposts=10'); ?>
	<?php while (have_posts()) : the_post(); ?>
	<h5><a href="<?php the_permalink() ?>" rel="bookmark" title="Continue reading <?php the_title(); ?>"><?php the_title(); ?></a></h5>
	<p><?php the_time('F jS, Y') ?></p>
	<?php endwhile; ?>
</div>