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
require_once TEMPLATEPATH .'/functions.php';

class AdminPage
{
    function __construct($name,$filename,$parent = null,$arrays = array())
    {
        global $optpre;
        $this->page_title = "WicketPixie $name";
        $this->page_name = $name;
        $this->page_description = '';
        $this->filename = $filename;
        $this->parent = $parent;
        $this->arrays = $arrays;
        $this->optpre = $optpre;
    }
    
    function __destruct()
    {
        // This specific array has the potential to consume a bunch of memory,
        // so we unset it when we are done.
        unset($this->arrays);
    }
    
    function default_save_types($value)
    {
        if (isset($value['id']) && isset($_POST[$value['id']])) {
            update_option($value['id'],$_POST[$value['id']]);
        }
    }
    
    function extra_types_html($value,$checkdata)
    { ?>
        <input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php echo $checkdata; ?>" <?php if(($value['type'] == 'checkbox' || $value['type'] == 'radio') && $checkdata == 'true') { echo 'checked="checked"'; } ?> /> <?php
    }
    
    function after_form()
    {
    }
    
    function save_hook()
    {
    }
    
    function save()
    {
        foreach($this->arrays as $array) {
            if((isset($array['name']) && $array['name'] == $_POST['group']) || (!isset($array['name']) && $_POST['group'] == '')) {
                foreach($array as $value) {
                    if(is_array($value)) {
                        switch($value['type']) {
                            case 'checkbox':
                                if(isset($_POST[$value['id']])) {
                                    update_option($value['id'],'true');
                                } else {
                                    update_option($value['id'],'false');
                                }
                                break;
                            default:
                                $this->default_save_types($value);
                                break;
                        }
                    }
                }
            }
        }
        
        $this->save_hook();
        
        wp_redirect($_SERVER['PHP_SELF'] .'?page='.$this->filename.'&saved=true');
    }
    
    function add_page_to_menu()
    {
        $this->request_check();
        
        if($this->parent == null) {
            add_menu_page($this->page_title,$this->page_name,'edit_themes',$this->filename,array($this,'page_output'),get_template_directory_uri() .'/images/wicketsmall.png');
        } else {
            add_submenu_page($this->parent,$this->page_title,$this->page_name,'edit_themes',$this->filename,array($this,'page_output'));
        }
    }
    
    function page_output()
    {
        $this->page_display();
    }
    
    function request_check()
    {
        if (isset($_GET['page']) && isset($_POST['action'])) {
            if($_GET['page'] == $this->filename && $_POST['action'] == 'save') {
                check_admin_referer('wicketpixie-settings');
                $this->save();
            }
        }
    }
    
    function page_display()
    {
        ?>
        <div class="wrap">
            <div id="admin-options">
                <h2><?php echo $this->page_name; ?></h2>
                <?php echo $this->page_description; ?>
                <?php foreach($this->arrays as $array) { ?>
                <?php if (isset($array['name']) && $array['name'] != '') { echo '<h3>',$array["name"],'</h3>'; } ?>
                <?php if (isset($array['desc']) && $array['desc'] != '') { echo $array['desc']; } ?>
                <form method="post" enctype="multipart/form-data" style="padding:20px 0 40px;" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo $this->filename; ?>">
                    <?php wp_nonce_field('wicketpixie-settings'); ?>
                    <table class="form-table">
                        <?php foreach( $array as $value ) {
                            if(is_array($value)) { ?>
                        <tr valign="top">
		                    <th scope="row" style="font-size:12px;text-align:left;padding-right:10px;">
			                    <acronym title="<?php echo $value['description']; ?>"><?php echo $value['name']; ?></acronym>
    	                        </th>
		                    <td style="padding-right:10px;">
			                    <?php
			                        if (get_option($value['id'])) {
				                        $optdata = get_option($value['id']);
			                        } else { 
				                        $optdata = (isset($value['std'])) ? $value['std'] : '';
			                        }
			                    ?>
			                    <?php
			                    if($value['type'] == 'select') {
			                    ?>
			                    <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
                                    <?php
                                    foreach($value['options'] as $option) {
                                    ?>
                                    <option value="<?php echo $option; ?>" <?php if($optdata == $option) { echo 'selected="selected"'; } ?>><?php echo $option; ?></option>
			                        <?php
			                        }
			                        ?>
			                    </select>
			                    <?php
			                    } else {
			                        $this->extra_types_html($value,$optdata);
			                    } ?>
		                    </td>
	                    </tr>
                        <?php }
                        } ?>
                    </table>
                    <p class="submit">
                        <input name="save" type="submit" value="Save changes" />
                        <input type="hidden" name="action" value="save" />
                        <input type="hidden" name="group" value="<?php echo (isset($array['name'])) ? $array['name'] : ''; ?>" />
                    </p>
                </form>
                <?php } ?>
                <?php $this->after_form(); ?>
                
            </div>
            <?php include_once('advert.php'); ?>
        <?php
    }
}
?>
