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
class NotifyAdmin extends AdminPage
{

    function __construct()
    {
        parent::__construct('Notifications Manager','notify.php','wicketpixie-admin.php',null);
    }
    
    function page_output()
    {
        $this->notifyMenu();
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
		$table= $wpdb->prefix . 'wik_notify';
				
		$q= '';
		if( $wpdb->get_var( "show tables like '$table'" ) != $table ) {
			$q= "CREATE TABLE " . $table . "( 
				id int NOT NULL AUTO_INCREMENT,
				service varchar(255) NOT NULL,
				username varchar(255) NULL,
				password varchar(255) NULL,
				apikey varchar(255) NULL,
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
		$table= $wpdb->prefix . 'wik_notify';
		if( $wpdb->get_var( "show tables like '$table'" ) == $table ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	 function count() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_notify';
		$total= $wpdb->get_results( "SELECT ID as count FROM $table" );
		if (isset($total[0])) {
		    return $total[0]->count;
		} else {
		    return 0;
		}
	}
	
	 function add( $_REQUEST ) {
		global $wpdb;
		
		$args= $_REQUEST;		
		$table= $wpdb->prefix . 'wik_notify';
		if( $args['service'] != 'Service Name' ) {
            if($args['apikey'] == "API Key") $args['apikey'] = "";
            if($args['username'] == "Username") $args['username'] = "";
            if($args['password'] == "Password") $args['password'] = "";
		    if( $wpdb->get_var( "SELECT id FROM $table WHERE service = '" . $args['service'] . "'" ) == NULL ) {
			    $id= $wpdb->get_var( "SELECT sortorder FROM $table ORDER BY sortorder DESC LIMIT 1" );
			    $new_id= ( $id + 1 );
			
			    $i= "INSERT INTO " . $table . " (id,service,username,password,apikey,sortorder) VALUES('', '" 
				    . $args['service'] . "','" 
				    . $args['username'] . "','"
                    . $args['password'] . "','"
                    . $args['apikey'] . "',"
				    . $new_id . ")";
			    $query= $wpdb->query( $i );
		    }
		}
	}
	
	 function collect() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_notify';
		$sources= $wpdb->get_results( "SELECT * FROM $table" );
		if( is_array( $sources ) ) {
			return $sources;
		} else {
			return array();
		}
	}
	
	 function gather( $id ) {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_notify';
		$gather= $wpdb->get_results( "SELECT * FROM $table WHERE id= $id" );
		return $gather;
	}
	
	 function burninate( $id ) {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_notify';
		$d= $wpdb->query( "DELETE FROM $table WHERE id = $id" );
		$trogdor= $wpdb->query( $d );
	}
	
	/**
	* Returns the list of services that WicketPixie will notify when
	* a blog post is published.
	**/
	function show_notifications()
	{
		global $wpdb;
		$table= $wpdb->prefix . 'wik_notify';
		$show= $wpdb->get_results( "SELECT * FROM $table ORDER BY sortorder ASC" );
		return $show;
	}
	
	function positions()
	{
		global $wpdb;
		$table= $wpdb->prefix . 'wik_notify';
		$numbers= $wpdb->get_results( "SELECT sortorder FROM $table ORDER BY sortorder ASC" );
		return $numbers;
	}
	
	function sort( $_REQUEST )
	{
		global $wpdb;
		$args= $_REQUEST;
		$table= $wpdb->prefix . 'wik_notify';
		$orig_sort= $wpdb->get_results( "SELECT sortorder FROM $table WHERE id= " . $args['id'] );
		$old_value= $orig_sort[0]->sortorder;
		if( $orig_sort ) {
			$bump_up= $wpdb->query( "UPDATE $table SET sortorder= sortorder + 1 WHERE sortorder > " . $args['newsort'] );
			$update= $wpdb->query( "UPDATE $table SET sortorder= ". ( $args['newsort'] + 1 ) . " WHERE id= " . $args['id'] );
			$bump_down= $wpdb->query( "UPDATE $table SET sortorder= sortorder -1 WHERE sortorder > " . $old_value );
		}
	}
	
	// Turns WicketPixie Notifications on and off
	function toggle()
	{
	    if(get_option('wicketpixie_notifications_enable')) {
	        if(get_option('wicketpixie_notifications_enable') == 'true') {
	            update_option('wicketpixie_notifications_enable','false');
	        } elseif(get_option('wicketpixie_notifications_enable') == 'false') {
	            update_option('wicketpixie_notifications_enable','true');
	        }
	    } else {
	        add_option('wicketpixie_notifications_enable','true');
	    }
	    wp_redirect($_SERVER['PHP_SELF'] .'?page='.$this->filename.'&toggled=true');
	}
	
	function request_check()
	{
	    $notify = new NotifyAdmin;
	    if (isset($_GET['page']) && isset($_POST['action']) && $_GET['page'] == basename(__FILE__)) {
	        if ('add' == $_POST['action']) {
				$notify->add( $_REQUEST );
			} elseif ( 'delete' == $_POST['action'] ) {
				$notify->burninate( $_REQUEST['id'] );
			} elseif ( 'toggle' == $_POST['action'] ) {
			    $notify->toggle();
			} elseif('install' == $_POST['action']) {
			    $notify->install();
			}
		}
		unset($notify);
	}
	/**
	* The admin page where the user selects the services that
	* should be notified whenever a blog post is published.
	*/
	 function notifyMenu()
	 {
		$notify= new NotifyAdmin;
        $wp_notify = get_option('wicketpixie_notifications_enable');
		?>
		<?php if ( isset( $_REQUEST['add'] ) ) { ?>
		<div id="message" class="updated fade"><p><strong><?php echo __('Service added.'); ?></strong></p></div>
		<?php } ?>
			<div class="wrap">
			
				<div id="admin-options">
					<h2><?php _e('Notification Settings'); ?></h2>
                    <?php if($wp_notify != 'true') { ?>
                    <p><strong>WicketPixie Notifications are currently disabled, please go to the WicketPixie Options page to enable them.</strong><br /></p>
                    <?php } ?>
                    <p>What are WicketPixie Notifications? They send out messages to different services like Twitter and Ping.fm to let your followers know of any new blog posts. Need more help? <a href="#explain" title="Click for more info" id="explaintext">It's only a click away</a>.</p>
					<div id="explain">
                        <h3>For those that need help, here's some guidelines:</h3>
                            <ol>
                                <li>Use of the Ping.fm service only requires you to enter your App key, which you can obtain <a href="http://ping.fm/key">here</a>.</li>
                                <li>Use of the Twitter service only requires you to enter your Twitter username and password, no API/App key required.</li>
                                <li>WicketPixie is completely open-source, so if you don't believe this doesn't steal your information, check the source code ;)</li>
                            </ol>
					</div>
                    <p>If you choose to use Ping.fm, unless the other services are not setup in your Ping.fm account please do not add your details for them, as the notification will be sent out twice.</p>
                    <p><strong>Please note:</strong> <em>When entering service details, you may only need to enter a username and password, you may only need to enter an API/App key, or you may enter both. It all depends on which service you select.</em></p>
                    
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo $this->filename; ?>" class="form-table">
                    <?php wp_nonce_field('wicketpixie-settings'); ?>
					    <h2>Toggle WicketPixie Notifications</h2>
					    <?php
					    if(get_option('wicketpixie_notifications_enable')) {
					        if(get_option('wicketpixie_notifications_enable') == "true") {
					            $val = "off";
					        } else {
					            $val = "on";
					        }
					    } else {
					        $val = "on";
					    }
					    ?>
					    <p class="submit">
					    <input type="submit" name="toggle" value="Turn <?php echo $val; ?> WicketPixie Notifications" />
					    <input type="hidden" name="action" value="toggle" />
					    </p>
					</form>
					
					<?php if( $notify->check() == true && $notify->count() != '' ) { ?>
					<table class="form-table" style="margin-bottom:30px;">
						<tr>
							<th>Service</th>
							<th style="text-align:center;">Username</th>
							<th style="text-align:center;" colspan="1">Actions</th>
						</tr>
					<?php 
						foreach( $notify->collect() as $service ) {
					?>		
						<tr>
							<td><?php echo $service->service; ?></td>
						   	<td style="text-align:center;"><?php echo $service->username; ?></td>
							<td style="text-align:center;">
							<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=notify.php&amp;delete=true&amp;id=<?php echo $service->id; ?>">
								<input type="submit" name="action" value="Delete" />
								<input type="hidden" name="action" value="delete" />
							</form>
							</td>
						</tr>
					<?php } ?>
					</table>
					<?php } else { ?>
						<p>You haven't added any services, add them here.</p>
					<?php } ?>
				    <?php if($notify->check() == true) { ?>
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=notify.php&amp;add=true" class="form-table">
						<?php wp_nonce_field('wicketpixie-settings'); ?>
							<h2>Add a Service</h2>
							<p><select name="service" id="title">
                            <option value="ping.fm">Ping.fm</option>
                            <option value="twitter">Twitter</option>
                            </select></p>
                            <p><input type="text" name="username" id="url" onfocus="if(this.value=='Username')value=''" onblur="if(this.value=='')value='Username';" value="Username" /></p>
                            <p><input type="text" name="password" id="url" onfocus="if(this.value=='Password')value=''" onblur="if(this.value=='')value='Password';" value="Password" /></p>
                            <p><input type="text" name="apikey" id="url" onfocus="if(this.value=='API/App Key')value=''" onblur="if(this.value=='')value='API/App Key';" value="API/App Key" /></p>
                            <p class="submit">
                                <input name="save" type="submit" value="Add Service" /> 
                                <input type="hidden" name="action" value="add" />
							</p>
						</form>
					<?php } else { ?>
					    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo $this->filename; ?>&amp;install=true">
					        <h2>Install Notifications table</h2>
                            <p>Before you can add a service, you must install the table.</p>
                            <p class="submit">
                                <input name="save" type="submit" value="Install Notifications table"/>
                                <input type="hidden" name="action" value="install"/>
                            </p>
                        </form>
					<?php } ?>
				</div>
                <?php include_once('advert.php'); ?>
<?php
	}
}
/**
* A variable that is checked when we want to know if WicketPixie Notifications
* are enabled or not.
**/
$wp_notify = get_option('wicketpixie_notifications_enable');

