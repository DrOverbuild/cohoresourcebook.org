<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/page/Page.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/table/TableView.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/data/DBManager.php');

$page = new Page();
$manager = new DBManager();
$manager->loadCategories();
$manager->loadResources();

$categories = new TableView('Categories', 'main_left', $page);
$categories->loadComponents('TABLE_ELEMENTS', $manager->categories);

$resources = new TableView('Resources', 'main_right', $page);
$resources->loadComponents('TABLE_ELEMENTS', $manager->resources);

$page->loadComponents('content', [$categories, $resources]);
//var_dump($page);

//$page->process();
$page->show();

?>