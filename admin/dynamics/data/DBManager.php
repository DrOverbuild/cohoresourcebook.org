<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 7/6/18
 * Time: 11:59 PM
 */

require_once($_SERVER['DOCUMENT_ROOT']. '/vendor/autoload.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/table/CategoryTableElement.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/table/ResourceTableElement.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/table/CountyTableElement.php');

class DBManager {
	var $pageLength = 25;

	// categories
	var $categories = array();
	var $categoryPages;

	var $counties = array();
	var $countyPages;

	// resources
	var $resources = array();
	var $resourcePages;
	// users
	var $users = array();

	/** @var Kreait\Firebase\Factory */
	var $factory;

	/** @var Kreait\Firebase\Database */
	var $database;

	public function __construct() {
		$this->startDbConn();
	}

	function startDbConn() {
		$this->factory = (new Kreait\Firebase\Factory)->withServiceAccount($_SERVER['DOCUMENT_ROOT'] . '/cohodatabase-firebase-adminsdk-w3ovy-3caa0052fb.json');

		if (!is_null($this->factory)) {
			$this->database = $this->factory->createDatabase();


			if (!is_null($this->database)) {
				return 'SUCCESS';
			}
		}
		return 'FAILED';

	}

	function loadCategories($search = '', $page = 0) {
		// run query to load categories
		if (is_null($this->database)) {
			return;
		}

		$categoriesSnap = $this->database->getReference('categories')->getSnapshot();
		$categoriesJSON = $categoriesSnap->getValue();

		foreach ($categoriesJSON as $categoryJSON) {
			$name = $categoryJSON['name'];
			$id = $categoryJSON['id'];
			array_push($this->categories, new CategoryTableElement($name, $id));
		}

//		$stmt = $this->conn->prepare('SELECT `name`, `id` FROM `categories` ORDER BY `id` DESC LIMIT ?,?');
//		$startIndex = $page * $this->pageLength;
//		$stmt->bind_param('ii', $startIndex, $this->pageLength);
//		$stmt->execute();
//		$result = $stmt->get_result();
//
//		while ($row = $result->fetch_assoc()) {
//			if (isset($row['name'])) {
//				$name = $row['name'];
//				$id = $row['id'];
//
//				array_push($this->categories, new CategoryTableElement($name, intval($id)));
//			}
//		}

		$this->categoryPages = ceil($categoriesSnap->numChildren() / $this->pageLength);

//		$result = $this->conn->query('SELECT COUNT(*) AS count FROM `categories`');
//		while ($row = $result->fetch_assoc()) {
//			if (isset($row['count'])) {
//				$numelements = intval($row['count']);
//				$this->categoryPages = ceil($numelements / $this->pageLength);
//			}
//		}
	}

	function getAllCategories() {
//		if (is_null($this->conn)) {
//			return array();
//		}
//
//		$stmt = $this->conn->prepare('SELECT `name`, `id` FROM `categories` ORDER BY `name` ASC');
//		$stmt->execute();
//		$result = $stmt->get_result();
//
//		$allCategories = array();
//
//		while ($row = $result->fetch_assoc()) {
//			if (isset($row['name'])) {
//				$name = $row['name'];
//				$id = $row['id'];
//
//				$allCategories[$id] = $name;
//			}
//		}

		if (is_null($this->database)) {
			return;
		}

		$categoriesSnap = $this->database->getReference('categories')->getSnapshot();
		$categoriesJSON = $categoriesSnap->getValue();

		$allCategories = array();

		foreach ($categoriesJSON as $categoryJSON) {
			$name = $categoryJSON['name'];
			$id = $categoryJSON['id'];
			$allCategories[$id] = $name;
		}

		return $allCategories;
		// todo figure out pagination

	}

	function loadCounties($search = '', $page = 0) {
		// run query to load categories
//		if (is_null($this->conn)) {
//			return;
//		}
//
//		$stmt = $this->conn->prepare('SELECT `name`, `id` FROM `counties` ORDER BY `id` DESC LIMIT ?,?');
//		$startIndex = $page * $this->pageLength;
//		$stmt->bind_param('ii', $startIndex, $this->pageLength);
//		$stmt->execute();
//		$result = $stmt->get_result();
//
//		while ($row = $result->fetch_assoc()) {
//			if (isset($row['name'])) {
//				$name = $row['name'];
//				$id = $row['id'];
//
//				array_push($this->categories, new CountyTableElement($name, intval($id)));
//			}
//		}

		if (is_null($this->database)) {
			return;
		}

		$countiesSnap = $this->database->getReference('counties')->getSnapshot();
		$countiesJSON = $countiesSnap->getValue();

		foreach ($countiesJSON as $countyJSON) {
			$name = $countyJSON['name'];
			$id = $countyJSON['id'];
			array_push($this->counties, new CountyTableElement($name, $id));
		}

//		$result = $this->conn->query('SELECT COUNT(*) AS count FROM `counties`');
//		while ($row = $result->fetch_assoc()) {
//			if (isset($row['count'])) {
//				$numelements = intval($row['count']);
//				$this->categoryPages = ceil($numelements / $this->pageLength);
//			}
//		}

		$this->countyPages = ceil($countiesSnap->numChildren() / $this->pageLength);
		// todo figure out pagination

	}

