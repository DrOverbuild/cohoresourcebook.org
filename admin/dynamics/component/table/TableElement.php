<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 7/7/18
 * Time: 1:17 AM
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/Component.php');

class TableElement extends Component {

	function __construct($title, $action, $title_link, $action_link) {
		parent::__construct($_SERVER['DOCUMENT_ROOT'] . '/dynamics/html/tableelement.html');

		$this->assignedValues['TITLE'] = $title;
		$this->assignedValues['ACTION'] = $action;
		$this->assignedValues['TITLE_LINK'] = $title_link;
		$this->assignedValues['ACTION_LINK'] = $action_link;
	}
}