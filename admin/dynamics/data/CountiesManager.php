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

		$stmt = $this->manager->conn->prepare('INSERT INTO `counties` (name, description, icon) VALUES (?, ?, ?)');
		$stmt->bind_param('sss', $county['name'], $county['desc'], $county['icon']);
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
			SET `name` = ?, `description` = ?, `icon` = ?  
			WHERE `counties`.`id` = ?');
		$stmt->bind_param('sssi', $county['name'], $county['desc'], $county['icon'], $id);
		$stmt->execute();
	}

	function loadCounty($id) {
		$stmt = $this->manager->conn->prepare('SELECT * FROM `counties` WHERE id = ?');
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$result = $stmt->get_result();

		while ($row = $result->fetch_assoc()) {
			if (isset($row['id'])){
				return ['name'=>$row['name'], 'desc'=>$row['description'], 'icon' => $row['icon']];
			}
		}
	}
}