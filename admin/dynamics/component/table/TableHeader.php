<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 7/9/18
 * Time: 12:20 AM
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/Component.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/page/Page.php');

class TableHeader extends Component {
	function __construct(Page $_page) {
		$_path = $_SERVER['DOCUMENT_ROOT'] . '/dynamics/html/form/tableheader.html';
		$_page->addHeadElement("<link rel='stylesheet' href='/css/form.css'>");
		parent::__construct($_path);
	}
}