	function getAllCounties() {
		if (is_null($this->database)) {
			return array();
		}

		$countiesSnap = $this->database->getReference('counties')->getSnapshot();
		$countiesJSON = $countiesSnap->getValue();

		$allCounties = array();

		foreach ($countiesJSON as $countyJSON) {
			$name = $countyJSON['name'];
			$id = $countyJSON['id'];
			$allCounties[$id] = $name;
		}

		return $allCounties;
//		if (is_null($this->conn)) {
//			return array();
//		}
//
//		$stmt = $this->conn->prepare('SELECT `name`, `id` FROM `counties` ORDER BY `name` ASC');
//		$stmt->execute();
//		$result = $stmt->get_result();
//
//		$allCategories = array();
//
//		while ($row = $result->fetch_assoc()) {
//			if (isset($row['name'])) {
//				$name = $row['name'];
//				$id = $row['id'];
//
//				$allCategories[$id] = $name;
//			}
//		}
//
//		return $allCategories;
	}

	function loadResources($search = '', $cat = '', $county = '', $page = 0) {
		echo '<!-- loadResources -->';
		// run query to load resources
		if (is_null($this->database)) {
			return;
		}

//		$query = 'SELECT `name`, `id` FROM `resources` ORDER BY `id` DESC LIMIT ?,?';
		$catId = -1;
		$countyId = -1;

//		if (!empty($cat) && is_numeric($cat)) {
//			$catId = intval($cat);
//			$query = "SELECT `name`, `id` FROM `resources` WHERE `categories` LIKE '%,$catId,%' ORDER BY `name` DESC LIMIT ?,?";
//		} else if (!empty($county) && is_numeric($county)) {
//			$countyId = intval($county);
//			$query = "SELECT `name`, `id` FROM `resources` WHERE `counties` LIKE '%,$countyId,%' ORDER BY `name` DESC LIMIT ?,?";
//		}


		$resourcesSnap = $this->database->getReference('resources')->getSnapshot();
		$resourcesJSON = $resourcesSnap->getValue();

		if (!empty($cat) && is_numeric($cat)) {
			$catId = intval($cat);
			foreach ($resourcesJSON as $resourceJSON) {
				$name = $resourceJSON['name'];
				$id = $resourceJSON['id'];

				if (in_array($catId, $resourceJSON['categories'])) {
					array_push($this->resources, new ResourceTableElement($name, intval($id)));
				}
			}
		} else if (!empty($county) && is_numeric($county)) {
			$countyId = intval($county);
			foreach ($resourcesJSON as $resourceJSON) {
				$name = $resourceJSON['name'];
				$id = $resourceJSON['id'];

				if (in_array($countyId, $resourceJSON['counties'])) {
					array_push($this->resources, new ResourceTableElement($name, intval($id)));
				}
			}
		} else {
			foreach ($resourcesJSON as $resourceJSON) {
				$name = $resourceJSON['name'];
				$id = $resourceJSON['id'];

				array_push($this->resources, new ResourceTableElement($name, intval($id)));
			}
		}




//		$stmt = $this->conn->prepare($query);
//		$startIndex = $page * $this->pageLength;
//		$stmt->bind_param('ii', $startIndex, $this->pageLength);
//		$stmt->execute();
//		$result = $stmt->get_result();
//
//		while ($row = $result->fetch_assoc()) {
//			if (isset($row['name'])) {
//				$name = $row['name'];
//				$id = $row['id'];
//
//				array_push($this->resources, new ResourceTableElement($name, intval($id)));
//			}
//		}

		$this->resourcePages = ceil($resourcesSnap->numChildren() / $this->pageLength);

		// todo figure out pagination

//		$result = $this->conn->query('SELECT COUNT(*) AS count FROM `resources`');
//		while ($row = $result->fetch_assoc()) {
//			if (isset($row['count'])) {
//				$numelements = intval($row['count']);
//				$this->resourcePages = ceil($numelements / $this->pageLength);
//			}
//		}
	}

	function loadUsers() {
		// run query to load users
	}
}