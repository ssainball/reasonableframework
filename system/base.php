<?php
// view render
if(!function_exists('renderView')) {
	function renderView($name, $data=array()) {
		if(count($data) > 0) {
			extract($data);
		}

		// load view file
		$viewfile = './view/' . $name . '.php';
		if(file_exists($viewfile)) {
			include($viewfile);
		}
	}
}

// load system module
if(!function_exists('loadModule')) {
	function loadModule($name) {
		// load system file
		$modules = explode(';', $name);
		foreach($modules as $name2) {
			$systemfile = './system/' . $name2 . '.php';
			if(file_exists($systemfile)) {
				include($systemfile); 
			}
		}
	}
}

// load helper
if(!function_exists('loadHelper')) {
	function loadHelper($name) {
		// load helper file
		$helpers = explode(';', $name);
		foreach($helpers as $name2) {
			$helperfile = './helper/' . $name2 . '.php';
			if(file_exists($helperfile)) {
				include($helperfile); 
			}
		}
	}
}

// re-route
if(!function_exists('reRoute')) {
	function reRoute($name, $data=array()) {
		if(count($data) > 0) {
			extract($data);
		}
		
		// load route file
		$routefile = './route/' . $name . '.php';

		if(file_exists($routefile)) {
			include($routefile); 
		}
	}
}

if(!function_exists('array_key_empty')) {
	function array_key_empty($key, $array) {
		$empty = true;
		
		if(is_array($array)) {
			if(array_key_exists($key, $array)) {
				if(!empty($array[$key])) {
					$empty = false;
				}
			}
		}
		
		return $empty;
	}
}

if(!function_exists('array_multikey_empty')) {
	function array_multikey_empty($keys, $array) {
		$empty = true;
		foreach($keys as $key) {
			$empty = ($empty && array_key_empty($key, $array));
		}
		return $empty;
	}
}

if(!function_exists("get_value_in_array")) {
	function get_value_in_array($name, $arr=array(), $default=0) {
		$output = 0;

		if(is_array($arr)) {
			$output = array_key_empty($name, $arr) ? $default : $arr[$name];
		} else {
			$output = $default;
		}

		return $output;
	}
}

if(!function_exists("cut_str")) {
	function cut_str($str, $start, $len=0) {
		$cutted_str = "";
		if(function_exists("iconv_substr")) {
			$cutted_str = iconv_substr($str, $start, $len, "utf-8");
		} elseif(function_exists("mb_substr")) {
			$cutted_str = mb_substr($str, $start, $len);
		} else {
			$cutted_str = substr($start, $len);
		}
		
		return $cutted_str;
	}
}

if(!function_exists("read_file_by_line")) {
	function read_file_by_line($filename) {
		$lines = array();
		$handle = fopen($filename, "r");
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
				$lines[] .= $line;
			}

			fclose($handle);
		}
		
		return $lines;
	}
}

if(!function_exists("nl2p")) {
	function nl2p($string) {
		$paragraphs = '';
		foreach (explode("\n", $string) as $line) {
			if (trim($line)) {
				$paragraphs .= '<p>' . $line . '</p>';
			}
		}
		return $paragraphs;
	}
}
