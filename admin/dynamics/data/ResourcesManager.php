<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 8/1/18
 * Time: 12:41 AM
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/form/DynamicAddresses.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/form/Address.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/data/model/AddrModel.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/form/DynamicContact.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/form/Contact.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/data/model/ContModel.php');


class ResourcesManager {
	/** @var DBManager */
	var $manager;

	/**
	 * CategoriesManager constructor.
	 * @param $manager
	 */
	public function __construct($manager) {
		$this->manager = $manager;
	}

	function resourceFromId($id) {
		return $resourceSnap = $this->manager->database
			->getReference('resources')->orderByChild('id')
			->equalTo($id);
	}

	function addResource($resource) {
		if (is_null($this->manager->conn)) { // todo change this to support firebase changes
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
		if (is_null($this->manager->database)) {
			return;
		}

		$resource['id'] = $id;

		$value = $this->resourceFromId($id)->getValue();
		if(sizeof($value) > 0) {
			$uid = array_keys($value)[0];

			$this->manager->database->getReference('resources')->update([$uid => $resource]);
		}
	}

	function loadResource($id) {
		$resourceSnap = $this->resourceFromId($id)->getSnapshot();

		if ($resourceSnap->numChildren() >= 1) {
			$resourceJSON = array_values($resourceSnap->getValue())[0];
			return ['name'=>$resourceJSON['name'], 'desc'=>$resourceJSON['description'],
				'tags'=>$resourceJSON['tags'], 'services'=>$resourceJSON['services'],
				'hours'=>$resourceJSON['hours'], 'documentation'=>$resourceJSON['documentation'],
				'categories'=>$resourceJSON['categories'], 'counties'=>$resourceJSON['counties'],
				'locations'=>$resourceJSON['locations'], 'contact'=>$resourceJSON['contact'], 'id'=>$id];
		}

		return [];
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
	function loadAddresses($resource, $selectWhat = '*') {
		$addresses = array();
		foreach ($resource['locations'] as $row) {
			if (isset($row['id'])) {
				$description = $street1 = $street2 = $city = $state = $zip = '';

				$id = intval($row['id']);

				if (isset($row['desc'])) {
					$description = $row['desc'];
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

				$address = new AddrModel($description, $street1, $street2, $city, $state, $zip, $id, $resource['id']);

				array_push($addresses, $address);
			}
		}
		return $addresses;
	}

	function loadAddressComponents($resource) {
		$addresses = new DynamicAddresses();

		foreach ($this->loadAddresses($resource) as $row) {
			$addresses->addAddrModel($row);
		}

		return $addresses;
	}

	/**
	 * @deprecated because Firebase automatically updates this
	 */
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

	/**
	 * @deprecated because Firebase automatically updates this
	 */
	function deleteAddr(AddrModel $addr) {
		$stmt = $this->manager->conn->prepare('DELETE FROM `addresses` WHERE id = ?');
		$stmt->bind_param('i', $addr->id);
		$stmt->execute();
	}

	/**
	 * @deprecated because Firebase automatically updates this
	 */
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
	function loadContacts($resource, $selectWhat = '*') {
		$contacts = array();

		foreach ($resource['contact'] as $row) {
			if (isset($row['id'])) {
				$type = 0 ;
				$name = $value = '';

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

				$address = new ContModel($type, $name, $value, $id, $resource['id']);

				array_push($contacts, $address);
			}
		}
		return $contacts;
	}

	function loadContactComponents($resource) {
		$contacts = new DynamicContact();

		foreach ($this->loadContacts($resource) as $row) {
			$contacts->addContModel($row);
		}

		return $contacts;
	}

	/**
	 * @deprecated because Firebase automatically updates this
	 */
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

	/**
	 * @deprecated because Firebase automatically updates this
	 */

	function deleteCont(ContModel $addr) {
		$stmt = $this->manager->conn->prepare('DELETE FROM `contact` WHERE id = ?');
		$stmt->bind_param('i', $addr->id);
		$stmt->execute();
	}

	/**
	 * @deprecated because Firebase automatically updates this
	 */

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