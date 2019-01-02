<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 8/28/18
 * Time: 11:08 AM
 */

namespace api\model;


class Category {
	public $name;
	public $description;
	public $icon;

	public $id;

	/** @var Resource[] */
	public $resources = array();

	/**
	 * Category constructor.
	 * @param $name
	 * @param $description
	 * @param $id
     * @param $icon
	 */
	public function __construct($name, $description, $id, $icon) {
		$this->name = $name;
		$this->description = $description;
		$this->id = $id;
		$this->icon = $icon;
	}

	public function toArray($withResources = true) {
		$resourceArr = [];

		if ($withResources) {
			/** @var Resource $resource */
			foreach ($this->resources as $resource) {
				array_push($resourceArr, $resource->toArray());
			}
		}

		return ["name" => $this->name, "description" => $this->description, "icon" => $this->icon, "id" => $this->id, "resources" => $resourceArr];
	}

	public static function categoryFromArray($arr) {
		$cat = new Category($arr['name'], $arr['description'], intval($arr['id']), $arr['icon']);

		if (isset($arr['resources'])) {
			$cat->resources = Resource::resourcesFromArr($arr['resources']);
		}

		return $cat;
	}

	public static function categoriesFromArray($arr) {
		$categories = [];
		foreach ($arr as $cat) {
			array_push($categories, self::categoryFromArray($cat));
		}

		return $categories;
	}
}