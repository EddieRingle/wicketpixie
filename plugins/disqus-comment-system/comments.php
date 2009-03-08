
<?php
	global $dsq_response, $dsq_sort;
	$site_url = get_option('siteurl');
?>

<div id="disqus_thread">
	<div id="dsq-content">
		<div id="dsq-post-top" style="display: none">
			<?php if ( !$dsq_response['thread_locked'] ) : ?>
				<div id="dsq-auth">
					<div class="dsq-by"><a href="http://disqus.com/" target="_blank"><img src="<?php echo DISQUS_MEDIA_URL; ?>/images/embed/dsq-button-120x19.png" alt="discussion by DISQUS" /></a></div>
					<div class="dsq-auth-header">
						<h3 id="dsq-add-new-comment">Add New Comment</h3>
						<span id="dsq-auth-as"><noscript><br />You must have JavaScript enabled to comment.</noscript></span>
					</div>
				</div>
			<?php else : ?>
				<span id="dsq-post-add">Comments for this post are closed.</span>
			<?php endif ; ?>
		</div>
		<div style="margin:10px 0">
			<a id="dsq-options-toggle" href="#" onclick="Dsq.Thread.toggleOptions(); return false"><img src="<?php echo DISQUS_MEDIA_URL; ?>/images/embed/dsq-options-plus.png" alt="" /></a>
		</div>

		<div id="dsq-options" style="display:none">
			<span id="dsq-auth-wrap"></span>
			<div id="dsq-extra-links">
				<li>
					<img src="<?php echo DISQUS_MEDIA_URL; ?>/images/embed/bullet-feed.png" alt="" /><strong>Subscribe</strong>:&nbsp;
					<a href="http://<?php echo strtolower(get_option('disqus_forum_url')) . '.' . DISQUS_DOMAIN . '/' . $dsq_response['thread_slug'] . '/latest.rss'; ?>">This Thread</a>
				</li>
				<li>
					<img src="<?php echo DISQUS_MEDIA_URL; ?>/images/embed/bullet-go.png" alt="" /><strong>Go to</strong>:&nbsp;
					<a href="<?php echo DISQUS_URL . '/track/'; ?>">My Comments</a>&nbsp;&middot;&nbsp;
					<a href="http://<?php echo strtolower(get_option('disqus_forum_url')) . '.' . DISQUS_DOMAIN . '/' . $dsq_response['thread_slug'] . '/'; ?>">Community Page</a>
				</li>
			</div>
			<div class="dsq-extra-meta">
				<?php if ( $dsq_response['num_posts'] ) : ?>
					Sort thread by:
					<select onchange="Dsq.Thread.sortBy(this.value);">
						<option value="hot" <?php if(4==$dsq_sort){echo 'selected="selected"';}?>>Hot comments</option>
						<option value="best" <?php if(3==$dsq_sort){echo 'selected="selected"';}?>>Best comments</option>
						<option value="newest" <?php if(2==$dsq_sort){echo 'selected="selected"';}?>>Newest first</option>
						<option value="oldest" <?php if(1==$dsq_sort){echo 'selected="selected"';}?>>Oldest first</option>
					</select>
				<?php endif ; ?>
			</div>
		</div>

		<h3 id="dsq-comments-count">
			<?php if ( $dsq_response['num_posts'] ) : ?>
				Viewing <?php echo $dsq_response['num_posts']; ?> Comment<?php if($dsq_response['num_posts'] != 1) { echo 's'; }; ?>
			<?php endif ; ?>
		</h3>

		<div id="dsq-alerts">
			<p id="dsq-alerts-approve" style="display: none">Thanks. Your comment is awaiting approval by a moderator.</p>
			<p id="dsq-alerts-claim" style="display: none">Do you already have an account? <a href="<?php echo DISQUS_URL; ?>/claim/">Log in and claim this comment</a>.</p>
		</div>

		<ul id="dsq-comments">
			<?php foreach ( $dsq_response['posts'] as $comment ) : ?>
			<?php
				$dsq_profile_url = DISQUS_URL . '/people/' . $comment['user']['profile'] . '/';
			?>
				<?php if ( $comment['killed'] ) : ?>
					<li style="margin-left:<?php echo $comment['depth'] * 30; ?>px" class="disqus_commentset"><em>Comment removed.</em></li>
				<?php else : ?>
					<div id="comment-<?php echo $comment['id']; ?>"></div>
					<li id="dsq-comment-<?php echo $comment['id']; ?>" style="margin-left:<?php echo $comment['depth'] * 30; ?>px" class="dsq-comment<?php if($comment['user']['is_creator']) { echo ' special'; } ?><?php if($comment['user']['is_moderator']) { echo ' dsq-moderator'; } ?>">
						<ul class="dsq-comment-rate" id="dsq-rate-loading-<?php echo $comment['id']; ?>" style="display: none"><img src="<?php echo DISQUS_MEDIA_URL; ?>/images/loading-small.gif" alt="" /></ul>
						<ul class="dsq-comment-rate" id="dsq-rate-<?php echo $comment['id']; ?>">
							<li id="dsq-rate-up-<?php echo $comment['id']; ?>"><a id="dsq-rate-up-a-<?php echo $comment['id']; ?>" class="dsq-arrows" href="#" title="Rate Up"><img src="<?php echo DISQUS_MEDIA_URL; ?>/images/embed/arrow2-up.png" alt="^" /></a></li>
							<li id="dsq-rate-down-<?php echo $comment['id']; ?>"><a id="dsq-rate-down-a-<?php echo $comment['id']; ?>" class="dsq-arrows" href="#" title="Rate Down"><img src="<?php echo DISQUS_MEDIA_URL; ?>/images/embed/arrow2-down.png" alt="v" /></a></li>
						</ul>
						<div id="dsq-comment-header-<?php echo $comment['id']; ?>" class="dsq-comment-header">
							<div class="dsq-header-avatar" id="dsq-header-avatar-<?php echo $comment['id']; ?>">
								<a id="dsq-avatar-<?php echo $comment['id']; ?>" href="<?php echo $dsq_profile_url; ?>" title="Profile">
									<img src="<?php echo $comment['user']['avatar_url']; ?>" alt="" />
								</a>
								<ul id="dsq-menu-<?php echo $comment['id']; ?>" class="dsq-menu" style="display:none">
									<?php if ( $comment['parent_id'] ) : ?>
										<li><a href="#comment-<?php echo $comment['parent_id']; ?>">Parent</a></li>
									<?php endif ; ?>
									<li><a href="#comment-<?php echo $comment['id']; ?>">Permalink</a></li>
									<li style="display: none">
										<a id="dsq-admin-toggle-<?php echo $comment['id']; ?>" class="dsq-admin-toggle" href="#">
											Admin<img src="<?php echo DISQUS_MEDIA_URL; ?>/images/embed/pointer-right.png" alt="" />
										</a>
									</li>
									<li id="dsq-admin-panel-<?php echo $comment['id']; ?>" class="dsq-admin-panel" style="display: none">
										<ul>
											<li id="dsq-admin-email-<?php echo $comment['id']; ?>" class="dsq-admin-email"></li>
											<li id="dsq-admin-ip-<?php echo $comment['id']; ?>" class="dsq-admin-ip"></li>
											<li><a id="dsq-remove-<?php echo $comment['id']; ?>" href="#">Remove&nbsp;Post</a></li>
											<?php if($comment['user']['id']) : ?>
												<li><a id="dsq-block-username-<?php echo $comment['id']; ?>" href="#">Block username</a></li>
											<?php endif; ?>
											<li><a id="dsq-block-email-<?php echo $comment['id']; ?>" href="#">Block email</a></li>
											<li><a id="dsq-block-ip-<?php echo $comment['id']; ?>" href="#">Block IP address</a></li>
										</ul>
									</li>
								</ul>
							</div>

							<cite id="dsq-cite-<?php echo $comment['id']; ?>">
								<?php if($comment['user']['url']) : ?>
									<a id="dsq-author-user-<?php echo $comment['id']; ?>" href="<?php echo $comment['user']['url']; ?>" rel="nofollow">
										<?php echo $comment['user']['display_name']; ?>
									</a>
								<?php else: ?>
									<span id="dsq-author-user-<?php echo $comment['id']; ?>">
										<?php echo $comment['user']['display_name']; ?>
									</span>
								<?php endif; ?>
							</cite>

							<span class="dsq-header-meta">
								<a id="dsq-time-<?php echo $comment['id']; ?>" class="dsq-header-time" href="#comment-<?php echo $comment['id']; ?>" title="Permalink"><?php echo $comment['date_fmt']; ?></a>
								<span id="dsq-points-<?php echo $comment['id']; ?>" class="dsq-header-points" style="display: none"><?php echo $comment['points']; ?> point<?php if($comment['points'] != 1) { echo 's'; } ?></span>
							</span>
						</div>

						<div class="dsq-comment-body">
							<p id="dsq-login-<?php echo $comment['id']; ?>" style="display:none">Please <a href="<?php echo DISQUS_URL . '/login/?next=article:' . $dsq_response['thread_id']; ?>">login</a> to rate.</p>
							<p id="dsq-comment-alert-<?php echo $comment['id']; ?>" class="dsq-comment-alert" style="display: none">
								Do you already have an account? <a href="<?php echo DISQUS_URL . '/claim/';?>">Log in and claim this comment</a>.
							</p>
							<div id="dsq-comment-message-<?php echo $comment['id']; ?>" class="dsq-comment-message"><?php echo $comment['message']; ?></div>

							<?php if($dsq_response['seesmic_enabled'] && $comment['seesmic']['id']) : ?>

								<div id='<?php echo $comment['seesmic']['id'][0]; ?>_preview'><a href="http://www.seesmic.com/video/<?php echo $comment['seesmic']['id'][0]; ?>" target='_blank' class='see_link'>&nbsp;</a>
									<div style='display:block;width:160px; height:120px; border:none; background-image:url(<?php echo $comment['seesmic']['metadata'][0]; ?>)'>
										<div id='<?php echo $comment['seesmic']['id'][0]; ?>_hide' class='seePlayOverlay' style='display:none;'>
											<img onclick="see_play_video('<?php echo $comment['seesmic']['id'][0]; ?>',false)" src='<?php echo DISQUS_MEDIA_URL; ?>/images/seesmic/stopOverlay.png' width='50'  height='50' style='cursor:pointer; cursor:hand; padding-top: 30px; padding-left: 50px' alt="" />
										</div>
										<div id='<?php echo $comment['seesmic']['id'][0]; ?>_show' class='seePlayOverlay'>
											<img onclick="see_play_video('<?php echo $comment['seesmic']['id'][0]; ?>',true)" src='<?php echo DISQUS_MEDIA_URL; ?>/images/seesmic/playOverlay.png' width='50'  height='50' style='cursor:pointer; cursor:hand; border:none; padding-top: 30px; padding-left: 50px' alt="" />
										</div>
									</div>
								</div>
								<div id='<?php echo $comment['seesmic']['id'][0]; ?>_content' style='display:block; width:100%; padding-top:5px'></div>
							<?php endif ; ?>
						</div>

						<div class="dsq-comment-footer" id="dsq-comment-footer-<?php echo $comment['id']; ?>">
							<?php if ( !$dsq_response['thread_locked'] ) : ?>
								<a href="#" id="dsq-reply-link-<?php echo $comment['id'] ?>">reply</a>
								<span id="dsq-edit-wrap-<?php echo $comment['id'] ?>" style="display: none">
									&nbsp;<a href="#" id="dsq-edit-<?php echo $comment['id'] ?>" style="display: none">edit</a>
								</span>
								<?php if ( $dsq_response['seesmic_enabled'] ) : ?>
									&nbsp;<a id="dsq-post-video-<?php echo $comment['id']; ?>" href="#" style="display: none"><img src="<?php echo DISQUS_MEDIA_URL; ?>/images/seesmic/record.png" class="dsq-record-img" alt="" /> record video comment</a>
								<?php endif ; ?>
							<?php endif ; ?>
							<span id="dsq-reblog-wrap-<?php echo $comment['id'] ?>" style="display: none">
								&nbsp;<a href="#" id="dsq-reblog-<?php echo $comment['id'] ?>" class="dsq-reblog">reblog</a>
							</span>
							<span id="dsq-post-report-<?php echo $comment['id'] ?>" style="display: none">
								&nbsp;<a id="dsq-post-report-a-<?php echo $comment['id'] ?>" href="#" class="dsq-post-report">flag</a>
							</span>
							<div id="dsq-reply-<?php echo $comment['id']; ?>"><!-- iframe .dsq-post-reply  injected here --></div>
						</div>

						<div id="dsq-hidden-data-<?php echo $comment['id']; ?>" style="display:none">
							<?php if($comment['user']['id']) : ?>
								<span style="display: none" id="dsq-hidden-clout-<?php echo $comment['id']; ?>"><?php echo $comment['user']['points']; ?></span>
								<span style="display: none" id="dsq-hidden-userurl-<?php echo $comment['id']; ?>">/people/<?php echo $comment['user']['profile']; ?>/</span>
								<span style="display: none" id="dsq-hidden-follow-<?php echo $comment['id']; ?>">/people/<?php echo $comment['user']['profile']; ?>/following/</span>

								<span style="display: none" id="dsq-hidden-blog-<?php echo $comment['id']; ?>"><?php echo $comment['user']['url']; ?></span>
								<span style="display: none" id="dsq-hidden-facebook-<?php echo $comment['id']; ?>"><?php echo $comment['user']['service_facebook']; ?></span>
								<span style="display: none" id="dsq-hidden-linkedin-<?php echo $comment['id']; ?>"><?php echo $comment['user']['service_linkedin']; ?></span>
								<span style="display: none" id="dsq-hidden-twitter-<?php echo $comment['id']; ?>"><?php echo $comment['user']['service_twitter']; ?></span>
								<span style="display: none" id="dsq-hidden-delicious-<?php echo $comment['id']; ?>"><?php echo $comment['user']['service_delicious']; ?></span>
								<span style="display: none" id="dsq-hidden-flickr-<?php echo $comment['id']; ?>"><?php echo $comment['user']['service_flickr']; ?></span>
								<span style="display: none" id="dsq-hidden-tumblr-<?php echo $comment['id']; ?>"><?php echo $comment['user']['service_tumblr']; ?></span>
							<?php else : ?>
								<span style="display: none" id="dsq-hidden-blog-<?php echo $comment['id']; ?>"><?php echo $comment['user']['url']; ?></span>
								<span style="display: none" id="dsq-hidden-userurl-<?php echo $comment['id']; ?>">/people/<?php echo $comment['user']['profile']; ?>/</span>
								<span style="display: none" id="dsq-hidden-facebook-<?php echo $comment['id']; ?>"></span>
								<span style="display: none" id="dsq-hidden-linkedin-<?php echo $comment['id']; ?>"></span>
								<span style="display: none" id="dsq-hidden-twitter-<?php echo $comment['id']; ?>"></span>
								<span style="display: none" id="dsq-hidden-delicious-<?php echo $comment['id']; ?>"></span>
								<span style="display: none" id="dsq-hidden-flickr-<?php echo $comment['id']; ?>"></span>
								<span style="display: none" id="dsq-hidden-tumblr-<?php echo $comment['id']; ?>"></span>
							<?php endif; ?>
							<span style="display: none" id="dsq-hidden-avatar-<?php echo $comment['id']; ?>"><img src="<?php echo $comment['user']['avatar_url']; ?>" alt="" /></span>
						</div>
					</li>
				<?php endif ; ?>
			<?php endforeach; ?>
		</ul>
		<div id="dsq-pagination">
