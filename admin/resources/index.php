<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 7/7/18
 * Time: 10:42 AM
*/

// GET['q'] is set = search term
// GET['p'] is set = page number

require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/page/Page.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/table/TableView.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/data/DBManager.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/data/CategoriesManager.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/data/CountiesManager.php');

$page = new Page();
$manager = new DBManager();

$query = '';
$cat = '';
$county = '';
$p = 0;
$title = 'Resources';

if(isset($_GET['q'])) {
	$query = $_GET['q'];
}

if (isset($_GET['cat']) && is_numeric($_GET['cat'])) {
	$cat = $_GET['cat'];
	$catMan = new CategoriesManager($manager);
	$catName = $catMan->loadCategory(intval($cat))['name'];
	$title = 'Resources in ' . $catName;
} else if (isset($_GET['county']) && is_numeric($_GET['county'])) {
	$county = $_GET['county'];
	$countyMan = new CountiesManager($manager);
	$countyName = $countyMan->loadCounty(intval($county))['name'];
	$title = 'Resources in county ' . $countyName;
}

if(isset($_GET['p'])) {
	$p = $_GET['p'];
}

$manager->loadResources($query, $cat, $county, $p);

$resources = new TableView($title, '', $page);
$resources->loadComponents('TABLE_ELEMENTS', $manager->resources);
$resources->enableHeader();

if ($manager->resourcePages > 1) {
	$resources->enablePagination($p, $manager->resourcePages, "/resources/?p={P}");
}

$page->loadComponent('content', $resources);
//var_dump($page);

//$page->process();
$page->show();