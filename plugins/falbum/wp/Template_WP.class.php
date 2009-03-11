<?php

/*
Copyright (c) 2007
Released under the GPL license
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

require_once (dirname(__FILE__).'/../Template.class.php');

class Template_WP extends Template {

	/**
	 * Constructor
	 */
	function Template_WP() {
		// set to default falbum template
		parent :: Template('default');
	
  	// overwrite with wordpress one if available
		$wp_dir = get_template_directory()."/falbum/";		
		if (file_exists($wp_dir)) {
        	$this->templatePath = $wp_dir;
    }
	
  }
	
}
?>
