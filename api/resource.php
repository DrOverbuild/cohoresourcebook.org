<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 8/28/18
 * Time: 9:57 AM
 */

namespace api;

include_once ("loadall.php");

include_once ("database/Connection.php");
include_once ("database/ResourceServer.php");

use api\database\Connection;
use api\database\ResourceServer;

$result = [];

$conn = new Connection();
$resourceServer = new ResourceServer($conn);

if (isset($_GET['id'])) {
	// load specific resource
	$id = intval($_GET['id']);

	$resource = $resourceServer->resourceFromId($id);

	if ($resource) {
		$result['resource'] = $resource->toArray();
	} else {
		$result["error"] = "Resource not found";
	}
} else {
	// load all resources
	$resources = [];

	if (isset($_GET['p'])){
		$resources = $resourceServer->listAllResources(true, intval($_GET['p']));
	} else {
		$resources = $resourceServer->listAllResources();
	}

	if (sizeof($resources) == 0) {
		$result['error'] = "No resources available";
	} else {
		$result['resources'] = [];
		foreach ($resources as $resource) {
			array_push($result['resources'], $resource->toArray());
		}
	}
}

header('Content-Type: application/json');
echo json_encode($result);

