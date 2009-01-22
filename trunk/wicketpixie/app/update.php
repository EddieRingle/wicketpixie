<?php
class SourceUpdate
{
	public function activated() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_sources';
		$check= $wpdb->get_results( "SELECT updates FROM $table" );
		return $check;
	}
	
	public function select() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_sources';
		$newest= $wpdb->get_results( "SELECT * FROM $table WHERE updates = 1" );
		return $newest;
	}
	
	public function display() {
		require_once('simplepie.php');
		$feed= self::select();
		$feed_path= $feed[0]->feed_url;
		$feed= new SimplePie( (string) $feed_path, ABSPATH . '/' . (string) '/wp-content/uploads/activity/' );

		SourceAdmin::clean_dir();

		$feed->handle_content_type();

		if( $feed->data ) {
			foreach( $feed->get_items() as $entry ) {
				$name= $stream->title;
				$update[]['name']= (string) $name;
				$update[]['title']= $entry->get_title();
				$update[]['link']= $entry->get_permalink();
				$update[]['date']= strtotime( substr( $entry->get_date(), 0, 25 ) );
			}
			
			$return= array_slice( $update, 0, 5);			
			return substr($return[1]['title'], 0, 150) . ' &mdash; <a href="' . $return[2]['link'] . '" title="">' . date( 'g:ia', $return[3]['date'] ) . '</a>';
		} else {
			return 'Thanks for exploring my world! Can you believe this avatar is talking to you?';
		}
	}
}
?>
