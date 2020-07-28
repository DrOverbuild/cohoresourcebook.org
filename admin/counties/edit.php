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

require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/dynamics/component/page/Page.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/dynamics/component/form/EditForm.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/dynamics/data/DBManager.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/dynamics/data/CountiesManager.php');

//var_dump($_POST);

$manager = new DBManager();

$page = new Page();
$editForm = new EditForm($_SERVER['DOCUMENT_ROOT'] . '/admin/dynamics/html/form/categoriesedit.html', $page);

$actionTitle = "NEW COUNTY";

$name = $desc = $icon = "";

$required = false;

$result = "none";
$actionResult = "";

$idElement = "";

if (isset($_GET['id'])) {
	// TODO: LOAD STUFF
	$countyMan = new CountiesManager($manager);
	$county = $countyMan->loadCounty(intval($_GET['id']));
	$name = $county['name'];
	$desc = $county['desc'];
	$icon = $county['icon'];
	$actionTitle = "EDIT COUNTY — ".$name;
	$idElement = '<input type="hidden" id="county_id" name="id" value="' . $_GET['id'] . '">';

	if (isset($_GET['success'])) {
		$result = "block";

		if ($_GET['success'] == "fail") {
			$actionResult = "Add new county failed";
		}else {
			$actionResult = "County ".$_GET['success']." successfully";
		}
	}
}

// load icons for dropdown
$icons = "<option>None</option>";
$iconsDir = $_SERVER['DOCUMENT_ROOT'].'/icon/counties';
$iconsArr = array_diff(scandir($iconsDir), array('..', '.'));
foreach ($iconsArr as $iconFileName) {
	$iconName = basename($iconFileName, ".svg");
	if ($icon == 'counties/'.$iconName) {
		$icons = $icons . "<option selected='selected' value='counties/" . $iconName . "'>" . $iconName . "</option>";
	} else {
		$icons = $icons . "<option value='counties/" . $iconName . "'>" . $iconName . "</option>";
	}
}

if (isset($_POST['name']) && isset($_POST['desc'])) {
	if (empty($_POST['name'])) {
		$editForm->loadComponents("NAME_REQUIRED", "required");
		$required = true;
	} else {
		$name = $_POST['name'];
	}

	if (empty($_POST['desc'])) {
		$editForm->loadComponents("DESC_REQUIRED", "required");
		$required = true;
	} else {
		$desc = $_POST['desc'];}

	if(!empty($_POST['icon'])) {
		$icon = $_POST['icon'];
	}

	if (!$required) { // POST SUCCESS
		$countyMan = new CountiesManager($manager);
		$county = ['name' => $name,
			'desc' => $desc, 'icon' => $icon];
		if (isset($_POST['id'])) {
			// edit category
			$countyMan->editCounty(intval($_POST['id']), $county);
			$actionResult = "edited";
			$id = intval($_POST['id']);
		} else {
			$id = $countyMan->addCounty($county);
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


$editForm->loadComponents("PREV_NAME", $name);
$editForm->loadComponents("PREV_DESC", $desc);
$editForm->loadComponents("ACTION_TITLE", $actionTitle);
$editForm->loadComponents("CATEGORY_ID", $idElement);

$editForm->loadComponents("ICONS", $icons);

$editForm->loadComponents("RESULT", $result);
$editForm->loadComponents("ACTION_RESULT", $actionResult);

$page->loadComponent('content', $editForm);

$page->addHeadElement("<script type='text/javascript' src='/js/preventUnload.js'></script>");
$page->addHeadElement("<script type='text/javascript' src='/js/loadicon.js'></script>");

$page->show();