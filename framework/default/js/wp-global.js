/**
 * WicketPixie
 * (c) 2006-2011 Eddie Ringle <eddie@eringle.net>
 * Provided by Chris Pirillo <chris@pirillo.com>
 *
 * Licensed under the New BSD License.
 */

/* Do all the cool JS stuff once the page is ready */
jQuery(document).ready(function ($) {
    /* Top bar dropdowns */
    $('.dropdown').hide();
    $('#inner-top-bar .dropdown').parent().toggle(
        function() {
            $('#inner-top-bar > ul > li > .dropdown').slideDown();
            return false;
        },
        function () {
            $('#inner-top-bar > ul > li > .dropdown').slideUp();
            return false;
        }
    );
});