<?php
			if ( $dsq_response['paginate'] && $dsq_response['pages'] > 1 ) {
				echo '<a href="#" onclick="Dsq.Thread.appendPage(2); return false">Show more comments...</a>';
			} else {
				echo '&nbsp;';
			}
?>
		</div>
		<div id="dsq-post-bottom" style="display: none">
			<?php if ( !$dsq_response['thread_locked'] ) : ?>
				<div id="dsq-auth">
					<div class="dsq-by"><a href="http://disqus.com/" target="_blank"><img src="<?php echo DISQUS_MEDIA_URL; ?>/images/embed/dsq-button-120x19.png" alt="discussion by DISQUS" /></a></div>
					<div class="dsq-auth-header">
						<h3 id="dsq-add-new-comment">Add New Comment</h3>
						<span id="dsq-auth-as"><noscript><br />You must have JavaScript enabled to comment.</noscript></span>
					</div>
				</div>
			<?php else : ?>
				<span id="dsq-post-add">Comments for this post are closed.</span>
			<?php endif ; ?>
		</div>
		<?php if ($dsq_response['linkbacks_enabled'] ) : ?>
			<h3>Trackbacks</h3>
			<p>(<a href="<?php trackback_url(); ?>" rel="trackback">Trackback URL</a>)</p>
			<ul id="dsq-references">
				<?php foreach ($comments as $comment) : ?>
					<?php $comment_type = get_comment_type(); ?>
						<?php if($comment_type != 'comment') { ?>
							<li>
								<cite><?php comment_author_link(); ?></cite>
								<p class="dsq-meta"><?php comment_date(); ?> at <?php comment_time(); ?></p>
								<p class="dsq-content"><?php comment_excerpt(); ?></p>
							</li>
						<?php } ?>
				<?php endforeach; ?>
			</ul>
		<?php endif ; ?>
	</div>
