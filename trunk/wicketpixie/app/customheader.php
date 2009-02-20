<?php

define("CUSTOMPATH",TEMPLATEPATH ."/app/custom");
function customheader_add_admin()
{
	add_theme_page("WicketPixie Custom Header","WicketPixie Custom Header",'edit_themes',basename(__FILE__),'customheader_admin');
}

function checkfs($q,$file = NULL)
{
    clearstatcache();
    if($q == 1) {
        $isdir = file_exists(CUSTOMPATH);
        if($isdir != true) {
            mkdir(CUSTOMPATH,0777);
            return false;
        } else {
            return true;
        }
    } elseif($q == 2) {
        $isfile = file_exists(CUSTOMPATH .'/head.php');
        if($isfile != true) {
            touch(CUSTOMPATH .'/head.php');
            return false;
        } else {
            return true;
        }
    }
}

function writeto($code,$magick = false)
{
    checkfs(1);
    checkfs(2);
    
    // Write the submitted code to the file
    file_put_contents(CUSTOMPATH ."/head.php",($magick)?$code:stripslashes($code));
}

function fetchcustomheader()
{
    if(file_exists(CUSTOMPATH) && file_exists(CUSTOMPATH .'/head.php')) {
        return file_get_contents(CUSTOMPATH ."/head.php");
    } else {
        return "<!-- No custom code found, add code on the WicketPixie Custom Header admin page. -->";
    }
}
    
/**
* The admin menu for our faves system
*/
function customheader_admin()
{
    if ( $_GET['page'] == basename(__FILE__) ) {
        if ( 'add' == $_REQUEST['action'] ) {
            writeto($_POST['code']);
        }			
        elseif ( 'clear' == $_REQUEST['action'] ) {
            unlink(CUSTOMPATH .'/head.php');
        }
    }
    ?>
    <?php if ( isset( $_REQUEST['add'] ) ) { ?>
    <div id="message" class="updated fade"><p><strong><?php echo __('Custom Header saved.'); ?></strong></p></div>
    <?php } ?>
        <div class="wrap">
        
            <div id="admin-options">
                <h2><?php _e('Custom Header'); ?></h2>
                <p>Enter HTML markup or PHP code that you would like to appear between the &lt;head&gt; and &lt;/head&gt; tags of your site.</p>
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=customheader.php&amp;add=true" class="form-table">
                        <h2>Edit Custom Header file</h2>
                        <p><textarea name="code" id="code" style="border: 1px solid #999999;" cols="80" rows="25" /><?php echo fetchcustomheader(); ?></textarea></p>
                        <p class="submit">
                            <input name="save" type="submit" value="Save Custom Header" /> 
                            <input type="hidden" name="action" value="add" />
                        </p>
                    </form>
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=customheader.php&amp;clear=true" class="form-table">
                        <h2>Clear custom header</h2>
                        <p>WARNING: This will delete all custom code you have entered for your header, if you want to continue, click 'Clear Custom Header'</p>
                        <p class="submit">
                            <input name="clear" type="submit" value="Clear Custom Header" />
                            <input type="hidden" name="action" value="clear" />
                        </p>
                    </form>
            </div>
            <?php include_once('advert.php'); ?>
<?php
}

/**
* This is what is displayed in the header
**/
function wp_customheader()
{
    echo fetchcustomheader();
}

add_action('admin_menu', 'customheader_add_admin');
?>