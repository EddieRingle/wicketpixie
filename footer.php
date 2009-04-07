		</div>
		<!-- /mid -->
		
		<div class="clearer"></div>
		
	</div>
	<!-- wrapper -->
	
	<!-- footer -->
	<div id="footer">
		<p id="footer-credits" class="left">&copy; 2008 <?php bloginfo('name'); ?>, All Rights Reserved</p>		
		<p id="footer-meta" class="right"><a href="http://code.idlesoft.net/projects/wicketpixie/issues">Bugs or Suggestions?</a> - Powered by <a href="http://chris.pirillo.com/wicketpixie">Wicketpixie</a> provided by <a href="http://chris.pirillo.com">Chris</a></p>		
		<div class="clearer"></div>		
	</div>
	<!-- footer -->
	
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/suckerfish-keyboard.js"></script>
	<!--[if lte IE 6]><script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/suckerfish-ie.js"></script><![endif]-->
	<script type="text/javascript">
		$(document).ready(function() {
		  $('#subscribe').hide();
		  $("#topbar-subscribe a").toggle(
		    function () { $("#subscribe").animate({ height: "show", duration: 700, easing:"easeInQuad"}); 
		    return false; 
		  },
		    function () { $("#subscribe").animate({ height: "hide", duration: 700, easing:"easeOutQuad"}); 
		    return false; 
		  });
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
		  $('#share').hide();
		  $("#topbar-share a").toggle(
		    function () { $("#share").animate({ height: "show", duration: 700, easing:"easeInQuad"}); 
		    return false; 
		  },
		    function () { $("#share").animate({ height: "hide", duration: 700, easing:"easeOutQuad"}); 
		    return false; 
		  });
		});
	</script>
	
<?php wp_footer(); ?>
<?php echo "\n"; ?>
<?php wp_customfooter(); ?>
<?php echo "\n"; ?>
</body>
</html>
