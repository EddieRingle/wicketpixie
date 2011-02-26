<?php
/**
 * WicketPixie
 * (c) 2006-2011 Eddie Ringle <eddie@eringle.net>
 * Provided by Chris Pirillo <chris@pirillo.com>
 *
 * Licensed under the New BSD License.
 */

function module_post_meta()
{
?>
    <div id="post-meta">
        <div id="post-meta-tags">
            <h6>Tags</h6>
            <ul>
            <?php
            if (get_the_tag_list()) {
                the_tags('<li>','</li><li>','</li>');
            } else {
            ?>
                <li>No tags here!</li>
            <?php
            }
            ?>
            </ul>
        </div>
        <div id="post-meta-categories">
            <h6>Categories</h6>
            <?php
            if (get_the_category()) {
                the_category();
            } else {
            ?>
            <ul>
                <li>No categories here!</li>
            </ul>
            <?php
            }
            ?>
        </div>
        <div class="clear"></div>
    </div>
<?php
}

/* Here's the section where we hook all these functions in */
add_action('wipi_post_meta', 'module_post_meta');

?>

