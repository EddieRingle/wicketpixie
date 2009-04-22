$(document).ready(function () {
    //Add all your global onReady Functions here
    setTimeout("replaceCSS_navbar()",300);
    //setTimeout("replaceCSS_mid()",1000);
    setTimeout("replaceCSS_prewrapper()",300);
    setTimeout("replaceCSS_wrapper()",350);
    setTimeout("replaceCSS_sidebar()",300);
});

function replaceCSS_navbar() {
    $("#navLoading").css("display","none")
        .css("background-color", "transparent");
}

function replaceCSS_sidebar() {
    $("#sidebar").css("background-color","none");
}

function replaceCSS_prewrapper() {
    $("#wrapper").css("padding","0 20px");
}
function replaceCSS_wrapper() {
    $("#wrapper").css("background-color","transparent");
}

function replaceCSS_mid() {
    $("#mid").css("background-color","transparent");
}
