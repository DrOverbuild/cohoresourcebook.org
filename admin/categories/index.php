<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 7/7/18
 * Time: 10:42 AM
*/

// GET['q'] is set = search term
// GET['p'] is set = page number

require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/dynamics/component/page/Page.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/dynamics/component/table/TableView.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/dynamics/data/DBManager.php');

$q = '';
$p = 0;

if(isset($_GET['q'])) {
	$q = $_GET['q'];
}

if(isset($_GET['p'])) {
	$p = $_GET['p'];
}

$page = new Page();

$manager = new DBManager();
$manager->loadCategories($q, $p);

$categories = new TableView('Categories', '', $page);
$categories->loadComponents('TABLE_ELEMENTS', $manager->categories);
$categories->enableHeader();

if ($manager->categoryPages > 1) {
	$categories->enablePagination($p, $manager->categoryPages, "/categories/?p={P}");
}

$page->loadComponent('content', $categories);
//var_dump($page);

//$page->process();
$page->show();