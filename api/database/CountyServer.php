<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 8/28/18
 * Time: 11:05 AM
 */

namespace api\database;


use api\model\County;

class CountyServer extends Server {
	/** @var Connection Main connection*/
	private $conn;

	/**
	 * CategoryServer constructor.
	 * @param $conn
	 */
	public function __construct($conn) {
		$this->conn = $conn;
	}

	/**
	 * Loads all categories.
	 *
	 * @param int $page Page number if pagination is enabled
	 * @param bool $enablePagination Enables pagination. If false, all categories will be loaded
	 * @return Category[]
	 */
	public function listAllCounties($page = 0, $enablePagination = true, $withResources = true) {
		$stmt = null;

		if ($enablePagination) {
			$startIndex = $page * $this->conn->pageLength;
			$stmt = $this->conn->prepare('SELECT * FROM `counties` ORDER BY `name` ASC LIMIT ?,?');
			$stmt->bind_param('ii', $startIndex, $this->conn->pageLength);

		} else {
			$stmt = $this->conn->prepare('SELECT * FROM `counties` ORDER BY `name` ASC');
		}

		$stmt->execute();
		$result = $stmt->get_result();

		$counties = array();

		while ($row = $result->fetch_assoc()) {
			if (isset($row['id'])) {
				$name = $row['name'];
				$id = $row['id'];
				$desc = $row['description'];
				$icon = $row['icon'];

				$county = new County($name, $desc, $icon, $id);

				if ($withResources) {
					$resourceConn = new Connection();
					$resourceServer = new ResourceServer($resourceConn, $this);
					$county->resources = $resourceServer->allResourcesFromCounty($id);
				}

				array_push($counties, $county);
			}
		}

		return $counties;
	}

	/**
	 * Loads a category and its resources
	 *
	 * @param int $id Id of the category
	 * @param bool $loadResources If set to false the resources array of the category will be empty
	 * @return Category
	 */
	public function loadCounty(int $id, $loadResources = true) {
		$stmt = $this->conn->prepare('SELECT * FROM `counties` WHERE `id` = ?');

		$stmt->bind_param('i', $id);
		$stmt->execute();
		$result = $stmt->get_result();


		while ($row = $result->fetch_assoc()) {

			if (isset($row['id'])) {
				$name = $this->defaultIfNull($row,'name', '');
				$id = intval($row['id']);
				$desc = $this->defaultIfNull($row,'description', '');
				$icon = $row['icon'];

				$county = new County($name, $desc, $icon, $id);

				if($loadResources) {
					$resourceServer = new ResourceServer($this->conn, $this);
					$county->resources = $resourceServer->allResourcesFromCounty($id);
				}

				return $county;
			}
		}
	}
}