$(document).ready(function() {
	$('a').each(function (idx) {
		if ($(this).attr('href').indexOf('#disqus_thread') >= 0) {
			count = parseInt($(this).html());
			$(this).html(count);
		}
	});
});
