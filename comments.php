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

    // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			?>

			<p class="nocomments">This post is password protected. Enter the password to view comments.<p>

			<?php
			return;
		}
	}

	/* This variable is for alternating comment background */
	$oddcomment = 'alt';
?>

<!-- You can start editing here. -->
						
<!-- comments -->
<div id="comments">

<?php if ($comments) : ?>

	<h2><?php comments_number('No Comments', 'One Comment', '% Comments' );?></h2>

	<?php foreach ($comments as $comment) : ?>
						
	<!-- comment -->
	<div class="comment" id="comment-<?php comment_ID() ?>">		
		
		<!-- comment meta -->
		<div class="meta">			
			<h3 style="padding:5px 0 0 0;" title="Visit this author's website"><?php comment_author_link() ?></h3>
			<h5><a href="#comment-<?php comment_ID() ?>" title="Permanent Link to this comment"><strong><?php comment_time('F jS, Y') ?></strong><br/> at <?php comment_time('g:ia') ?></a></h5>			
		</div>
		<!-- /comment meta -->
		
		<!-- comment content -->
		<div class="content">			
			<?php echo get_avatar( get_comment_author_email(), $size = '36', $default = 'images/avatar.jpg' ); ?>			
			<?php if ($comment->comment_approved == '0') : ?>
			<p><em>Your comment is awaiting moderation.</em></p>
			<?php endif; ?>			
			<?php comment_text() ?>					
		</div>
		<!-- /comment content -->
	
	</div>
	<!-- /comment -->

	<?php endforeach; /* end for each comment */ ?>

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Comments are closed.</p>

	<?php endif; ?>
	
<?php endif; ?>
						
</div>
<!-- /comments -->


<?php if ('open' == $post->comment_status) : ?>
						
	<!-- comment form -->
	<div id="comment-form">

		<h2 id="respond">Leave a Comment</h2>
	
		<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
		<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">logged in</a> to post a comment.</p>
		<?php else : ?>
								
		<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
			<div>
				<?php if ( $user_ID ) : ?>
				<p class="yourname" style="width:100%;">
					Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Logout &raquo;</a>
				</p>
				<?php else : ?>
				<p class="yourname">
					<label for="author">Your Name: <?php if ($req) echo "*"; ?></label>
					<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="30" tabindex="1" class="inputfield" />
				</p>
				<p class="email">		
					<label for="email">Email Address: <?php if ($req) echo "*"; ?></label>								
					<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="30" tabindex="2" class="inputfield" />					
				</p>
				<p class="website">	
					<label for="url">Website:</label>									
					<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="30" tabindex="3" class="inputfield" />
				</p>
				<?php endif; ?>
				<p>					
					<label for="comment">Comment:</label>
					<textarea name="comment" id="comment-message" rows="10" cols="40" class="message" tabindex="4"></textarea>
				</p>
				<p>
					<input type="image" src="<?php bloginfo('template_directory'); ?>/images/button-publish.jpg" name="submit" id="comment-button" alt="Publish My Comment" />
					<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
				</p>
			</div>							
			<div class="clearer">&nbsp;</div>
			<?php do_action('comment_form', $post->ID); ?>
		</form>
						
	</div>
	<!-- /comment form -->

<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>
