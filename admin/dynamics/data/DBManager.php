<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 7/6/18
 * Time: 11:59 PM
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/table/CategoryTableElement.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/table/ResourceTableElement.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/table/CountyTableElement.php');

class DBManager {
	var $pageLength = 25;

	// categories
	var $categories = array();
	var $categoryPages;
	// resources
	var $resources = array();
	var $resourcePages;
	// users
	var $users = array();

	/** @var mysqli */
	var $conn = null;

	public function __construct() {
		$this->startDbConn();
	}

	/**
	 * @return conn
	 */
	public function getConn() {
		return $this->conn;
	}

	function startDbConn() {


		$config = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/dynamics/data/db.ini');

		$servername = $config['servername'];
		$username = $config['username'];
		$password = $config['password'];
		$dbname = $config['dbname'];


		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
			$this->conn = null;
			return "NO CONNECTION";
		}

		$this->conn = $conn;

		return "SUCCESS";
	}

	function loadCategories($search = '', $page = 0) {
		// run query to load categories
		if (is_null($this->conn)) {
			return;
		}

		$stmt = $this->conn->prepare('SELECT `name`, `id` FROM `categories` ORDER BY `id` DESC LIMIT ?,?');
		$startIndex = $page * $this->pageLength;
		$stmt->bind_param('ii', $startIndex, $this->pageLength);
		$stmt->execute();
		$result = $stmt->get_result();

		while ($row = $result->fetch_assoc()) {
			if (isset($row['name'])) {
				$name = $row['name'];
				$id = $row['id'];

				array_push($this->categories, new CategoryTableElement($name, intval($id)));
			}
		}

		$result = $this->conn->query('SELECT COUNT(*) AS count FROM `categories`');
		while ($row = $result->fetch_assoc()) {
			if (isset($row['count'])) {
				$numelements = intval($row['count']);
				$this->categoryPages = ceil($numelements / $this->pageLength);
			}
		}
	}

	function getAllCategories() {
		if (is_null($this->conn)) {
			return array();
		}

		$stmt = $this->conn->prepare('SELECT `name`, `id` FROM `categories` ORDER BY `name` ASC');
		$stmt->execute();
		$result = $stmt->get_result();

		$allCategories = array();

		while ($row = $result->fetch_assoc()) {
			if (isset($row['name'])) {
				$name = $row['name'];
				$id = $row['id'];

				$allCategories[$id] = $name;
			}
		}

		return $allCategories;
	}

	function loadCounties($search = '', $page = 0) {
		// run query to load categories
		if (is_null($this->conn)) {
			return;
		}

		$stmt = $this->conn->prepare('SELECT `name`, `id` FROM `counties` ORDER BY `id` DESC LIMIT ?,?');
		$startIndex = $page * $this->pageLength;
		$stmt->bind_param('ii', $startIndex, $this->pageLength);
		$stmt->execute();
		$result = $stmt->get_result();

		while ($row = $result->fetch_assoc()) {
			if (isset($row['name'])) {
				$name = $row['name'];
				$id = $row['id'];

				array_push($this->categories, new CountyTableElement($name, intval($id)));
			}
		}

		$result = $this->conn->query('SELECT COUNT(*) AS count FROM `counties`');
		while ($row = $result->fetch_assoc()) {
			if (isset($row['count'])) {
				$numelements = intval($row['count']);
				$this->categoryPages = ceil($numelements / $this->pageLength);
			}
		}
	}

	function getAllCounties() {
		if (is_null($this->conn)) {
			return array();
		}

		$stmt = $this->conn->prepare('SELECT `name`, `id` FROM `counties` ORDER BY `name` ASC');
		$stmt->execute();
		$result = $stmt->get_result();

		$allCategories = array();

		while ($row = $result->fetch_assoc()) {
			if (isset($row['name'])) {
				$name = $row['name'];
				$id = $row['id'];

				$allCategories[$id] = $name;
			}
		}

		return $allCategories;
	}

	function loadResources($search = '', $cat = '', $county = '', $page = 0) {
		// run query to load resources
		if (is_null($this->conn)) {
			return;
		}

		$query = 'SELECT `name`, `id` FROM `resources` ORDER BY `id` DESC LIMIT ?,?';
		$catId = -1;

		if (!empty($cat) && is_numeric($cat)) {
			$catId = intval($cat);
			$query = "SELECT `name`, `id` FROM `resources` WHERE `categories` LIKE '%,$catId,%' ORDER BY `name` DESC LIMIT ?,?";
		} else if (!empty($county) && is_numeric($county)) {
			$countyId = intval($county);
			$query = "SELECT `name`, `id` FROM `resources` WHERE `counties` LIKE '%,$countyId,%' ORDER BY `name` DESC LIMIT ?,?";
		}


		$stmt = $this->conn->prepare($query);
		$startIndex = $page * $this->pageLength;
		$stmt->bind_param('ii', $startIndex, $this->pageLength);
		$stmt->execute();
		$result = $stmt->get_result();

		while ($row = $result->fetch_assoc()) {
			if (isset($row['name'])) {
				$name = $row['name'];
				$id = $row['id'];

				array_push($this->resources, new ResourceTableElement($name, intval($id)));
			}
		}

		$result = $this->conn->query('SELECT COUNT(*) AS count FROM `resources`');
		while ($row = $result->fetch_assoc()) {
			if (isset($row['count'])) {
				$numelements = intval($row['count']);
				$this->resourcePages = ceil($numelements / $this->pageLength);
			}
		}
	}

	function loadUsers() {
		// run query to load users
	}
}