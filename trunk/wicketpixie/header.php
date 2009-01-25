<?php
/* Change this to wherever your blog feed is located. Default is WordPress-generated feed. */
$blogfeed = get_bloginfo_rss('rss2_url');
?>
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
	
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php echo $blogfeed; ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<link rel="shortcut icon" type="image/ico" href="<?php bloginfo('home'); ?>/favicon.ico" />	
	
	<?php
    include_once (TEMPLATEPATH . '/plugins/random-posts.php');
	include_once (TEMPLATEPATH . '/plugins/related-posts.php');
	include_once (TEMPLATEPATH . '/plugins/search-excerpt.php');
	include_once (TEMPLATEPATH . '/plugins/search-highlight.php');
    
    clearstatcache();
    if(!is_dir(ABSPATH.'wp-content/uploads/activity'))
    {
        if(!is_Dir(ABSPATH.'wp-content/uploads'))
        {
            mkdir(ABSPATH.'wp-content/uploads',0777);
        }
        mkdir(ABSPATH.'wp-content/uploads/activity',0777);
    }
    
    if(is_user_logged_in()) { ?>
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
            <?php include (TEMPLATEPATH . '/searchform.php'); ?>
		</div>
		<!-- /topbar-inner -->
		
	</div>
	<!-- /topbar -->
	
	<!-- subscribe -->
	<div id="subscribe">			
		<ul>				
			<li><a href="<?php echo $blogfeed; ?>" title="Subscribe to my feed" class="feed">RSS Feed</a></li>
			<li><a href="http://www.bloglines.com/sub/<?php echo $blogfeed; ?>" class="feed">Bloglines</a></li>
			<li><a href="http://fusion.google.com/add?feedurl=<?php echo $blogfeed; ?>" class="feed">Google Reader</a></li>			
			<li><a href="http://feeds.my.aol.com/add.jsp?url=<?php echo $blogfeed; ?>" class="feed">My AOL</a></li>
			<li><a href="http://my.msn.com/addtomymsn.armx?id=rss&ut=<?php echo $blogfeed; ?>&ru=<?php echo get_settings('home'); ?>" class="feed">My MSN</a></li>
			<li><a href="http://add.my.yahoo.com/rss?url=<?php echo $blogfeed; ?>" class="feed">My Yahoo!</a></li>
			<li><a href="http://www.newsgator.com/ngs/subscriber/subext.aspx?url=<?php echo $blogfeed; ?>" class="feed">NewsGator</a></li>			
			<li><a href="http://www.pageflakes.com/subscribe.aspx?url=<?php echo $blogfeed; ?>" class="feed">Pageflakes</a></li>
			<li><a href="http://technorati.com/faves?add=<?php echo get_settings('home'); ?>" class="feed">Technorati</a></li>
			<li><a href="http://www.live.com/?add=<?php echo $blogfeed; ?>" class="feed">Windows Live</a></li>
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