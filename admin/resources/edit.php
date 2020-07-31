<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 7/7/18
 * Time: 10:42 AM
 */

// GET arg `id` = edit mode
// `id` not set = add mode
// POST arg `resource` set = save

require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/page/Page.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/form/EditForm.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/data/DBManager.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/data/ResourcesManager.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/data/model/AddrModel.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/data/model/ContModel.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/form/DynamicAddresses.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/form/DynamicContact.php');


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//var_dump($_POST);

$manager = new DBManager();

$page = new Page();
$editForm = new EditForm($_SERVER['DOCUMENT_ROOT'] . '/dynamics/html/form/resourcesedit.html', $page);

$actionTitle = "NEW RESOURCE";

$name = $desc = $tags = $services = $documentation = $hours = "";
$checkedCategories = array();
$checkedCounties = array();
$catStr = "";
$countiesStr = "";

$addresses = new DynamicAddresses();
$addresssesModel = [];

$contacts = new DynamicContact();
$contModel = [];

$required = false;

$result = "none";
$actionResult = "";

$idElement = "";

if (isset($_GET['id'])) {
	$id = $_GET['id'];
	if ($id >= 0) {
		$resourcesMan = new ResourcesManager($manager);
		$resources = $resourcesMan->loadResource(intval($id));
		$name = $resources['name'];
		$desc = $resources['desc'];
		$tags = $resources['tags'];
		$services = $resources['services'];
		$hours = $resources['hours'];
		$documentation = $resources['documentation'];

		$checkedCategories = $resources['categories'];
		$checkedCounties = $resources['counties'];

		$addresses = $resourcesMan->loadAddressComponents($resources);

		$contacts = $resourcesMan->loadContactComponents($resources);


		//TODO add form elements here
		$actionTitle = "EDIT RESOURCE — " . $name;
		$idElement = '<input type="hidden" id="cat_id" name="id" value="' . $id . '">';
	}

	if (isset($_GET['success'])) {
		$result = "block";

		if ($_GET['success'] == "fail") {
			$actionResult = "Add new resource failed";
		}else {
			$actionResult = "Resource ".$_GET['success']." successfully";
		}
	}
}


if (isset($_POST['name'])) {

	// debug post
/*	highlight_string("<?php\n\$_POST =\n" . var_export($_POST, true) . ";\n?>\n\n");*/

	if (empty($_POST['name'])) {
		$editForm->loadComponents("NAME_REQUIRED", "required");
		$required = true;
	} else {
		$name = $_POST['name'];
	}

	if (!empty($_POST['desc'])) {
		$desc = $_POST['desc'];
	}

	if (!empty($_POST['tags'])) {
		$tags = $_POST['tags'];
	}

	if (!empty($_POST['services'])) {
		$services = $_POST['services'];
	}

	if (!empty($_POST['hours'])) {
		$hours = $_POST['hours'];
	}

	if (!empty($_POST['documentation'])) {
		$documentation = $_POST['documentation'];
	}

	if(isset($_POST['categories'])) {
		$catStr = ','.implode(',',$_POST['categories']).',';

	}

	if(isset($_POST['counties'])) {
		$countiesStr = ','.implode(',',$_POST['counties']).',';

	}

	//TODO add form elements here


	if (!$required) { // POST SUCCESS
		$resourcesMan = new ResourcesManager($manager);
		$resources = ['name' => $name,
			'description' => $desc,
			'tags' => $tags,
			'services' => $services,
			'hours' => $hours,
			'documentation' => $documentation,
			'categories' => $_POST['categories'],
			'counties' => $_POST['counties']
		];
		if (isset($_POST['id'])) {
			// edit category
			if (isset($_POST['addresses'])) {
				$resources['locations'] = $_POST['addresses'];
			}

			if (isset($_POST['contact'])) {
				$resources['contact'] = $_POST['contact'];
			}

			$resourcesMan->editResource(intval($_POST['id']), $resources);

			$actionResult = "edited";
			$id = intval($_POST['id']);
		} else {
			$id = $resourcesMan->addResource($resources);

			if (isset($_POST['addresses'])) {
				$addresssesModel = AddrModel::addressesFromArr($_POST['addresses'], $id);
			}

			if (isset($_POST['contact'])) {
				$contModel = ContModel::contactsFromArr($_POST['contact'], $id);
			}

			$resourcesMan->updateAddresses($addresssesModel, $id);
			$resourcesMan->updateContacts($contModel, $id);

			$actionResult = "added";
		}

		if ($id == -1) {
			$actionResult = "fail";
			exit();
		}

		$location = "Location: edit.php?id=$id&success=$actionResult";
		header($location);
		exit();
	}
}

// LOAD CATEGORIES

$categoriesFieldset = '';
$categories = $manager->getAllCategories();

foreach ($categories as $catId => $catName) {
	$checked = '';

	if (in_array($catId, $checkedCategories)) {
		$checked = " checked";
	}

	$line = '<div class="checkbox_item">
			     <input class="category_checkbox" type="checkbox" name="categories[]" value="'.$catId.'" id="cat_'.$catId.'"'.$checked.'>
				 <label for="cat_'.$catId.'">'.$catName.'</label>	
			 </div>';
	$categoriesFieldset.=$line;
}

// LOAD COUNTIES

$countiesFieldset = '';
$counties = $manager->getAllCounties();

foreach ($counties as $countyId => $countyName) {
	$checked = '';

	if (in_array($countyId, $checkedCounties)) {
		$checked = " checked";
	}

	$line = '<div class="checkbox_item">
			     <input class="category_checkbox" type="checkbox" name="counties[]" value="'.$countyId.'" id="county_'.$countyId.'"'.$checked.'>
				 <label for="county_'.$countyId.'">'.$countyName.'</label>	
			 </div>';
	$countiesFieldset.=$line;
}

$editForm->loadComponents("PREV_NAME", $name);
$editForm->loadComponents("PREV_DESC", $desc);
$editForm->loadComponents("PREV_TAGS", $tags);
$editForm->loadComponents("PREV_SERVICES", $services);
$editForm->loadComponents("PREV_HOURS", $hours);
$editForm->loadComponents("PREV_DOCUMENTATION", $documentation);

$editForm->loadComponents("CATEGORIES", $categoriesFieldset);
$editForm->loadComponents("COUNTIES", $countiesFieldset);

if ($addresses) {
	$editForm->loadComponent("ADDRESSES", $addresses);
} else {
	$editForm->loadComponents("ADDRESSES",'');
}

if ($contacts) {
	$editForm->loadComponent("CONTACT", $contacts);
} else {
	$editForm->loadComponents("CONTACT",'');
}

//TODO add form elements here

$editForm->loadComponents("ACTION_TITLE", $actionTitle);
$editForm->loadComponents("RESOURCES_ID", $idElement);

$editForm->loadComponents("RESULT", $result);
$editForm->loadComponents("ACTION_RESULT", $actionResult);

$page->loadComponent('content', $editForm);

$page->addHeadElement("<script type='text/javascript' src='/js/preventUnload.js'></script>");

$page->show();