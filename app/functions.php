<?php
/**
 * WicketPixie v2.0
 * (c) 2006-2009 Eddie Ringle,
 *               Chris J. Davis,
 *               Dave Bates
 * Provided by Chris Pirillo
 *
 * Licensed under the New BSD License.
 */

function enable_widgetized_sidebar()
{
    if ( function_exists('register_sidebar') ) {
        register_sidebar(array('name'=>'sidebar_top',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
		    'before_title' => '<h3>',
		    'after_title' => '</h3>',
        ));
	    register_sidebar(array('name'=>'sidebar1',
		    'before_widget' => '<div id="%1$s" class="widget %2$s">',
		    'after_widget' => '</div>',
		    'before_title' => '<h3>',
		    'after_title' => '</h3>',
	    ));	
	    register_sidebar(array('name'=>'sidebar2',		
		    'before_widget' => '<div id="%1$s" class="widget %2$s">',
		    'after_widget' => '</div>',
		    'before_title' => '<h3>',
		    'after_title' => '</h3>',
	    ));
	    register_sidebar(array('name'=>'sidebar3',		
		    'before_widget' => '<div id="%1$s" class="widget %2$s">',
		    'after_widget' => '</div>',
		    'before_title' => '<h3>',
		    'after_title' => '</h3>',
	    ));
	    register_sidebar(array('name'=>'sidebar4',		
		    'before_widget' => '<div id="%1$s" class="widget %2$s">',
		    'after_widget' => '</div>',
		    'before_title' => '<h3>',
		    'after_title' => '</h3>',
	    ));
	    register_sidebar(array('name'=>'sidebar5',		
		    'before_widget' => '<div id="%1$s" class="widget %2$s">',
		    'after_widget' => '</div>',
		    'before_title' => '<h3>',
		    'after_title' => '</h3>',
	    ));
	    register_sidebar(array('name'=>'sidebar6',		
		    'before_widget' => '<div id="%1$s" class="widget %2$s">',
		    'after_widget' => '</div>',
		    'before_title' => '<h3>',
		    'after_title' => '</h3>',
	    ));
    }
}

function load_admin_pages()
{
    // The parent AdminPage class
    require_once(TEMPLATEPATH .'/app/admin-page.php');

    // WicketPixie Admin page
    require_once(TEMPLATEPATH .'/app/wicketpixie-admin.php');
    $a = new WiPiAdmin();
    add_action('admin_menu',array($a,'add_page_to_menu'));
    unset($a);

    /* WiPi Plugins page
    require_once(TEMPLATEPATH .'/app/wipi-plugins.php');
    $a = new WiPiPlugins();
    add_action('admin_menu',array($a,'add_page_to_menu'));
    add_plugins();
    unset($a); */

    // Adsense Settings page
    require_once(TEMPLATEPATH .'/app/adsenseads.php');
    $a = new AdsenseAdmin();
    add_action('admin_menu',array($a,'add_page_to_menu'));
    unset($a);
    register_activation_hook('/app/adsenseads.php',array('AdsenseAdmin','install'));

    // Custom Code page
    require_once(TEMPLATEPATH .'/app/customcode.php');
    $a = new CustomCodeAdmin();
    add_action('admin_menu',array($a,'add_page_to_menu'));
    unset($a);

    // Faves Manager
    require_once(TEMPLATEPATH .'/app/faves.php');
    $a = new FavesAdmin();
    add_action('admin_menu',array($a,'add_page_to_menu'));
    unset($a);
    register_activation_hook('/app/faves.php',array('FavesAdmin','install'));

    // Home Editor
    require_once(TEMPLATEPATH .'/app/homeeditor.php');
    $a = new HomeAdmin();
    add_action('admin_menu',array($a,'add_page_to_menu'));
    unset($a);

    // WicketPixie Notifications page
    require_once(TEMPLATEPATH .'/app/notify.php');
    $a = new NotifyAdmin();
    add_action('admin_menu',array($a,'add_page_to_menu'));
    unset($a);
    register_activation_hook('/app/notify.php',array('NotifyAdmin','install'));

    // Social Me Manager
    require_once(TEMPLATEPATH .'/app/sourcemanager.php' );
    $a = new SourceAdmin();
    add_action('admin_menu',array($a,'add_page_to_menu'));
    unset($a);
    register_activation_hook('/app/sourcemanager.php', array( 'SourceAdmin', 'install' ) );

    // Theme Options
    require_once(TEMPLATEPATH .'/app/theme-options.php');
    $a = new ThemeOptions();
    add_action('admin_menu',array($a,'add_page_to_menu'));
    unset($a);
    add_action('admin_head', 'wicketpixie_admin_head');
    add_action('wp_head', 'wicketpixie_wp_head');
}

function wicketpixie_add_admin_footer()
{
	echo "Thank you for using WicketPixie v".WIK_VERSION.", a free premium WordPress theme from <a href='http://chris.pirillo.com/'>Chris Pirillo</a>.<br/>";
}

function show_version_in_admin_footer()
{
    add_action('in_admin_footer', 'wicketpixie_add_admin_footer');
}

function load_widgets()
{
    if(function_exists('register_widget')) {
        // My Profiles
        require_once(TEMPLATEPATH .'/widgets/my-profiles.php');
        add_action('widgets_init','MyProfilesInit');

        // Social Badges
        require_once(TEMPLATEPATH .'/widgets/social-badges.php');
        add_action('widgets_init','SocialBadgesInit');

        // Ustream
        require_once(TEMPLATEPATH .'/widgets/ustream-widget.php');
        add_action('widgets_init','UstreamWidgetInit');

        // Social Me Feed Widgets
        include_once(TEMPLATEPATH .'/widgets/sources.php');
        foreach(SourceAdmin::collect() as $widget ) {
            if(SourceAdmin::feed_check($widget->title) == 1) {
                $source_title = $widget->title;
                $t_title = str_replace(' ','',$source_title);
	            $cleaned= strtolower( $source_title );
	            $cleaned= preg_replace( '/\W/', ' ', $cleaned );
	            $cleaned= str_replace( " ", "", $cleaned );
                if(is_file(TEMPLATEPATH .'/widgets/'.$cleaned.'.php')) {
                    add_action('widgets_init',"${t_title}Init");
                }
	        }
        }
    }
}

function get_related_posts($numToShow = 5)
{
    $tags = wp_get_post_tags($post->ID);
    if($tags) {
        $tags_ids = array();
        foreach($tags as $tag) {
            $tag_ids[] = $tag->term_id;
        }
        $args = array(
                    'tag__in' => $tags_ids,
                    'post__not_in' => array($post->ID),
                    'showposts' => $numToShow,
                    'caller_get_posts' => 1
        );
        $query = new WP_Query($args);
        if($query->have_posts()) {
            $posts = array();
            while($query->have_posts()) {
                $query->the_post();
                $posts[the_title('','',false)] = get_permalink();
            }
            return $posts;
        }
    }

    return NULL;
}

function print_related_posts($form = 'list', $numToShow = 5)
{
    $related_posts = get_related_posts($numToShow);
    if($related_posts !== NULL) {
        switch($form) {
        case 'list':
            ?>
            <ul class="related-posts">
                <?php
                foreach($related_posts as $title => $url) {
                ?>
                <li class="related-post"><a href="<?php echo $url; ?>"><?php echo $title; ?></a></li>
                <?php
                }
                ?>
            </ul>
            <?php
            break;
        default:
            break;
        }
    }
}
?>
