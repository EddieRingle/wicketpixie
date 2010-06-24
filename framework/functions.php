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

function wipi_get_template_directory()
{
    // Just return the default directory for now
    return get_bloginfo("template_directory") . "/framework/default";
}

function wipi_init_frontend()
{
    get_header();
    get_footer();
}

?>
