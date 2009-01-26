<?php
class FavesAdmin
{
	
	/**
	* Here we install the tables and initial data needed to
	* power our special functions
	*/
	 function install() {
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
					}
				}
			
	}
	
	 function check() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_faves';
		if( $wpdb->get_var( "show tables like '$table'" ) != $table ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	 function count() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_faves';
		$total= $wpdb->get_results( "SELECT ID as count FROM $table" );
		return $total[0]->count;
	}
	
	 function add( $_REQUEST ) {
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
		}
		}
	}
	
	 function collect() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_faves';
		$sources= $wpdb->get_results( "SELECT * FROM $table" );
		if( is_array( $sources ) ) {
			return $sources;
		} else {
			return array();
		}
	}
	
	 function gather( $id ) {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_faves';
		$gather= $wpdb->get_results( "SELECT * FROM $table WHERE id= $id" );
		return $gather;
	}
	
	/**
	* Edit the information for a given fave.
	*/
	 function edit( $_REQUEST ) {
		global $wpdb;
		$args= $_REQUEST;
			$table= $wpdb->prefix . 'wik_faves';
			$u= "UPDATE ". $table . 
						" SET title = '" . $args['title'] .
						"', feed_url = '" . $args['url'] .
						"' WHERE id = " . $args['id'];
			$query= $wpdb->query( $u );
	}
	
	 function burninate( $id ) {
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
	 function show_faves() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_faves';
		$show= $wpdb->get_results( "SELECT * FROM $table ORDER BY sortorder ASC" );
		return $show;
	}
	
	 function positions() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_faves';
		$numbers= $wpdb->get_results( "SELECT sortorder FROM $table ORDER BY sortorder ASC" );
		return $numbers;
	}
	
	 function sort( $_REQUEST ) {
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
	
	 function addFavesMenu() {
		add_options_page( __('WicketPixie Faves'), __('WicketPixie Faves'), 9, basename(__FILE__), array( 'FavesAdmin', 'favesMenu' ) );
	}
	
	/**
	* The admin menu for our faves system
	*/
	 function favesMenu() {
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
				<?php include_once('advert.php'); ?>
<?php
	}
}
add_action ('admin_menu', array( 'FavesAdmin', 'addFavesMenu' ) );
FavesAdmin::install();
?>