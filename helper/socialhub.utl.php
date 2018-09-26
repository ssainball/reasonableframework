<?php
/**
 * @file socialhub.utl.php
 * @date 2018-09-27
 * @author Go Namhyeon <gnh1201@gmail.com>
 * @brief SocialHub Utilities (refactoring from SocioRouter Utilities)
 */
 
if(!function_exists("socialhub_send_message")) {
	function socialhub_send_message($provider, $adapter, $message, $options=array()) {
		$response = false;
		$status = array(
			"message" => $message
		);

		switch($provider) {
			case "facebook":
				$status['link'] = get_value_in_array("link", $options, "");
				$status['picture'] = get_value_in_array("picture", $options, "");
				$response = $adapter->setUserStatus($status);
				break;

			case "linkedin":
				$status['content'] => array(
					"title" => get_value_in_array("title", $options, "");
					"description" => get_value_in_array("description", $options, "");
					"submitted-url" => get_value_in_array("link", $options, "");
					"submitted-image-url" => get_value_in_array("picture", $options, "");
				);
				$status['visibility'] => array(
					"code" => "anyone",
				);
				$response = $adapter->setUserStatus($status);
				break;

			case "twitter":
				$status['link'] = get_value_in_array("link", $options, "");
				$status['picture'] = get_value_in_array("picture", $options, "");
				$response = $adapter->setUserStatus($status);
				break;

			default:
				set_error("Unknown provider");
				show_errors();
		}

		return $response;
	}
}
 
if(!function_exists("socialhub_parse_object_id")) {
	function socialhub_parse_object_id($provider, $response) {
		$object_id = false;

		switch($provider) {
			case "facebook":
				$decodedBody = get_property_value("decodedBody", $response, true);
				$object_id = $decodedBody['id'];
				break;
			case "linkedin":
				$object_id = get_property_value("updateKey", $response);
				break;
			case "twitter":
				$object_id = get_property_value("id_str", $response);
				break;
		}

		return $object_id;
	}
}

if(!function_exists("socialhub_get_object")) {
	function socialhub_get_object($provider, $adapter, $access_token, $type="post") {
		$result = false;
		
		switch($provider) {
			case "facebook":
				$result = socialhub_get_object_facebook($provider, $adapter, $access_token, $type);
				break;
		}
		
		return $result;
	}
}

if(!function_exists("socialhub_get_object_facebook")) {
	function socialhub_get_object_facebook($object_id, $adapter, $access_token, $type="post") {
		$result = false;
		$response = false;

		try {
			switch($object_type) {
				case "post":
					$response = $adapter->api()->get("/" . $object_id, $access_token);
					break;
				case "likes":
					$response = $adapter->api()->get("/" . $object_id . "/likes", $access_token);
					break;
				case "comments":
					$response = $adapter->api()->get("/" . $object_id . "/comments", $access_token);
					break;
				case "sharedposts":
					$response = $adapter->api()->get("/" . $object_id . "/sharedposts", $access_token);
					break;
				case "reactions":
					$response = $adapter->api()->get("/" . $object_id . "/reactions", $access_token);
					break;
			}
		} catch(Exception $e) {
			set_error($e->getMessage());
			show_errors();
		}

		// get response body
		$body = get_property_value("body", $response, true);
		$decoded_body = json_decode($body);
		$result = $decoded_body;

		return $result;
	}
}
