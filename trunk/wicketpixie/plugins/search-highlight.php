<?php
/*
Plugin Name: Search Hilite
Plugin URI: http://www.blog.mediaprojekte.de/cms-systeme/wordpress/wordpress-plugin-search-hilite/
Description: When someone is referred from a search engine like Google, Yahoo, or WordPress' own, the searchterms are highlighted. You can set the markup-style in Options pages or in your own css.  /original Version 1.5 by <a href="http://dev.wp-plugins.org/file/google-highlight/>Ryan Boren </a>. Support for Chinese Language Added by <a href="http://blog.taglife.net/2006/06/28/121/" rel="external">Xuefeng Li</a>
Version: 1.8
Author: Georg Leciejewski
Author URI: http://www.blog.mediaprojekte.de

*/
//added by  Xuefeng Li
function gb2utf8($text) {
   $charset[0]=0;
   $filename = dirname(__FILE__).'/'."gb2utf8.txt";
   $fp = fopen($filename,"r");
   while(! feof($fp)) {
      list($gb,$utf8) = fgetcsv($fp,10);
      $charset[$gb] = $utf8;
   }
   fclose($fp);

   preg_match_all("/(?:[\x80-\xff].)|[\x01-\x7f]+/",$text,$tmp);
   $tmp = $tmp[0];
   $ar = array_intersect($tmp, array_keys($charset));
   foreach($ar as $k=>$v)
   	$tmp[$k] = $charset[$v];
   return join('',$tmp);
}

function get_search_query_terms($engine = 'google') {
        $referer = urldecode($_SERVER['HTTP_REFERER']);
        $query_array = array();
        switch ($engine) {
        case 'google':
                // Google query parsing code adapted from Dean Allen's
                // Google Hilite 0.3. http://textism.com
                $query_terms = preg_replace('/^.*q=([^&]+)&?.*$/i','$1', $referer);
                $query_terms = preg_replace('/\'|"/', '', $query_terms);
                $query_array = preg_split ("/[\s,\+\.]+/", $query_terms);
                break;
        //added by  Xuefeng Li        
        case 'baidu':
                // My server don't support iconv function, so i had to use another function gb2utf8 to replace it.
        	//$referer = iconv('gb2312', 'utf-8', $referer);
                $referer =  gb2utf8($referer);
                $query_terms = preg_replace('/^.*(wd=|word=)([^&]+)&?.*$/i','$2', $referer);
                $query_terms = preg_replace('/\'|"/', '', $query_terms);
                $query_array = preg_split ("/[\s,\+\.]+/", $query_terms);
                break;

        case 'lycos':
                $query_terms = preg_replace('/^.*query=([^&]+)&?.*$/i','$1', $referer);
                $query_terms = preg_replace('/\'|"/', '', $query_terms);
                $query_array = preg_split ("/[\s,\+\.]+/", $query_terms);
                break;

        case 'yahoo':
                $query_terms = preg_replace('/^.*p=([^&]+)&?.*$/i','$1', $referer);
                $query_terms = preg_replace('/\'|"/', '', $query_terms);
                $query_array = preg_split ("/[\s,\+\.]+/", $query_terms);
                break;
                
        case 'wordpress':
                $search = get_query_var('s');
                $search_terms = get_query_var('search_terms');

                if (!empty($search_terms)) {
                        $query_array = $search_terms;
                } else if (!empty($search)) {
                        $query_array = array($search);
                } else if (empty($search)) {
                    //do nothing man
                } else {
                        $query_terms = preg_replace('/^.*s=([^&]+)&?.*$/i','$1', $referer);
                        $query_terms = preg_replace('/\'|"/', '', $query_terms);
                        $query_array = preg_split ("/[\s,\+\.]+/", $query_terms);
                }
        }
        
        return $query_array;
}

function is_referer_search_engine($engine = 'google') {
        if( empty($_SERVER['HTTP_REFERER']) && 'wordpress' != $engine ) {
                return false;
        }

        $referer = urldecode($_SERVER['HTTP_REFERER']);

        if ( ! $engine ) {
                return false;
        }

        switch ($engine) {
        case 'google':
                if (preg_match('|^http://(www)?\.?google.*|i', $referer)) {
                        return true;
                }
                break;

        case 'lycos':
                if (preg_match('|^http://search\.lycos.*|i', $referer)) {
                        return true;
                }
                break;

        case 'yahoo':
                if (preg_match('|^http://search\.yahoo.*|i', $referer)) {
                        return true;
                }
                break;
		//added by  Xuefeng Li
        case 'baidu':
                if (preg_match('|^http://(www)?\.?baidu.com|i', $referer)) {
                        return true;
                }
                break;
        case 'wordpress':
                if ( is_search() )
                        return true;

                $siteurl = get_option('home');
                if (preg_match("#^$siteurl#i", $referer))
                        return true;

                break;
        }

        return false;
}

