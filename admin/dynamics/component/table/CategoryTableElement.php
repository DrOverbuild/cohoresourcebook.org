<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 7/7/18
 * Time: 12:59 AM
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/dynamics/component/table/TableElement.php');

class CategoryTableElement extends TableElement {
	var $desc;
	var $id;

	function __construct(string $name, int $id) {
		parent::__construct($name, "EDIT", "/resources/?cat=".$id, "/categories/edit.php?id=".$id);
		$this->id = $id;
		$this->name = $name;
	}
}