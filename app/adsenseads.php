<?php
/**
 * WicketPixie v1.2
 * (c) 2006-2009 Eddie Ringle,
 *               Chris J. Davis,
 *               Dave Bates
 * Provided by Chris Pirillo
 *
 * Licensed under the New BSD License.
 */
class AdsenseAdmin extends AdminPage
{

    function __construct()
    {
        parent::__construct('AdSense Settings','adsenseads.php','wicketpixie-admin.php',null);
    }
    
    function page_output()
    {
        $this->AdsenseMenu();
    }
    
    function __destruct()
    {
        parent::__destruct();
    }
    
	/**
	* Here we install the tables and initial data needed to
	* power our special functions
	*/
	 function install() {
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$table= $wpdb->prefix . 'wik_adsense';

        $wipi_adsense_db_version = '1.2p1';
		$q= '';
		if( $wpdb->get_var( "show tables like '$table'" ) != $table ) {
			$q= "CREATE TABLE " . $table . "( 
				id int NOT NULL AUTO_INCREMENT,
				ad_code VARCHAR(512) NOT NULL,
			    placement VARCHAR(255) NOT NULL,
                sortorder smallint(9) NOT NULL,
				UNIQUE KEY id (id)
			);";
		}
		if( $q != '' ) {
			dbDelta( $q );
		}
		if ($wipi_adsense_db_version != get_option('wicketpixie_adsense_db_version')) {
		    $q= "CREATE TABLE " . $table . "( 
				id int NOT NULL AUTO_INCREMENT,
				ad_code VARCHAR(512) NOT NULL,
			    placement VARCHAR(255) NOT NULL,
                sortorder smallint(9) NOT NULL,
				UNIQUE KEY id (id)
			);";

