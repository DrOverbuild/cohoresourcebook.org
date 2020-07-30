<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 7/7/18
 * Time: 1:54 AM
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/table/TableElement.php');

class ResourceTableElement extends TableElement {
	var $id;
	var $name;

	function __construct(string $name, int $id) {
		parent::__construct($name, "EDIT", "/resources/edit.php?id=".$id, "/resources/edit.php?id=".$id);
		$this->id = $id;
		$this->name = $name;
	}
}