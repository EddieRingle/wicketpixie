<?php
class FavesAdmin
{
	
	/**
	* Here we install the tables and initial data needed to
	* power our special functions
	*/
	public function install() {
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$table= $wpdb->prefix . 'wik_faves';
				
		$q= '';
		if( $wpdb->get_var( "show tables like '$table'" ) != $table ) {
			$q= "CREATE TABLE " . $table . "( 
				id int NOT NULL AUTO_INCREMENT,
				title varchar(255) NOT NULL,
				feed_url varchar(255) NOT NULL,
				favicon varchar(255) NOT NULL,
				sortorder tinyint(9) NOT NULL,
				UNIQUE KEY id (id)
			);";
		}
			if( $q != '' ) {
				dbDelta( $q );
			}
			
			$dfaves= array(
						array('title' => 'Chris Pirillo', 
									'feed_url' => 'http://feeds.pirillo.com/ChrisPirillo', 
									'sortorder' => '1'),
						array('title' => 'Lockergnome.com', 
									'feed_url' => 'http://feed.lockergnome.com/nexus/all', 
									'sortorder' => '2'),
						array('title' => 'Lockergnome Coupons', 
									'feed_url' => 'http://feeds.feedburner.com/NewCoupons', 
									'sortorder' => '3'),				
					);

