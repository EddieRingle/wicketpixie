<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
	
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

	<title><?php if (is_home()) { ?><?php bloginfo('name'); ?><?php } else { ?><?php wp_title('',true,''); ?> &raquo; <?php bloginfo('name'); ?><?php } ?></title>	

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/reset.css" type="text/css" media="screen, projection" />	
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/screen.css" type="text/css" media="screen, projection" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/print.css" type="text/css" media="print" />
	<!--[if IE]><link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/ie.css" type="text/css" media="screen, projection" /><![endif]-->
	<!--[if gte IE 7]><link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/ie7.css" type="text/css" media="screen, projection" /><![endif]-->
	<!--[if lte IE 6]><link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/ie6.css" type="text/css" media="screen, projection" /><![endif]-->	
	
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<link rel="shortcut icon" type="image/ico" href="<?php bloginfo('home'); ?>/favicon.ico" />	
	
	<?php include (TEMPLATEPATH . '/plugins/random-posts.php'); ?>
	<?php include (TEMPLATEPATH . '/plugins/related-posts.php'); ?>
	<?php include (TEMPLATEPATH . '/plugins/search-excerpt.php'); ?>
	<?php include (TEMPLATEPATH . '/plugins/search-highlight.php'); ?>
	<?php if (is_user_logged_in()) { ?>
	<script src="http://myintarweb.uservoice.com/pages/general/widgets/tab.js?alignment=left&amp;color=00BCBA" type="text/javascript"></script>
	<?php } ?>
		
<?php wp_head(); ?>	
	
</head>

<body>
	
	<!-- topbar -->
	<div id="topbar">
		
		<!-- topbar-inner -->
		<div id="topbar-inner">			
			<ul>
				<li id="topbar-subscribe"><a href="#">Subscribe</a></li>
				<li id="topbar-share"><a href="#">Bookmark/Share</a></li>
				<?php if (is_user_logged_in()) { ?><li id="topbar-admin"><a href="<?php bloginfo('wpurl'); ?>/wp-admin">Admin</a></li><?php } ?>
			</ul>		
		</div>
		<!-- /topbar-inner -->
		
	</div>
	<!-- /topbar -->
	
	<!-- subscribe -->
	<div id="subscribe">			
		<ul>				
			<li><a href="<? bloginfo('rss2_url') ?>" title="Subscribe to my feed" class="feed">RSS Feed</a></li>
			<li><a href="http://www.bloglines.com/sub/<? bloginfo('rss2_url') ?>" class="feed">Bloglines</a></li>
			<li><a href="http://fusion.google.com/add?feedurl=<? bloginfo('rss2_url') ?>" class="feed">Google Reader</a></li>			
			<li><a href="http://feeds.my.aol.com/add.jsp?url=<? bloginfo('rss2_url') ?>" class="feed">My AOL</a></li>
			<li><a href="http://my.msn.com/addtomymsn.armx?id=rss&ut=<? bloginfo('rss2_url') ?>&ru=<? echo get_settings('home'); ?>" class="feed">My MSN</a></li>
			<li><a href="http://add.my.yahoo.com/rss?url=<? bloginfo('rss2_url') ?>" class="feed">My Yahoo!</a></li>
			<li><a href="http://www.newsgator.com/ngs/subscriber/subext.aspx?url=<? bloginfo('rss2_url') ?>" class="feed">NewsGator</a></li>			
			<li><a href="http://www.pageflakes.com/subscribe.aspx?url=<? bloginfo('rss2_url') ?>" class="feed">Pageflakes</a></li>
			<li><a href="http://technorati.com/faves?add=<? echo get_settings('home'); ?>" class="feed">Technorati</a></li>
			<li><a href="http://www.live.com/?add=<? bloginfo('rss2_url') ?>" class="feed">Windows Live</a></li>
		</ul>		
	</div>
	<!-- /subscribe -->
	
	<!-- share -->
	<div id="share">
		
	</div>
	<!-- /share -->
		
	<!-- header -->
	<div id="header">
		
		<!-- header-inner -->
		<div id="header-inner">
			
			<div id="logo">
				<a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a>
			</div>
			
			<?php 
				$status= new SourceUpdate;
				$sources= new SourceAdmin;
			?>
			<?php if (function_exists('aktt_latest_tweet')) { ?>				
			<!-- status -->
			<div id="status">	
				<div id="twitter-tools">
					<?php echo get_avatar('1', $size = '36', $default = 'images/avatar.jpg'); ?>
					<p><?php aktt_latest_tweet(); ?></p>
					<div id="status-bottom">&nbsp;</div>
				</div>
			</div>
			<!-- /status -->
			<?php } elseif ($status->select()) { ?>
			<!-- status -->
			<div id="status">	
				<?php echo get_avatar('1', $size = '36', $default = 'images/avatar.jpg'); ?>
				<p><?php echo $status->display(); ?></p>
				<div id="status-bottom">&nbsp;</div>
			</div>
			<!-- /status -->
			<?php } else { ?>
			<p id="description"><?php bloginfo('description'); ?></p>
			<?php } ?>
			
			<!-- leaderboard -->
			<!-- <div id="leaderboard">
				ad code goes here
			</div> -->
			<!-- /leaderboard -->
			
		</div>
		<!-- /header-inner -->
		
	</div>
	<!-- /header -->
	
	<!-- nav -->
	<div id="nav">
		<ul>
			<?php if (is_home()) { echo ''; } else { ?><li><a href="<?php bloginfo('home'); ?>/">Home</a><?php } ?>				
			<?php	wp_list_pages('depth=1&sort_column=menu_order&title_li='); ?>
		</ul>
	</div>
	<!-- /nav -->

	<!-- wrapper -->
	<div id="wrapper">
		
		<!-- mid -->
		<div id="mid" class="content">