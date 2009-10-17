<?php
/**
 * WicketPixie v2.0
 * (c) 2006-2009 Eddie Ringle,
 *               Chris J. Davis,
 *               Dave Bates
 * Provided by Chris Pirillo
 *
 * Licensed under the New BSD License.
 */
if(get_option('wicketpixie_adsense_search_enabled') != 'true') { ?>
<form id="search" method="get" action="<?php bloginfo('home'); ?>/">
	<div>
		<input type="text" name="s" id="s" value="" size="35" />
		<input name="search" type="image" src="<?php bloginfo('template_directory'); ?>/images/search-button.gif" alt="Search" id="search-submit" />
	</div>
</form>
<?php
} elseif(get_option('wicketpixie_adsense_search_enabled') == 'true' && get_option('wicketpixie_adsense_search_pubid') != "") {
    $search_page = get_bloginfo('home') . '/search/';
?>
<form id="search" action="<?php echo $search_page; ?>">
  <div>
    <input type="hidden" name="cx" value="<?php echo get_option('wicketpixie_adsense_search_pubid'); ?>" />
    <input type="hidden" name="cof" value="FORID:10" />
    <input type="hidden" name="ie" value="ISO-8859-1" />
    <input type="text" id="s" name="q" size="35" />
    <input type="image" src="<?php bloginfo('template_directory'); ?>/images/search-button.gif" alt="Search" name="sa" id="search-submit" />
  </div>
</form>

<script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=search&amp;lang=en"></script>
<?php } ?>
