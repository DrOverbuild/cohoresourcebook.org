<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 7/31/18
 * Time: 10:52 AM
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/data/DBManager.php');

class CountiesManager {
	var $manager;

	/**
	 * CountiesManager constructor.
	 * @param $manager
	 */
	public function __construct($manager) {
		$this->manager = $manager;
	}


	function addCounty($county) {
		if (is_null($this->manager->conn)) {
			return;
		}

		$stmt = $this->manager->conn->prepare('INSERT INTO `counties` (name, description) VALUES (?, ?)');
		$stmt->bind_param('ss', $county['name'], $county['desc']);
		$stmt->execute();

		if (!empty($stmt->error)) {
			echo ($stmt->error);
			return -1;
		}

		return $stmt->insert_id;
	}

	function editCounty($id, $county) {
		if (is_null($this->manager->conn)) {
			return;
		}

		$stmt = $this->manager->conn->prepare('UPDATE `counties` 
			SET `name` = ?, `description` = ?  
			WHERE `counties`.`id` = ?');
		$stmt->bind_param('ssi', $county['name'], $county['desc'], $id);
		$stmt->execute();
	}

	function loadCounty($id) {
		$stmt = $this->manager->conn->prepare('SELECT * FROM `counties` WHERE id = ?');
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$result = $stmt->get_result();

		while ($row = $result->fetch_assoc()) {
			if (isset($row['id'])){
				return ['name'=>$row['name'], 'desc'=>$row['description']];
			}
		}
	}
}