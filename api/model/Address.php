<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 8/28/18
 * Time: 11:08 AM
 */

namespace api\model;


class Address {
	public $desc;
	public $street1;
	public $street2;
	public $city;
	public $state;
	public $zip;

	public $id;
	public $resourceId;

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

	public function toArray() {
		return ['description' => $this->desc, 'street1' => $this->street1, 'street2' => $this->street2,
			'city' => $this->city, 'state' => $this->state, 'zip' => $this->zip, 'id' => $this->id,
			'resourceId' => $this->resourceId];
	}

	public static function addressFromArr($arr, $resourceID) {
		$id = -1;

		if (isset($arr['id']) && !empty($arr['id'])) {
			$id = intval($arr['id']);
		}

		return new Address($arr['description'], $arr['street1'], $arr['street2'], $arr['city'], $arr['state'],
			$arr['zip'], $id, $resourceID);
	}

	public static function addressesFromArr($arr, $resourceId) {
		$addresses = [];
		foreach($arr as $addr) {
			array_push($addresses, self::addressFromArr($addr, $resourceId));
		}

		return $addresses;
	}
}