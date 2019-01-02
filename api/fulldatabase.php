<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 10/21/18
 * Time: 9:09 PM
 */

namespace api;

require_once("loadall.php");

use api\database\CategoryServer;
use api\database\Connection;
use api\database\CountyServer;
use api\database\ResourceServer;

$time = time();

$result = [];

$conn = new Connection();
$categoryServer = new CategoryServer($conn);
$countyServer = new CountyServer($conn);
$resourceServer = new ResourceServer($conn);

$result['categories'] = [];
$result['resources'] = [];
$result['counties'] = [];
$result['error'] = [];

$categories = $categoryServer->listAllCategories(0,false,false);
$counties = $countyServer->listAllCounties(0,false,false);
$resources = $resourceServer->listAllResources(false);


if (sizeof($categories) == 0) {
	array_push($result['error'], "No categories available");

} else {
	foreach ($categories as $category) {
		array_push($result['categories'], $category->toArray());
	}
}

if (sizeof($counties) == 0) {
	array_push($result['error'], "No counties available");
} else {
	foreach ($counties as $county) {
		array_push($result['counties'], $county->toArray());
	}
}

if (sizeof($resources) == 0) {
	array_push($result['error'], "No resources available");
} else {
	$result['resources'] = [];
	foreach ($resources as $resource) {
		array_push($result['resources'], $resource->toArray());
	}
}

$time = time() - $time;

$result["time_elapsed"] = $time;

header('Content-Type: application/json');

echo json_encode($result);