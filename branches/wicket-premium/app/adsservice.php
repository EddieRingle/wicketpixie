<?php
class AdsService
{
	function install() {
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$table= $wpdb->prefix . 'wik_ads';		
		$q= '';
		if( $wpdb->get_var( "show tables like '$table'" ) != $table ) {
			$q= "CREATE TABLE " . $table . "( 
				id int NOT NULL AUTO_INCREMENT,
				title varchar(255) NOT NULL,
				location varchar(255) NOT NULL,
				code text NULL,
				UNIQUE KEY id (id)
			);";
		}
		if( $q != '' ) {
			dbDelta( $q );
		}			
	}
	
	function count() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_ads';
		$total= $wpdb->get_results( "SELECT ID as count FROM $table" );
		return $total[0]->count;
	}
	
	function check() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_ads';
		if( $wpdb->get_var( "show tables like '$table'" ) != $table ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function collect() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_ads';
		$ads= $wpdb->get_results( "SELECT id, title, location, code FROM $table" );
		if( is_array( $ads ) ) {
			return $ads;
		} else {
			return array();
		}
	}
	
	function gather( $id ) {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_ads';
		$gather= $wpdb->get_results( "SELECT id, title, location, code FROM $table WHERE id= $id" );
		return $gather;
	}
	
	function add( $_REQUEST ) {
		global $wpdb;
		$args= $_REQUEST;
		$table= $wpdb->prefix . 'wik_ads';
		if( $args['title'] != 'Ad Title' ) {
			if( !$wpdb->get_var( "SELECT id FROM $table WHERE title = '" . $args['title'] . "'" ) ) {
				$i= "INSERT INTO " . $table . " (id,title,location,code) VALUES('', '" 
					. $args['title'] . "','" 
					. $args['location'] . "', '" 
					. $args['code'] . "')";
				$query= $wpdb->query( $i );
				$message= 'Ad Saved';
			} else {
				$message= 'You forgot to fill out some information, please try again.';
			}
		}
		return $message;
	}
	
	function edit( $args ) {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_ads';
		$u= "UPDATE $table SET title = '" 
			. $args['title'] . 
			"', location = '" 
			. $args['location'] . 
			"', code = '" 
			. $args['code'] .
			"' WHERE id = " . $args['id'];
		$query= $wpdb->query( $u );
	}
	
	function burninate( $id ) {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_ads';
		$d= $wpdb->query( "DELETE FROM $table WHERE id = $id" );
		$trogdor= $wpdb->query( $d );
	}
	
	function addAdsMenu() {
		add_options_page( __('WicketPixie Ads'), __('WicketPixie Ads'), 9, basename(__FILE__), array( 'AdsService', 'adsmenu' ) );
	}
	
	function AdsMenu() {
		$ads= new AdsService;
		if ( $_GET['page'] == basename(__FILE__) ) {
			switch( $_REQUEST['action'] ) {
				case 'add' :
					$ads->add( $_REQUEST );
				break;
				case 'edit' :
					$ads->edit( $_REQUEST );
				break;
				case 'delete' :
					$ads->burninate( $_REQUEST['id'] );
				break;
			}
		}
?>
	<?php if ( isset( $_REQUEST['add'] ) ) { ?>
		<div id="message" class="updated fade"><p><strong><?php echo __('Ad added.'); ?></strong></p></div>
	<?php } ?>
		<div class="wrap">
			<div id="admin-options">
				<h2><?php _e('Manage My Ads'); ?></h2>
				<p>Wicketpixie has a number of areas preconfigured for your ads. All you need to do is choose an area and paste in the appropriate code.</p>
				<?php if( $ads->check() != 'false' && $ads->count() != '' ) { ?>
				<table class="form-table" style="margin-bottom:30px;">
					<tr>
						<th>Title</th>
						<th style="text-align:center;">Location</th>
						<th style="text-align:center;" colspan="2">Actions</th>
					</tr>
				<?php 
					foreach( $ads->collect() as $ad ) {
				?>		
					<tr>
						<td><?php echo $ad->title; ?></td>
					   	<td style="text-align:center;"><?php echo $ad->location; ?></td>
					   	<td style="text-align:center;">
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=adsservice.php&amp;gather=true&amp;id=<?php echo $ad->id; ?>">
							<input type="submit" value="Edit" />
							<input type="hidden" name="action" value="gather" />
						</form>
						</td>
						<td style="text-align:center;">
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=adsservice.php&amp;delete=true&amp;id=<?php echo $ad->id; ?>">
							<input type="submit" name="action" value="Delete" />
							<input type="hidden" name="action" value="delete" />
						</form>
						</td>
					</tr>
				<?php } ?>
				</table>
				<?php } else { ?>
					<p>You don't have any Ads, why not add some?</p>
				<?php } ?>
				<?php if ( isset( $_REQUEST['gather'] ) ) { ?>
					<?php $data= $ads->gather( $_REQUEST['id'] ); ?>
					<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=adsservice.php&amp;edit=true" class="form-table" style="margin-bottom:30px;">
						<h2>Editing "<?php echo $data[0]->title; ?>"</h2>
						<p>Title:<br /><input type="text" name="title" id="title" value="<?php echo $data[0]->title; ?>" /></p>
						<p>Location:<br />
							<select name="location" id="location">
								<option value="head">Head</option>
								<option value="leaderboard" <?php if( 'leaderboard' == $data[0]->location ) { echo 'selected'; } ?>>Header Leaderboard</option>
								<option value="post_home" <?php if( 'post_home' == $data[0]->location ) { echo 'selected'; } ?>>In Post (home)</option>
								<option value="post_single" <?php if( 'post_single' == $data[0]->location ) { echo 'selected'; } ?>>In Post (single)</option>
								<option value="sidebar_home" <?php if( 'sidebar_home' == $data[0]->location ) { echo 'selected'; } ?>>Sidebar ad (home)</option>
								<option value="sidebar_single" <?php if( 'sidebar_single' == $data[0]->location ) { echo 'selected'; } ?>>Sidebar ad (single)</option>
							</select>
						</p>
						<p>Ad Code: <br /><textarea name="code" id="code" cols="50" rows="10"><?php echo $data[0]->code; ?></textarea></p>
						<p class="submit">
							<input name="save" type="submit" value="Save Ad" />
							<input type="hidden" name="action" value="edit" />
							<input type="hidden" name="id" value="<?php echo $data[0]->id; ?>">
						</p>
					</form>
				<?php } ?>
				<?php if( $ads->check() != 'false' && !isset( $_REQUEST['gather'] ) ) { ?>
				<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=adsservice.php&amp;add=true" class="form-table" style="margin-bottom:30px;">
						<h2>Add a New Ad</h2>
						<p><input type="text" name="title" id="title" onfocus="if(this.value=='Ad Title')value=''" onblur="if(this.value=='')value='Ad Title';" value="Ad Title" /></p>
						<p>Location:<br />
							<select name="location" id="location">
								<option value="head">Head</option>
								<option value="leaderboard">Header Leaderboard</option>
								<option value="post_home">In Post (home)</option>
								<option value="post_single">In Post (single)</option>
								<option value="sidebar_home">Sidebar ad (home)</option>
								<option value="sidebar_single">Sidebar ad (single)</option>
							</select>
						</p>
						<p>Ad Code: <br /><textarea name="code" id="code" cols="50" rows="10"></textarea></p>
						<p class="submit">
							<input name="save" type="submit" value="Save Ad" />    
							<input type="hidden" name="action" value="add" />
						</p>
					</form>
			<?php } ?>
			</div>
		</div>
<?php
	}

	function header() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_ads';
		$ad= $wpdb->get_results("SELECT * FROM $table WHERE location= 'head'");
		if( is_array( $ad ) ) {
			return $ad[0]->code;
		} else {
			// return nothing.
		}
	}
	
	function leaderboard() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_ads';
		$ad= $wpdb->get_results("SELECT * FROM $table WHERE location= 'leaderboard'");
		if( is_array( $ad ) ) {
			return $ad[0]->code;
		} else {
			// return nothing.
		}
	}

	function home() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_ads';
		$ad= $wpdb->get_results("SELECT * FROM $table WHERE location= 'post_home'");
		if( is_array( $ad ) ) {
			return $ad[0]->code;
		} else {
			// return nothing.
		}	
	}

	function sidebar_home() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_ads';
		$ad= $wpdb->get_results("SELECT * FROM $table WHERE location= 'sidebar_home'");
		if( is_array( $ad ) ) {
			return $ad[0]->code;
		} else {
			// return nothing.
		}	
	}
	
	function sidebar_single() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_ads';
		$ad= $wpdb->get_results("SELECT * FROM $table WHERE location= 'sidebar_single'");
		if( is_array( $ad ) ) {
			return $ad[0]->code;
		} else {
			// return nothing.
		}	
	}
}
add_action ('admin_menu', array( 'AdsService', 'addAdsMenu' ) );
AdsService::install();
?>