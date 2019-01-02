<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 8/28/18
 * Time: 11:08 AM
 */

namespace api\model;


class County {
	public $name;
	public $description;

	public $id;

	/** @var Resource[] */
	public $resources = array();

	/**
	 * County constructor.
	 * @param $name
	 * @param $description
	 * @param $id
	 */
	public function __construct($name, $description, $id) {
		$this->name = $name;
		$this->description = $description;
		$this->id = $id;
	}

	public function toArray($withResources = true) {
		$resourceArr = [];

		if ($withResources) {
			/** @var Resource $resource */
			foreach ($this->resources as $resource) {
				array_push($resourceArr, $resource->toArray());
			}
		}

		return ["name" => $this->name, "description" => $this->description, "id" => $this->id, "resources" => $resourceArr];
	}

	public static function countyFromArray($arr) {
		$cat = new County($arr['name'], $arr['description'], intval($arr['id']));

		if (isset($arr['resources'])) {
			$cat->resources = Resource::resourcesFromArr($arr['resources']);
		}

		return $cat;
	}

	public static function countiesFromArray($arr) {
		$counties = [];
		foreach ($arr as $cat) {
			array_push($counties, self::categoryFromArray($cat));
		}

		return $counties;
	}
}