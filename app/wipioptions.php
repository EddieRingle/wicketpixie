<?php
/**
* WicketPixie File-based Config System
* Why did I write one when WordPress has one of it's own?
* Because I can.
**/

// The directory where options are stored.
define("WIPIOPTSPATH",TEMPLATEPATH .'/app/wipioptions');
function checkdir() {
    clearstatcache();
    if(is_dir(WIPIOPTSPATH)) {
        return true;
    } else {
        mkdir(WIPIOPTSPATH,0777);
        return false;
    }
}

/**
* Checks to see if that option file contains no data.
**/
function wp_option_isempty($option) {
    $dirchk = checkdir();
    
    if(file_exists(WIPIOPTSPATH ."/$option.wp")) {
        if(file_get_contents(WIPIOPTSPATH ."/$option.wp") == "") {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
* Returns the contents of an option file
**/
function wp_get_option($option) {
    $dirchk = checkdir();
    if($dirchk == false) {
        return false;
    } elseif ($dirchk == true) {
        if(is_file(WIPIOPTSPATH ."/$option.wp")) {
            if(!wp_option_isempty($option)) {
                return file_get_contents(WIPIOPTSPATH ."/$option.wp");
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

/**
* Adds a new option file
**/
function wp_add_option($option,$value) {
    $dirchk = checkdir();
    
    if(is_file(WIPIOPTSPATH ."/$option.wp")) {
        return false;
    }
    
    file_put_contents(WIPIOPTSPATH ."/$option.wp",(string)$value);
    return true;
}

/**
* Updates the contents of an existing option file
**/
function wp_update_option($option,$newvalue) {
    $dirchk = checkdir();
    
    if($dirchk == true && is_file(WIPIOPTSPATH ."/$option.wp")) {
        file_put_contents(WIPIOPTSPATH ."/$option.wp",(string)$newvalue);
        return true;
    } else {
        return false;
    }
}

/**
* Deletes an option file
**/
function wp_delete_option($option) {
    $dirchk = checkdir();
    
    if($dirchk == false) {
        return false;
    } elseif(!file_exists(WIPIOPTSPATH ."/$option.wp")) {
        return false;
    } else {
        unlink(WIPIOPTSPATH ."/$option.wp");
        return true;
    }
}
?>
