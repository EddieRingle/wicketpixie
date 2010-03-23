		</div>
            </div>
        </div>
        <div id="footer-wrap">
            <div id="footer">
                <span class="footer-left">
                    Copyright &copy; 2009 idlesoft labs
                </span>
                <span class="footer-right">
                    Powered by <a href="http://chris.pirillo.com/wicketpixie">WicketPixie</a>.
                </span>
            </div>
        </div>
    </div>
<?php wp_footer(); ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="http://github.com/malsup/corner/raw/master/jquery.corner.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#body").corner("tr");
    $("#footer-wrap").corner("bottom");
    $("#page").corner("right bottom");
    $("#sideline").corner();
  });
</script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/superfish.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('ul.sf-menu').superfish();
  });
</script>
</body>
</html>