<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 8/30/18
 * Time: 10:53 AM
 */

namespace api;

require_once ("loadall.php");

use api\database\CategoryServer;
use api\database\Connection;

$result = [];

$conn = new Connection();
$categoryServer = new CategoryServer($conn);

if (isset($_GET['id'])) {
	// load specific category
	$id = intval($_GET['id']);

	$categories = $categoryServer->loadCategory($id, true);

	if ($categories) {
		$result['category'] = $categories->toArray();
	} else {
		$result["error"] = "Category not found";
	}
} else {
	// load all categories
	$categories = [];
	$result['categories'] = [];

	if (isset($_GET['p'])){
		$categories = $categoryServer->listAllCategories(intval($_GET['p']));
	} else {
		$categories = $categoryServer->listAllCategories();
	}

	if (sizeof($categories) == 0) {
		$result['error'] = "No categories available";
	} else {
		foreach ($categories as $category) {
			array_push($result['categories'], $category->toArray());
		}
	}
}

header('Content-Type: application/json');

echo json_encode($result);

