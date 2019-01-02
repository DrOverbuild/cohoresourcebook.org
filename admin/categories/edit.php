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
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/data/CategoriesManager.php');

//var_dump($_POST);

$manager = new DBManager();

$page = new Page();
$editForm = new EditForm($_SERVER['DOCUMENT_ROOT'] . '/dynamics/html/form/categoriesedit.html', $page);

$actionTitle = "NEW CATEGORY";

$name = $desc = $icon = "";

$required = false;

$result = "none";
$actionResult = "";

$idElement = "";

// loading an existing category
if (isset($_GET['id'])) {
	$categoriesMan = new CategoriesManager($manager);
	$category = $categoriesMan->loadCategory(intval($_GET['id']));
	$name = $category['name'];
	$desc = $category['desc'];
	$icon = $category['icon'];
	$actionTitle = "EDIT CATEGORY — ".$name;
	$idElement = '<input type="hidden" id="cat_id" name="id" value="' . $_GET['id'] . '">';

	if (isset($_GET['success'])) {
		$result = "block";

		if ($_GET['success'] == "fail") {
			$actionResult = "Add new category failed";
		}else {
			$actionResult = "Category ".$_GET['success']." successfully";
		}
	}
}

// load icons for dropdown
$icons = "<option>None</option>";
$iconsDir = $_SERVER['DOCUMENT_ROOT'].'/icon/';
$iconsArr = array_diff(scandir($iconsDir), array('..', '.'));
foreach ($iconsArr as $iconFileName) {
    $iconName = basename($iconFileName, ".svg");
    if ($icon == $iconName) {
        $icons = $icons . "<option selected='selected'>" . $iconName . "</option>";
    } else {
        $icons = $icons . "<option>" . $iconName . "</option>";
    }
}

// saving changes
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
		$desc = $_POST['desc'];
	}

	if(!empty($_POST['icon'])) {
	    $icon = $_POST['icon'];
    }

	if (!$required) { // POST SUCCESS
		$categoriesMan = new CategoriesManager($manager);
		$category = ['name' => $name,
			'desc' => $desc,
            'icon' => $icon];
		if (isset($_POST['id'])) {
			// edit category
			$categoriesMan->editCategory(intval($_POST['id']), $category);
			$actionResult = "edited";
			$id = intval($_POST['id']);
		} else {
			$id = $categoriesMan->addCategory($category);
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

$page->show();