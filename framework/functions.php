<?php
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

function wipi_get_template_uri()
{
    // Just return the default directory for now
    return get_bloginfo('template_directory') . '/framework/default';
}

function wipi_get_template_path()
{
    // Just return the default directory for now
    return TEMPLATEPATH . '/framework/default';
}

function wipi_template_uri()
{
    echo wipi_get_template_uri();
}

function wipi_init_frontend()
{
    get_header();
    if (is_home()) {
        include_once wipi_get_template_path() . '/index.php';
    } elseif (is_single()) {
        include_once wipi_get_template_path() . '/single.php';
    } else {
        include_once wipi_get_template_path() . '/index.php';
    }
    get_footer();
}

?>
