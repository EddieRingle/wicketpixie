<?php

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

function wp_get_option($option) {
    $dirchk = checkdir();
    if($dirchk == false) {
        return false;
    } elseif ($dirchk == true) {
        if(is_file(WIPIOPTSPATH ."/$option.wp")) {
            $optvalue = file_get_contents(WIPIOPTSPATH ."/$option.wp");
            return $optvalue;
        } else {
            return false;
        }
    }
}

function wp_add_option($option,$value) {
    $dirchk = checkdir();
    
    if(is_file(WIPIOPTSPATH ."/$option.wp")) {
        return false;
    }
    
    file_put_contents(WIPIOPTSPATH ."/$option.wp",$value);
    return true;
}

function wp_update_option($option,$newvalue) {
    $dirchk = checkdir();
    
    if($dirchk == true && is_file(WIPIOPTSPATH ."/$option.wp")) {
        file_put_contents(WIPIOPTSPATH ."/$option.wp",$newvalue);
        return true;
    } else {
        return false;
    }
}

function wp_delete_option($option) {
    $dirchk = checkdir();
    
    if($dirchk == false) {
        return false;
    } elseif(is_file(WIPIOPTSPATH ."/$option.wp")) {
        return false;
    } else {
        unlink(WIPIOPTSPATH ."/$option.wp");
        return true;
    }
}
?>
