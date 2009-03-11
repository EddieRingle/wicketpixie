<?php
/*
Copyright (c) 2007
http://www.gnu.org/licenses/gpl.txt

This file is part of FAlbum.
FAlbum is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

define('FALBUM_VERSION', '0.7.1');

define('FALBUM_PATH', dirname(__FILE__));

define('FALBUM_DO_NOT_CACHE', 0);
define('FALBUM_CACHE_EXPIRE_SHORT', 3600); //How many seconds to wait between refreshing cache (default = 3600 seconds - hour)
define('FALBUM_CACHE_EXPIRE_LONG', 604800); //How many seconds to wait between refreshing cache (default = 604800 seconds - 1 week)

define('FALBUM_API_KEY', '15c8257735c58c6fb497def1ab289f96');
define('FALBUM_SECRET', '9220711d42110dbd');

define('FALBUM_FLICKR_URL_IMAGE_1', 'http://farm');
define('FALBUM_FLICKR_URL_IMAGE_2', '.static.flickr.com');

class FAlbum {

	var $options = none;

	var $can_edit;
	var $show_private;

	var $has_error;
	var $error_detail;

	var $logger;

	var $template;


	function FAlbum() {

		require_once FALBUM_PATH.'/lib/Log.php';

		// Init Lang
		include_once(FALBUM_PATH . '/falbum-lang.php');

		//

		$this->has_error = false;
		$this->error_detail = null;

		$this->options = $this->get_options();

		$this->can_edit = $this->_can_edit();
		$this->show_private = $this->_show_private();

		$this->_construct_template($this->options['style']);

		$conf = array ('title' => 'FAlbum Log Output');
		if ($this->can_edit == true) {
			//this->logger = & Log :: factory('fwin', 'LogWindow', '', $conf);
			//$this->logger = & Log :: factory('display', 'LogWindow', '', $conf);
			$this->logger = & Log :: factory('null', 'LogWindow');
		} else {
			//$this->logger = & Log :: factory('fwin', 'LogWindow', '', $conf);
			$this->logger = & Log :: factory('null', 'LogWindow');
		}

		//$mask = Log::UPTO(PEAR_LOG_INFO);
		//$this->logger->setMask($mask);

	}

	/* The main function - called in album.php, and can be called in any WP template. */
	function show_photos() {
		echo $this->show_photos_main();
	}

	function show_photos_main() {
		$album = $_GET['album'];
		$photo = $_GET['photo'];
		$page = $_GET['page'];
		$tags = $_GET['tags'];
		$show = $_GET['show'];

		$output = '';
		$continue = true;
		if (!is_null($show)) {
			if ($show == 'tags') {
				$output = $this->show_tags();
				$continue = false;
			}
			elseif ($show == 'recent') {
				$tags = '';
			}
		}

		if ($continue) {
			// Show list of albums/photosets (none have been selected yet)
			if (is_null($album) && is_null($tags) && is_null($photo)) {
				$output = $this->show_albums($page);
			}
			// Show list of photos in the selected album/photoset
			elseif (!is_null($album) && is_null($photo)) {
				$output = $this->show_album_thumbnails($album, $page);
			}
			// Show list of photos of the selected tags
			elseif (!is_null($tags) && is_null($photo)) {
				$output = $this->show_tags_thumbnails($tags, $page);
			}
			// Show the selected photo in the slected album/photoset
			elseif ((!is_null($album) || !is_null($tags)) && !is_null($photo)) {
				$output = $this->show_photo($album, $tags, $photo, $page);
			}
		}

		if ($this->has_error) {
			$this->template->reset('error');
			$this->template->set('message', $this->error_detail);
			$output = $this->template->fetch();
		}

		return $output;
	}

	/* Shows list of all albums - this is the first thing you see */
	function show_albums($page = 1) {

		$this->logger->info("show_albums($page)");

		if ($page == '') {
			$page = 1;
		}

		$output = $this->_get_cached_data("showAlbums-$page");

		if (!isset ($output)) {

			$this->template->reset('albums');
			
			$this->template->set('page_title', $this->get_page_title());

			$output = '';

			$count = 0;
			$albums_list = array ();

			if ($this->options['number_recent'] != 0) {
				$count ++;

				if ($page == 1) {

					$resp = $this->_call_flickr_php('flickr.photos.search', array ("user_id" => $this->options['nsid'], "per_page" => '1', "sort" => 'date-taken-desc'));
					if (!isset ($resp)) {
						return;
					}

					$server = $resp['photos']['photo']['0']['server'];
					$farm = $resp['photos']['photo']['0']['farm'];
					$secret = $resp['photos']['photo']['0']['secret'];
					$photo_id = $resp['photos']['photo']['0']['id'];
					$thumbnail = $this->_create_flickr_image_url($farm, $server, $photo_id, $secret, $this->options['tsize']); 
					
					$data['tsize'] = $this->options['tsize'];
					$data['url'] = $this->create_url("show/recent/");
					$data['title'] = fa__('Recent Photos');
					$data['title_d'] = fa__('View all recent photos');
					$data['tags_url'] = $this->create_url("show/tags/");
					$data['tags_title'] = fa__('Tags');
					$data['tags_title_d'] = fa__('Tags');
					$data['description'] = fa__('See the most recent photos posted, regardless of which photo set they belong to.');
					$data['thumbnail'] = $thumbnail;

					$albums_list[] = $data;

				}
			}

			$resp = $this->_call_flickr_php('flickr.photosets.getList', array ("user_id" => $this->options['nsid']));
			if (!isset ($resp)) {
				return;
			}

			$countResult = sizeof($resp['photosets']['photoset']);

			$photo_title_array = array ();
			for ($i = 0; $i < $countResult; $i ++) {

				if (($this->options['albums_per_page'] == 0) || (($count >= ($page -1) * $this->options['albums_per_page']) && ($count < $page * $this->options['albums_per_page']))) {

					$photos = $resp['photosets']['photoset'][$i]['photos'];

					if ($photos != 0) {
						$data = null;

						$id = $resp['photosets']['photoset'][$i]['id'];
						$server = $resp['photosets']['photoset'][$i]['server'];
						$farm = $resp['photosets']['photoset'][$i]['farm'];
						$primary = $resp['photosets']['photoset'][$i]['primary'];
						$secret = $resp['photosets']['photoset'][$i]['secret'];
						$title = $this->_unhtmlentities($resp['photosets']['photoset'][$i]['title']['_content']);
						$description = $this->_unhtmlentities($resp['photosets']['photoset'][$i]['description']['_content']);

						$link_title = $this->_get_link_title($title, $id, $photo_title_array);
						$thumbnail = $this->_create_flickr_image_url($farm, $server, $primary, $secret, $this->options['tsize']); 
						
						$data['tsize'] = $this->options['tsize'];
						$data['url'] = $this->create_url("album/$link_title/");
						$data['title'] = $title;
						$data['title_d'] = strtr(fa__('View all pictures in #title#'), array ("#title#" => $title));
						$data['meta'] = strtr(fa__('This photoset has #num_photots# pictures'), array ("#num_photots#" => $photos));
						$data['description'] = $description;
						$data['thumbnail'] = $thumbnail;

						$albums_list[] = $data;

					} else {
						$count --;
					}
				}
				$count ++;
			}

			$this->template->set('albums', $albums_list);

			if ($this->options['albums_per_page'] != 0) {
				$pages = ceil($count / $this->options['albums_per_page']);
				if ($pages > 1) {
					$this->template->set('top_paging', $this->_build_paging($page, $pages, 'page/', 'top'));
					$this->template->set('bottom_paging', $this->_build_paging($page, $pages, 'page/', 'bottom'));
				}
			}

			$this->template->set('css_type_thumbnails', $this->options['display_dropshadows']);

			$this->template->set('remote_url', $this->options['url_falbum_dir']."/falbum-remote.php");
			$this->template->set('url_root', $this->options['url_root']);

			$output = $this->template->fetch();

			$this->_set_cached_data("showAlbums-$page", $output);

		}

		return $output;
	}

	/* Shows Thumbnails of all photos in selected album */
	function show_album_thumbnails($album, $page = 1) {

		$this->logger->info("show_album_thumbnails($album, $page)");

		if ($page == '') {
			$page = 1;
		}

		$output = $this->_get_cached_data("showAlbumThumbnails-$album-$page");
		if (!isset ($output)) {

			$this->template->reset('album-thumbnails');
			
			$this->template->set('page_title', $this->get_page_title());

			list ($album_id, $album_title) = $this->_get_album_info($album);

			$resp = $this->_call_flickr_php('flickr.photosets.getPhotos', array ("photoset_id" => $album_id));
			if (!isset ($resp)) {
				return;
			}

			$countResult = sizeof($resp['photoset']['photo']);

			$photo_title_array = array ();
			$thumbnails_list = array ();

			$count = 0;
			for ($i = 0; $i < $countResult; $i ++) {

				if (($this->options['photos_per_page'] == 0) || (($count >= ($page -1) * $this->options['photos_per_page']) && ($count < ($page * $this->options['photos_per_page'])))) {
					$photo_id = $resp['photoset']['photo'][$i]['id'];
					$photo_title = $resp['photoset']['photo'][$i]['title'];
					$server = $resp['photoset']['photo'][$i]['server'];
					$farm = $resp['photoset']['photo'][$i]['farm'];
					$secret = $resp['photoset']['photo'][$i]['secret'];

					$photo_link = $this->_get_link_title($photo_title, $photo_id, $photo_title_array);
					$thumbnail = $this->_create_flickr_image_url($farm, $server, $photo_id, $secret, $this->options['tsize']); 
					
					$data['tsize'] = $this->options['tsize'];
					$data['url'] = $this->create_url("album/$album/page/$page/photo/$photo_link");
					$data['title'] = $photo_title;
					$data['thumbnail'] = $thumbnail;

					$thumbnails_list[] = $data;
				}
				$count ++;
			}

			if ($this->options['photos_per_page'] != 0) {
				$pages = ceil($countResult / $this->options['photos_per_page']);

				if ($pages > 1) {
					$this->template->set('top_paging', $this->_build_paging($page, $pages, 'album/'.$album.'/page/', 'top'));
					$this->template->set('bottom_paging', $this->_build_paging($page, $pages, 'album/'.$album.'/page/', 'bottom'));
				}
			}

			$this->template->set('url', $this->create_url());
			$this->template->set('album_title', $album_title);
			$this->template->set('album_id', $album_id);
			$this->template->set('photos_label', fa__('Photos'));
			$this->template->set('slide_show_label', fa__('View as a slide show'));
			$this->template->set('thumbnails', $thumbnails_list);

			$this->template->set('css_type_thumbnails', $this->options['display_dropshadows']);

			$this->template->set('remote_url', $this->options['url_falbum_dir']."/falbum-remote.php");
			$this->template->set('url_root', $this->options['url_root']);

			$output = $this->template->fetch();

			$this->_set_cached_data("showAlbumThumbnails-$album-$page", $output);

		}
		return $output;
	}

	/* Shows thumbnails for all Recent and Tag thumbnails */
	function show_tags_thumbnails($tags, $page = 1) {

		$this->logger->info("show_tags_thumbnails($tags, $page)");

		if ($page == '') {
			$page = 1;
		}

		$output = $this->_get_cached_data("show_tags_thumbnails-$tags-$page");
		if (!isset ($output)) {

			$this->template->reset('tag-thumbnails');
			
			$this->template->set('page_title', $this->get_page_title());

			$output = '';

			if ($tags == '') {
				// Get recent photos
				if ($this->options['number_recent'] == -1) {
					$resp = $this->_call_flickr_php('flickr.photos.search', array ('user_id' => $this->options['nsid'], 'sort' => 'date-taken-desc', 'per_page' => $this->options['photos_per_page'], 'page' => $page));
				} else {
					$resp = $this->_call_flickr_php('flickr.photos.search', array ('user_id' => $this->options['nsid'], 'sort' => 'date-taken-desc', 'per_page' => $this->options['number_recent'], 'page' => '1'));
				}
			} else {
				$resp = $this->_call_flickr_php('flickr.photos.search', array ('user_id' => $this->options['nsid'], 'tags' => $tags, 'tag_mode' => 'all', 'per_page' => $this->options['photos_per_page'], 'page' => $page));
			}

			if (!isset ($resp)) {
				return;
			}

			$countResult = sizeof($resp['photos']['photo']);

			if ($tags == '') {
				$urlPrefix = 'show/recent/page/';
				$this->template->set('recent_label', fa__('Recent Photos'));
			} else {
				$urlPrefix = "tags/$tags/page/";

				$this->template->set('tag_url', $this->create_url('show/tags'));
				$this->template->set('tags_label', fa__('Tags'));
				$this->template->set('tags', $tags);
			}

			$photo_title_array = array ();
			$thumbnails_list = array ();
			$count = 0;

			for ($i = 0; $i < $countResult; $i ++) {

				if (($this->options['photos_per_page'] == 0) || $tags != '' || $this->options['number_recent'] == -1 || (($count >= ($page -1) * $this->options['photos_per_page']) && ($count < ($page * $this->options['photos_per_page'])))) {

					$photo_id = $resp['photos']['photo'][$i]['id'];
					$photo_title = $resp['photos']['photo'][$i]['title'];
					$server = $resp['photos']['photo'][$i]['server'];
					$farm = $resp['photos']['photo'][$i]['farm'];
					$secret = $resp['photos']['photo'][$i]['secret'];

					$photo_link = $this->_get_link_title($photo_title, $photo_id, $photo_title_array);
					$thumbnail = $this->_create_flickr_image_url($farm, $server, $photo_id, $secret, $this->options['tsize']); 
					
					$data['tsize'] = $this->options['tsize'];
					$data['url'] = $this->create_url($urlPrefix."$page/photo/$photo_link");
					$data['title'] = $photo_title;
					$data['thumbnail'] = $thumbnail;

					$thumbnails_list[] = $data;

				}
				$count ++;
			}

			if ($this->options['photos_per_page'] != 0) {

				$this->logger->info("tags($tags)");
				$this->logger->info("number_recent->".$this->options['number_recent']);

				if ($tags == '' && $this->options['number_recent'] != -1) {

					$this->logger->info("here");

					$pages = ceil($this->options['number_recent'] / $this->options['photos_per_page']);
				} else {
					$pages = $resp['photos']['pages'];
				}

				$this->logger->info("pages($pages)");

				if ($pages > 1) {
					$this->template->set('top_paging', $this->_build_paging($page, $pages, $urlPrefix, 'top'));
					$this->template->set('bottom_paging', $this->_build_paging($page, $pages, $urlPrefix, 'bottom'));
				}
			}

			$this->template->set('thumbnails', $thumbnails_list);
			$this->template->set('url', $this->create_url());
			$this->template->set('photos_label', fa__('Photos'));

			$this->template->set('css_type_thumbnails', $this->options['display_dropshadows']);

			$this->template->set('remote_url', $this->options['url_falbum_dir']."/falbum-remote.php");
			$this->template->set('url_root', $this->options['url_root']);

			$output = $this->template->fetch();

			$this->_set_cached_data("show_tags_thumbnails-$tags-$page", $output);

		}
		return $output;
	}

	/* Shows the selected photo */
	function show_photo($album, $tags, $photo, $page = 1) {

		$this->logger->info("show_photo($album, $tags, $photo, $page)");

		if ($page == '') {
			$page = 1;
		}
		if ($album == '') {
			$album = null;
		}

		$in_photo = $photo;
		$in_album = $album;

		$output = $this->_get_cached_data("show_photo-$in_album-$tags-$in_photo-$page");

		if (!isset ($output)) {

			$this->template->reset('photo');

			$this->template->set('page_title', $this->get_page_title());

			$this->template->set('album', $album);
			$this->template->set('in_tags', $tags);

			$output = '';

			// Get Prev and Next Photos

			if (!is_null($album) && $album != '') {
				$url_prefix = "album/$album";
				list ($album_id, $album_title) = $this->_get_album_info($album);
				$resp = $this->_call_flickr_php('flickr.photosets.getPhotos', array ('photoset_id' => $album_id));
			} else {
				if ($tags == '') {
					$url_prefix = 'show/recent';
					$album_title = fa__('Recent Photos');
					$resp = $this->_call_flickr_php('flickr.photos.search', array ('user_id' => $this->options['nsid'], 'sort' => 'date-taken-desc', 'per_page' => $this->options['photos_per_page'], 'page' => $page));
				} else {
					$url_prefix = "tags/$tags";
					$album_title = fa__('Tags');
					$album_title = $tags;
					$resp = $this->_call_flickr_php('flickr.photos.search', array ('user_id' => $this->options['nsid'], 'tags' => $tags, 'tag_mode' => 'all', 'per_page' => $this->options['photos_per_page'], 'page' => $page));
				}
			}

			//Get navigation info
			if (!isset ($resp)) {
				return;
			}

			$photo = $this->_get_photo_id($resp, $photo);
			if (!is_null($album)) {
				$result = $resp['photoset']['photo'];
			} else {
				$total_pages = $resp['photos']['pages'];
				$total_photos = $resp['photos']['total'];
				$result = $resp['photos']['photo'];
			}

			$prev = $tmp_prev = $next = $photo;
			$prevPage = $nextPage = $page;

			$control = 1;

			$photo_title_array = array ();
			$tmp_prev_title = '';

			$countResult = sizeof($result);

			for ($i = 0; $i < $countResult; $i ++) {
				$photo_id = $result[$i]['id'];
				$photo_title = $result[$i]['title'];
				$secret = $result[$i]['secret'];
				$server = $result[$i]['server'];

				$photo_title = $this->_get_link_title($photo_title, $photo_id, $photo_title_array);

				if ($control == 0) {
					// Selected photo was the last one, so this one is the next one
					$next = $photo_id; // Set ID of the next photo
					$next_title = $photo_title;
					$next_sec = $secret; // Set ID of the next photo
					$next_server = $server; // Set ID of the next photo
					break; // Break out of the foreach loop
				}

				if ($photo_id == $photo) {

					// This is the selected photo
					$prev = $tmp_prev; // Set ID of the previous photo
					$prev_title = $tmp_prev_title;
					$control --; // Decrement control variable to tell next iteration of loop that the selected photo was found

					if (is_null($album)) {
						if ($i == 0 && $page > 1) {
							$findPrev = true;
						}
						if (($i == ($countResult -1)) && ($page < $total_pages)) {
							$findNext = true;
						}
					} else {
						if ($this->options['photos_per_page'] > 0) {
							$pages = ($countResult / $this->options['photos_per_page']);

							if ($page > 1 && ($i % $this->options['photos_per_page']) == 0) {
								$prevPage = $prevPage -1;
							}

							if ($page < $pages && (($i +1) % $this->options['photos_per_page']) == 0) {
								$nextPage = $nextPage +1;
							}
						} else {
							$pages = $prevPage = $nextPage = 1;
						}
					}

				}
				$tmp_prev = $photo_id; // Keep the last photo in a temporary variable in case next photo is the selected on
				$tmp_prev_title = $photo_title;
			}

			if ($findPrev) {
				$prevPage = $prevPage -1;

				if ($tags == '') {
					$url_prefix = 'show/recent';
					$resp = $this->_call_flickr_php('flickr.photos.search', array ('user_id' => $this->options['nsid'], 'sort' => 'date-taken-desc', 'per_page' => $this->options['photos_per_page'], 'page' => $prevPage));
				} else {
					$url_prefix = "tags/$tags";
					$resp = $this->_call_flickr_php('flickr.photos.search', array ('user_id' => $this->options['nsid'], 'tags' => $tags, 'tag_mode' => 'all', 'per_page' => $this->options['photos_per_page'], 'page' => $prevPage));
				}
				if (!isset ($resp)) {
					return;
				}

				$result = $resp['photos']['photo'];
				$countResult = sizeof($result);

				$photo_title_array = array ();
				for ($i = 0; $i < $countResult; $i ++) {
					$prev = $result[$i]['id'];
					$prev_title = $result[$i]['title'];
					$prev_title = $this->_get_link_title($prev_title, $prev, $photo_title_array);
				}
			}

			if ($findNext) {

				$nextPage = $nextPage +1;

				if ($tags == '') {
					$url_prefix = 'show/recent';
					$resp = $this->_call_flickr_php('flickr.photos.search', array ('user_id' => $this->options['nsid'], 'sort' => 'date-taken-desc', 'per_page' => $this->options['photos_per_page'], 'page' => $nextPage));
				} else {
					$url_prefix = "tags/$tags";
					$resp = $this->_call_flickr_php('flickr.photos.search', array ('user_id' => $this->options['nsid'], 'tags' => $tags, 'tag_mode' => 'all', 'per_page' => $this->options['photos_per_page'], 'page' => $nextPage));
				}
				if (!isset ($resp)) {
					return;
				}

				$result = $resp['photos']['photo'];

				$next = $result[0]['id']; // Set ID of the next photo
				$next_title = $result[0]['title'];
				$next_sec = $result[0]['secret']; // Set ID of the next photo
				$next_server = $result[0]['server']; // Set ID of the next photo

				$photo_title_array = array ();
				$next_title = $this->_get_link_title($next_title, $next, $photo_title_array);
			}

			$resp = null;

			if ($this->options['friendly_urls'] == 'title') {
				$nav_next = sanitize_title($next_title);
				$nav_prev = sanitize_title($prev_title);
			} else {
				$nav_next = $next;
				$nav_prev = $prev;
			}

			// Display Photo
			$resp = $this->_call_flickr_php('flickr.photos.getInfo', array ('photo_id' => $photo));

			if (!isset ($resp)) {
				return;
			}

			$server = $resp['photo']['server'];
			$secret = $resp['photo']['secret'];
			$photo_id = $resp['photo']['id'];
			$title = $this->_unhtmlentities($resp['photo']['title']['_content']);
			$date_taken = $resp['photo']['dates']['taken'];
			$description = $this->_unhtmlentities(nl2br($resp['photo']['description']['_content']));
			$comments = $resp['photo']['comments']['_content'];

			//remove $imagex = FALBUM_FLICKR_URL_IMAGE."/{$server}/{$photo}_{$secret}";
			//remove $image = $imagex.".jpg"; // Build URL to medium size image
			//$next_image = FALBUM_FLICKR_URL_IMAGE."/{$next_server}/{$next}_{$next_sec}.jpg"; // Build URL to medium size image
			//$this->template->set('next_image', $next_image);

			//Get Next Photo Size Data
			$resp_sizes = $this->_call_flickr_php('flickr.photos.getSizes', array ('photo_id' => $next));
			if (!isset ($resp_sizes)) {
				return;
			}
			$next_source = '';
			$countResult = sizeof($resp_sizes['sizes']['size']);
			for ($i = 0; $i < $countResult; $i ++) {
				$size = $resp_sizes['sizes']['size'][$i];
				if ($size['label'] == 'Medium') {
					$next_source = $size['source'];
				}
			}
			$this->template->set('next_image', $next_source);

			//Get Current Photo Size Data
			$resp_sizes = $this->_call_flickr_php('flickr.photos.getSizes', array ('photo_id' => $photo));
			if (!isset ($resp_sizes)) {
				return;
			}

			$orig_w_m = null;
			$sizes_list = array ();

			$countResult = sizeof($resp_sizes['sizes']['size']);

			for ($i = 0; $i < $countResult; $i ++) {
				$size = $resp_sizes['sizes']['size'][$i];

				$width = $size['width'];
				$height = $size['height'];
				$source = $size['source'];

				if ($size['label'] == 'Square') {
					$data['image'] = $source;
					$data['display'] = fa__('SQ');
					$data['title'] = fa__('Square')." ({$width}x{$height})";
					$data['value'] = 'sq';
					$sizes_list[] = $data;
				}
				else if ($size['label'] == 'Thumbnail') {
					$data['image'] = $source;
					$data['display'] = fa__('T');
					$data['title'] = fa__('Thumbnail')." ({$width}x{$height})";
					$data['value'] = 't';
					$sizes_list[] = $data;
				}
				else if ($size['label'] == 'Small') {
					$data['image'] = $source;
					$data['display'] = fa__('S');
					$data['title'] = fa__('Small')." ({$width}x{$height})";
					$data['value'] = 's';
					$sizes_list[] = $data;
				}
				else if ($size['label'] == 'Medium') {

					$image = $source;
					$orig_w_m = $width;

					$data['image'] = $source;
					$data['display'] = fa__('M');
					$data['title'] = fa__('Medium')." ({$width}x{$height})";
					$data['value'] = 'm';
					$sizes_list[] = $data;
				}
				else if ($size['label'] == 'Large') {
					$data['image'] = $source;
					$data['display'] = fa__('L');
					$data['title'] = fa__('Large')." ({$width}x{$height})";
					$data['value'] = 'l';
					$sizes_list[] = $data;
				}
				else if ($size['label'] == 'Original') {
					$data['image'] = $source;
					$data['display'] = fa__('O');
					$data['title'] = fa__('Original')." ({$width}x{$height})";
					$data['value'] = 'o';
					$sizes_list[] = $data;
				}

			}

			$this->template->set('sizes', $sizes_list);

			$this->template->set('home_url', $this->create_url());
			$this->template->set('home_label', fa__('Photos'));
			$this->template->set('title_url', $this->create_url("$url_prefix/page/{$page}/"));
			$this->template->set('title_label', $album_title);
			$this->template->set('title', $title);

			//Date Taken
			$this->template->set('date_taken', strtr(fa__('Taken on: #date_taken#'), array ("#date_taken#" => $date_taken)));

			//Tags
			$result = $resp['photo']['tags']['tag'];
			$countResult = count($result);
			if ($countResult > 0) {
				$this->template->set('tags_url', $this->create_url('show/tags'));
				$this->template->set('tags_label', fa__('Tags'));
				$tags_list = array ();
				for ($i = 0; $i < $countResult; $i ++) {
					$value = $result[$i][raw];
					$data['url'] = $this->create_url('tags/'.$result[$i]['_content'].'/');
					$data['tag'] = $value;
					$tags_list[] = $data;
				}
				$this->template->set('tags', $tags_list);
			}

			//Notes
			$result = $resp['photo']['notes']['note'];
			$notes_countResult = count($result);
			if ($notes_countResult > 0) {
				if ($this->options['max_photo_width'] > 0 && $this->options['max_photo_width'] < $orig_w_m) {
					$scale = $this->options['max_photo_width'] / $orig_w_m; // Notes are relative to Medium Size
				} else {
					$scale = 1;
				}
				$notes_list = array ();
				for ($i = 0; $i < $notes_countResult; $i ++) {
					$value = nl2br($result[$i]['_content']);
					$x = 5 + $result[$i]['x'] * $scale;
					$y = 5 + $result[$i]['y'] * $scale;
					$w = $result[$i]['w'] * $scale;
					$h = $result[$i]['h'] * $scale;

					$data['title'] = htmlentities($value);
					$data['coords'] = ($x).','. ($y).','. ($x + $w -1).','. ($y + $h -1);
					$notes_list[] = $data;
				}
				$this->template->set('notes', $notes_list);
			}

			//Photo
			if ($next != $photo) {
				$this->template->set('photo_url', $this->create_url("$url_prefix/page/$nextPage/photo/$nav_next/"));
				$this->template->set('photo_title_label', fa__('Click to view next image'));

			} else {
				$this->template->set('photo_url', $this->create_url("$url_prefix/page/$page/"));
				$this->template->set('photo_title_label', fa__('Click to return to album'));
			}

			$this->template->set('image', $image);

			if ($notes_countResult > 0) {
				$this->template->set('usemap', " usemap='imgmap'");
			}

			if ($this->options['max_photo_width'] != '0' && $this->options['max_photo_width'] < $orig_w_m) {
				$this->template->set('photo_width', $this->options['max_photo_width']);
			} else {
				$this->template->set('photo_width', $orig_w_m);
			}

			// Navigation
			if ($prev != $photo) {
				$this->template->set('prev_button', $this->_create_button('pageprev', $this->create_url("$url_prefix/page/$prevPage/photo/$nav_prev/"), "&laquo; ".fa__('Previous'), fa__('Previous Photo'), 1));

				$this->template->set('prev_page', $prevPage);
				$this->template->set('prev_id', $nav_prev);
			}
			if ($next != $photo) {
				$this->template->set('next_button', $this->_create_button('pagenext', $this->create_url("$url_prefix/page/$nextPage/photo/$nav_next/"), "&nbsp;&nbsp; ".fa__('Next')." &raquo;&nbsp;&nbsp;", fa__('Next Photo'), 1));

				$this->template->set('next_page', $nextPage);
				$this->template->set('next_id', $nav_next);
			}
			$this->template->set('return_button', $this->_create_button('return', $this->create_url("$url_prefix/page/$page/"), fa__('Album Index'), fa__('Return to album index'), 1));

			//Description
			$this->template->set('description_orig', $description);
			if (trim($description) == '') {
				$this->template->set('no_description_text', fa__('click here to add a description'));
				$this->template->set('description', '&nbsp;&nbsp;');
			} else {
				$this->template->set('description', $description);
			}

			//Meta Information
			//Sizes
			if ($this->options['display_sizes'] == 'true') {
				$this->template->set('sizes_label', fa__('Available Sizes'));
			}

			// Flickr / Comments
			if ($comments > 0) {

				$resp_comments = $this->_call_flickr_php('flickr.photos.comments.getList', array ('photo_id' => $photo));
				if (isset ($resp_comments)) {

					$result = $resp_comments['comments']['comment'];
					$notes_countResult = sizeof($result);

					$this->logger->info($notes_countResult);

					$comments_list = array ();
					for ($i = 0; $i < $notes_countResult; $i ++) {
						$value = nl2br($result[$i]['_content']);
						$author = $result[$i]['author'];

						//flickr.people.getInfo
						$resp_info = $this->_call_flickr_php('flickr.people.getInfo', array ('user_id' => $author));
						if (isset ($resp_info)) {
							$data['author_name'] = $resp_info['person']['username']['_content'];
							$data['author_url'] = $resp_info['person']['photosurl']['_content'];
							$data['author_location'] = $resp_info['person']['location']['_content'];
						}

						$data['text'] = $this->_unhtmlentities($value);

						$comments_list[] = $data;
					}
					$this->template->set('comments', $comments_list);

				}

			}

			$this->template->set('nsid', $this->options['nsid']);
			$this->template->set('photo', $photo);

			$this->template->set('flickr_label', fa__('See this photo on Flickr'));

			$remote_url = $this->options['url_falbum_dir']."/falbum-remote.php";
			$this->template->set('remote_url', $remote_url);

			$this->template->set('url_root', $this->options['url_root']);

			$this->template->set('photo_id', $photo_id);

			//Exif
			if (strtolower($this->options['display_exif']) == 'true') {
				$this->template->set('exif_data', "{$photo_id}','{$secret}','{$remote_url}");
				$this->template->set('exif_label', fa__('Show Exif Data'));
			}

			$this->template->set('can_edit', $this->can_edit);

			//Post Helper
			$post_value = '[fa:p:';
			if ($tags != '') {
				$post_value .= "t=$tags,";
			} else
				if ($album != '') {
					$post_value .= "a=$album,";
				}
			if ($page != '' and $page != 1) {
				$post_value .= "p=$page,";
			}
			$post_value .= "id=$photo_id,j=l,s=s,l=p]";
			$this->template->set('post_value', $post_value);

			$this->template->set('css_type_photo', $this->options['display_dropshadows']);

			$output = $this->template->fetch();

			$this->_set_cached_data("show_photo-$in_album-$tags-$in_photo-$page", $output);

		}

		return $output;
	}

	/* Shows all the tag cloud */
	function show_tags() {

		$this->logger->info("show_tags()");

		$output = $this->_get_cached_data('show_tags');

		if (!isset ($output)) {
			
			$this->template->reset('show_tags');
			
			$this->template->set('page_title', $this->get_page_title());

			$resp = $this->_call_flickr_php('flickr.tags.getListUserPopular', array ('count' => '500', user_id => $this->options['nsid']));

			if (!isset ($resp)) {
				return;
			}

			$this->template->reset('tags');

			$this->template->set('home_url', $this->create_url());
			$this->template->set('home_label', fa__(Photos));
			$this->template->set('tags_label', fa__('Tags'));

			$result = $resp['who']['tags']['tag'];
			$countResult = sizeof($result);

			$tagcount = 0;
			$maxcount = 0;
			for ($i = 0; $i < $countResult; $i ++) {
				$tagcount = $result[$i]['count'];
				if ($tagcount > $maxcount) {
					$maxcount = $tagcount;
				}
			}

			$tags_list = array ();

			for ($i = 0; $i < $countResult; $i ++) {

				$tagcount = $result[$i]['count'];
				$tag = $result[$i]['_content'];

				if ($tagcount <= ($maxcount * .1)) {
					$tagclass = 'falbum-tag1';
				}
				elseif ($tagcount <= ($maxcount * .2)) {
					$tagclass = 'falbum-tag2';
				}
				elseif ($tagcount <= ($maxcount * .3)) {
					$tagclass = 'falbum-tag3';
				}
				elseif ($tagcount <= ($maxcount * .5)) {
					$tagclass = 'falbum-tag4';
				}
				elseif ($tagcount <= ($maxcount * .7)) {
					$tagclass = 'falbum-tag5';
				}
				elseif ($tagcount <= ($maxcount * .8)) {
					$tagclass = 'falbum-tag6';
				} else {
					$tagclass = 'falbum-tag7';
				}

				$data['url'] = $this->create_url("tags/$tag");
				$data['class'] = $tagclass;
				$data['title'] = $tagcount." ".fa__('photos');
				$data['name'] = $tag;

				$tags_list[] = $data;
			}

			$this->template->set('tags', $tags_list);

			$remote_url = $this->options['url_falbum_dir']."/falbum-remote.php";
			$this->template->set('remote_url', $remote_url);

			$output = $this->template->fetch();

			$this->_set_cached_data('show_tags', $output);

		}
		return $output;
	}

	/* Return EXIF data for the selected photo */
	function show_exif($photo_id, $secret) {

		$this->logger->info("show_exif($photo_id, $secret)");

		$output = $this->_get_cached_data("get_exif-$photo_id-$secret");
		if (!isset ($output)) {

			$this->template->reset('exif');

			$exif_resp = $this->_call_flickr_php('flickr.photos.getExif', array ('photo_id' => $photo_id, 'secret' => $secret), FALBUM_CACHE_EXPIRE_LONG);
			if (!isset ($exif_resp)) {
				return;
			}

			$result = $exif_resp['photo']['exif'];
			$countResult = sizeof($result);

			$exif_list = array ();

			for ($i = 0; $i < $countResult; $i ++) {
				$label = $result[$i]['label'];

				if ($i % 2 == 0) {
					$data['class'] = 'even';
				} else {
					$data['class'] = 'odd';
				}

				$data['label'] = $label;

				$r1 = $result[$i]['clean'];
				if (count($r1) > 0) {
					$data['data'] = htmlentities($result[$i]['clean']['_content']);
				} else {
					$data['data'] = htmlentities($result[$i]['raw']['_content']);
				}

				$exif_list[] = $data;
			}

			$this->template->set('exif', $exif_list);

			$output = $this->template->fetch();

			$this->_set_cached_data("get_exif-$photo_id-$secret", $output);
		}

		return $output;
	}

	function update_metadata($photo_id, $title, $description) {

		$this->logger->info("update_metadata($photo_id, $title, $description)");

		if ($this->_can_edit()) {

			$resp = $this->_call_flickr_php('flickr.photos.setMeta', array ('photo_id' => $photo_id, 'title' => $title, 'description' => $description), FALBUM_DO_NOT_CACHE, true);
			if (!isset ($resp)) {
				return;
			}

			$this->_clear_cached_data();

			$resp = $this->_call_flickr_php('flickr.photos.getInfo', array ('photo_id' => $photo_id));

			if (!isset ($resp)) {
				return;
			}

			$data['title'] = $resp['photo']['title'];
			$data['description'] = nl2br($resp['photo']['description']['_content']);

		}

		return $data;
	}

	/* Function to show recent photos - commonly used in the sidebar */
	function show_recent($num = 5, $style = 0, $size = '') {

		$this->logger->info("show_recent($num, $style, $size)");

		if ($size == '') {
			$size = $this->options['tsize'];
		}

		$output = $this->_get_cached_data("show_recent-$num-$style-$size");

		if (!isset ($output)) {

			$output = '';

			$resp = $this->_call_flickr_php('flickr.photos.search', array ('user_id' => $this->options['nsid'], 'per_page' => $num, 'sort' => 'date-taken-desc'));
			if (!isset ($resp)) {
				return;
			}

			if ($style == 0) {
				$output .= "<ul class='falbum-recent'>\n";
			} else {
				$output .= "<div class='falbum-recent'>\n";
			}

			$result = $resp['photos']['photo'];
			$countResult = sizeof($result);

			for ($i = 0; $i < $countResult; $i ++) {
				$server = $result[$i]['server'];
				$farm = $result[$i]['farm'];
				$secret = $result[$i]['secret'];
				$photo_id = $result[$i]['id'];
				$photo_title = $result[$i]['title'];

				$photo_link = $photo_id;

				if ($style == 0) {
					$output .= "<li>\n";
				} else {
					$output .= "<div class='falbum-thumbnail".$this->options['display_dropshadows']."'>";
				}

				$thumbnail = $this->_create_flickr_image_url($farm, $server, $photo_id, $secret, $size); 
								
				$output .= "<a href='".$this->create_url("show/recent/photo/$photo_link/")."'>";

				$output .= "<img src='$thumbnail' alt=\"".htmlentities($photo_title)."\" title=\"".htmlentities($photo_title)."\" />";
				$output .= "</a>\n";

				if ($style == 0) {
					$output .= "</li>\n";
				} else {
					$output .= "</div>\n";
				}
			}
			if ($style == 0) {
				$output .= "</ul>\n";
			} else {
				$output .= "</div>\n";
			}

			$this->_set_cached_data("show_recent-$num-$style-$size", $output);
		}
		return $output;
	}

	/* Function to show a random selection of photos - commonly used in the sidebar */
	function show_random($num = 5, $tags = '', $style = 0, $size = '') {

		$this->logger->info("show_random($num, $tags, $style, $size)");

		if ($size == '') {
			$size = $this->options['tsize'];
		}

		$output = '';
		$page = 1;

		if ($tags == '') {
			$resp = $this->_call_flickr_php('flickr.photos.search', array ('user_id' => $this->options['nsid'], 'sort' => 'date-taken-desc', 'per_page' => $this->options['photos_per_page']));
		} else {
			$resp = $this->_call_flickr_php('flickr.photos.search', array ('user_id' => $this->options['nsid'], 'tags' => $tags, 'tag_mode' => 'all', 'per_page' => $this->options['photos_per_page'], 'page' => $page));
		}

		if (!isset ($resp)) {
			return;
		}

		$totalPages = $resp['photos']['pages'];
		$total = $resp['photos']['total'];

		$no_dups = ($total - $num >= 0);

		if ($style == 0) {
			$output .= "<ul class='falbum-random'>\n";
		} else {
			$output .= "<div class='falbum-random'>\n";
		}

		$rand_array = array ();

		for ($j = 0; $j < $num; $j ++) {
			$page = mt_rand(1, $totalPages);
			if ($tags == '') {
				$resp = $this->_call_flickr_php('flickr.photos.search', array ('user_id' => $this->options['nsid'], 'sort' => 'date-taken-desc', 'per_page' => $this->options['photos_per_page'], 'page' => $page));
			} else {
				$resp = $this->_call_flickr_php('flickr.photos.search', array ('user_id' => $this->options['nsid'], 'tags' => $tags, 'tag_mode' => 'all', 'per_page' => $this->options['photos_per_page'], 'page' => $page));
			}

			if (!isset ($resp)) {
				return;
			}
			$result = $resp['photos']['photo'];
			$countResult = count($result);

			$randPhoto = mt_rand(0, $countResult -1);

			$photo_id = $result[$randPhoto]['id'];

			$dup = false;
			if ($no_dups) {
				if (in_array($photo_id, $rand_array)) {
					$dup = true;
					$j --;
				} else {
					$rand_array[] = $photo_id;
				}
			}

			$this->logger->debug("dup->". ($dup ? 't' : 'f'));

			if (!$dup) {

				$server = $result[$randPhoto]['server'];
				$farm = $result[$randPhoto]['farm'];
				$secret = $result[$randPhoto]['secret'];
				$photo_title = $result[$randPhoto]['title'];

				$photo_link = $photo_id;

				if ($style == 0) {
					$output .= "<li>\n";
				} else {
					$output .= "<div class='falbum-thumbnail".$this->options['display_dropshadows']."'>";
				}

				$thumbnail = $this->_create_flickr_image_url($farm, $server, $photo_id, $secret, $size);  
				
				if ($tags != '') {
					$output .= "<a href='".$this->create_url("tags/$tags/page/$page/photo/$photo_link/")."'>";
				} else {
					$output .= "<a href='".$this->create_url("show/recent/page/$page/photo/$photo_link/")."'>";
				}

				$output .= "<img src='$thumbnail' alt=\"".htmlentities($photo_title)."\" title=\"".htmlentities($photo_title)."\" class='falbum-recent-thumbnail' />";
				$output .= "</a>\n";
				if ($style == 0) {
					$output .= "</li>\n";
				} else {
					$output .= "</div>\n";
				}
			}

		}
		if ($style == 0) {
			$output .= "</ul>\n";
		} else {
			$output .= "</div>\n";
		}

		return $output;
	}

	function show_album_tn($album, $size = 'm') {

		$this->logger->info("show_album_tn($album)");

		$output = $this->_get_cached_data("show_album_tn-$album");

		if (!isset ($output)) {

			$output = '';

			$resp = $this->_call_flickr_php('flickr.photosets.getList', array ("user_id" => $this->options['nsid']));
			if (!isset ($resp)) {
				return;
			}

			$photosets = $resp['photosets']['photoset'];
			$count = sizeof($photosets);

			for ($j = 0; $j < $count; $j ++) {
				if ($photosets[$j]['id'] == $album) {
					$result = $photosets[$j];
					break;
				}
			}

			//$result = $xpath->match("/rsp/photosets/photoset[@id=$album]");
			//$result = $result[0];

			$photos = $result['photos'];

			if ($photos > 0) {
				$id = $result['id'];
				$server = $result['server'];
				$farm = $result['farm'];
				$primary = $result['primary'];
				$secret = $result['secret'];
				$title = $this->_unhtmlentities($result['title']['_content']);
				$thumbnail = $this->_create_flickr_image_url($farm, $server, $primary, $secret, $size); 
				
				$url = $this->create_url("album/$album/");

				$output .= '	<div class=\'falbum-thumbnail'.$this->options['display_dropshadows'].'\'>';
				$output .= "		<a href='$url' title='$title'>";
				$output .= '			<img src="'.$thumbnail.'" alt="" />';
				$output .= '		</a>';
				$output .= '	</div>';
			}

			$this->_set_cached_data("show_album_tn-$album", $output);

		}

		return $output;
	}

	function show_single_photo($album, $tags, $photo, $page, $size, $linkto) {

		$this->logger->info("show_single_photo($album, $tags, $photo, $page, $size, $linkto)");

		$output = $this->_get_cached_data("show_single_photo-$album-$tags-$photo-$page-$size-$linkto");

		if (!isset ($output)) {

			$output = '';

			if ($size == 'sq') {
				$size = '_s';
			}
			elseif ($size == 't') {
				$size = '_t';
			}
			elseif ($size == 's') {
				$size = '_m';
			}
			elseif ($size == 'm') {
				$size = '';
			}
			elseif ($size == 'l') {
				$size = '_b';
			}
			elseif ($size == 'o') {
				$size = '_o';
			}

			// Display Photo
			$resp = $this->_call_flickr_php('flickr.photos.getInfo', array ('photo_id' => $photo));
			if (!isset ($resp)) {
				return;
			}

			$id = $resp['photo']['id'];
			$server = $resp['photo']['server'];
			$farm = $resp['photo']['farm'];
			$secret = $resp['photo']['secret'];
			$title = $this->_unhtmlentities($resp['photo']['title']);
			$thumbnail = $this->_create_flickr_image_url($farm, $server, $id, $secret, $size); 
			
			if ($tags != '') {
				$url_prefix = "tags/$tags";
			} else
				if ($album != '') {
					$url_prefix = "album/$album";
				} else {
					$url_prefix = 'show/recent';
				}

			if (isset ($page)) {
				$url_prefix .= '/page/'.$page;
			}

			if (!($linkto == 'i' || $linkto == 'index')) {
				$url_prefix .= '/photo/'.$photo;
			}

			$url = $this->create_url("$url_prefix");

			$output .= '	<div class=\'falbum-thumbnail'.$this->options['display_dropshadows'].'\'>';
			$output .= "		<a href='$url' title='$title'>";
			$output .= '			<img src="'.$thumbnail.'" alt="" />';
			$output .= '		</a>';
			$output .= '	</div>';

			$this->_set_cached_data("show_single_photo-$album-$tags-$photo-$page-$size-$linkto", $output);

		}

		return $output;

	}

	/* Creates the URLs used in Falbum */
	function create_url($parms = '') {
		if ($parms != '') {
			$element = explode('/', $parms);
			for ($x = 1; $x < count($element); $x ++) {
				$element[$x] = urlencode($element[$x]);
			}
			if (strtolower($this->options['friendly_urls']) == 'false') {
				$parms = '?'.$element[0].'='.$element[1].'&'.$element[2].'='.$element[3].'&'.$element[4].'='.$element[5].'&'.$element[6].'='.$element[7];
				$parms = str_replace('&=', '', $parms);
			} else {
				$parms = implode('/', $element);
			}

			if ($this->options['photos_per_page'] == 0) {
				$parms = preg_replace("`/page/[0-9]+`", "", $parms);
			}

		}
		return htmlspecialchars($this->options['url_root']."$parms");
	}

	function get_page_title($sep = '&raquo;') {

		$this->logger->info("get_page_title($sep)");

		$_GET = array_merge($_POST,$_GET);

		$album = $_GET['album'];
		$photo = $_GET['photo'];
		$page = $_GET['page'];
		$tags = $_GET['tags'];
		$show = $_GET['show'];

		$this->logger->info("get_page_title_v($album $photo $page $tags $show)");
		
		if (!is_null($album)) {
			list ($album_id, $album_title) = $this->_get_album_info($album);
			if (!is_null($photo)) {
				$resp = $this->_call_flickr_php('flickr.photosets.getPhotos', 
					array ('photoset_id' => $album_id));
			}
		} else {
			if ($show == 'tags') {
				$album_title = fa__('Tags');
			} else
				if ($show == 'recent') {
					$album_title = fa__('Recent Photos');
					if (!is_null($photo)) {
						$resp = $this->_call_flickr_php('flickr.photos.search', 
							array ('user_id' => $this->options['nsid'], 'sort' => 'date-taken-desc', 'per_page' => $this->options['photos_per_page'], 'page' => $page));
					}
				} else {
					//$album_title = fa__('Tags');
					$album_title = $tags;
					if (!is_null($photo)) {
						$resp = $this->_call_flickr_php('flickr.photos.search', 
							array ('user_id' => $this->options['nsid'], 'tags' => $tags, 'tag_mode' => 'all', 'per_page' => $this->options['photos_per_page'], 'page' => $page));
					}
				}
		}

		if (!is_null($photo)) {
			if (!isset ($resp)) {
				return;
			}
			$photo = $this->_get_photo_id($resp, $photo);
			//$this->logger->debug("photo-$photo");
			//$photo_title = $xpath->getData("//photo[@id='$photo']/@title");

			//$photos = $resp['photos']['photo'];
			
			if (!is_null($album)) {
				$photos = $resp['photoset']['photo'];
			} else {
				$photos = $resp['photos']['photo'];
			}
			
			$count = sizeof($photos);
			for ($j = 0; $j < $count; $j ++) {
				if ($photos[$j]['id'] == $photo) {
					$photo_title = $photos[$j]['title'];
					break;
				}
			}
		}

		$title = fa__('Photos');
		if (isset ($album_title)) {
			$title .= '&nbsp;'.$sep.'&nbsp;'.$album_title;
		}
		if (isset ($photo_title)) {
			$title .= '&nbsp;'.$sep.'&nbsp;'.$photo_title;
		}

		return $title;
	}

	function get_options() {

		$falbum_options = array ();

		include('falbum-config.php');

		//echo '<pre>'.print_r($falbum_options, true).'</pre>';

		return $falbum_options;
	}

	/* Function that actually makes the flickr calls */
	function _call_flickr_php($method, $args = array (), $cache_option = FALBUM_CACHE_EXPIRE_SHORT, $post = false) {

		$args = array_merge(array ('method' => $method, 'api_key' => FALBUM_API_KEY, 'format'	=> 'php_serial'), $args);

		if ($this->_show_private() == 'true' || $post == true) {
			$args = array_merge($args, array ('auth_token' => $this->options['token']));
		}

		ksort($args);

		$auth_sig = '';
		foreach ($args as $key => $data) {
			$auth_sig .= $key.$data;
		}

		$api_sig = '';
		if ($this->_show_private() == 'true' || $post == true) {
			$api_sig = md5(FALBUM_SECRET.$auth_sig);
		}

	    $args = array_merge($args, array ('api_sig' => $api_sig));
	    ksort($args);

		$url = 'http://www.flickr.com/services/rest/';
		if ($post) {
			$resp = $this->_fopen_url($url, $args, $cache_option, true);
		} else {
			$resp = $this->_get_cached_data($url.implode('-', $args), $cache_option);
			if (!isset ($resp)) {
				$resp = $this->_fopen_url($url, $args, $cache_option, false);

				// only cache successful calls to Flickr
				$pos = strrpos($resp, '"ok"');
				if ($pos !== false) {
					$this->_set_cached_data($url.implode('-', $args), $resp, $cache_option);
				}
			}
		}

		$resp_data = unserialize($resp);

		$this->logger->debug(print_r($resp_data, TRUE));

		return $resp_data;
	}

	/* Function that opens the URLS - uses libcurl by default, else falls back to fsockopen */
	function _fopen_url($url, $args = array (), $cache_option = FALBUM_CACHE_EXPIRE_SHORT, $post = false, $fsocket_timeout = 120) {

		$urlParts = parse_url($url);
		$host = $urlParts['host'];
		$port = (isset ($urlParts['port'])) ? $urlParts['port'] : 80;

		if (!extension_loaded('curl')) {
			/* Use fsockopen */
			$this->logger->debug('request - fsockopen<br />'.htmlentities($url));

			$errno = '';
			$errstr = '';

			if (!$fp = @ fsockopen($host, $port, $errno, $errstr, $fsocket_timeout)) {
				$data = fa__('fsockopen:Flickr server not responding');
			} else {

				$postdata = implode('&', array_map(create_function('$a', 'return $a[0] . \'=\' . urlencode($a[1]);'), $this->_flattenArray('', $args)));

				$this->logger->debug('request - fsockopen<br />'.htmlentities($url).'<br />'.$postdata);

				//if (isset ($postdata)) {
				$post = "POST $url HTTP/1.0\r\nHost: $host\r\nContent-type: application/x-www-form-urlencoded\r\nUser-Agent: Mozilla 4.0\r\nContent-length: ".strlen($postdata)."\r\nConnection: close\r\n\r\n$postdata";
				if (!fwrite($fp, $post)) {
					$data = fa__('fsockopen:Unable to send request');
				}
				//} else {
				//	if (!fputs($fp, "GET $url?$postdata	HTTP/1.0\r\nHost:$host\r\n\r\n")) {
				//		$data = fa__('fsockopen:Unable to send request');
				//	}
				//}

				$ndata = null;
				stream_set_timeout($fp, $fsocket_timeout);
				$status = socket_get_status($fp);
				while (!feof($fp) && !$status['timed_out']) {
					$ndata .= fgets($fp, 8192);
					$status = socket_get_status($fp);
				}
				fclose($fp);

				// strip headers
				$sData = split("\r\n\r\n", $ndata, 2);
				$ndata = $sData[1];
			}
		} else {
			/* Use curl */
			$this->logger->debug('request - curl<br />'.htmlentities($url));

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_PORT, $port);
			curl_setopt($ch, CURLOPT_TIMEOUT, $fsocket_timeout);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FAILONERROR, 1);
			curl_setopt($ch, CURLOPT_HEADER, false);

			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $args);

			$ndata = curl_exec($ch);
			$error = curl_error($ch);
			curl_close($ch);
		}

		$this->logger->debug('response - <br />'.htmlentities($ndata));

		return $ndata;
	}


	/* Function that builds the album pages */
	function _build_paging($page, $pages, $urlPrefix, $pos) {

		$sAlbHeader .= "<div class='falbum-navigationBar' id='pages-$pos'>".fa__('Page:')."&nbsp;";

		if ($page > 1 && $pages > 1) {
			$title = strtr(fa__('Go to previous page (#page#)'), array ("#page#" => $page -1));
			$sAlbHeader .= $this->_create_button('pageprev-', $this->create_url($urlPrefix. ($page -1)), fa__('Previous'), $title, 0, '_self', true, $pos);
		}

		for ($i = 1; $i <= $pages; $i ++) {
			// We display 1 ... 14 15 16 17 18 ... 29 when there are too many pages
			if ($pages > 10) {

				$mn = $page -3;
				$mx = $page +3;

				if ($i <= $mn) {
					if ($i == 2)
						$sAlbHeader .= "<span class='pagedots'>&nbsp;&hellip;&nbsp;</span>";
					if ($i != 1)
						continue;
				}
				if ($i >= $mx) {
					if ($i == $pages -1)
						$sAlbHeader .= "<span class='pagedots'>&nbsp;&hellip;&nbsp;</span>";
					if ($i != $pages)
						continue;
				}
			}
			$id = "page$i";
			if ($i == $page) {
				$id = 'curpage';
			}

			$sAlbHeader .= $this->_create_button($id, $this->create_url($urlPrefix.$i), $i, '', ($i ? 0 : 1), '_self', true, $pos);
		}
		if ($page < $pages) {
			$title = strtr(fa__('Go to next page (#page#)'), array ("#page#" => $page +1));
			$sAlbHeader .= $this->_create_button('pagenext', $this->create_url($urlPrefix. ($page +1)), fa__('Next'), $title, 1, '_self', true, $pos);
		}
		$sAlbHeader .= "</div>\n\n";

		return $sAlbHeader;
	}

	/* Build pretty navigation buttons */
	function _create_button($id, $href, $text, $title, $nSpacer, $target = '_self', $bCallCustom = true, $pos = '') {
		if (substr($id, 0, 1) == '#')
			return '';

		$class = 'buttonLink';
		if ($id == 'curpage') {
			$class = 'curPageLink';
		} else
			if (preg_match('/^page[0-9]+$/', $id)) {
				$class = 'otherPageLink';
			}

		$x = '';

		if ($nSpacer == 1)
			$space = '&nbsp;';
		if ($nSpacer == 2)
			$space = '&nbsp;&nbsp;&nbsp;';

		if (!empty ($space))
			$x .= "<span id='space_{$id}_{$pos}' class='buttonspace'>$space</span>";

		if (!empty ($href))
			$x .= "<a class='$class' href='$href' id='$id-$pos' title='$title'>$text</a>";
		else
			$x .= "<span class='disabledButtonLink' id='$id-$pos' >$text</span>";
		return $x;
	}

	/* Removes all HTML entities - commonly used for the descriptions */
	function _unhtmlentities($string) {
		// replace numeric entities
		$string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
		$string = preg_replace('~&#([0-9]+);~e', 'chr(\\1)', $string);
		// replace literal entities
		$trans_tbl = get_html_translation_table(HTML_ENTITIES);
		$trans_tbl = array_flip($trans_tbl);
		return strtr($string, $trans_tbl);
	}

	function _get_link_title($title, $id, & $title_array) {

		if ($this->options['friendly_urls'] == 'title') {

			$s_title = sanitize_title($title);

			if (preg_match("/^[A-Za-z0-9-_]+$/", $s_title)) {
				if (!in_array($s_title, $title_array)) {
					$title_array[$id] = $s_title;
					$link_title = $s_title;
				} else {
					$dup_count = 1;
					while (in_array($s_title.'-'.$dup_count, $title_array)) {
						$dup_count ++;
					}
					$link_title = $s_title.'-'.$dup_count;
					$title_array[$id] = $link_title;
				}
			} else {
				$link_title = $id;
			}

		} else {
			$link_title = $id;
		}
		return $link_title;

	}

	/* et photo id from title if using friendly URLs */
	function _get_photo_id(& $resp, $photo) {

		if ($this->options['friendly_urls'] == 'title') {

			if ($resp['photos']) {
				$result = $resp['photos']['photo'];
			} else {
				$result = $resp['photoset']['photo'];
			}

			$photo_title_array = array ();
			for ($i = 0; $i < count($result); $i ++) {
				$photo_title = sanitize_title($result[$i]['title']);
				if (preg_match("/^[A-Za-z0-9-_]+$/", $photo_title)) {
					$photo_id = $result[$i]['id'];
					if (!in_array($photo_title, $photo_title_array)) {
						$photo_title_array[$photo_id] = $photo_title;
					} else {
						$dup_count = 1;
						while (in_array($photo_title.'-'.$dup_count, $photo_title_array)) {
							$dup_count ++;
						}
						$photo_title = $photo_title.'-'.$dup_count;
						$photo_title_array[$photo_id] = $photo_title;
					}
				}
			}
			if (in_array($photo, $photo_title_array)) {
				$photo = array_search($photo, $photo_title_array);
			}
		}

		return $photo;
	}

	/* Get album ID from the album title */
	function _get_album_info($album) {

		$resp = $this->_call_flickr_php('flickr.photosets.getList', array ('user_id' => $this->options['nsid']));
		if (!isset ($resp)) {
			return;
		}

		if ($this->options['friendly_urls'] == 'title') {

			$album_id_array = array ();
			$photosets = $resp['photosets']['photoset'];
			for ($i = 0; $i < count($photosets); $i ++) {

				$album_title = $photosets[$i]['title']['_content'];

				$album_title = sanitize_title($album_title);

				if (preg_match("/^[A-Za-z0-9-_]+$/", $album_title)) {

					$album_id = $photosets[$i]['id'];
					if (!in_array($album_title, $album_id_array)) {
						$album_id_array[$album_id] = $album_title;
					} else {
						$count = 1;
						while (in_array($album_title.'-'.$count, $album_id_array)) {
							$count ++;
						}
						$album_id_array[$album_id] = $album_title.'-'.$count;
					}
				}
			}

			if (in_array($album, $album_id_array)) {
				$album_id = array_search($album, $album_id_array);
			} else {
				$album_id = $album;
			}

		} else {
			$album_id = $album;
		}

		//$album_title = $xpath->getData("//photoset[@id='$album_id']/title");

		$photosets = $resp['photosets']['photoset'];
		$count = sizeof($photosets);
		for ($j = 0; $j < $count; $j ++) {
			if ($photosets[$j]['id'] == $album_id) {
				$album_title = $photosets[$j]['title']['_content'];
				break;
			}
		}

		return array ($album_id, $album_title);
	}

	/* Outputs a true or false variable for showing private photos based on the registered user level */
	function _show_private() {
		$PrivateAlbumChoice = false;
		return $PrivateAlbumChoice;
	}

	/* Gets info from Cache Table */
	function _get_cached_data($key, $cache_option = FALBUM_CACHE_EXPIRE_SHORT) {

		require_once (FALBUM_PATH.'/lib/Lite.php');

		$options = array ("cacheDir" => FALBUM_PATH."/cache/", "lifeTime" => $cache_option);

		$Cache_Lite = new Cache_Lite($options);
		$data = $Cache_Lite->get($key);

		if ($data == '') {
			$data = null;
		}

	    $this->logger->debug('cache get - key - '.$key.'<br />'.'cache - '. (isset ($data) ? 'hit' : 'miss'));

		$data = null;

		return $data;
	}

	/* Function to store the data in the cache table */
	function _set_cached_data($key, $data, $cache_option = FALBUM_CACHE_EXPIRE_SHORT) {

		require_once (FALBUM_PATH.'/lib/Lite.php');

		$options = array ("cacheDir" => FALBUM_PATH."/cache/", "lifeTime" => $cache_option);

		$Cache_Lite = new Cache_Lite($options);

		$Cache_Lite->save($data, $key);

		$this->logger->debug('cache set - key - '.$key);

	}

	function _clear_cached_data() {

	}

	function _can_edit() {
		return false;
	}

	function _flattenArray($name, $values) {
		if (!is_array($values)) {
			return array (array ($name, $values));
		} else {
			$ret = array ();
			foreach ($values as $k => $v) {
				if (empty ($name)) {
					$newName = $k;
				}
				//elseif ($this->_useBrackets) {
				//	$newName = $name.'['.$k.']';
				//}
				else {
					$newName = $name;
				}
				$ret = array_merge($ret, $this->_flattenArray($newName, $v));
			}
			return $ret;
		}
	}


	function _error($message) {
		$this->has_error = true;

		$msg .= "<b>$message</b>\n\n";

		$msg .= "Backtrace:\n";
		$backtrace = debug_backtrace();

		foreach ($backtrace as $bt) {
			$args = '';
			if (is_array($bt['args'])) {
				foreach ($bt['args'] as $a) {
					if (!empty ($args)) {
						$args .= ', ';
					}
					switch (gettype($a)) {
						case 'integer' :
						case 'double' :
							$args .= $a;
							break;
						case 'string' :
							$a = htmlspecialchars(substr($a, 0, 64)). ((strlen($a) > 64) ? '...' : '');
							$args .= "\"$a\"";
							break;
						case 'array' :
							$args .= 'Array('.count($a).')';
							break;
						case 'object' :
							$args .= 'Object('.get_class($a).')';
							break;
						case 'resource' :
							$args .= 'Resource('.strstr($a, '#').')';
							break;
						case 'boolean' :
							$args .= $a ? 'True' : 'False';
							break;
						case 'NULL' :
							$args .= 'Null';
							break;
						default :
							$args .= 'Unknown';
					}
				}
			}

			$file_path = str_replace('\\', '/', $bt['file']);

			$file = substr($file_path, strrpos($file_path, '/') + 1);
			$line = $bt['line'];

			$args = '';

			$msg .= "  $file:{$line} - {$file_path}\n";
			$msg .= "     {$bt['class']}{$bt['type']}{$bt['function']}($args)\n";

		}

		$this->error_detail .= $msg."\n\n";
		$this->logger->err($msg);
	}

	function is_album_page() {
		return defined('FALBUM') && constant('FALBUM');
	}


	/**
	 * Construct Template object which will be used to
	 * generate output HTML.
	 *
	 * @param $_style The template style to use.
	 */
	function _construct_template($_style) {
		require_once(FALBUM_PATH.'/Template.class.php');
		$this->template = new Template($_style);
	}
	
	function _create_flickr_image_url($farm, $server, $photo_id, $secret, $size)  {
		$url = FALBUM_FLICKR_URL_IMAGE_1."{$farm}".FALBUM_FLICKR_URL_IMAGE_2."/{$server}/{$photo_id}_{$secret}_{$size}.jpg";
		return $url;
	}

}