function hilite($text) {
        $search_engines = array('wordpress', 'google', 'lycos', 'yahoo', 'baidu');

        foreach ($search_engines as $engine) {
                if ( is_referer_search_engine($engine)) {
                        $query_terms = get_search_query_terms($engine);
                        foreach ($query_terms as $term) {
                                if (!empty($term) && $term != ' ') {
                    $term = preg_quote($term, '/');
                                        if (!preg_match('/<.+>/',$text)) {
                                                $text = preg_replace('/('.$term.')/i','<span class="hilite">$1</span>',$text);
                                        } else {
                                                $text = preg_replace('/(?<=>)([^<]+)?('.$term.')/i','$1<span class="hilite">$2</span>',$text);  //taken out the \b option to also mark substrings
                                        }
                                }
                        }
                        break;
                }
        }

        return $text;
}
//insert hilite css into head
//changed by georg leciejewski
function hilite_head() {

    $css = search_hilite_getCss();

     if(get_option('search_hilite_use_own_css')=='1') 
     {	 
	 }	 
	 elseif (!empty ($css) )
     {
      echo $css ;

     }else{
       echo "";
     }
}
//admin menu Page
//added by George leciejewski
function search_hilite_options() {

    if($_POST['search_hilite_options_save']){
	 update_option('search_hilite_css',$_POST['search_hilite_css']);
	 update_option('search_hilite_use_own_css',$_POST['search_hilite_use_own_css']);
     	echo '<div class="updated"><p>Search Highlite Option erfolgreich gespeichert.</p></div>';
	}
	?>
	<div class="wrap">
	<h2>Hilite CSS Options</h2>
	<form method="post" id="search_hilite_options" action="">
		<fieldset class="options">
		<legend>Insert your own CSS highlite Style </legend>
		<table width="100%" cellspacing="2" cellpadding="5" class="editform">
		<tr valign="top">
				<th width="33%" scope="row">take CSS Style from my Stylesheet:</th>
				<td>
               <input type="checkbox" name="search_hilite_use_own_css" value='1' <?php if(get_option('search_hilite_use_own_css')=='1') { echo "checked='checked'";  } ?> ><br />Should be set if you inserted the hilite style in your existing css. If not set and Style Field below is empty. The default style (example) will be used -> Orange.
                </td>
			</tr>
			<tr valign="top">
				<th width="33%" scope="row">CSS Style:</th>
				<td>
                <textarea rows="10" cols="30" name="search_hilite_css" tabindex="4" id="search_hilite_css"><?php echo stripslashes(get_option('search_hilite_css')) ;  ?>
</textarea>
               <br /><br />  <strong>Example:</strong> <br />
                 If not set this is used as Default Style (orange highlite):
               <?php
               echo "<br />
               &lt;style type='text/css'&gt;<br />
.hilite {<br />
color: #fff;<br />
background-color: #f93;<br />
}<br />
&lt;/style&gt;
                ";
                ?>
                </td>
			</tr>
		</table>
		<p class="submit"><input type="submit" name="search_hilite_options_save" value="Save" /></p>
		</fieldset>
	</form>
	</div>
<?php
}
 //get search_hilite_css
 //added by George leciejewski
function search_hilite_getCss(){
	if($css = get_option('search_hilite_css')){
       $css = stripslashes($css) ;
		return $css;
	} else {	}
}
 //look for old google_hilite_css option and removes them
 // just for security if someone uses the old plugin
 //added by George leciejewski
function search_hilite_install(){
	if(get_option('google_hilite_css')){
      delete_option ('google_hilite_css');		
	} else {	}
	//install standart options
	add_option('search_hilite_css', " <style type='text/css'>
       .hilite {
            color: #fff;
            background-color: #f93;
            }
      </style>");
	add_option('search_hilite_use_own_css', "0");
}


//Admin Menu hook
//added by George leciejewski
function search_hilite_adminmenu(){
	add_options_page('Search Hilite Options', 'Search Hilite', 9, 'search-hilite.php', 'search_hilite_options');
}

if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
	add_action('init', 'search_hilite_install');
	}

add_action('admin_menu','search_hilite_adminmenu',1);

// Highlight text and comments:
add_filter('the_content', 'hilite');
add_filter('the_excerpt', 'hilite');
add_filter('comment_text', 'hilite');
add_action('wp_head', 'hilite_head');

?>