<?php
/**
 * WicketPixie
 * (c) 2006-2011 Eddie Ringle <eddie@eringle.net>
 * Provided by Chris Pirillo <chris@pirillo.com>
 *
 * Licensed under the New BSD License.
 */
?>
		<?php wipi_after_page(); ?>
		<div class="clear"></div>
		</div>
	</div>
	<div id="footer">
		<span class="footer-left">
		Copyright &copy; 2010 Eddie Ringle
		</span>
		<span class="footer-right">
		Powered by <a href="http://chris.pirillo.com/wicketpixie">WicketPixie</a>.
		</span>
	</div>
</div>
<?php wipi_after_wrapper(); ?>
<?php wp_footer(); ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.curvycorners.packed.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/superfish.packed.js"></script>
<script type="text/javascript">
$(document).ready(function() {
$('ul.sf-menu').superfish();
});
</script>
<script type="text/javascript" src="<?php wipi_template_uri(); ?>/js/wp-global.js"></script>
</body>
</html>