<?php
define('FALBUM', true);
define('FALBUM_STANDALONE', true);


require_once (dirname(__FILE__).'/../falbum.php');

?>
<html>
<head>
	<link rel='stylesheet' href='<?php echo $falbum->options['url_falbum_dir']?>/styles/<?php echo $falbum->options['style']?>/falbum.css' type='text/css' />
</head>

<body>
<script type="text/javascript" src="../res/falbum.js"></script>
<script type="text/javascript" src="../res/overlib.js"></script>
<script type="text/javascript" src="../res/jquery-c.js"></script>

<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

<?php $falbum->show_photos(); ?>

</body>
</html>