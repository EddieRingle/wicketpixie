jQuery(document).ready(function ($) {
    //Add all your global onReady Functions here
    setTimeout("hideLoading()", 500);
    /* Subscribe dropdown */
    $('#subscribe').hide();
    $("#topbar-subscribe a").toggle(
    function () { $("#subscribe").slideDown();
    return false;
    },
    function () { $("#subscribe").slideUp();
    return false;
    });
    /* Share dropdown */
    $('#share').hide();
    $("#topbar-share a").toggle(
    function () { $("#share").slideDown();
    return false;
    },
    function () { $("#share").slideUp();
    return false;
    });
});


function hideLoading() {
    jQuery("#loadingFrame").css('display', 'none');
}
