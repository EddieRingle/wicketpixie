<?php
/*
Plugin Name: WicketPixie Source Manager
Plugin URI: http://chrispirillo.com
Description: Management Screen for the sources in WicketPixie
Author: Chris J. Davis
Version: 1.0
Author URI: http://chrispirillo.com
*/

class SourceAdmin {
	
	var $db_version= '1.0';
	
	/**
	* Here we install the tables and initial data needed to
	* power our special functions
	*/
	function install() {
		global $wpdb, $db_version;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$table= $wpdb->prefix . 'wik_sources';
		$link= $wpdb->prefix . 'wik_source_types';
		$life= $wpdb->prefix . 'wik_life_data';
		
		$q= '';
		if( $wpdb->get_var( "show tables like '$table'" ) != $table ) {
			$q= "CREATE TABLE " . $table . "( 
				id int NOT NULL AUTO_INCREMENT,
				title varchar(255) NOT NULL,
				profile_url varchar(255) NOT NULL,
				feed_url varchar(255) NOT NULL,
				type boolean NOT NULL,
				lifestream boolean NOT NULL,
				updates boolean NOT NULL,
				favicon varchar(255) NOT NULL,
				UNIQUE KEY id (id)
			);";
		}
		if( $wpdb->get_var( "show tables like '$life'" ) != $life ) {
			$q .= "CREATE TABLE " . $life . "(
				id int NOT NULL AUTO_INCREMENT,
				name varchar(255) NOT NULL,
				content TEXT NOT NULL,
				date varchar(255) NOT NULL,
				link varchar(255) NOT NULL,
				enabled boolean NOT NULL,
				UNIQUE KEY id (id)
			);";
		}
		if( $wpdb->get_var( "show tables like '$link'" ) != $link ) {
			$q .= "CREATE TABLE " . $link . "( 
				id int NOT NULL AUTO_INCREMENT,
				type_id tinyint(4) NOT NULL,
				name varchar(255) NOT NULL,
				UNIQUE KEY id (id)
			);";
  		}
			if( $q != '' ) {
				dbDelta( $q );
			}
			$types= array(
						array( 'type_id' => '3', 'name' => 'Social Network' ),
						array( 'type_id' => '2', 'name' => 'Website/Blog' ),
						array( 'type_id' => '1', 'name' => 'Images' )
					);

				foreach( $types as $type ) {
					if( !$wpdb->get_var( "SELECT type_id FROM $link WHERE type_id = " . $type['type_id'] ) ) {
					$i= "INSERT INTO " . $link . " (id,type_id,name) VALUES('', '" . $type['type_id'] . "','" . $type['name'] . "')";
					$query= $wpdb->query( $i );
					}
				}
			
			add_option( 'wik_db_version', $db_version );
			
	}
	
	/**
	* Just calling WP's method to add a new menu to the design section.
	*/
	function addMenu() {
		add_options_page( __('WicketPixie Sources'), __('WicketPixie Sources'), 9, basename(__FILE__), array( 'SourceAdmin', 'sourceMenu' ) );
	}
	
	/**
	* Grab all the sources we have stored in the db.
	* <code>
	* foreach( $sources->collect() as $source ) {
	* 	echo $source->title;	
	* }
	* </code>
	*/
	function collect() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_sources';
		$sources= $wpdb->get_results( "SELECT * FROM $table" );
		if( is_array( $sources ) ) {
			return $sources;
		} else {
			return array();
		}
	}
	
	function clean_dir() {
		$cache= ABSPATH . 'wp-content/uploads/activity/';
		$d= dir( $cache );
		while( $entry= $d->read() ) {
			if ( $entry!= "." && $entry!= ".." ) {
				unlink( $cache . $entry );	
			}
		 }
		$d->close();
	}
	
	function get_streams() {
		global $wpdb;
		require_once( 'simplepie.php' );
		$this->clean_dir();

		$table= $wpdb->prefix . 'wik_sources';
		$streams= $wpdb->get_results( "SELECT title,feed_url FROM $table WHERE lifestream = 1" );
		
		foreach ( $streams as $stream ) {
			$feed_path= $stream->feed_url;
			$feed= new SimplePie( (string) $feed_path, ABSPATH . '/' . (string) '/wp-content/uploads/activity/' );
			$feed->set_cache_duration(10);
			$feed->handle_content_type();
			if( $feed->data ) {
				foreach( $feed->get_items() as $entry ) {
					$name= $stream->title;
					$date = strtotime( substr( $entry->get_date(), 0, 25 ) );
					$stream_contents[$date]['name']= (string) $name;
					$stream_contents[$date]['title']= $entry->get_title();
					$stream_contents[$date]['link']= $entry->get_permalink();
					$stream_contents[$date]['date']= strtotime( substr( $entry->get_date(), 0, 25 ) );
					if ( $enclosure = $entry->get_enclosure( 0 ) ) {
						$stream_contents[$date]['enclosure'] = $enclosure->get_link();
					}
				}
			}
		}

		krsort( $stream_contents );
		return $stream_contents;
	}

	function archive_streams() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_life_data';
		foreach( $this->get_streams() as $archive ) {
			if( !$wpdb->get_var( "SELECT id FROM $table WHERE link = '" . $archive['link'] . "' AND date= " . $archive['date'] ) ) {
				$a= "INSERT INTO $table (id,name,content,date,link,enabled) VALUES('', '" 
					. addslashes( $archive['name'] ) . "','" 
					. addslashes( $archive['title'] ) . "', '" 
					. $archive['date'] . "', '" 
					. $archive['link'] . "', "
					. "1)";
			$query= $wpdb->query( $a );
			}
		}
	}
	
	/**
	* Method to grab all of our lifestream data from the DB.
	* <code>
	* foreach( $sources->show_streams() as $stream ) {
	*	// do something clever
	* }
	* </code>
	*/
	function show_streams() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_life_data';
		$show= $wpdb->get_results( "SELECT * FROM $table WHERE enabled = 1 ORDER BY date DESC" );
		return $show;
	}
	
	function flush_streams( $stream ) {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_life_data';
		$delete= $wpdb->get_results( "DELETE FROM $table WHERE name = '$stream'" );
	}
	
	function source( $name ) {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_sources';
		$which= $wpdb->get_results( "SELECT profile_url, favicon FROM $table WHERE title = '$name'" );
		return $which[0];
	}
	
	function legend_types() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_sources';
		$types= $wpdb->get_results( "SELECT * FROM $table ORDER BY title" );
		return $types;
	}
	
	/**
	* Convenience method for counting the number of 
	* sources currently in the DB.
	* <code>
	* $sources->count();
	* </code>
	*/
	function count() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_sources';
		$total= $wpdb->get_results( "SELECT ID as count FROM $table" );
		return $total[0]->count;
	}
	
	/**
	* Convenience method for checking if we have installed
	* the table for sources. Returns TRUE or FALSE.
	* <code>
	* $sources->check();
	* </code>
	*/
	function check() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_sources';
		if( $wpdb->get_var( "show tables like '$table'" ) != $table ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	/**
	* Method for adding sources to the DB. Simple
	* pass the entire request body and add() takes
	* care of the rest.
	* <code>
	* $sources->add( $_REQUEST );
	* </code>
	*/
	function add( $_REQUEST ) {
		global $wpdb;
		$args= $_REQUEST;
			if( $args['lifestream'] == 1 ) { 
				$stream= 1; 
			} else { 
				$stream= 0;
			}
			
			if( $args['updates'] == 1 ) { 
				$update= 1; 
			} else { 
				$update= 0;
			}
		
		$table= $wpdb->prefix . 'wik_sources';
		if( $args['title'] != 'Source Title' ) {
		if( !$wpdb->get_var( "SELECT id FROM $table WHERE feed_url = '" . $args['url'] . "'" ) ) {
		$favicon_url= explode('/', $args['profile']);
		$i= "INSERT INTO " . $table . " (id,title,profile_url,feed_url,type,lifestream,updates,favicon) VALUES('', '" 
		. $args['title'] . "','" 
		. $args['profile'] . "', '" 
		. $args['url'] . "', " 
		. $args['type'] . ", " 
		. $stream . ", "
		. $update . ", "
		. "'http://www.google.com/s2/favicons?domain=$favicon_url[2]')";
		$query= $wpdb->query( $i );
		$this->create_widget();
		$message= 'Source Saved';
		} else {
			$message= 'You forgot to fill out some information, please try again.';
		}
		}
		return $message;
	}
	
	 function gather( $id ) {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_sources';
		$gather= $wpdb->get_results( "SELECT * FROM $table WHERE id= $id" );
		return $gather;
	}
	
	/**
	* Edit the information for a given source.
	*/
	 function edit( $_REQUEST ) {
		global $wpdb;
		$args= $_REQUEST;
			if( $args['lifestream'] == 1 ) { 
				$stream= 1;
				$this->toggle( $args['id'], 1 );
			} else { 
				$stream= 0;
				$this->toggle( $args['id'], 0 );
			}

			if( $args['updates'] == 1 ) { 
				$update= 1; 
			} else { 
				$update= 0;
			}
			
			$table= $wpdb->prefix . 'wik_sources';
			$u= "UPDATE ". $table . 
						" SET title = '" . $args['title'] .
						"', profile_url = '" . $args['profile'] .
						"', feed_url = '" . $args['url'] .
						"', type = " . $args['type'] .
						", lifestream = " . $stream .
						", updates = " . $update .
						" WHERE id = " . $args['id'];
			$query= $wpdb->query( $u );
			$this->create_widget();
	}
	
	 function toggle( $id, $direction ) {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_sources';
		$name= $wpdb->get_results( "SELECT title FROM $table WHERE id = $id" );
		$source= $name[0]->title;
		$lifedata= $wpdb->prefix . 'wik_life_data';
		if( $direction == 0 ) {
			$u= $wpdb->query( "UPDATE $lifedata SET enabled= 0 WHERE name= '$source'" );
		} elseif( $direction == 1 ) {
			$u= $wpdb->query( "UPDATE $lifedata SET enabled= 1 WHERE name= '$source'" );
		}
	}
	
	/**
	* We call burninate so that Trogdor the Dragon-Man can
	* burninate the peasants. Or sources as the case may be.
	* Removes the offending record from the DB.
	* <code>
	* $sources->burninate( $id );
	* </code>
	*/
	 function burninate( $id ) {
		global $wpdb;
		$this->toggle( $id, 0 );
		$table= $wpdb->prefix . 'wik_sources';
		$u= $wpdb->query( "UPDATE $lifedata SET enabled= 0 WHERE name= '$source'" );
		$d= $wpdb->query( "DELETE FROM $table WHERE id = $id" );
		$trogdor= $wpdb->query( $d );
		
		$this->create_widget();
	}
	
	 function hulk_smash() {
		global $wpdb;
		$puny_table= $wpdb->prefix . 'wik_sources';
		$to_smash= $wpdb->query( "DROP TABLE $puny_table" );
		$hulk= $wpdb->query( $to_smash );
	}
	
	/**
	* Method to fetch the types of sources we have stored in the db.
	* <code>
	* $sources->types();
	* </code>
	*/
	 function types() {
		global $wpdb;
		$link= $wpdb->prefix . 'wik_source_types';
		$types= $wpdb->get_results( "SELECT * FROM $link" );
			if( is_array( $types ) ) {
				return $types;
			} else {
				return array();
			}
	}
	
	/**
	* Helper method to return the human readable name
	* of a type, given a type_id.
	* <code>
	* $sources->type_name( $type_id );
	* </code>
	*/
	 function type_name( $id ) {
		global $wpdb;
		$link= $wpdb->prefix . 'wik_source_types';
		$name= $wpdb->get_results( "SELECT name FROM $link WHERE type_id = '$id'" );
		return $name[0]->name;
	}

	 function get_feed( $url ) {
		require_once ( 'simplepie.php' );
			$feed_path= $url;
			$feed= new SimplePie( (string) $feed_path, ABSPATH . '/' . (string) '/wp-content/uploads/activity/' );
			SourceAdmin::clean_dir();
			$feed->handle_content_type();
				if( $feed->data ) {
					foreach( $feed->get_items() as $entry ) {
						$name= $stream->title;
						$date = strtotime( substr( $entry->get_date(), 0, 25 ) );
						$widget_contents[$date]['name']= (string) $name;
						$widget_contents[$date]['title']= $entry->get_title();
						$widget_contents[$date]['link']= $entry->get_permalink();
						$widget_contents[$date]['date']= strtotime( substr( $entry->get_date(), 0, 25 ) );
						if ( $enclosure = $entry->get_enclosure( 0 ) ) {
							$widget_contents[$date]['enclosure'] = $enclosure->get_link();
						}
					}
				}
				return $widget_contents;
	}

	 function create_file( $widget ) {
		$cleaned= strtolower( $widget->title );
		$cleaned= preg_replace( '/\W/', ' ', $cleaned );
		$cleaned= str_replace( " ", "", $cleaned );
		$favicon_url= explode('/', $widget->profile_url);
		
		$data= '';
		$data .= '<?php' . "\n";
		$data .= '$total= 5;' . "\n";
		$data .= 'echo \'<div class="widget">\';' . "\n";
		$data .= "echo '<h3><img src=\"http://www.google.com/s2/favicons?domain=$favicon_url[2]\" alt=\"$widget->title\" />$widget->title</h3>';" . "\n";
		$data .= "echo '<ul>';" . "\n";
		$data .= '$items= SourceAdmin::get_feed( "'. $widget->feed_url . '" );
		foreach( $items as $item ) {
			if( $i != $total ) {
				echo \'<li><a href="\' . $item[\'link\'] . \'" title="\' . $item[\'title\'] . \'">\' . $item[\'title\'] . \'</a></li>\';' . "\n";
			$data .= '$i++;' . "\n";
			$data .= '}' . "\n";
		$data .= '}' . "\n";
		$data .= "echo '</ul>';" . "\n";
		$data .="echo '</div>';" . "\n";
		$path= ABSPATH . "wp-content/themes/wicketpixie/widgets/" . $cleaned . ".php";
		file_put_contents( $path, $data );
		error_log( 'Creating widget.' );
	}

	 function create_widget() {
		$data= '';
		$data='<?php';
		foreach( $this->collect() as $widget ) {
			$cleaned= strtolower( $widget->title );
			$cleaned= preg_replace( '/\W/', ' ', $cleaned );
			$cleaned= str_replace( " ", "", $cleaned );
			$data .= "
			function wicketpixie_$cleaned() {
				include( ABSPATH . 'wp-content/themes/wicketpixie/widgets/$cleaned.php');
			}";
			add_option( $cleaned . '-num', 5 );	
			$this->create_file( $widget );
		}
		$data .= ' ?>';
		file_put_contents( ABSPATH . 'wp-content/themes/wicketpixie/widgets/sources.php', $data );
	}

	/**
	* The admin menu for our sources/activity system.
	*/
	 function sourceMenu() {
		$sources= new SourceAdmin;
		if ( $_GET['page'] == basename(__FILE__) ) {
	        if ( 'add' == $_REQUEST['action'] ) {
				$return= $sources->add( $_REQUEST );
			}
			
			if ( 'gather' == $_REQUEST['action'] ) {
				$sources->gather( $_REQUEST['id'] );
			}
			
			if ( 'edit' == $_REQUEST['action'] ) {
				$sources->edit( $_REQUEST );
			}
			
	        if ( 'delete' == $_REQUEST['action'] ) {
				$sources->burninate( $_REQUEST['id'] );
			}
			
			if( 'hulk_smash' == $_REQUEST['action'] ) {
				$sources->hulk_smash();
			}
			
			if( 'install' == $_REQUEST['action'] ) {
				$sources->install();
			}
			
			if( 'flush' == $_REQUEST['action'] ) {
				$sources->flush_streams( $_REQUEST['flush_name'] );
			}
		}
		?>
		<?php if ( isset( $_REQUEST['add'] ) ) { ?>
		<div id="message" class="updated fade"><p><strong><?php echo __('Source saved.'); ?></strong></p></div>
		<?php } ?>
			<div class="wrap">
				
				<div id="admin-options">
				
					<h2><?php _e('Manage My Sources'); ?></h2>
					<?php if( $sources->check() != 'false' && $sources->count() != '' ) { ?>
					<form>
						<p style="margin-bottom:0;">Sort by: <select name="type" id="type">
							<?php foreach( $sources->types() as $type ) { ?>
								<option value="<?php echo $type->type_id; ?>"><?php echo $type->name; ?></option>
							<?php } ?>
						</select>	</p>
					</form>
					<table class="form-table" style="margin-bottom:20px;">
						<tr>
							<th>Title</th>
							<th style="text-align:center;">Feed</th>
							<th style="text-align:center;">Type</th>
							<th style="text-align:center;">Activity</th>
							<th style="text-align:center;" colspan="3">Actions</th>
						</tr>
						<?php 
							foreach( $sources->collect() as $source ) {
								if( $source->lifestream == 0 ) {
									$streamed= 'No';
								} else {
									$streamed= 'Yes';
								}
						?>		
						<tr>
							<td><a href="<?php echo $source->profile_url; ?>"><?php echo $source->title; ?></a></td>
					   	<td style="text-align:center;"><a href="<?php echo $source->feed_url; ?>"><img src="<?php bloginfo('template_directory'); ?>/images/icon-feed.gif" alt="View"/></a></td>
					   	<td style="text-align:center;"><?php echo $sources->type_name( $source->type ); ?></td>
					   	<td style="text-align:center;"><?php echo $streamed; ?></td>
					   	<td>
							<form method="post" action="options-general.php?page=sourcemanager.php&amp;gather=true&amp;id=<?php echo $source->id; ?>">
								<input type="submit" value="Edit" />
								<input type="hidden" name="action" value="gather" />
							</form>
							</td>
							<td>
							<form method="post" action="options-general.php?page=sourcemanager.php&amp;delete=true&amp;id=<?php echo $source->id; ?>">
								<input type="submit" name="action" value="Delete" />
								<input type="hidden" name="action" value="delete" />
							</form>
							</td>
							<td>
							<form method="post" action="options-general.php?page=sourcemanager.php&amp;flush=true&amp;id=<?php echo $source->id; ?>">
								<input type="submit" value="Flush" />
								<input type="hidden" name="action" value="flush" />
								<input type="hidden" name="flush_name" value="<?php echo $source->title; ?>" />
							</form>
							</td>
						</tr>
					<?php } ?>
					</table>
					<?php } else { ?>
						<p>You don't have any sources, why not add some?</p>
					<?php } ?>
					<?php if ( isset( $_REQUEST['gather'] ) ) { ?>
						<?php $data= $sources->gather( $_REQUEST['id'] ); ?>
						<form method="post" action="options-general.php?page=sourcemanager.php&amp;edit=true" class="form-table" style="margin-bottom:30px;">
							<h2>Editing "<?php echo $data[0]->title; ?>"</h2>
							<p><input type="text" name="title" id="title" value="<?php echo $data[0]->title; ?>" /></p>
							<p><input type="text" name="profile" id="profile" value="<?php echo $data[0]->profile_url; ?>" /></p>
							<p><input type="text" name="url" id="url" value="<?php echo $data[0]->feed_url; ?>" /></p>
							<p><input type="checkbox" name="lifestream" id="lifestream" value="1" <?php if( $data[0]->lifestream == '1' ) { echo 'checked'; } ?>> Add to Activity?</p>
							<p><input type="checkbox" name="updates" id="updates" value="1" <?php if( $data[0]->updates == '1' ) { echo 'checked'; } ?>> Use for Status Updates?</p>
							<p>Type:
								<select name="type" id="type">
									<?php foreach( $sources->types() as $type ) { ?>
										<option value="<?php echo $type->type_id; ?>" <?php if( $type->type_id == $data[0]->type ) { echo 'selected'; } ?>><?php echo $type->name; ?></option>
									<?php } ?>
								</select>
							</p>
							<p class="submit">
								<input name="save" type="submit" value="Save Source" />
								<input type="hidden" name="action" value="edit" />
								<input type="hidden" name="id" value="<?php echo $data[0]->id; ?>">
							</p>
						</form>
					<?php } ?>
					<?php if( $sources->check() != 'false' && !isset( $_REQUEST['gather'] ) ) { ?>
						<form method="post" action="options-general.php?page=sourcemanager.php&amp;add=true" class="form-table" style="margin-bottom:30px;">
							<h2>Add a New Source</h2>
							<p><input type="text" name="title" id="title" onfocus="if(this.value=='Source Title')value=''" onblur="if(this.value=='')value='Source Title';" value="Source Title" /></p>
							<p><input type="text" name="profile" id="profile" onfocus="if(this.value=='Profile URL')value=''" onblur="if(this.value=='')value='Profile URL';" value="Profile URL" /></p>
							<p><input type="text" name="url" id="url" onfocus="if(this.value=='Profile Feed URL')value=''" onblur="if(this.value=='')value='Profile Feed URL';" value="Profile Feed URL" /></p>
							<p><input type="checkbox" name="lifestream" id="lifestream" value="1" checked="checked"> Add to Activity Stream?</p>
							<p><input type="checkbox" name="updates" id="updates" value="1"> Use for Status Updates?</p>
							<p>Type:
								<select name="type" id="type">
									<?php foreach( $sources->types() as $type ) { ?>
										<option value="<?php echo $type->type_id; ?>"><?php echo $type->name; ?></option>
									<?php } ?>
								</select>
							</p>
							<p class="submit">
								<input name="save" type="submit" value="Save Source" />    
								<input type="hidden" name="action" value="add" />
							</p>
						</form>
						<form name="hulk_smash" id="hulk_smash" method="post" action="options-general.php?page=sourcemanager.php&amp;hulk_smash=true">
							<h2>Delete the Sources Table</h2>
							<p>Please note, this is undoable and will result in the loss of all the data you have stored to date. Only do this if you are having problems with your sources and you have exhausted every other option.</p>
							<p class="submit">
								<input name="save" type="submit" value="Delete Sources" />    
								<input type="hidden" name="action" value="hulk_smash" />
							</p>
						</form>
						<?php } else { ?>
							<p>Table not installed. You should go ahead and run the installer.</p>
							<form name="install" id="install" method="post" action="options-general.php?page=sourcemanager.php&amp;install=true">
								<p class="submit">
									<input type="hidden" name="action" value="install" />
									<input type="submit" value="Install Sources" />
								</p>
							</form>
						<?php } ?>
				
					</div>

					<?php include_once('advert.php'); ?>
				
<?php
	}
}

add_action ('admin_menu', array( 'SourceAdmin', 'addMenu' ) );
register_activation_hook( __FILE__, array( 'SourceAdmin', 'install' ) );

include( ABSPATH . 'wp-content/themes/wicketpixie/app/faves.php');
include( ABSPATH . 'wp-content/themes/wicketpixie/app/update.php');

?>