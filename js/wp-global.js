$(document).ready(function () {
    //Add all your global onReady Functions here
    setTimeout("hideLoading()", 500);
});


function hideLoading() {
    $("#loadingFrame").css('display', 'none');	
	$("#wrapper").css('display','block');
}
