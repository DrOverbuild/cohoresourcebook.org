<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 8/28/18
 * Time: 11:08 AM
 */

namespace api\model;


class Resource {
	public $id;
	public $name;
	public $tags;


	/** @var int[] */
	public $categories;

	public $counties;

	/** @var Address[] */
	public $locations;

	/** @var Contact[] */
	public $contacts;

	public $description;
	public $services;
	public $documentation;
	public $hours;

	/**
	 * Resource constructor.
	 * @param $id
	 * @param $name
	 * @param $tags
	 * @param int[] $categories
	 * @param Address[] $locations
	 * @param Contact[] $contacts
	 * @param $description
	 * @param $services
	 * @param $documentation
	 * @param $hours
	 */
	public function __construct($id, $name, $tags, array $categories, array $counties, array $locations, array $contacts, $description, $services, $documentation, $hours) {
		$this->id = $id;
		$this->name = $name;
		$this->tags = $tags;
		$this->categories = $categories;
		$this->counties = $counties;
		$this->locations = $locations;
		$this->contacts = $contacts;
		$this->description = $description;
		$this->services = $services;
		$this->documentation = $documentation;
		$this->hours = $hours;
	}

	static public function resourceFromArr(array $arr) {
		$id = intval($arr['id']);
		return new Resource($id, $arr['name'], $arr['tags'], $arr['categories'], $arr['counties'],
			Address::addressesFromArr($arr['locations'], $id), Contact::contactsFromArr($arr['contacts'], $id),
			$arr['description'], $arr['services'], $arr['documentation'], $arr['hours']);
	}

	static public function resourcesFromArr(array $arr) {
		$resources = [];
		foreach($arr as $cat) {
			array_push($resources, self::resourceFromArr($cat));
		}

		return $resources;
	}

	public function toArray() {
		$array = [];

		$array['id'] = $this->id;
		$array['name'] = $this->name;
		$array['tags'] = $this->tags;
		$array['categories'] = $this->categories;
		$array['counties'] = $this->counties;
		$array['locations'] = $this->locationsArray();
		$array['contact'] = $this->contactsArray();
		$array['description'] = $this->description;
		$array['services'] = $this->services;
		$array['documentation'] = $this->documentation;
		$array['hours'] = $this->hours;

		return $array;
	}

	public function locationsArray(){
		$array = [];

		foreach ($this->locations as $loc) {
			array_push($array, $loc->toArray());
		}

		return $array;
	}

	public function contactsArray(){
		$array = [];

		foreach ($this->contacts as $contact) {
			array_push($array, $contact->toArray());
		}

		return $array;
	}
}