				foreach( $dfaves as $fave ) {
					if( !$wpdb->get_var( "SELECT id FROM $table WHERE feed_url = '" . $fave['feed_url'] . "'" ) ) {
					$i= "INSERT INTO " . $table . " (id,title,feed_url,sortorder) VALUES('', '" . $fave['title'] . "','" . $fave['feed_url'] . "', '" . $fave['sortorder'] . "')";
					$query= $wpdb->query( $i );
					self::favicache( $fave['feed_url'], $fave['title'] );
					}
				}
			
	}
	
	private function fetch_remote_file( $file ) {
		$path = parse_url( $file );

		if ($fs = @fsockopen($path['host'], isset($path['port'])?$path['port']:80)) {

			$header = "GET " . $path['path'] . " HTTP/1.0\r\nHost: " . $path['host'] . "\r\n\r\n";

			fwrite($fs, $header);

			$buffer = '';

			while ($tmp = fread($fs, 1024)) { $buffer .= $tmp; }

			preg_match('/HTTP\/[0-9\.]{1,3} ([0-9]{3})/', $buffer, $http);
			preg_match('/Location: (.*)/', $buffer, $redirect);

			if (isset($redirect[1]) && $file != trim($redirect[1])) { return self::fetch_remote_file(trim($redirect[1])); }

			if (isset($http[1]) && $http[1] == 200) { return substr($buffer, strpos($buffer, "\r\n\r\n") +4); } else { return false; }

		} else { return false; }

	}
	
	private function favicache( $feed, $name ) {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_faves';
		$folder= 'wp-content/themes/wicketpixie/images/favicache/';

		if ( !is_dir( ABSPATH . $folder ) ) { 
			mkdir( ABSPATH . $folder, 0777 ); 
		}

		$url= parse_url( $feed );

		$cache= self::fetch_remote_file( 'http://' . $url['host'] . '/favicon.ico' );
		
		if ( !$cache ) {
			preg_match( '/<link.*(?:rel="icon" href="(.*)"|href="(.*)" rel="icon").*>/U',
			self::fetch_remote_file( $_POST['link_url'] ), $matches );
			$cache= self::fetch_remote_file( $matches[1] );
		}

		if ( $cache ) {
			file_put_contents( ABSPATH . $folder . md5( $url['host'] ) . '.ico', $cache );
			$icon= get_option( 'siteurl' ) . '/' . $folder . md5( $url['host'] ) . '.ico';
		} elseif( is_file( ABSPATH . 'wp-content/themes/wicketpixie/images/' . 'icon-source.gif' ) ) {
			$icon= get_option( 'siteurl' ) . '/wp-content/themes/wicketpixie/images/icon-source.gif';
		}
		
		$wpdb->query( 'UPDATE `' . $table . '` SET `favicon` = "' . $icon . '" WHERE `title` = "' . $name . '"' );
		return false;
	}
	
	public function check() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_faves';
		if( $wpdb->get_var( "show tables like '$table'" ) != $table ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function count() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_faves';
		$total= $wpdb->get_results( "SELECT ID as count FROM $table" );
		return $total[0]->count;
	}
	
	public function add( $_REQUEST ) {
		global $wpdb;
		
		$args= $_REQUEST;		
		$table= $wpdb->prefix . 'wik_faves';
		if( $args['title'] != 'Fave Title' ) {
		if( !$wpdb->get_var( "SELECT id FROM $table WHERE feed_url = '" . $args['url'] . "'" ) ) {
			$id= $wpdb->get_var( "SELECT sortorder FROM $table ORDER BY sortorder DESC LIMIT 1" );
			$new_id= ( $id + 1 );
			
			$i= "INSERT INTO " . $table . " (id,title,feed_url,sortorder) VALUES('', '" 
				. $args['title'] . "','" 
				. $args['url'] . "',"
				. $new_id . ")";
			$query= $wpdb->query( $i );
			self::favicache( $args['url'], $args['title'] );
		}
		}
	}
	
	public function collect() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_faves';
		$sources= $wpdb->get_results( "SELECT * FROM $table" );
		if( is_array( $sources ) ) {
			return $sources;
		} else {
			return array();
		}
	}
	
	public function gather( $id ) {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_faves';
		$gather= $wpdb->get_results( "SELECT * FROM $table WHERE id= $id" );
		return $gather;
	}
	
	/**
	* Edit the information for a given fave.
	*/
	public function edit( $_REQUEST ) {
		global $wpdb;
		$args= $_REQUEST;
			$table= $wpdb->prefix . 'wik_faves';
			$u= "UPDATE ". $table . 
						" SET title = '" . $args['title'] .
						"', feed_url = '" . $args['url'] .
						"' WHERE id = " . $args['id'];
			$query= $wpdb->query( $u );
			self::favicache( $args['url'], $args['title'] );
	}
	
	public function burninate( $id ) {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_faves';
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
	public function show_faves() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_faves';
		$show= $wpdb->get_results( "SELECT * FROM $table ORDER BY sortorder ASC" );
		return $show;
	}
	
	public function positions() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_faves';
		$numbers= $wpdb->get_results( "SELECT sortorder FROM $table ORDER BY sortorder ASC" );
		return $numbers;
	}
	
	public function sort( $_REQUEST ) {
		global $wpdb;
		$args= $_REQUEST;
		$table= $wpdb->prefix . 'wik_faves';
		$orig_sort= $wpdb->get_results( "SELECT sortorder FROM $table WHERE id= " . $args['id'] );
		$old_value= $orig_sort[0]->sortorder;
		if( $orig_sort ) {
			$bump_up= $wpdb->query( "UPDATE $table SET sortorder= sortorder + 1 WHERE sortorder > " . $args['newsort'] );
			$update= $wpdb->query( "UPDATE $table SET sortorder= ". ( $args['newsort'] + 1 ) . " WHERE id= " . $args['id'] );
			$bump_down= $wpdb->query( "UPDATE $table SET sortorder= sortorder -1 WHERE sortorder > " . $old_value );
		}
	}
	
	public function addFavesMenu() {
		add_management_page( __('WicketPixie Faves'), __('WicketPixie Faves'), 9, basename(__FILE__), array( 'FavesAdmin', 'favesMenu' ) );
	}
	
	/**
	* The admin menu for our faves system
	*/
	public function favesMenu() {
		$faves= new FavesAdmin;
		if ( $_GET['page'] == basename(__FILE__) ) {
	        if ( 'add' == $_REQUEST['action'] ) {
				$faves->add( $_REQUEST );
			}
			
		    if ( 'edit' == $_REQUEST['action'] ) {
				$faves->edit( $_REQUEST );
			}
			
			if ( 'delete' == $_REQUEST['action'] ) {
				$faves->burninate( $_REQUEST['id'] );
			}
		}
		?>
		<?php if ( isset( $_REQUEST['add'] ) ) { ?>
		<div id="message" class="updated fade"><p><strong><?php echo __('Fave saved.'); ?></strong></p></div>
		<?php } ?>
			<div class="wrap">
			
				<div id="admin-options">
					<h2><?php _e('Manage My Faves'); ?></h2>
					<?php if( $faves->check() != 'false' && $faves->count() != '' ) { ?>
					<table class="form-table" style="margin-bottom:30px;">
						<tr>
							<th>Title</th>
							<th style="text-align:center;">Feed</th>
							<th style="text-align:center;" colspan="2">Actions</th>
						</tr>
					<?php 
						foreach( $faves->collect() as $fave ) {
					?>		
						<tr>
							<td><?php echo $fave->title; ?></td>
						   	<td style="text-align:center;"><a href="<?php echo $fave->feed_url; ?>" title="<?php echo $fave->feed_url; ?>"><img src="<?php bloginfo('template_directory'); ?>/images/icon-feed.gif" alt="View"/></a></td>
						   	<td style="text-align:center;">
							<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=faves.php&amp;gather=true&amp;id=<?php echo $fave->id; ?>">
								<input type="submit" value="Edit" />
								<input type="hidden" name="action" value="gather" />
							</form>
							</td>
							<td style="text-align:center;">
							<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=faves.php&amp;delete=true&amp;id=<?php echo $fave->id; ?>">
								<input type="submit" name="action" value="Delete" />
								<input type="hidden" name="action" value="delete" />
							</form>
							</td>
						</tr>
					<?php } ?>
					</table>
					<?php } else { ?>
						<p>You don't have any Favorites, why not add some?</p>
					<?php } ?>
					<?php if ( isset( $_REQUEST['gather'] ) ) { ?>
						<?php $data= $faves->gather( $_REQUEST['id'] ); ?>
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=faves.php&amp;edit=true">
							<h2>Editing "<?php echo $data[0]->title; ?>"</h2>
							<p><input type="text" name="title" id="title" value="<?php echo $data[0]->title; ?>" /></p>
							<p><input type="text" name="url" id="url" value="<?php echo $data[0]->feed_url; ?>" /></p>
							<p class="submit">
								<input name="save" type="submit" value="Save Changes" />
								<input type="hidden" name="action" value="edit" />
								<input type="hidden" name="id" value="<?php echo $data[0]->id; ?>">
							</p>
						</form>
					<?php } ?>
					<?php if( $faves->check() != 'false' && !isset( $_REQUEST['gather'] ) ) { ?>
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=faves.php&amp;add=true" class="form-table">
							<h2>Add a New Fave</h2>
							<p><input type="text" name="title" id="title" onfocus="if(this.value=='Fave Title')value=''" onblur="if(this.value=='')value='Fave Title';" value="Fave Title" /></p>
							<p><input type="text" name="url" id="url" onfocus="if(this.value=='Fave Feed URL')value=''" onblur="if(this.value=='')value='Fave Feed URL';" value="Fave Feed URL" /></p>
							<p class="submit">
								<input name="save" type="submit" value="Add Fave" />    
								<input type="hidden" name="action" value="add" />
							</p>
						</form>
					<?php } ?>
				</div>
<?php
	}
}
add_action ('admin_menu', array( 'FavesAdmin', 'addFavesMenu' ) );
FavesAdmin::install();
?>
