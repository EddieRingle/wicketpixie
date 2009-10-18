<?php
/**
 * WicketPixie v1.3
 * (c) 2006-2009 Eddie Ringle,
 *               Chris J. Davis,
 *               Dave Bates
 * Provided by Chris Pirillo
 *
 * Licensed under the New BSD License.
 */
/**
* WicketPixie Home Editor
* Now you finally do not have to dig into the template files to
* modify your home page! Whee! \o/
**/

// Arrays that hold our options
$homeoptions = array(
    array(
    "name" => "Flickr Badge",
    "description" => "Display the Flickr badge on the home page.",
    "id" => 'wicketpixie_home_flickr_enable',
    "std" => 'false',
    "type" => "checkbox"),
    array(
    'name' => '# of Flickr Images',
    'description' => 'Select how many images will be displayed in the Flickr badge.',
    'id' => 'wicketpixie_home_flickr_number',
    'std' => '5',
    'type' => 'select',
    'options' => array('3','4','5','6')),
    array(
    "name" => "Video Embed",
    "description" => "Enable the Video Embed Code entered below.",
    "id" => 'wicketpixie_home_video_enable',
    "std" => 'false',
    "type" => "checkbox"),
    array(
    "name" => "Video Object Code",
    "description" => "Enter code for a video object. For example, a YouTube custom player.",
    "id" => 'wicketpixie_home_video_code',
    "std" => "",
    "type" => "textarea"),
    array(
    "name" => "Show 'My Videos' heading",
    "description" => "Show the 'My Videos' heading above the video embed.",
    "id" => 'wicketpixie_home_show_video_heading',
    "std" => 'false',
    "type" => "checkbox"),
    array(
    "name" => "Show 'Recent Photos' heading",
    "description" => "Show the 'Recent Photos' heading above the video embed.",
    "id" => 'wicketpixie_home_show_photo_heading',
    "std" => 'false',
    "type" => "checkbox"),
    array(
    "name" => "Enable Social Buttons Widget",
    "description" => "Show the Social Buttons Widget in the homepage sidebar.",
    "id" => 'wicketpixie_home_social_buttons_enable',
    "std" => 'false',
    "type" => "checkbox"),
    array(
    "name" => "Ustream Widget",
    "description" => "Check to enable the Ustream embed on the homepage sidebar.",
    "id" => 'wicketpixie_home_ustream_enable',
    "std" => 'false',
    "type" => "checkbox"),
    array(
    "name" => "Autoplay Ustream",
    "description" => "Check if you want the Ustream object to automatically play on page load.",
    "id" => 'wicketpixie_home_ustream_autoplay',
    "std" => 'false',
    "type" => "checkbox"),
    array(
    "name" => "Ustream Object Heading",
    "description" => "The heading that will appear above the Ustream Object.",
    "id" => 'wicketpixie_home_ustream_heading',
    "std" => "Live Video",
    "type" => "textbox"),
    array(
    "name" => "Ustream Object Height",
    "description" => "Enter height of the Ustream object in pixels. 293px is recommended.",
    "id" => 'wicketpixie_home_ustream_height',
    "std" => "293",
    "type" => "textbox"),
    array(
    "name" => "Ustream Object Width",
    "description" => "Enter width of the Ustream object in pixels. 340px is recommended.",
    "id" => 'wicketpixie_home_ustream_width',
    "std" => "340",
    "type" => "textbox"),
    array(
    "name" => "Custom Code",
    "description" => "Content that is displayed after the post meta data but before the Flickr Widget, Embedded Video, etc.",
    "id" => 'wicketpixie_home_custom_code',
    "std" => "<!-- No custom code yet... -->",
    "type" => "textarea")
);

class HomeAdmin extends AdminPage {

    function __construct()
    {
        parent::__construct('Home Editor','homeeditor.php','wicketpixie-admin.php',array($GLOBALS['homeoptions']));
    }
    
    function request_check()
    {
        parent::request_check();
        require_once(TEMPLATEPATH .'/app/customcode.php');
        if (isset($_POST['save-custom-code'])) {
            writeto($_POST['code'],'homesidebar.php');
        } elseif (isset($_POST['clear-custom-code'])) {
            unlink(CUSTOMPATH .'/homesidebar.php');
        }
    }
    
    function after_form()
    {
		require_once(TEMPLATEPATH .'/app/customcode.php');
	    ?>
	    <h3>Custom Sidebar Code</h3>
        <p>Enter HTML markup, PHP code, or JavaScript that you would like to appear between the after the Recent Posts section of the homepage sidebar.</p>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo $this->filename; ?>" class="form-table">
        <?php wp_nonce_field('wicketpixie-settings'); ?>
            <h4>Edit Custom Sidebar code</h4>
            <p><textarea name="code" id="code" style="border: 1px solid #999999;" cols="80" rows="25" /><?php echo fetchcustomcode("homesidebar.php",true); ?></textarea></p>
            <p class="submit">
                <input name="save" type="submit" value="Save Custom Sidebar code" /> 
                <input type="hidden" name="save-custom-code" value="true" />
                <input type="hidden" name="file" value="homesidebar" />
            </p>
        </form>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo $this->filename; ?>" class="form-table">
        <?php wp_nonce_field('wicketpixie-settings'); ?>
            <h4>Clear Custom Sidebar code</h4>
            <p>WARNING: This will delete all custom code you have entered to appear after the Recent Posts section of the homepage sidebar, if you want to continue, click 'Clear Custom Sidebar code'</p>
            <p class="submit">
                <input name="clear" type="submit" value="Clear Custom Sidebar code" />
                <input type="hidden" name="clear-custom-code" value="true" />
                <input type="hidden" name="file" value="homesidebar" />
            </p>
        </form>
        <?php
    }
    
    function __destruct()
    {
        parent::__destruct();
        unset($GLOBALS['homeoptions']);
    }
}
?>
