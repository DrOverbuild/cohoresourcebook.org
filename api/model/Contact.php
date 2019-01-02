<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 8/28/18
 * Time: 11:08 AM
 */

namespace api\model;


class Contact {
	public $type;
	public $name;
	public $value;

	public $id;
	public $resourceId;

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

		return new Contact(intval($arr['type']), $arr['name'], $arr['value'], $id, $resourceID);
	}

	public static function contactsFromArr($arr, $resourceId) {
		$contacts = [];
		foreach($arr as $cont) {
			array_push($contacts, self::contFromArr($cont, $resourceId));
		}

		return $contacts;
	}

	public function toArray() {
		return ["type" => $this->type, "name" => $this->name, "value" => $this->value, "id" => $this->id,
			"resourceid" => $this->resourceId];
	}
}