/**
* This is called when a post is published and
* prepares to notify all services listed in the database
**/
function prep_notify($id) {
    global $wpdb;
    $table = $wpdb->posts;
    $post['title'] = $wpdb->get_var("SELECT post_title FROM $table WHERE ID=$id");
    $post['link'] = get_permalink($id);
    $post['id'] = $id;
    
    /**
    * Developer API Keys
    * DO NOT MODIFY FOR ANY REASON!
    **/
    $devkeys = array(
    "ping.fm" => "7cf76eb04856576acaec0b2abd2da88b"
    );
    
    notify($post,$devkeys);
    return $id;
}

/**
* This calls each service's notification function
**/
function notify($post,$devkeys) {
    $notify = new NotifyAdmin();
    foreach($notify->collect() as $services) {
        if($services->service == 'ping.fm') {
            $errnum = notify_pingfm($post,$services->apikey,$devkeys['ping.fm']);
        }
        elseif($services->service == 'twitter') {
            $errnum = notify_twitter($post,$services);
        }
    }
}

/**
* Executes a cURL request and returns the output
*/
function notify_go($service,$type,$postdata,$ident) {
    if($service == 'ping.fm')
    {
        // Set the url based on type
        $url = "http://api.ping.fm/v1/".$type;
        
        // Setup cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        
        // Send the data and close up shop
        $output = curl_exec($ch);
        curl_close($ch);
        
        return $output;
    }
    elseif($service == 'twitter')
    {
        // Tidy $postdata before sending it
        $postdata = urlencode(stripslashes(urldecode($postdata)));
        
        // Set the url based on type and add the POST data
        $url = "http://twitter.com/".$type."?status=".$postdata."&source=wicketpixie";
        
        // Setup cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERPWD, $ident['user'].":".$ident['pass']);
        curl_setopt($ch, CURLOPT_URL, $url);
        
        // Send the data and fetch the HTTP code
        $output = curl_exec($ch);
        $outArray = curl_getinfo($ch);
        
        if($outArray['http_code'] == 200)
        {
            return 1;
        } else {
            return 0;
        }
    }
}

