<?php
class Template {
	var $vars; // Holds all the template variables
	var $templatePath;
	var $file;

	/**
	 * Constructor
	 *
	 * @param $file string the file name you want to load
	 */
	function Template($style = null) {	
	  	$this->templatePath = dirname(__FILE__).'/styles/'.$style.'/';
	}


	/**
	 * Set a template variable.
	 */
	function set($name, $value) {
		$this->vars[$name] = is_object($value) ? $value->fetch() : $value;
	}

	/**
	 * Reset all template variables.
	 * 
	 * @param $_file The template file to use if fetch() is called with a null 
	 * argument.
	 */	 	 	 	 	
	function reset($_file) {
		$this->file = $_file;
		$this->vars = null;
	}

	/**
	 * Open, parse, and return the template file.
	 *
	 * @param $file string the template file name
	 */
	function fetch($file = null) {
	
		if (!$file) {
			$file = $this->file;
		}

		$file = $file.'.tpl.php';	

		extract($this->vars); // Extract the vars to local namespace
		ob_start(); // Start output buffering
		include ($this->templatePath.$file); // Include the file
		$contents = ob_get_contents(); // Get the contents of the buffer
		ob_end_clean(); // End buffering and discard
		return $contents; // Return the contents
	}

	//
	function has_next(& $array) {
		$A_work = $array; //$A_work is a copy of $array but with its internal pointer set to the first element.
		$PTR = current($array);
		$this->_array_set_pointer($A_work, $PTR);

		if (is_array($A_work)) {
			if (next($A_work) === false)
				return false;
			else
				return true;
		} else {
			return false;
		}
	}

	function _array_set_pointer(& $array, $value) {
		reset($array);
		while ($val = current($array)) {
			if ($val == $value)
				break;
			next($array);
		}
	}
}
?>
