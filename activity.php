<?php
/**
 * Template Name: Activity Stream
 * Template for the Activity Stream page
**/
require_once( ABSPATH . WPINC . '/rss-functions.php' );
$activities= new SourceAdmin;
$activities->archive_streams();
?>
<?php get_header(); ?>
			<!-- content -->
			<div id="content">
					
				<!-- page -->
				<div class="page">
					
					<?php if (have_posts()) : ?>	
					<?php while (have_posts()) : the_post(); ?>
						
					<h1><?php the_title(); ?></h1>
					<?php the_content(); ?>
					
					<!-- activity -->
					<div id="activity">
						<div>
							<?php
							$data= $activities->show_streams();
							$page= ( isset( $_GET['page'] ) && ( $_GET['page'] > 0 ) ) ? intval( $_GET['page'] ) : 1;
							$view= 50;
							$results= array_chunk( $data, $view, true );
							$test= range( 1, count( $results ) );
							krsort( $test );
							$last= array_slice( $test, 0, 1);
							$get= $_GET;
							?>							
						</div>
						<?php
						?>
						<table>
							<?php
								$day= '';
								foreach( (array)$results[$page - 1] as $stream ) {
								$source_data= $activities->source( $stream->name );
								$this_day= date("F jS", $stream->date );
								if ( $day != $this_day ) {
							?>
							<tr>
								<th colspan="3"><?php echo $this_day; ?></th>
							</tr>
							<?php } ?>
							<tr>
								<td class="activity-time"><?php echo date( 'g:i:a', $stream->date ); ?></td>
								<td class="activity-content"><div><a href="<?php echo $stream->link; ?>" rel="nofollow"><?php echo $stream->content; ?></a></div></td>	
								<td class="activity-source"><a href="<?php echo $source_data->profile_url; ?>" rel="nofollow"><img src="<?php echo $source_data->favicon; ?>" alt="<?php echo $stream->name; ?>" /></a></td>
							</tr>
							<?php $day= $this_day; } ?>
						</table>				
					</div>
					<!-- /activity -->
						
					<?php endwhile; ?>
					<?php endif; ?>
				
				</div>
				<!-- /page -->
				
				<div class="navigation">					
					<?php 
						if( $last[0] != $page ) { ?>
					<div class="left">
						<a href="<?php echo '?page=' . ( $page +1 ); ?>" title="More"><span>&nbsp;</span>More</a>
					</div>
					<?php } ?>
					<?php if( $page != 1 ) { ?>
						<div class="right">
							<a href="<?php echo '?page=' . ( $page -1 ); ?>" title="Newer"><span>&nbsp;</span>Newer</a>
						</div>
					<?php } ?>
				</div>				
				<div class="clearer"></div>

			</div>
			<!-- content -->

			<!-- sidebar -->
			<?php get_sidebar(); ?>
			<!-- sidebar -->
			
<?php get_footer(); ?>
