<?php

function wicketpixie_toplevel_admin() {
add_menu_page('WicketPixie Admin', 'WicketPixie', 'edit_themes', 'wicketpixie-admin.php', 'wicketpixie_admin_index',get_template_directory_uri() .'/images/wicketsmall.png');
}

function wicketpixie_admin_index() {
?>
<p>More content here later (list of WicketPixie admin pages available).</p>
<?php
}

?>
