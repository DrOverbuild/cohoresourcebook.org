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
$manager->loadCounties($q, $p); // loadCounties

$counties = new TableView('Counties', '', $page);
$counties->loadComponents('TABLE_ELEMENTS', $manager->categories); // Counties
$counties->enableHeader();

if ($manager->categoryPages > 1) {
	$counties->enablePagination($p, $manager->categoryPages, "/counties/?p={P}");
}

$page->loadComponent('content', $counties);
//var_dump($page);

//$page->process();
$page->show();