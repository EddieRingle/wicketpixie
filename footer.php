		</div>
	</div>
	</div>
	<div id="footer-wrap">
	<div id="footer">
		<span class="footer-left">
		Copyright &copy; 2010 idlesoft labs
		</span>
		<span class="footer-right">
		Powered by <a href="http://chris.pirillo.com/wicketpixie">WicketPixie</a>.
		</span>
	</div>
	</div>
</div>
<?php wp_footer(); ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.curvycorners.packed.js"></script>
<script type="text/javascript">
$(document).ready(function() {
if ($.support.leadingWhitespace) {
    $("#body").corner({ tl: { radius: 0 }, tr: { radius: 10 }, br: { radius: 0 }, bl: { radius: 0 } });
    $("#footer-wrap").corner({ tl: { radius: 0 }, tr: { radius: 0 }, bl: { radius: 10 }, br: { radius: 10 } });
    $("#page").corner({ tl: { radius: 0 }, tr: { radius: 10 }, br: { radius: 10 }, bl: { radius: 10 } });
    $("#sideline").corner();
}
});
</script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/superfish.packed.js"></script>
<script type="text/javascript">
$(document).ready(function() {
$('ul.sf-menu').superfish();
});
</script>
</body>
</html>
