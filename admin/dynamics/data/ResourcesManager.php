<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 8/1/18
 * Time: 12:41 AM
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/dynamics/component/form/DynamicAddresses.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/dynamics/component/form/Address.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/dynamics/data/model/AddrModel.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/dynamics/component/form/DynamicContact.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/dynamics/component/form/Contact.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/dynamics/data/model/ContModel.php');


class ResourcesManager {
	var $manager;

	/**
	 * CategoriesManager constructor.
	 * @param $manager
	 */
	public function __construct($manager) {
		$this->manager = $manager;
	}


	function addResource($resource) {
		if (is_null($this->manager->conn)) {
			return;
		}

		$stmt = $this->manager->conn->prepare('INSERT INTO `resources` (name, categories, counties, description, tags, services, hours, documentation) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
		$stmt->bind_param('ssssssss', $resource['name'], $resource['categories'], $resource['counties'], $resource['desc'],
			$resource['tags'], $resource['services'], $resource['hours'], $resource['documentation']);
		$stmt->execute();

		if (!empty($stmt->error)) {
			echo ($stmt->error);
			return -1;
		}

		return $stmt->insert_id;
	}

	function editResource($id, $resource) {
		if (is_null($this->manager->conn)) {
			return;
		}

		/*
		 * 'tags' => $tags,
			'services' => $services,
			'hours' => $hours,
			'documentation' => $documentation];
		 */
		$stmt = $this->manager->conn->prepare('UPDATE `resources` 
			SET `name` = ?, `categories` = ?, `counties` = ?, `description` = ?, `tags` = ?, `services` = ?,
				`hours` = ?, `documentation` = ?
			WHERE `resources`.`id` = ?');
		$stmt->bind_param('ssssssssi', $resource['name'], $resource['categories'], $resource['counties'],
			$resource['desc'], $resource['tags'], $resource['services'], $resource['hours'],
			$resource['documentation'], $id);
		$stmt->execute();
	}

	function loadResource($id) {
		$stmt = $this->manager->conn->prepare('SELECT * FROM `resources` WHERE id = ?');
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$result = $stmt->get_result();

		while ($row = $result->fetch_assoc()) {
			if (isset($row['id'])){
				return ['name'=>$row['name'], 'desc'=>$row['description'],
						'tags'=>$row['tags'], 'services'=>$row['services'],
					    'hours'=>$row['hours'], 'documentation'=>$row['documentation'],
						'categories'=>$row['categories'], 'counties'=>$row['counties']];
			}
		}
	}

	function parseIDs($str) {
		$ids = array();
		foreach (explode(',',$str) as $idStr) {
			if (!empty($idStr)) {
				array_push($ids, intval($idStr));
			}
		}

		return $ids;
	}

	function encodeIDs($idArr) {
		$idStr = ','.implode(',',$idArr).',';
		return $idStr;
	}

	// ADDRESS METHODS

	function loadAddresses($resourceID, $selectWhat = '*') {
		$stmt = $this->manager->conn->prepare('SELECT ' . $selectWhat . ' FROM `addresses` WHERE resourceid = ?');
		$stmt->bind_param('i', $resourceID);
		$stmt->execute();
		$result = $stmt->get_result();

		$addresses = array();

		while ($row = $result->fetch_assoc()) {
			if (isset($row['id'])) {
				$description = $street1 = $street2 = $city = $state = $zip = '';
				$resourceID = -1;

				$id = intval($row['id']);

				if (isset($row['description'])) {
					$description = $row['description'];
				}

				if (isset($row['street1'])) {
					$street1 = $row['street1'];
				}

				if (isset($row['street2'])) {
					$street2 = $row['street2'];
				}

				if (isset($row['city'])) {
					$city = $row['city'];
				}

				if (isset($row['state'])) {
					$state = $row['state'];
				}

				if (isset($row['zip'])) {
					$zip = $row['zip'];
				}

				if (isset($row['resourceid'])) {
					$resourceID = intval($row['resourceid']);
				}

				$address = new AddrModel($description, $street1, $street2, $city, $state, $zip, $id, $resourceID);

				array_push($addresses, $address);
			}
		}
		return $addresses;
	}

	function loadAddressComponents($resourceID) {
		$addresses = new DynamicAddresses();

		foreach ($this->loadAddresses($resourceID) as $row) {
			$addresses->addAddrModel($row);
		}

		return $addresses;
	}

	function updateAddresses($submittedAddresses, $resourceID) {
		/*
		 * 1. load addresses from database
		 * 2. search through addresses in database
		 * 3. if address in database is not contained in submitted addresses, delete it
		 * 4. search through addresses submitted
		 * 5. if address submitted is not contained in database, add it
		 */

		$databaseAddr = $this->loadAddresses($resourceID, 'id');

		foreach ($databaseAddr as $addr) {
			$contains = false;
			foreach ($submittedAddresses as $subAddr) {
				if ($subAddr->id == $addr->id) {
					$contains = true;
					break;
				}
			}

			if (!$contains) {
				$this->deleteAddr($addr);
			}
		}

		foreach ($submittedAddresses as $subAddr) {
			$this->updateAddr($subAddr);
		}
	}

	function deleteAddr(AddrModel $addr) {
		$stmt = $this->manager->conn->prepare('DELETE FROM `addresses` WHERE id = ?');
		$stmt->bind_param('i', $addr->id);
		$stmt->execute();
	}

	function updateAddr(AddrModel $addr) {
		if($addr->id == -1) {
			$query = 'INSERT INTO `addresses` 
           		(description, street1, street2, city, state, zip, resourceid) VALUES (?, ?, ?, ?, ?, ?, ?)';
			$stmt = $this->manager->conn->prepare($query);
			$stmt->bind_param('ssssssi', $addr->desc, $addr->street1, $addr->street2,
				$addr->city, $addr->state, $addr->zip, $addr->resourceId);
			$stmt->execute();

		} else {
			$query = 'UPDATE `addresses` SET `description` = ?, `street1` = ?, `street2` = ?, `city` = ?, `state` = ?,
				`zip` = ? WHERE `id` = ?';
			$stmt = $this->manager->conn->prepare($query);
			$stmt->bind_param('ssssssi', $addr->desc, $addr->street1, $addr->street2, $addr->city, $addr->state, $addr->zip, $addr->id);
			$stmt->execute();
		}
	}

	// CONTACT METHODS
	function loadContacts($resourceID, $selectWhat = '*') {
		$stmt = $this->manager->conn->prepare('SELECT ' . $selectWhat . ' FROM `contact` WHERE resourceid = ?');
		$stmt->bind_param('i', $resourceID);
		$stmt->execute();
		$result = $stmt->get_result();

		$contacts = array();

		while ($row = $result->fetch_assoc()) {
			if (isset($row['id'])) {
				$type = 0 ;
				$name = $value = '';
				$resourceID = -1;

				$id = intval($row['id']);


				if (isset($row['type'])) {
					$type = intval($row['type']);
				}

				if (isset($row['name'])) {
					$name = $row['name'];
				}

				if (isset($row['value'])) {
					$value = $row['value'];
				}

				$address = new ContModel($type, $name, $value, $id, $resourceID);

				array_push($contacts, $address);
			}
		}
		return $contacts;
	}

	function loadContactComponents($resourceID) {
		$contacts = new DynamicContact();

		foreach ($this->loadContacts($resourceID) as $row) {
			$contacts->addContModel($row);
		}

		return $contacts;
	}

	function updateContacts($submittedContacts, $resourceID) {
		$databaseCont = $this->loadContacts($resourceID, 'id');

		foreach ($databaseCont as $cont) {
			$contains = false;
			foreach ($submittedContacts as $subCont) {
				if ($subCont->id == $cont->id) {
					$contains = true;
					break;
				}
			}

			if (!$contains) {
				$this->deleteCont($cont);
			}
		}

		foreach ($submittedContacts as $subCont) {
			$this->updateCont($subCont);
		}
	}

	function deleteCont(ContModel $addr) {
		$stmt = $this->manager->conn->prepare('DELETE FROM `contact` WHERE id = ?');
		$stmt->bind_param('i', $addr->id);
		$stmt->execute();
	}

	function updateCont(ContModel $cont) {
		if($cont->id == -1) {
			$query = 'INSERT INTO `contact` 
           		(`type`, `name`, `value`, `resourceid`) VALUES (?, ?, ?, ?)';
			$stmt = $this->manager->conn->prepare($query);
			$stmt->bind_param('issi', $cont->type, $cont->name, $cont->value, $cont->resourceId);
			$stmt->execute();

		} else {
			$query = 'UPDATE `contact` SET `type` = ?, `name` = ?, `value` = ? WHERE `id` = ?';
			$stmt = $this->manager->conn->prepare($query);
			$stmt->bind_param('issi', $cont->type, $cont->name, $cont->value, $cont->id);
			$stmt->execute();
		}
	}
}