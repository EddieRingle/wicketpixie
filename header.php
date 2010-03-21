<!DOCTYPE html>
<html>
<head>
<title>Layout Test</title>
<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/style.css" />
<?php
	wp_head();
	comments_popup_script(400, 400);
?>
</head>
<body>
    <div id="wrapper">
        <div id="header-wrap">
            <div id="header">
                <div id="logo">
                    <h1><a href="/">A</a></h1>
                </div>
                <div id="sideline">
                    <p>Bla blah blah bleh blee!</p>
                </div>
            </div>
        </div>
        <div id="body-wrap">
            <div id="body">
                <div id="navigation">
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About</a></li>
                    </ul>
                </div>
                <div id="page">