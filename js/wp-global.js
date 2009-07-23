jQuery(document).ready(function ($) {
    //Add all your global onReady Functions here
    setTimeout("hideLoading()", 500);
});


function hideLoading() {
    jQuery("#loadingFrame").css('display', 'none');	
	jQuery("#wrapper").css('display','block');
}