/**
* Ping.fm notification function
*/
function notify_pingfm($post,$appkey,$apikey) {
    // Message to be sent
    $message = $post['title'] . " ~ " . $post['link'];
    
    // First, we validate the user's app key
    $postdata = array('api_key' => $apikey, 'user_app_key' => $appkey);
    $apicall = "user.validate";
    $output = notify_go('ping.fm',$apicall,$postdata,NULL);
    
    if(preg_match('/<rsp status="OK">/',$output))
    {
        // Okay, app key validated, now we can continue
        $postdata = array('api_key' => $apikey, 'user_app_key' => $appkey, 'post_method' => 'status', 'body' => $message);
        $apicall = "user.post";
        $output = notify_go('ping.fm',$apicall,$postdata,NULL);
        $success = preg_match('/<rsp status="OK">/',$output);
        return $success;
    }
}

/**
* Twitter notification function
*/
function notify_twitter($post,$dbdata) {
    // Message to be sent
    $message = $post['title'] . " ~ " . $post['link'];
    
    // Put username and password into an array for easier passing
    $ident = array("user" => $dbdata->username,"pass" => $dbdata->password);
    
    // Choose update format (update.xml or update.json)
    $type = "statuses/update.xml";
    
    $success = notify_go('twitter',$type,$message,$ident);
    return $success;
}

if($wp_notify == 'true')
{
    add_action ('publish_post', 'prep_notify');
}
?>
