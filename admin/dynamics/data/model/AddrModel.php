<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 8/21/18
 * Time: 9:44 AM
 */

class AddrModel {
	var $desc;
	var $street1;
	var $street2;
	var $city;
	var $state;
	var $zip;

	var $id;
	var $resourceId;

	/**
	 * AddrModel constructor.
	 * @param $desc
	 * @param $street1
	 * @param $street2
	 * @param $city
	 * @param $state
	 * @param $zip
	 * @param $id
	 * @param $resourceId
	 */
	public function __construct($desc, $street1, $street2, $city, $state, $zip, $id, $resourceId) {
		$this->desc = $desc;
		$this->street1 = $street1;
		$this->street2 = $street2;
		$this->city = $city;
		$this->state = $state;
		$this->zip = $zip;
		$this->id = $id;
		$this->resourceId = $resourceId;
	}

	public static function addrFromArr($arr, $resourceID) {
		$id = -1;

		if (isset($arr['id']) && !empty($arr['id'])) {
			$id = intval($arr['id']);
		}

		return new AddrModel($arr['description'], $arr['street1'], $arr['street2'], $arr['city'], $arr['state'],
			$arr['zip'], $id, $resourceID);
	}

	public static function addressesFromArr($arr, $resourceId) {
		$addresses = [];
		foreach($arr as $addr) {
			array_push($addresses, self::addrFromArr($addr, $resourceId));
		}

		return $addresses;
	}
}