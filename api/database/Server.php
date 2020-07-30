<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 9/3/18
 * Time: 4:44 PM
 */

namespace api\database;


class Server {
	/**
	 * Returns the value of the element in an array of the given key if it exists and is not null. Otherwise, returns
	 * value of default. By default, default is an empty string.
	 *
	 * @param $array
	 * @param $key
	 * @param string $default
	 * @return string
	 */
	public function defaultIfNull($array, $key, $default = '') {
		if (isset($array[$key]) && $array[$key]) {
			return $array[$key];
		} else {
			return $default;
		}
	}

	/**
	 * Returns an array of integers originally stored in a string separated by commas.
	 * @param $str
	 * @return array
	 */
	function parseIDs($str) {
		$ids = array();
		foreach (explode(',',$str) as $idStr) {
			if (!empty($idStr)) {
				array_push($ids, intval($idStr));
			}
		}

		return $ids;
	}

	/**
	 * Returns a string containing a list of integers from the given array, separated by commas. There are commas on the
	 * beginning and end of the string as well to help with MySQL searching.
	 * @param $idArr
	 * @return string
	 */
	function encodeIDs($idArr) {
		$idStr = ','.implode(',',$idArr).',';
		return $idStr;
	}
}