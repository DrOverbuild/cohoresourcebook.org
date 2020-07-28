<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 7/27/18
 * Time: 7:21 PM
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/dynamics/component/Component.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/dynamics/component/page/Page.php');

class EditForm extends Component {
	var $page;

	function __construct($path, Page $page) {
		parent::__construct($path);
		$this->page = $page;

		$this->page->addHeadElement("<link rel='stylesheet' href='/css/form.css'>");
	}
}