<?php
/**
 * WicketPixie
 * (c) 2006-2011 Eddie Ringle <eddie@eringle.net>
 * Provided by Chris Pirillo <chris@pirillo.com>
 *
 * Licensed under the New BSD License.
 */

function module_top_bar()
{
?>
    <div id="top-bar">
        <div id="inner-top-bar">
            <ul>
                <li id="top-bar-subscribe">
                    <a href="#">Subscribe</a>
                    <?php
                    $blogfeed = get_bloginfo_rss('rss2_url');
                    ?>
                    <div id="subscribe" class="dropdown">
                        <ul>
                            <li><a href="<?php echo $blogfeed; ?>" title="Subscribe to my feed" class="feed" rel="nofollow">RSS Feed</a></li>
                            <li><a href="http://www.bloglines.com/sub/<?php echo $blogfeed; ?>" class="feed" rel="nofollow">Bloglines</a></li>
                			<li><a href="http://fusion.google.com/add?feedurl=<?php echo $blogfeed; ?>" class="feed" rel="nofollow">Google Reader</a></li>			
                			<li><a href="http://feeds.my.aol.com/add.jsp?url=<?php echo $blogfeed; ?>" class="feed" rel="nofollow">My AOL</a></li>
                			<li><a href="http://my.msn.com/addtomymsn.armx?id=rss&amp;ut=<?php echo $blogfeed; ?>&amp;ru=<?php echo get_option('home'); ?>" class="feed" rel="nofollow">My 
MSN</a></li>
                			<li><a href="http://add.my.yahoo.com/rss?url=<?php echo $blogfeed; ?>" class="feed" rel="nofollow">My Yahoo!</a></li>
                			<li><a href="http://www.newsgator.com/ngs/subscriber/subext.aspx?url=<?php echo $blogfeed; ?>" class="feed" rel="nofollow">NewsGator</a></li>			
                			<li><a href="http://www.pageflakes.com/subscribe.aspx?url=<?php echo $blogfeed; ?>" class="feed" rel="nofollow">Pageflakes</a></li>
                			<li><a href="http://technorati.com/faves?add=<?php echo get_option('home'); ?>" class="feed" rel="nofollow">Technorati</a></li>
                			<li><a href="http://www.live.com/?add=<?php echo $blogfeed; ?>" class="feed" rel="nofollow">Windows Live</a></li>
                		</ul>
                    </div>
                </li>
                <?php
                if (is_user_logged_in()) {
                ?>
                <li id="top-bar-admin"><a href="<?php bloginfo('wpurl'); ?>/wp-admin">Admin</a></li>
                <?php
                }
                ?>
            </ul>
            <div id="clear"></div>
        </div>
    </div>
<?php
}

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

/*
 * Here's the section where we hook all these functions in
 */

/* Enable the top bar */
add_action('wipi_before_wrapper', 'module_top_bar');

/* Post meta box (tags & categories) */
add_action('wipi_post_meta', 'module_post_meta');

?>

