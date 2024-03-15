<?php
/****************************************************************************************************
 Utility Helpers
****************************************************************************************************/

if (!function_exists('get_document_root')) 
{
/**
 * Returns base document root of site
 * 
 * @return string
 */
  function get_document_root()
  {
    $absolute_dir = dirname(__FILE__);
    $relative_dir = drupal_get_path('module', 'jarvis_vision').'/helpers';

    return substr($absolute_dir, 0, -1 * (1 + strlen($relative_dir)));
  }
}


// Ensure that only admins can access the Ajax URL
if (!function_exists('only_admin')) {
	function only_admin() {
		global $user;

		return ($user->uid > 0);
	}
}


// Optional Link
if (!function_exists('ol')) {
	function ol($el, $link, $options = array()) {
		if (is_array($link) && $link['url']) {
			if (@$link['attributes']['class'] && !is_array($link['attributes']['class']))
				$link['attributes']['class'] = array($link['attributes']['class']);

			return l($el, $link["url"], array_merge_recursive($options, array('html' => TRUE, 'attributes' => $link['attributes'])));
		} elseif (!is_array($link) && !empty($link)) {
			return l($el, $link, array('html' => TRUE));
		} else {
			return $el;
		}
	}
}

// Extracts a particular field from objects in an array
if (!function_exists('extract_field')) {
	function extract_field($arr, $field='nid') {
		$response = array();
		foreach ($arr as $a)
			$response[] = @$a->$field;

		return $response;
	}
}


// Merge objects to an array
if (!function_exists('object_array_merge')) {
	function object_array_merge($arr, $arr2) {
		$merged = array();
		foreach ($arr as $k=>$v)
			$merged[] = (object) array_merge((array)$arr[$k], (array) $arr2[$k]);

		return $merged;
	}
}