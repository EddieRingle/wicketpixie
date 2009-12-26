jQuery(document).ready(function ($) {
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
