<?php
/**
 * WicketPixie
 * (c) 2006-2010 Eddie Ringle <eddie@eringle.net>
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
            <?php the_tags('<ul><li>','</li><li>','</li></ul>'); ?>
        </div>
        <div id="post-meta-categories">
            <h6>Categories</h6>
            <?php the_category(); ?>
        </div>
    </div>
<?php
}

/* Here's the section where we hook all these functions in */
add_action('wipi_post_meta', 'module_post_meta');

?>