</div>


<!-- embed_thread_profile.html -->

<!-- profile -->
<div id="dsq-template-profile" class="dsq-popupdiv" style="display:none">
	<div id="dsq-popup-profile">
		<div id="dsq-popup-top">
		</div>
		<div id="dsq-popup-body" class="clearfix">
			<div id="dsq-popup-body-padding">
				<div id="dsq-popup-header">
					<a class="dsq-close-link" href="#" onclick="Dsq.Popup.hidePopup(); return false">close</a>
					<span id="dsq-profile-avatar"></span>
					<cite>
						<span id="dsq-profile-cite"></span>(<a id="dsq-profile-userurl" href="#"></a>)
					</cite>
				</div>
				<div id="dsq-profile-services">
					<a id="dsq-profile-clout" class="dsq-profile-badge" href="#"></a>
					<ul>
						<li style="display:none">
							<a id="dsq-service-blog" href="#" target="_blank">
								<img src="<?php echo DISQUS_MEDIA_URL; ?>/images/embed/services/blog.png" alt="" />
							</a>
						</li>
						<li style="display:none">
							<a id="dsq-service-facebook" href="#" target="_blank">
								<img src="<?php echo DISQUS_MEDIA_URL; ?>/images/embed/services/facebook.png" alt="" />
							</a>
						</li>
						<li style="display:none">
							<a id="dsq-service-linkedin" href="#" target="_blank">
								<img src="<?php echo DISQUS_MEDIA_URL; ?>/images/embed/services/linkedin.png" alt="" />
							</a>
						</li>
						<li style="display:none">
							<a id="dsq-service-twitter" href="#" target="_blank">
								<img src="<?php echo DISQUS_MEDIA_URL; ?>/images/embed/services/twitter.png" alt="" />
							</a>
						</li>
						<li style="display:none">
							<a id="dsq-service-delicious" href="#" target="_blank">
								<img src="<?php echo DISQUS_MEDIA_URL; ?>/images/embed/services/delicious.png" alt="" />
							</a>
						</li>
						<li style="display:none">
							<a id="dsq-service-flickr" href="#" target="_blank">
								<img src="<?php echo DISQUS_MEDIA_URL; ?>/images/embed/services/flickr.png" alt="" />
							</a>
						</li>
						<li style="display:none">
							<a id="dsq-service-tumblr" href="#" target="_blank">
								<img src="<?php echo DISQUS_MEDIA_URL; ?>/images/embed/services/tumblr.png" alt="" />
							</a>
						</li>
					</ul>
				</div>
				<div id="dsq-profile-status">
					<p class="dsq-profile-label">status via twitter</p>
					<p></p>
				</div>
				<div id="dsq-profile-recentcomments">
					<p class="dsq-profile-label">recent comments <span>(<a href="#" id="dsq-profile-follow">follow comments</a>)</span></p>
					<ul id="dsq-profile-commentlist"></ul>
				</div>
				<div class="show-more"><a href="#" id="dsq-profile-showmore">View Profile »</a></div>
				<div class="powered-by"><a href="<?php echo DISQUS_URL; ?>">Powered by <span class="disqus">Disqus</span></a>&nbsp;&middot;&nbsp;<a href="<?php echo DISQUS_URL; ?>">Learn more</a></div>
			</div> <!-- padding -->
		</div> <!-- body -->
		<div id="dsq-popup-bottom"></div>
	</div>
