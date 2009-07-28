<?php
/*
Plugin Name: WicketPixie Source Manager
Plugin URI: http://chris.pirillo.com
Description: Management Screen for the sources in WicketPixie
Author: Chris J. Davis and Eddie Ringle
Version: 1.1b1
Author URI: http://chris.pirillo.com
*/

class SourceAdmin extends AdminPage {
	
	var $db_version= '1.0';
	
	/**
	* Here we install the tables and initial data needed to
	* power our special functions
	*/
	static function install() {
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
			
			add_option('wicketpixie_sources_db_version', $db_version );
			
	}
	
	function __construct()
	{
	    parent::__construct('Social Me Manager','sourcemanager.php','wicketpixie-admin.php',null);
	}
	
	function page_output()
	{
	    $this->SourceMenu();
	}
	
	function __destruct()
	{
	    parent::__destruct();
	}
	
	/**
	* Grab all the sources we have stored in the db.
	* <code>
	* foreach( SourceAdmin::collect() as $source ) {
	* 	echo $source->title;	
	* }
	* </code>
	*/
	static function collect() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_sources';
		$sources= $wpdb->get_results( "SELECT * FROM $table" );
		if( is_array( $sources ) ) {
			return $sources;
		} else {
			return array();
		}
	}
	
	/**
	* Called when we think the caches are getting messy
	**/
	static function clean_dir() {
        clearstatcache();
    
        // Clean the activity stream
		$cache = ABSPATH . 'wp-content/uploads/activity/';
        if(is_dir($cache))
        {
            $d = dir($cache);
            while($entry = $d->read()) {
                if ($entry != "." && $entry != "..") {
                    unlink($cache . $entry);
                }
            }
            $d->close();
        }
        // Clean the WiPi cache
        $cache = TEMPLATEPATH . '/app/cache/';
        if(is_dir($cache))
        {
            $d = dir($cache);
            while($entry = $d->read()) {
                if($entry != "." && $entry != "..") {
                    unlink($cache . $entry);
                }
            }
            $d->close();
        }
	}
	
	static function get_streams() {
		global $wpdb;
		require_once(SIMPLEPIEPATH);
		SourceAdmin::clean_dir();

		$table= $wpdb->prefix . 'wik_sources';
		$streams= $wpdb->get_results( "SELECT title,feed_url FROM $table WHERE lifestream = 1" );
		
		foreach ( $streams as $stream ) {
			$feed_path= $stream->feed_url;
			$feed= new SimplePie( (string) $feed_path, ABSPATH . (string) 'wp-content/uploads/activity' );
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

	static function archive_streams() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_life_data';
		foreach( SourceAdmin::get_streams() as $archive ) {
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
	* foreach( SourceAdmin::show_streams() as $stream ) {
	*	// do something clever
	* }
	* </code>
	*/
	static function show_streams() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_life_data';
		$show= $wpdb->get_results( "SELECT * FROM $table WHERE enabled = 1 ORDER BY date DESC" );
		return $show;
	}
	
	static function flush_streams( $stream ) {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_life_data';
		$delete= $wpdb->get_results( "DELETE FROM $table WHERE name = '$stream'" );
	}
	
	static function source( $name ) {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_sources';
		$which= $wpdb->get_results( "SELECT profile_url, favicon FROM $table WHERE title = '$name'" );
		return $which[0];
	}
    
    static function feed_check ($name) {
        global $wpdb;
        $table = $wpdb->prefix . 'wik_sources';
        $feedlink = $wpdb->get_var("SELECT feed_url FROM $table WHERE title = '$name'");
        if ($feedlink == "")
        {
            $isfeed = 0;
        } elseif ($feedlink != "") {
            $isfeed = 1;
        }
        return $isfeed;
    }
	
	static function legend_types() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_sources';
		$types= $wpdb->get_results( "SELECT * FROM $table ORDER BY title" );
		return $types;
	}
	
	/**
	* Convenience method for counting the number of 
	* sources currently in the DB.
	* <code>
	* SourceAdmin::count();
	* </code>
	*/
	static function count() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_sources';
		$total= $wpdb->get_results( "SELECT ID as count FROM $table" );
		return $total[0]->count;
	}
	
	/**
	* Convenience method for checking if we have installed
	* the table for sources. Returns TRUE or FALSE.
	* <code>
	* SourceAdmin::check();
	* </code>
	*/
	static function check() {
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
	* SourceAdmin::add( $_REQUEST );
	* </code>
	*/
	static function add( $_REQUEST ) {
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
        
        if( $args['url'] == "Profile Feed URL" ) {
            $dbfeedurl = "";
        } else {
            $dbfeedurl = $args['url'];
        }

		$table= $wpdb->prefix . 'wik_sources';
		if( $args['title'] != 'Social Site Title' ) {
		if( !$wpdb->get_var( "SELECT id FROM $table WHERE feed_url = '" . $args['url'] . "'" ) ) {
		$favicon_url= explode('/', $args['profile']);
		$i= "INSERT INTO " . $table . " (id,title,profile_url,feed_url,type,lifestream,updates,favicon) VALUES('', '" 
		. $args['title'] . "','" 
		. $args['profile'] . "', '" 
		. $dbfeedurl . "', " 
		. $args['type'] . ", " 
		. $stream . ", "
		. $update . ", "
		. "'http://www.google.com/s2/favicons?domain=$favicon_url[2]')";
		$query= $wpdb->query( $i );
		SourceAdmin::create_widget();
		$message= 'Social Site Saved';
		} else {
			$message= 'You forgot to fill out some information, please try again.';
		}
		}
		return $message;
	}
	
	static function gather( $id ) {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_sources';
		$gather= $wpdb->get_results( "SELECT * FROM $table WHERE id= $id" );
		return $gather;
	}
	
	/**
	* Edit the information for a given source.
	*/
	static function edit( $_REQUEST ) {
		global $wpdb;
		$args= $_REQUEST;
			if( $args['lifestream'] == 1 ) { 
				$stream= 1;
				SourceAdmin::toggle( $args['id'], 1 );
			} else { 
				$stream= 0;
				SourceAdmin::toggle( $args['id'], 0 );
			}

			if( $args['updates'] == 1 ) { 
				$update= 1; 
			} else { 
				$update= 0;
			}
            if( $args['url'] == "Profile Feed URL" ) {
                $dbfeedurl = "";
            } else {
                $dbfeedurl = $args['url'];
            }
			
			$table= $wpdb->prefix . 'wik_sources';
			$u= "UPDATE ". $table . 
						" SET title = '" . $args['title'] .
						"', profile_url = '" . $args['profile'] .
						"', feed_url = '" . $dbfeedurl .
						"', type = " . $args['type'] .
						", lifestream = " . $stream .
						", updates = " . $update .
						" WHERE id = " . $args['id'];
			$query= $wpdb->query( $u );
			SourceAdmin::create_widget();
	}
	
	static function toggle( $id, $direction ) {
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
	* SourceAdmin::burninate( $id );
	* </code>
	*/
	static function burninate( $id ) {
		global $wpdb;
		SourceAdmin::toggle( $id, 0 );
		$table= $wpdb->prefix . 'wik_sources';
		$u= $wpdb->query( "UPDATE $lifedata SET enabled= 0 WHERE name= '$source'" );
		$d= $wpdb->query( "DELETE FROM $table WHERE id = $id" );
		$trogdor= $wpdb->query( $d );
		
		SourceAdmin::create_widget();
	}
	
	static function hulk_smash() {
		global $wpdb;
		$puny_table= $wpdb->prefix . 'wik_sources';
		$to_smash= $wpdb->query( "DROP TABLE $puny_table" );
		$hulk= $wpdb->query( $to_smash );
	}
	
	/**
	* Method to fetch the types of sources we have stored in the db.
	* <code>
	* SourceAdmin::types();
	* </code>
	*/
	static function types() {
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
	* SourceAdmin::type_name( $type_id );
	* </code>
	*/
    static function type_name( $id ) {
		global $wpdb;
		$link= $wpdb->prefix . 'wik_source_types';
		$name= $wpdb->get_results( "SELECT name FROM $link WHERE type_id = '$id'" );
		return $name[0]->name;
	}    static function get_feed( $url ) {
		require_once ( SIMPLEPIEPATH );
		$feed_path= $url;
		$feed= new SimplePie( (string) $feed_path, ABSPATH . (string) 'wp-content/uploads/activity' );
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

	 static function create_file($title,$cleaned,$favicon_url,$feed_url)
	 {
	    $t_title = str_replace(' ','',$title);
		$data = null;
        $data =
        "<?php
        /**
        * ${t_title}FeedWidget Class
        */
        class ${t_title}FeedWidget extends WP_Widget
        {
            function ${t_title}FeedWidget()
            {
                \$widget_ops = array('classname' => 'widget_${cleaned}_feed','description' => __('Lists feed items from the ${title} feed added in the Social Me Manager.'));
                \$this->WP_Widget('${cleaned}feed',__('${title} Feed'),\$widget_ops,null);
            }

            function widget(\$args,\$instance)
            {
                extract(\$args);

                echo \$before_widget;
                echo \$before_title, '<img src=\"http://www.google.com/s2/favicons?domain=$favicon_url[2]\" alt=\"','${title}','\" />','${title}', \$after_title;
                
                \$items = SourceAdmin::get_feed('${feed_url}');
                ?>
                    <ul>
                    <?php
                    \$total = 5;
                    foreach(\$items as \$item) {
                        if(\$i != \$total) {
                            echo '<li><a href=\"',\$item['link'],'\" title=\"',\$item['title'],'\">',\$item['title'],'</a></li>';
                            \$i++;
                        }
                    }
                    ?>
                    </ul>
                <?php
                echo \$after_widget;
            }

            function update(\$new_instance,\$old_instance)
            {
                return \$old_instance;
            }

            function form(\$instance)
            {
            }
        }
        ?>";
        
		$path= TEMPLATEPATH . "/widgets/" . $cleaned . ".php";
		file_put_contents( $path, $data );
		error_log( 'Creating '.$title.' widget.' );
	}

    /**
    * Makes a widget which you can put in your sidebar.
    * The widget displays the 5 most recent entries in the source's feed.
    **/
	static function create_widget() {
		$data= '';
		$data='<?php';
		foreach( SourceAdmin::collect() as $widget ) {
            if(SourceAdmin::feed_check($widget->title) == 1) {
                $title = $widget->title;
                $t_title = str_replace(' ','',$title);
                $cleaned= strtolower( $title );
                $cleaned= preg_replace( '/\W/', ' ', $cleaned );
                $cleaned= str_replace( " ", "", $cleaned );
                $data .= "
                function ${t_title}Init() {
                    include_once( TEMPLATEPATH . '/widgets/$cleaned.php');
                    register_widget('${t_title}FeedWidget');
                }";
                add_option( $cleaned . '-num', 5 );	
                SourceAdmin::create_file($title,$cleaned,explode('/',$widget->profile_url),$widget->feed_url);
            }
		}
		$data .= ' ?>';
		file_put_contents( TEMPLATEPATH . '/widgets/sources.php', $data );
	}

	/**
	* The admin page for our sources/activity system.
	**/
	 function sourceMenu() {
		if ( $_GET['page'] == basename(__FILE__) ) {
		    switch($_POST['action'])
		    {
		        case 'add':
		            SourceAdmin::add($_REQUEST);
		            ?>
		            <div id="message" class="updated fade"><p><strong><?php echo __('Social Me Account saved.'); ?></strong></p></div>
		            <?php
		            break;
		        case 'gather':
		            SourceAdmin::gather($_REQUEST['id']);
		            break;
                case 'edit':
                    SourceAdmin::edit($_REQUEST);
                    ?>
                    <div id="message" class="updated fade"><p><strong><?php echo __('Social Me Account modified.'); ?></strong></p></div>
		            <?php
                    break;
                case 'delete':
                    SourceAdmin::burninate($_REQUEST['id']);
                    ?>
                    <div id="message" class="updated fade"><p><strong><?php echo __('Social Me Account removed.'); ?></strong></p></div>
		            <?php
                    break;
                case 'regen_widgets':
                    SourceAdmin::create_widget();
                    ?>
                    <div id="message" class="updated fade"><p><strong><?php echo __('Social Me Widgets regenerated.'); ?></strong></p></div>
                    <?php
                    break;
                case 'hulk_smash':
                    SourceAdmin::hulk_smash();
                    ?>
                    <div id="message" class="updated fade"><p><strong><?php echo __('Social Me database cleared.'); ?></strong></p></div>
		            <?php
                    break;
                case 'install':
                    SourceAdmin::install();
                    ?>
                    <div id="message" class="updated fade"><p><strong><?php echo __('Social Me Manager installed.'); ?></strong></p></div>
		            <?php
                    break;
                case 'flush':
                    SourceAdmin::flush_streams($_REQUEST['flush_name']);
                    ?>
                    <div id="message" class="updated fade"><p><strong><?php echo __('Social Me Account flushed.'); ?></strong></p></div>
		            <?php
                    break;
                default:
                    break;
            }
		}
		?>
			<div class="wrap">
				
				<div id="admin-options">
				
					<h2><?php _e('Manage My "Social Me" Page'); ?></h2>
                    <p>If you'd like to let your visitors know where else they may find you throughout the Web,
                    you can do it easily through your "Social Me" page. This is an exclusive feature of the WicketPixie theme for WordPress.
                    If you have accounts on other blogs, social networks, forums, Web sites, etc, add them here. For example,
                    you might add your Twitter, YouTube, and Flickr accounts here - making sure you use the corresponding RSS (or Atom) feeds for your profile,
                    so that WicketPixie can display your latest content from them on your Social Me page.<br /><br />
                    You can also include the list of these accounts in your sidebar - just be sure to enable the <a href="widgets.php">WicketPixie Social Me widget</a> first!</p>
                    <h3>Widget Regenerator</h3>
                    <p>If you are upgrading to 1.2+ from a version earlier than 1.2, you will need to click this button if you already have Social Mes added. You can also press this button if widgets seem to be broken.</p>
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=sourcemanager.php&amp;regen_widgets=true">
                        <p class="submit">
                            <input type="submit" name="submit" value="Regenerate Widgets" />
                            <input type="hidden" name="action" value="regen_widgets" />
                        </p>
                    </form>
                    <h3>Social Me Listing</h3>
					<?php if( SourceAdmin::check() != 'false' && SourceAdmin::count() != '' ) { ?>
					<form>
					<?php wp_nonce_field('wicketpixie-settings'); ?>
						<p style="margin-bottom:0;">Sort by: <select name="type" id="type">
							<?php foreach( SourceAdmin::types() as $type ) { ?>
								<option value="<?php echo $type->type_id; ?>"><?php echo $type->name; ?></option>
							<?php } ?>
						</select>	</p>
					</form>
					<table class="form-table" style="margin-bottom:20px;">
						<tr>
                            <th>Icon</th>
							<th>Title</th>
							<th style="text-align:center;">Feed</th>
							<th style="text-align:center;">Type</th>
							<th style="text-align:center;">Activity</th>
							<th style="text-align:center;" colspan="3">Actions</th>
						</tr>
						<?php 
							foreach( SourceAdmin::collect() as $source ) {
								if( $source->lifestream == 0 ) {
									$streamed= 'No';
								} else {
									$streamed= 'Yes';
								}
                                $isfeed = SourceAdmin::feed_check($source->title);
						?>		
						<tr>
							<td style="width:16px;"><img src="<?php echo $source->favicon; ?>" alt="Favicon" style="width: 16px; height: 16;" /></td>
                            <td><a href="<?php echo $source->profile_url; ?>"><?php echo $source->title; ?></a></td>
                        <?php if ($isfeed == 1) { ?>
					   	<td style="text-align:center;"><a href="<?php echo $source->feed_url; ?>"><img src="<?php bloginfo('template_directory'); ?>/images/icon-feed.gif" alt="View"/></a></td>
                        <?php } elseif ($isfeed == 0) { ?>
                        <td style="text-align:center;">N/A</td>
                        <?php } else { ?>
                        <td style="text-align:center;">?</td>
                        <?php } ?>
					   	<td style="text-align:center;"><?php echo SourceAdmin::type_name( $source->type ); ?></td>
					   	<td style="text-align:center;"><?php echo $streamed; ?></td>
					   	<td>
							<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=sourcemanager.php&amp;gather=true&amp;id=<?php echo $source->id; ?>">
							<?php wp_nonce_field('wicketpixie-settings'); ?>
								<input type="submit" value="Edit" />
								<input type="hidden" name="action" value="gather" />
							</form>
							</td>
							<td>
							<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=sourcemanager.php&amp;delete=true&amp;id=<?php echo $source->id; ?>">
							<?php wp_nonce_field('wicketpixie-settings'); ?>
								<input type="submit" name="action" value="Delete" />
								<input type="hidden" name="action" value="delete" />
							</form>
							</td>
                            <?php if ($isfeed == 1) { ?>
							<td>
							<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=sourcemanager.php&amp;flush=true&amp;id=<?php echo $source->id; ?>">
							<?php wp_nonce_field('wicketpixie-settings'); ?>
								<input type="submit" value="Flush" />
								<input type="hidden" name="action" value="flush" />
								<input type="hidden" name="flush_name" value="<?php echo $source->title; ?>" />
							</form>
							</td>
                            <?php } ?>
						</tr>
					<?php } ?>
					</table>
					<?php } else { ?>
						<p>You don't have any Social Mes, why not add some?</p>
					<?php } ?>
					<?php if ( isset( $_REQUEST['gather'] ) ) { ?>
						<?php $data= SourceAdmin::gather( $_REQUEST['id'] ); ?>
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=sourcemanager.php&amp;edit=true" class="form-table" style="margin-bottom:30px;">
						<?php wp_nonce_field('wicketpixie-settings'); ?>
							<h2>Editing "<?php echo $data[0]->title; ?>"</h2>
							<p><input type="text" name="title" id="title" value="<?php echo $data[0]->title; ?>" /></p>
							<p><input type="text" name="profile" id="profile" value="<?php echo $data[0]->profile_url; ?>" /></p>
							<p><input type="text" name="url" id="url" value="<?php echo $data[0]->feed_url; ?>" /></p>
							<p><input type="checkbox" name="lifestream" id="lifestream" value="1" <?php if( $data[0]->lifestream == '1' ) { echo 'checked'; } ?>> Add to Activity?</p>
							<p><input type="checkbox" name="updates" id="updates" value="1" <?php if( $data[0]->updates == '1' ) { echo 'checked'; } ?>> Use for Status Updates?</p>
							<p>Type:
								<select name="type" id="type">
									<?php foreach( SourceAdmin::types() as $type ) { ?>
										<option value="<?php echo $type->type_id; ?>" <?php if( $type->type_id == $data[0]->type ) { echo 'selected'; } ?>><?php echo $type->name; ?></option>
									<?php } ?>
								</select>
							</p>
							<p class="submit">
								<input name="save" type="submit" value="Save Social Me" />
								<input type="hidden" name="action" value="edit" />
								<input type="hidden" name="id" value="<?php echo $data[0]->id; ?>">
							</p>
						</form>
					<?php } ?>
					<?php if( SourceAdmin::check() != 'false' && !isset( $_REQUEST['gather'] ) ) { ?>
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=sourcemanager.php&amp;add=true" class="form-table" style="margin-bottom:30px;">
						<?php wp_nonce_field('wicketpixie-settings'); ?>
							<h2>Add a New Social Me</h2>
							<p><input type="text" name="title" id="title" onfocus="if(this.value=='Social Me Title')value=''" onblur="if(this.value=='')value='Social Me Title';" value="Social Me Title" /></p>
							<p><input type="text" name="profile" id="profile" onfocus="if(this.value=='Profile URL')value=''" onblur="if(this.value=='')value='Profile URL';" value="Profile URL" /></p>
							<p><input type="text" name="url" id="url" onfocus="if(this.value=='Profile Feed URL')value=''" onblur="if(this.value=='')value='Profile Feed URL';" value="Profile Feed URL" /></p>
							<p><input type="checkbox" name="lifestream" id="lifestream" value="1" checked="checked"> Add to Activity Stream?</p>
							<p><input type="checkbox" name="updates" id="updates" value="1"> Use for Status Updates?</p>
							<p>Type:
								<select name="type" id="type">
									<?php foreach( SourceAdmin::types() as $type ) { ?>
										<option value="<?php echo $type->type_id; ?>"><?php echo $type->name; ?></option>
									<?php } ?>
								</select>
							</p>
							<p class="submit">
								<input name="save" type="submit" value="Save Social Me" />    
								<input type="hidden" name="action" value="add" />
							</p>
						</form>
						<form name="hulk_smash" id="hulk_smash" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=sourcemanager.php&amp;hulk_smash=true">
						<?php wp_nonce_field('wicketpixie-settings'); ?>
							<h2>Delete the Social Mes Table</h2>
							<p>Please note, this is undoable and will result in the loss of all the data you have stored to date. Only do this if you are having problems with your social mes and you have exhausted every other option.</p>
							<p class="submit">
								<input name="save" type="submit" value="Delete Social Mes" />    
								<input type="hidden" name="action" value="hulk_smash" />
							</p>
						</form>
						<?php } else { ?>
							<p>Table not installed. You should go ahead and run the installer.</p>
							<form name="install" id="install" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=sourcemanager.php&amp;install=true">
							<?php wp_nonce_field('wicketpixie-settings'); ?>
								<p class="submit">
									<input type="hidden" name="action" value="install" />
									<input type="submit" value="Install Social Me" />
								</p>
							</form>
						<?php } ?>
				
					</div>

					<?php include_once('advert.php'); ?>
				
<?php
	}
}
?>
