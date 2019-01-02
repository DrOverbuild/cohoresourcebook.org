<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 8/21/18
 * Time: 9:44 AM
 */

class ContModel {
	var $type;
	var $name;
	var $value;

	var $id;
	var $resourceId;

	/**
	 * ContModel constructor.
	 * @param $type
	 * @param $name
	 * @param $value
	 * @param $id
	 * @param $resourceId
	 */
	public function __construct($type, $name, $value, $id, $resourceId) {
		$this->type = $type;
		$this->name = $name;
		$this->value = $value;
		$this->id = $id;
		$this->resourceId = $resourceId;
	}


	public static function contFromArr($arr, $resourceID) {
		$id = -1;

		if (isset($arr['id']) && !empty($arr['id'])) {
			$id = intval($arr['id']);
		}

		return new contModel(intval($arr['type']), $arr['name'], $arr['value'], $id, $resourceID);
	}

	public static function contactsFromArr($arr, $resourceId) {
		$contacts = [];
		foreach($arr as $cont) {
			array_push($contacts, self::contFromArr($cont, $resourceId));
		}

		return $contacts;
	}
}