</div>

<!-- reblog -->
<div id="dsq-template-reblog" class="dsq-reblogdiv">
	<div id="dsq-popup-profile">
		<div id="dsq-popup-top">
		</div>
		<div id="dsq-popup-body" class="clearfix">
			<div id="dsq-popup-body-padding">
				<div id="dsq-popup-header">
					<a class="dsq-close-link" id="dsq-close-reblog" href="#" onclick="Dsq.Popup.hidePopup(); return false">close</a>
					<cite>Reblog this comment</cite>
				</div>
				<div id="dsq-reblog-form">
				</div>
				<div class="powered-by"><a href="<?php echo DISQUS_URL; ?>">Powered by <span class="disqus">Disqus</span></a>&nbsp;&middot;&nbsp;<a href="http://disqus.com/">Learn more</a></div>
			</div> <!-- padding -->
		</div> <!-- body -->
		<div id="dsq-popup-bottom"></div>
	</div>
</div>

<!-- /embed_thread_profile.html -->

<a href="http://disqus.com" class="dsq-brlink" style="padding: 0 10px !important;">blog comments powered by <span class="logo-disqus">Disqus</span></a>
<script type="text/javascript" charset="utf-8">
	var disqusMediaUrl = "<?php echo DISQUS_MEDIA_URL; ?>";
	var threadEl = document.getElementById('dsq-content');
	var disqus_url = '<?php echo get_permalink(); ?> ';
</script>
<script type="text/javascript" src="<?php echo DISQUS_API_URL; ?>/scripts/<?php echo strtolower(get_option('disqus_forum_url')); ?>/disqus.js?t=<?php echo $dsq_response['thread_slug']; ?>"></script>
<script type="text/javascript" src="<?php echo DISQUS_API_URL; ?>/api/v1/embed_reply.js?forum_url=<?php echo $dsq_response['forum_url']; ?>&thread_id=<?php echo $dsq_response['thread_id']; ?>"></script>
