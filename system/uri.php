<?php
/**
 * @file uri.php
 * @date 2018-04-13
 * @author Go Namhyeon <gnh1201@gmail.com>
 * @brief URI module
 */

if(!function_exists("base_url")) {
	function base_url() {
		return get_config_value("base_url");
	}
}

if(!function_exists("base_api_url")) {
	function base_api_url() {
		return get_config_value("base_api_url");
	}
}

if(!function_exists("get_uri")) {
	function get_uri() {
		$requests = get_requests();

		$request_uri = "";
		if(!array_key_empty("REQUEST_URI", $_SERVER)) {
			$request_uri = $requests["_URI"];
		}

		return $request_uri;
	}
}

if(!function_exists("read_requests")) {
	function read_requests() {
		$requests = array(
			"_ALL"   => $_REQUEST,
			"_POST"  => $_POST,
			"_GET"   => $_GET,
			"_URI"   => !array_key_empty("REQUEST_URI", $_SERVER) ? $_SERVER["REQUEST_URI"] : '',
			"_FILES" => is_array($_FILES) ? $_FILES : array(),
		);

		// with security module
		if(function_exists("get_clean_xss")) {
			foreach($requests['_GET'] as $k=>$v) {
				if(is_string($v)) {
					$requests['_GET'][$k] = get_clean_xss($v);
				}
			}
		}

		// set alias
		$requests['all'] = $requests['_ALL'];
		$requests['post'] = $requests['_POST'];
		$requests['get'] = $requests['_GET'];
		$requests['uri'] = $requests['_URI'];
		$requests['files'] = $requests['_FILES'];

		return $requests;
	}
}

if(!function_exists("get_requests")) {
	function get_requests() {
		$requests = get_scope("requests");
		
		if(!is_array($requests)) {
			set_scope("requests", read_requests());
		}

		return get_scope("requests");
	}
}

if(!function_exists("redirect_uri")) {
	function redirect_uri($uri, $permanent=false) {
		header("Location: " . $uri, true, $permanent ? 301 : 302);
		exit();
	}
}

if(!function_exists("read_requests")) {
	function read_requests() {
		$requests = array(
			"_ALL"  => $_REQUEST,
			"_POST" => $_POST,
			"_GET"  => $_GET,
			"_URI"  => !array_key_empty("REQUEST_URI", $_SERVER) ? $_SERVER["REQUEST_URI"] : '',
			"_FILES" => is_array($_FILES) ? $_FILES : array(),
		);

		// with security module
		if(function_exists("get_clean_xss")) {
			foreach($requests['_GET'] as $k=>$v) {
				if(is_string($v)) {
					$requests['_GET'][$k] = get_clean_xss($v);
				}
			}
		}

		// set alias
		$requests['all'] = $requests['_ALL'];
		$requests['post'] = $requests['_POST'];
		$requests['get'] = $requests['_GET'];
		$requests['uri'] = $requests['_URI'];
		$requests['files'] = $requests['_FILES'];

		return $requests;
	}
}

if(!function_exists("get_array")) {
	function get_array($arr) {
		return is_array($arr) ? $arr : array();
	}
}

// set scope
set_scope("requests", read_requests());
