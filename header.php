<!DOCTYPE html>
<html>
<head>
<title><?php wp_title('&laquo;', true, 'right'); bloginfo('name'); ?></title>
<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/style.css" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/css/superfish.css" />
<?php
	wp_head();
	comments_popup_script(400, 400);
?>
</head>
<body>
<?php flush(); ?>
<div id="wrapper">
	<div id="header-wrap">
	<div id="header">
		<div id="logo">
		<h1><a href="/">WicketPixie</a></h1>
		</div>
		<div id="sideline">
		<p><?php bloginfo('description'); ?></p>
		</div>
	</div>
	</div>
	<div id="body-wrap">
	<div id="body">
		<div id="navigation">
		<ul class="sf-menu">
			<?php
			if (!is_home()) {
			?>
			<li><a href="<?php bloginfo('home'); ?>">Home</a></li>
			<?php
			}
			wp_list_pages('depth=3&sort_column=menu_order&title_li=');
			?>
		</ul>
		</div>
		<div id="page">