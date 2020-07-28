<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 8/28/18
 * Time: 11:05 AM
 */

namespace api\database;


use api\model\Address;
use api\model\Contact;
use api\model\Resource;

class ResourceServer extends Server {
	/** @var Connection */
	private $conn;

	/** @var CategoryServer If a CategoryServer object is calling functions from this class, it is better to use that
	 instance instead of start a new one to load resource categories*/
	private $catServer = null;

	private $countyServer = null;

	/**
	 * ResourceServer constructor.
	 * @param $conn
	 * @param $catServer CategoryServer If a CategoryServer object is already initialized, go ahead and use that instead.
	 */
	public function __construct($conn, $catServer = null, $countyServer = null) {
		$this->conn = $conn;
		$this->catServer = $catServer;
		$this->$countyServer = $countyServer;
	}

	/**
	 * Loads all resources with our without pagination. If $enablePagination is false, or $page is less than 0, all
	 * resources are included and both parameters are disregarded. If $catId is greater than or equal to 0, only
	 * resources containing given category ID will be listed.
	 *
	 * @param bool $enablePagination If false, all resources are listed
	 * @param int $page The page number. Default 0. 50 resources are loaded per page.
	 * @param int $catId Category ID to list only resources in specified category
	 * @return Resource[]
	 */
	public function listAllResources($enablePagination = true, int $page = 0, int $catId = -1, int $countyId = -1) {
		$stmt = null;

		$catQuery = '';
		$pagination = '';

		if($catId > -1) {
			$catQuery = "WHERE `categories` LIKE '%," . $catId . ",%'";
		} else if ($countyId > -1) {
			$catQuery = "WHERE `counties` LIKE '%," . $countyId . ",%'";
		}

		if ($enablePagination && $page > 0) {
			$startIndex = $page * $this->conn->pageLength;
			$pagination = " LIMIT " . $startIndex . "," . $this->conn->pageLength;

		}
//		$stmt->execute();
//		$result = $stmt->get_result();

		$query = "SELECT * FROM `resources` " . $catQuery . " ORDER BY `name` ASC ".$pagination;

		$result = $this->conn->conn->query($query);

		$resources = array();

		while ($row = $result->fetch_assoc()) {

			$resource = $this->resourceFromRow($row);
			if ($resource) {
				array_push($resources, $resource);
			}
		}

		return $resources;
	}

	public function resourceFromRow($row) {
		if (isset($row['id'])) {

			$id = intval($row['id']);
			$name = $this->defaultIfNull($row, 'name');
			$categoriesStr = $this->defaultIfNull($row, 'categories');
			$countiesStr = $this->defaultIfNull($row, 'counties');
			$tags = $this->defaultIfNull($row, 'tags');
			$description = $this->defaultIfNull($row, 'description');
			$services = $this->defaultIfNull($row, 'services');
			$documentation = $this->defaultIfNull($row, 'documentation');
			$hours = $this->defaultIfNull($row, 'hours');

			$categories = array();
			$counties = array();
			$locations = $this->allLocationsFromResource($id);
			$contacts = $this->allContactsFromResource($id);

			if (!$this->catServer) {
				$this->catServer = new CategoryServer($this->conn);
			}

			if (!$this->countyServer) {
				$this->countyServer = new CountyServer($this->conn);
			}

			foreach ($this->parseIDs($categoriesStr) as $catId) {
				array_push($categories, $catId);
			}

			foreach ($this->parseIDs($countiesStr) as $countyID) {
				array_push($counties, $countyID);
			}

			return new Resource($id, $name, $tags, $categories, $counties, $locations, $contacts,
				$description, $services, $documentation, $hours);
		}

		return false;
	}

	public function allLocationsFromResource($id) {
		$stmt = $this->conn->prepare('SELECT * FROM `addresses` WHERE `resourceid` = ?');
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$result = $stmt->get_result();

		$locations = array();

		while ($row = $result->fetch_assoc()) {
			$loc = Address::addressFromArr($row, $id);
			array_push($locations, $loc);
		}

		return $locations;
	}

	public function allContactsFromResource(int $id) {
		$stmt = $this->conn->prepare('SELECT * FROM `contact` WHERE `resourceid` = ?');
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$result = $stmt->get_result();

		$contacts = array();

		while ($row = $result->fetch_assoc()) {
			$cont = Contact::contFromArr($row, $id);
			array_push($contacts, $cont);
		}

		return $contacts;
	}

	public function allResourcesFromCategory(int $id){
		return $this->listAllResources(false, -1, $id);
	}

	public function allResourcesFromCounty(int $id){
		return $this->listAllResources(false, -1, -1, $id);
	}

	public function resourceFromId($id) {
		$stmt = $this->conn->prepare("SELECT * FROM `resources` WHERE `id` = ?");
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$result = $stmt->get_result();

		while ($row = $result->fetch_assoc()) {
			return  $this->resourceFromRow($row);
		}

		return false;
	}
}