			dbDelta($q);
			update_option('wicketpixie_adsense_db_version',$wipi_adsense_db_version);
		}
	}
	
	 function check() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_adsense';
		if( $wpdb->get_var( "show tables like '$table'" ) == $table ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	 function count() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_adsense';
		$total= $wpdb->get_results( "SELECT ID as count FROM $table" );
		if (isset($total[0])) {
		    return $total[0]->count;
		} else {
		    return 0;
		}
	}
	
	// Adds an ad slot to the database
	 function add( $_REQUEST ) {
		global $wpdb;
		
		$args= $_REQUEST;		
		$table= $wpdb->prefix . 'wik_adsense';
        if($args['ad_code'] == "Ad Code") $args['ad_code'] = "";
		if( !$wpdb->get_var( "SELECT id FROM $table WHERE ad_code = '" . $args['ad_code'] . "'" ) ) {
			$id= $wpdb->get_var( "SELECT sortorder FROM $table ORDER BY sortorder DESC LIMIT 1" );
			$new_id= ( $id + 1 );
			
			$i= "INSERT INTO " . $table . " (id,ad_code,placement,sortorder) VALUES('', '" 
				. $args['ad_code'] . "','"
                . $args['placement'] . "',"
				. $new_id . ")";
			$query= $wpdb->query( $i );
		}
	}
	
	// Turns WicketPixie's AdSense features on and off
	function toggle() {
	    if(get_option('wicketpixie_enable_adsense')) {
	        if(get_option('wicketpixie_enable_adsense') == 'true') {
	            update_option('wicketpixie_enable_adsense','false');
	        } elseif(get_option('wicketpixie_enable_adsense') == 'false') {
	            update_option('wicketpixie_enable_adsense','true');
	        }
	    } else {
	        add_option('wicketpixie_enable_adsense','true');
	    }
	    wp_redirect($_SERVER['PHP_SELF'] .'?page='.$this->filename.'&toggled=true');
	}
	
	 function collect() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_adsense';
		$sources= $wpdb->get_results( "SELECT * FROM $table" );
		if( is_array( $sources ) ) {
			return $sources;
		} else {
			return array();
		}
	}
	
	 function gather( $id ) {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_adsense';
		$gather= $wpdb->get_results( "SELECT * FROM $table WHERE id= $id" );
		return $gather;
	}
	
	 function burninate( $id ) {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_adsense';
		$d= $wpdb->query( "DELETE FROM $table WHERE id = $id" );
		$trogdor= $wpdb->query( $d );
	}
	
	/**
	* Method to grab all of our lifestream data from the DB.
	* <code>
	* foreach( $sources->show_streams() as $stream ) {
	*	// do something clever
	* }
	* </code>
	*/
	
	 function positions() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_adsense';
		$numbers= $wpdb->get_results( "SELECT sortorder FROM $table ORDER BY sortorder ASC" );
		return $numbers;
	}
	
	 function sort( $_REQUEST ) {
		global $wpdb;
		$args= $_REQUEST;
		$table= $wpdb->prefix . 'wik_adsense';
		$orig_sort= $wpdb->get_results( "SELECT sortorder FROM $table WHERE id= " . $args['id'] );
		$old_value= $orig_sort[0]->sortorder;
		if( $orig_sort ) {
			$bump_up= $wpdb->query( "UPDATE $table SET sortorder= sortorder + 1 WHERE sortorder > " . $args['newsort'] );
			$update= $wpdb->query( "UPDATE $table SET sortorder= ". ( $args['newsort'] + 1 ) . " WHERE id= " . $args['id'] );
			$bump_down= $wpdb->query( "UPDATE $table SET sortorder= sortorder -1 WHERE sortorder > " . $old_value );
		}
	}
	
	function adsearch()
    {
        $o1 = 'wicketpixie_adsense_search_enabled';
        $o2 = 'wicketpixie_adsense_search_pubid';
        
        if(isset($_POST[$o1])) {
            update_option($o1,'true');
        } else {
            update_option($o1,'false');
        }
        
        update_option($o2,$_POST[$o2]);
        
        wp_redirect($_SERVER['PHP_SELF'] .'?page='.$this->filename.'&saved=true');
    }
	
	function wp_adsense($placement) {
	    global $wpdb;
	    $table = $wpdb->prefix . 'wik_adsense';
	    $ad_code = $wpdb->get_var("SELECT ad_code FROM $table WHERE placement= '$placement' LIMIT 1");
	    
	    if ($ad_code != "") {
            echo $ad_code;
        } elseif ($ad_code == "") {
            echo '<!-- No ad code found for this type, set one up on the WicketPixie AdSense Settings page. -->';
        } else {
            if($placement == 'blog_header') {
	            $width = "728";
	            $height = "90";
	            $ad_id = '5760307022';
	        } elseif($placement == 'blog_post_side') {
	            $width = "120";
	            $height = "240";
	            $ad_id = '3837687211';
	        } elseif($placement == 'blog_post_bottom') {
	            $width = "300";
	            $height = "250";
	            $ad_id = '0722333443';
	        } elseif($placement == 'blog_sidebar') {
	            $width = "120";
	            $height = "600";
	            $ad_id = '7794173943';
	        } else {
	            $width = "";
	            $height = "";
	            echo "<!-- An error has occurred. Check your settings on WicketPixie's AdSense Settings page. -->";
	            return NULL;
	        }
	        // The JavaScript for the ad
	        $codeblock = "<script type='text/javascript'><!--
    google_ad_client = 'pub-7561297527511227';
    google_ad_slot = '$ad_id';
    google_ad_width = $width;
    google_ad_height = $height;
    google_color_border = 'FFFFFF';
    //-->
    </script>
    <script type='text/javascript'
    src='http://pagead2.googlesyndication.com/pagead/show_ads.js'>
    </script>";

            echo $codeblock;
        }
	}
	
	function request_check()
	{
	    $adsense = new AdsenseAdmin;
		if (isset($_GET['page']) && $_GET['page'] == basename(__FILE__)) {
		    if (isset($_POST['action'])) {
		        switch ($_POST['action']) {
	            case 'add':
				    $adsense->add( $_REQUEST );
				    break;
				case 'toggle':
			        $adsense->toggle();
			        break;
			    case 'delete':
				    $adsense->burninate( $_REQUEST['id'] );
				    break;
				case 'install':
			        $adsense->install();
			        break;
			    case 'adsearch':
			        $adsense->adsearch();
			        break;
			    default:
			        break;
			    }
			}
		}
	}
	
	/**
	* The admin menu for our AdSense system
	*/
	 function adsenseMenu() {
	    $adsense = new AdsenseAdmin;
		?>
		<?php if ( isset( $_REQUEST['add'] ) ) { ?>
		<div id="message" class="updated fade"><p><strong><?php echo __('Ad code added.'); ?></strong></p></div>
		<?php } ?>
			<div class="wrap">
			
				<div id="admin-options">
					<h2><?php _e('AdSense Settings'); ?></h2>
                    <p>Here you can add in your AdSense information and ad code so it can be displayed
                    on your blog. Need more help? <a href="#explain" title="Click for more info" id="explaintext">It's only a click away</a>.</p>
					<div id="explain">
                        <h3>For those that need help, here's a rundown:</h3>
                            <ol>
                                <li>Create an ad slot after logging into Google AdSense.</li>
                                <li>On this page, enter the ad code given to you in the correct field.</li>
                                <li>Enjoy your monetized blog.</li>
                            </ol>
					</div>
					<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=adsenseads.php&toggle=true" class="form-table">
                    <?php wp_nonce_field('wicketpixie-settings'); ?>
					<h3>Toggle AdSense Ads</h3>
					<p>One click button to disable the showing of AdSense ads.</p>
					<?php
					if(get_option('wicketpixie_enable_adsense')) {
					    if(get_option('wicketpixie_enable_adsense') == "true") {
					        $val = "off";
					    } else {
					        $val = "on";
					    }
					} else {
					    $val = "on";
					}
					?>
					<p class="submit">
					<input type="submit" name="toggle" value="Turn <?php echo $val; ?> AdSense Ads" />
					<input type="hidden" name="action" value="toggle" />
					</p>
					</form>
					<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=adsenseads.php&adsearch=true" class="form-table">
					<?php wp_nonce_field('wicketpixie-settings'); ?>
					<h3>AdSense for Search</h3>
					<p>
					    Here you can enable and configure AdSense for Search to replace the WordPress Search in your blog. This can be a bit confusing, so here are some tips:
					    <ul style="list-style-type:disc;margin-left:2em;">
					        <li>Before you can enter anything here, you must create an AdSense for Search slot in <a href="http://adsense.google.com">Google AdSense</a>.</li>
					        <li>You must have Permalinks enabled. (See why in next tip)</li>
					        <li>You must also create a new page named 'Search' using the AdSense for Search template.</li>
					        <li>The PubID is the special ID in the code generated by Google AdSense (the value of the input tag named 'cx').<br/>
                                &nbsp;&nbsp;&nbsp;&nbsp;Example: partner-pub-012345678912345:ab123-12ab</li>
                        </ul>
					</p>
					<table class="form-table">
                        <tr valign="top">
		                    <th scope="row" style="font-size:12px;text-align:left;padding-right:10px;">
                            Enable AdSense for Search
    	                        </th>
		                    <td style="padding-right:10px;">
		                    <?php $c = (get_option('wicketpixie_adsense_search_enabled') == 'true') ? 'checked="checked"' : ''; ?>
		                    <input type='checkbox' name='wicketpixie_adsense_search_enabled' id='wicketpixie_adsense_search_enabled' <?php echo $c; ?> />
		                    </td>
	                    </tr>
	                    <tr valign="top">
		                    <th scope="row" style="font-size:12px;text-align:left;padding-right:10px;">
                            PubID
    	                        </th>
		                    <td style="padding-right:10px;">
		                    <input type='text' name='wicketpixie_adsense_search_pubid' id='wicketpixie_adsense_search_pubid' value="<?php echo get_option('wicketpixie_adsense_search_pubid'); ?>" />
		                    </td>
	                    </tr>
	                    <tr valign="top">
		                    <th scope="row" style="font-size:12px;text-align:left;padding-right:10px;">
                            Search Results URL
    	                        </th>
		                    <td style="padding-right:10px;">
		                    <?php bloginfo('home'); ?><input type='text' name='wicketpixie_adsense_search_url' id='wicketpixie_adsense_search_url' value="/search/" disabled="disabled" />
		                    </td>
	                    </tr>
                    </table>
					<p class="submit">
                        <input name="adsearch" type="submit" value="Save AdSense for Search Settings" />
                        <input type="hidden" name="action" value="adsearch" />
					</p>
					</form>

					<?php if( $adsense->check() == true && $adsense->count() != '' ) { ?>
					<table class="form-table" style="margin-bottom:30px;">
						<tr>
							<th>Ad Code</th>
							<th style="text-align:center;">Placement</th>
							<th style="text-align:center;" colspan="1">Actions</th>
						</tr>
					<?php 
						foreach( $adsense->collect() as $adslot ) {
					?>		
						<tr>
							<td><?php echo htmlentities($adslot->ad_code); ?></td>
						   	<td style="text-align:center;">
        					   	<?php
        					   	if($adslot->placement == 'blog_header') {
        					   	    echo "Blog Header (728x90)";
        					   	} elseif($adslot->placement == 'blog_post_side') {
        					   	    echo "Right of Blog Post (120x240)";
        					   	} elseif($adslot->placement == 'blog_post_bottom') {
        					   	    echo "Underneath Home Post (300x250)";
        					   	} elseif($adslot->placement == 'blog_sidebar') {
        					   	    echo "Bottom-left of Sidebar (120x600)";
        					   	} else {
        					   	    echo $adslot-placement;
        					   	}
						   	?>
						   	</td>
							<td style="text-align:center;">
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=adsenseads.php&amp;delete=true&amp;id=<?php echo $adslot->id; ?>">
                            <?php wp_nonce_field('wicketpixie-settings'); ?>
								<input type="submit" name="action" value="Delete" />
								<input type="hidden" name="action" value="delete" />
							</form>
							</td>
						</tr>
					<?php } ?>
					</table>
					<?php } else { ?>
						<p>You haven't added any ad slots, add them here.</p>
					<?php } ?>
					    
					    <?php if($adsense->check() == true) { ?>
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=adsenseads.php&amp;add=true" class="form-table">
                        <?php wp_nonce_field('wicketpixie-settings'); ?>
							<h2>Add an Ad Slot</h2>
							<p>Remember, copy your ad code from the AdSense site after creating your ad slot. Also remember, only one ad per placement. ;-)</p>
							<p><textarea name="ad_code" id="ad_code" onfocus="if(this.innerHTML=='Ad Code') this.innerHTML = ''">Ad Code</textarea></p>
							<p><select name="placement" id="title">
                            <option value="blog_header">Blog header (728x90)</option>
                            <option value="blog_post_side">Right of Blog Post (120x240)</option>
                            <option value="blog_post_bottom">Underneath Home Post (300x250)</option>
                            <option value="blog_sidebar">Bottom-left of Sidebar (120x600)</option>
                            </select></p>
                            <p class="submit">
                                <input name="save" type="submit" value="Add Ad Slot" /> 
                                <input type="hidden" name="action" value="add" />
							</p>
						</form>
						<?php } else { ?>
						<h2>Install AdSense table</h2>
						<p>You need to install the AdSense table before adding ad slots.</p>
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=adsenseads.php&amp;install=true">
						    <p class="submit">
						        <input name="save" type="submit" value="Install AdSense table"/>
						        <input type="hidden" name="action" value="install"/>
                            </p>
                        </form>
                        <?php } ?>
				</div>
                <?php include_once('advert.php'); ?>
<?php
	}
}

// This checks to see if WicketPixie's AdSense features are enabled
function is_enabled_adsense() {
    if(get_option('wicketpixie_enable_adsense')) {
        if(get_option('wicketpixie_enable_adsense') == 'true') {
            return true;
        } elseif(get_option('wicketpixie_enable_adsense') == 'false') {
            return false;
        }
    } else {
        return false;
    }
}
?>
