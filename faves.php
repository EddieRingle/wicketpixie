<?php
/**
 * Template Name: Faves
**/
?>
<?php get_header();
if ( is_user_logged_in() ) {
   	if ( 'sort' == $_REQUEST['action'] ) {
		FavesAdmin::sort( $_REQUEST );
	}
}
?>
			<!-- content -->
				
			<div id="content">
				
				<?php if (have_posts()) : ?>	
				<?php while (have_posts()) : the_post(); ?>
					
				<!-- page -->
				<div class="page">
					
					<h1><?php the_title(); ?></h1>

					<?php the_content('Continue reading &raquo;'); ?>
					
					<!-- faves -->
					<div id="faves">
						<!-- faves-feed -->
					<?php $i= 0; foreach( FavesAdmin::show_faves() as $fave ) { ?>
						<?php						
						$class= ( $i++ & 1 ) ? ' odd' : '';
						require_once (SIMPLEPIEPATH);
						$feed_path= $fave->feed_url;
						$feed= new SimplePie( (string) $feed_path, TEMPLATEPATH .(string)'/app/cache/activity' );
						$feed->handle_content_type();
							if( $feed->data ) {
						?>
						<div class="faves-feed<?php echo $class; ?>">
						    <?php $domain = explode($fave->feed_url,'/');
						    $domain = $domain[2]; ?>
							<h3><img src="http://www.google.com/s2/favicons?domain=<?php echo $domain; ?>" alt="<?php echo $fave->title; ?>" /><?php echo $fave->title; ?></h3>
							<?php if ( is_user_logged_in() ) { ?>
							<form name"re-order-<?php echo $fave->id; ?>" method="post" action="<?php the_permalink(); ?>?sort=true&amp;id=<?php echo $fave->id; ?>">
							<input type="hidden" name="action" value="sort">
							<input type="hidden" name="id" value="<?php echo $fave->id; ?>">
							<strong>Current Place: <?php echo $fave->sortorder; ?></strong> | New Place <select name="newsort" id="newsort-<?php echo $fave->id; ?>">
								<?php foreach( FavesAdmin::positions() as $place ) { ?>
									<option value="<?php echo $place->sortorder; ?>"><?php echo $place->sortorder; ?></option>
								<?php } ?>
							</select>
							<input type="submit" value="Sort" />
							</form>
							<?php } ?>					
							<ul>
							<?php
							$c= 0;
							$total= 5;
							foreach( $feed->get_items() as $entry ) {
								if( $c!= $total ) {
							?>
								<li><a href="<?php echo $entry->get_permalink(); ?>" rel="nofollow"><?php echo $entry->get_title(); $c++; ?></a></li>
							<?php
							} }
							?>
							</ul>							
						</div>
						<?php } } ?>
						<!-- /faves-feed -->
					</div>
					<!-- /faves -->
				</div>
				<!-- /page -->
				
				<?php endwhile; ?>
				<?php endif; ?>

			</div>
			<!-- content -->

			<!-- sidebar -->
			<?php get_sidebar(); ?>
			<!-- sidebar -->
<script type="text/javascript">
	jQuery(document).ready(
		function ($) {
			$('div.groupWrapper').Sortable(
				{
					accept: 'groupItem',
					helperclass: 'sortHelper',
					activeclass : 	'sortableactive',
					hoverclass : 	'sortablehover',
					handle: 'div.itemHeader',
					tolerance: 'pointer',
						onChange : function(ser) {
						},
						onStart : function() {
							$.iAutoscroller.start(this, document.getElementsByTagName('body'));
						},
						onStop : function() {
							$.iAutoscroller.stop();
						}
					}
				);
			}
	);
</script>
<?php get_footer(); ?>
