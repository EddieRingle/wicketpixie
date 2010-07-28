<?php
/**
 * WicketPixie
 * (c) 2006-2010 Eddie Ringle <eddie@eringle.net>
 * Provided by Chris Pirillo <chris@pirillo.com>
 *
 * Licensed under the New BSD License.
 */

    /* Do not delete these lines */
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if (!empty($post->post_password)) { /* if there's a password */
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  /* and it doesn't match the cookie */
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

<?php if ($comments) { ?>

	<h2><?php comments_number('No Comments', 'One Comment', '% Comments' );?></h2>

	<?php foreach ($comments as $comment) { ?>

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
			<?php if ($comment->comment_approved == '0') { ?>
			<p><em>Your comment is awaiting moderation.</em></p>
			<?php } ?>
			<?php comment_text() ?>
		</div>
		<!-- /comment content -->

	</div>
	<!-- /comment -->

	<?php } /* end for each comment */ ?>

 <?php } else { /* this is displayed if there are no comments so far */ ?>

	<?php if ('open' == $post->comment_status) { ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php } else { /* comments are closed */ ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Comments are closed.</p>

	<?php } ?>

<?php } ?>

</div>
<!-- /comments -->


<?php if ('open' == $post->comment_status) { ?>

	<!-- comment form -->
	<div id="comment-form">
        <?php comment_form(); ?>
	</div>
	<!-- /comment form -->

<?php } /* If registration required and not logged in */ ?>

<?php } /* if you delete this the sky will fall on your head */ ?>

