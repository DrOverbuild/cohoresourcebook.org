<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 7/31/18
 * Time: 10:52 AM
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/dynamics/data/DBManager.php');

class CategoriesManager {
	var $manager;

	/**
	 * CategoriesManager constructor.
	 * @param $manager
	 */
	public function __construct($manager) {
		$this->manager = $manager;
	}


	function addCategory($category) {
		if (is_null($this->manager->conn)) {
			return;
		}

		$stmt = $this->manager->conn->prepare('INSERT INTO `categories` (name, description, icon) VALUES (?, ?, ?)');
		$stmt->bind_param('sss', $category['name'], $category['desc'], $category['icon']);
		$stmt->execute();

		if (!empty($stmt->error)) {
			echo ($stmt->error);
			return -1;
		}

		return $stmt->insert_id;
	}

	function editCategory($id, $category) {
		if (is_null($this->manager->conn)) {
			return;
		}

		$stmt = $this->manager->conn->prepare('UPDATE `categories` 
			SET `name` = ?, `description` = ?, `icon` = ?  
			WHERE `categories`.`id` = ?');
		$stmt->bind_param('sssi', $category['name'], $category['desc'], $category['icon'], $id);
		$stmt->execute();
	}

	function loadCategory($id) {
		$stmt = $this->manager->conn->prepare('SELECT * FROM `categories` WHERE id = ?');
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$result = $stmt->get_result();

		while ($row = $result->fetch_assoc()) {
			if (isset($row['id'])){
				return ['name'=>$row['name'], 'desc'=>$row['description'], 'icon' =>$row['icon']];
			}
		}
	}
}