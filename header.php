<?php
/**
 * WicketPixie
 * (c) 2006-2011 Eddie Ringle <eddie@eringle.net>
 * Provided by Chris Pirillo <chris@pirillo.com>
 *
 * Licensed under the New BSD License.
 */
?>
<!DOCTYPE html>
<html>
<head>
<title><?php wp_title('&laquo;', true, 'right'); bloginfo('name'); ?></title>
<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="<?php wipi_template_uri(); ?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php wipi_template_uri(); ?>/css/superfish.css" />
<!--[if lt IE 9]>
	<link rel="stylesheet" type="text/css" href="<?php wipi_template_uri(); ?>/css/ie.css" />
<![endif]-->
<!--[if IE 8]>
  <link rel="stylesheet" type="text/css" href="<?php wipi_template_uri(); ?>/css/ie8_only.css" />
<![endif]-->
<?php
	wp_head();
?>
</head>
<body>
<?php flush(); ?>
<?php wipi_before_wrapper(); ?>
<div id="wrapper">
    <?php wipi_before_header(); ?>
	<div id="header">
		<div id="logo">
		<h1><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
		</div>
		<div id="sideline">
		<p><?php bloginfo('description'); ?></p>
		</div>
	</div>
	<?php wipi_after_header(); ?>
	<div id="body">
		<div id="navigation">
		<ul class="sf-menu">
			<?php
			if (!is_home()) {
			?>
			<li><a href="<?php bloginfo('url'); ?>">Home</a></li>
			<?php
			}
			wp_list_pages('depth=3&sort_column=menu_order&title_li=');
			?>
		</ul>
		</div>
		<div id="page">
		<?php wipi_before_page(); ?>
