<?php
class SourceUpdate
{
	function activated() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_sources';
		$check= $wpdb->get_results( "SELECT updates FROM $table" );
		return $check;
	}
	
	function select() {
		global $wpdb;
		$table= $wpdb->prefix . 'wik_sources';
		$newest= $wpdb->get_results( "SELECT * FROM $table WHERE updates = 1" );
		return $newest;
	}
    
    function fetchfeed() {
        require_once('simplepie.php');
        $feed = $this->select();
        
        if(preg_match('/twitter\.com/',$feed[0]->feed_url) == true) {
            $istwitter = 1;
        }
        
        $feed_path = $feed[0]->feed_url;
        $feed = new SimplePie((string)$feed_path, ABSPATH . (string)'wp-content/uploads/activity');
        
        SourceAdmin::clean_dir();
        
        $feed->handle_content_type();
        if($feed->data) {
            foreach($feed->get_items() as $entry) {
                $name = $stream->title;
                $update[]['name'] = (string)$name;
                $update[]['title']= $entry->get_title();
				$update[]['link']= $entry->get_permalink();
				$update[]['date']= strtotime( substr( $entry->get_date(), 0, 25 ) );
			}
            
            $return = array_slice($update,0,5);
            
            $return[1]['title'] = preg_replace('((?:\S)+://\S+[[:alnum:]]/?)', '<a href="\0">\0</a>', $return[1]['title']);
            
            if( $istwitter == 1 ) {
                $return[1]['title'] = preg_replace('/(@)([A-Za-z0-9_-]+)/', '<a href="http://twitter.com/\2">\0</a>', $return[1]['title']);
            }
            
			return substr($return[1]['title'], 0, 1000) . ' &mdash; <a href="' . $return[2]['link'] . '" title="">' . date( 'g:ia', $return[3]['date'] ) . '</a>';
        } else {
            return "Thanks for exploring my world! Can you believe this avatar is talking to you?";
        }
    }
    
    function chkfile($f) {
        clearstatcache();
        // Check to see if the feed file exists
        $isfile = is_file($f);
        
        if($isfile == false) {
            return false;
        } elseif ($isfile == true) {
            // Fetch the files last modification time
            $lastupdated = filemtime($f);
            // Now get the current time
            $currenttime = time();
            
            // Aaannnddd... compare!
            $diff = $currenttime - $lastupdated;
            
            // If it's been more than 45 seconds, refetch the feed
            if($diff >= 45) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }            
    }
    
    function cacheit($f) {        
        // Use SimplePie to fetch the latest feed
        $latest = $this->fetchfeed();
        
        // Store it in a file
        file_put_contents($f,$latest);
        // Set the file's last modified time to right now
        touch($f);
    }
    
    function getfeedfile($f) {
        // Simple, open the file and return the contents
        return file_get_contents($f);
    }
    
    /**
    * Displays the feed entry.
    **/
	function display() {
        $f = TEMPLATEPATH . '/app/cache/statusupdate.cache'; // The location of the feed file
        // Check to see if we're using a recent feed file
        $result = $this->chkfile($f);
        
        // If feed file is outdated, store a new one
        if($result == false) {
            $this->cacheit($f);
        }
        
        // Now prepare to display the latest item
        $out = $this->getfeedfile($f);
        
        // Time to let people know what's up!
        return $out;
	}
}
?>
