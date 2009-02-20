<?php
/* List of the plugins included in WicketPixie which should be activated.
Make sure you add an option in functions.php as well, in the $plugins array.
Example:
    if(get_option("wp_plug_pluginname")) {
        include "plugins/pluginname.php";
    }
*/

if(get_option("wp_plug_intensedebate")) {
    include TEMPLATEPATH ."/plugins/intensedebate/intensedebate.php";
}
?>