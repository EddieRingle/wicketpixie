<?php
/* List of the plugins included in WicketPixie which should be activated.
Make sure you add an option in functions.php as well, in the $plugins array.
Example:
    if(wp_get_option("wp_plug_pluginname")) {
        include "plugins/pluginname.php";
    }
"pluginname" is the name of the plugin.
*/

if(wp_get_option("wp_plug_intensedebate") == "1") {
    include TEMPLATEPATH ."/plugins/intensedebate/intensedebate.php";
}
if(wp_get_option("wp_plug_disqus") == "1") {
    include TEMPLATEPATH ."/plugins/disqus-comment-system/disqus.php";
}
if(wp_get_option("wp_plug_autohyperlink-urls") == "1") {
    include TEMPLATEPATH ."/plugins/autohyperlink-urls/autohyperlink-urls.php";
}
if(wp_get_option("wp_plug_kontera") == "1") {
    include TEMPLATEPATH ."/plugins/kontera/kontera.php";
}
if(wp_get_option("wp_plug_obfuscate-email") == "1") {
    include TEMPLATEPATH ."/plugins/obfuscate-email/obfuscate-email.php";
}
if(wp_get_option("wp_plug_nofollow_navigation") == "1" || wp_get_option("wp_plug_nofollow_navigation") != "0") {
    include TEMPLATEPATH ."/plugins/nofollow-navi/nofollow-navi.php";
}
if(wp_get_option("wp_plug_all_in_one_seo_pack") == "1") {
    include TEMPLATEPATH ."/plugins/all-in-one-seo-pack/all_in_one_seo_pack.php";
}
if(wp_get_option("wp_plug_falbum") == "1") {
    include TEMPLATEPATH ."/plugins/falbum/wordpress-falbum-plugin.php";
}
?>
