$(document).ready(function () {
    //Add all your global onReady Functions here
    setTimeout("safeLoading()",2500);
});

function safeLoading() {
    $("#mid").css("background-color","transparent");
//        .css("background-image","url(../images/mid-bg.jpg)")
//        .css("background-repeat", "no-repeat")
//        .css("background-position", "0 0");
}
