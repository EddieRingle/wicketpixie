<?php
$chrisads = array(
    "pubid" => "pub-7561297527511227",
    "120x240"   => "3837687211",
    "250x300"   => "0722333443",
    "728x90"    => "5760307022",
    "120x600"   => "7794173943"
);
class AdsenseAdmin
{
        
	/**
	* Here we install the tables and initial data needed to
	* power our special functions
	*/
	 function install() {
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$table= $wpdb->prefix . 'wik_adsense';
				
		$q= '';
		if( $wpdb->get_var( "show tables like '$table'" ) != $table ) {
			$q= "CREATE TABLE " . $table . "( 
				id int NOT NULL AUTO_INCREMENT,
				ad_id varchar(255) NOT NULL,
			    placement varchar(255) NOT NULL,
                sortorder smallint(9) NOT NULL,
				UNIQUE KEY id (id)
			);";
		}
		if( $q != '' ) {
			dbDelta( $q );
		}			
	}
	
	 function check() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_adsense';
		if( $wpdb->get_var( "show tables like '$table'" ) != $table ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	 function count() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_adsense';
		$total= $wpdb->get_results( "SELECT ID as count FROM $table" );
		return $total[0]->count;
	}
	
	// Adds an ad slot to the database
	 function add( $_REQUEST ) {
		global $wpdb;
		
		$args= $_REQUEST;		
		$table= $wpdb->prefix . 'wik_adsense';
        if($args['ad_id'] == "Ad ID") $args['ad_id'] = "";
		if( !$wpdb->get_var( "SELECT id FROM $table WHERE ad_id = '" . $args['ad_id'] . "'" ) ) {
			$id= $wpdb->get_var( "SELECT sortorder FROM $table ORDER BY sortorder DESC LIMIT 1" );
			$new_id= ( $id + 1 );
			
			$i= "INSERT INTO " . $table . " (id,ad_id,placement,sortorder) VALUES('', '" 
				. $args['ad_id'] . "','"
                . $args['placement'] . "',"
				. $new_id . ")";
			$query= $wpdb->query( $i );
		}
	}
	
	// Turns WicketPixie's AdSense features on and off
	function toggle() {
	    if(wp_get_option('enable_adsense')) {
	        if(wp_get_option('enable_adsense') == 'true') {
	            wp_update_option('enable_adsense','false');
	        } elseif(wp_get_option('enable_adsense') == 'false') {
	            wp_update_option('enable_adsense','true');
	        }
	    } else {
	        wp_add_option('enable_adsense','true');
	    }
	}
	
	// Sets the user's pub-id
	function pub_id($_REQUEST) {
	    $args = $_REQUEST;
	    
	    if(wp_get_option('adsense_pubid')) {
	        wp_update_option('adsense_pubid',$args['pubid']);
	    } else {
	        wp_add_option('adsense_pubid',$args['pubid']);
	    }
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
	
	function wp_adsense($placement) {
	    global $wpdb,$chrisads;
	    $table = $wpdb->prefix . 'wik_adsense';
	    $ad_id = $wpdb->get_var("SELECT ad_id FROM $table WHERE placement= '$placement' LIMIT 1");
	    $pubid = wp_get_option('adsense_pubid');
	    
	    if($ad_id != "" && $pubid != "") {
	        if($placement == 'blog_header') {
	            $width = "728";
	            $height = "90";
	        } elseif($placement == 'blog_post_side') {
	            $width = "120";
	            $height = "240";
	        } elseif($placement == 'blog_post_bottom') {
	            $width = "300";
	            $height = "250";
	        } elseif($placement == 'blog_sidebar') {
	            $width = "120";
	            $height = "600";
	        } else {
	            $width = "";
	            $height = "";
	        }
	        
	        // The JavaScript for the ad
	        $codeblock = "<script type='text/javascript'><!--
    google_ad_client = '$pubid';
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
        } else {
            if($placement == 'blog_header') {
	            $width = "728";
	            $height = "90";
	            $ad_id = $chrisads['728x90'];
	        } elseif($placement == 'blog_post_side') {
	            $width = "120";
	            $height = "240";
	            $ad_id = $chrisads['120x240'];
	        } elseif($placement == 'blog_post_bottom') {
	            $width = "300";
	            $height = "250";
	            $ad_id = $chrisads['300x250'];
	        } elseif($placement == 'blog_sidebar') {
	            $width = "120";
	            $height = "600";
	            $ad_id = $chrisads['120x600'];
	        } else {
	            $width = "";
	            $height = "";
	        }
	        $codeblock = "<script type='text/javascript'><!--
    google_ad_client = '".$chrisads['pubid']."';
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
	
	 function addAdsenseMenu() {
		add_submenu_page( 'wicketpixie-admin.php', __('WicketPixie AdSense Settings'), __('AdSense Settings'), 9, basename(__FILE__), array( 'AdsenseAdmin', 'adsenseMenu' ) );
	}
	
	/**
	* The admin menu for our AdSense system
	*/
	 function adsenseMenu() {
		$adsense = new AdsenseAdmin;
		if ( $_GET['page'] == basename(__FILE__) ) {
	        if ( 'add' == $_REQUEST['action'] ) {
				$adsense->add( $_REQUEST );
			}
			elseif ( 'toggle' == $_REQUEST['action'] ) {
			    $adsense->toggle();
			}
			elseif ( 'pubid' == $_REQUEST['action'] ) {
			    $adsense->pub_id( $_REQUEST );
			}			
			elseif ( 'delete' == $_REQUEST['action'] ) {
				$adsense->burninate( $_REQUEST['id'] );
			}
		}
		?>
		<?php if ( isset( $_REQUEST['add'] ) ) { ?>
		<div id="message" class="updated fade"><p><strong><?php echo __('Service added.'); ?></strong></p></div>
		<?php } ?>
			<div class="wrap">
			
				<div id="admin-options">
					<h2><?php _e('AdSense Settings'); ?></h2>
                    <p>Here you can add in your AdSense information and ad slot info so it can be displayed
                    on your blog. Need more help? <a href="#explain" title="Click for more info" id="explaintext">It's only a click away</a>.</p>
					<div id="explain">
                        <h3>For those that need help, here's a rundown:</h3>
                            <ol>
                                <li>Create an ad slot after logging into Google AdSense.</li>
                                <li>On this page, enter your pub-id and add enter ad details.</li>
                                <li>Enjoy. :-)</li>
                            </ol>
					</div>
					<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=adsenseads.php&toggle=true" class="form-table">
					<h2>Toggle AdSense Ads</h2>
					<p>One click button to disable the showing of AdSense ads.</p>
					<?php
					if(wp_get_option('enable_adsense')) {
					    if(wp_get_option('enable_adsense') == "true") {
					        $val = "off";
					    } else {
					        $val = "on";
					    }
					} else {
					    $val = "off";
					}
					?>
					<p class="submit">
					<input type="submit" name="toggle" value="Turn <?php echo $val; ?> AdSense Ads" />
					<input type="hidden" name="action" value="toggle" />
					</p>
					</form>
					<p>To find an ad's ID number, first log in to Google AdSense. Next, click 'AdSense Setup' and then 'Manage Ads'.
					In the 'Name (#ID)' column find the ad you've created. The ad's ID will be underneath the ad's name in gray font.</p>
					<?php if( $adsense->check() != 'false' && $adsense->count() != '' ) { ?>
					<table class="form-table" style="margin-bottom:30px;">
						<tr>
							<th>Ad ID</th>
							<th style="text-align:center;">Placement</th>
							<th style="text-align:center;" colspan="1">Actions</th>
						</tr>
					<?php 
						foreach( $adsense->collect() as $adslot ) {
					?>		
						<tr>
							<td><?php echo $adslot->ad_id; ?></td>
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
					    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=adsenseads.php&amp;pubid=true" class="form-table">
					        <h2>Google AdSense Publisher ID</h2>
					        <p>Please enter your AdSense Publisher ID here.</p>
					        <p style="font-style:italic;">The Publisher ID currently in use is:<br />
					        <?php if(wp_get_option('adsense_pubid') != false && wp_option_isempty('adsense_pubid') == false) {
                                                          echo wp_get_option('adsense_pubid');
                                                      } else {
                                                          echo "N/A";
                                                      }
                                                <?php>
					        </p>
					        <?php
					            if(wp_get_option('adsense_pubid')) {
					                $pub_id = wp_get_option('adsense_pubid');
					            } else {
					                $pub_id = "Pub-ID";
					            }
					        ?>
					        <p><input type="text" name="pubid" id="pubid" onfocus="if(this.value=='<?php echo $pub_id; ?>')value=''" value="<?php echo $pub_id; ?>" /></p>
					        <p class="submit">
					            <input name="save" type="submit" value="Save Pub-ID" />
					            <input type="hidden" name="action" value="pubid" />
					        </p>
					    </form>
					    
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=adsenseads.php&amp;add=true" class="form-table">
							<h2>Add an Ad Slot</h2>
							<p>Please leave off the pound sign (#) when entering the Ad ID. Also remember, only one ad per placement. ;-)</p>
							<p><input type="text" name="ad_id" id="ad_id" onfocus="if(this.value=='Ad ID')value=''" value="Ad ID" /></p>
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
				</div>
                <?php include_once('advert.php'); ?>
<?php
	}
}

// This checks to see if WicketPixie's AdSense features are enabled
function is_enabled_adsense() {
    if(wp_get_option('enable_adsense')) {
        if(wp_get_option('enable_adsense') == 'true') {
            return true;
        } elseif(wp_get_option('enable_adsense') == 'false') {
            return false;
        }
    } else {
        return true;
    }
}

add_action ('admin_menu', array( 'AdsenseAdmin', 'addAdsenseMenu' ) );

AdsenseAdmin::install();
?>
