<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 7/4/18
 * Time: 3:41 PM
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/Component.php');

class Page extends Component {
	var $headElements = array();

	function __construct() {
		$_path = $_SERVER['DOCUMENT_ROOT'] . '/dynamics/html/page.html';
		parent::__construct($_path);
	}

	function addHeadElement(string $element) {
		array_push($this->headElements, $element);
	}

	function process(): string {
		foreach ($this->headElements as $headElement) {
			$this->htmlContent = str_replace("<!-- head elements -->",
				$headElement . "\n<!-- head elements -->", $this->htmlContent);
		}

		return parent::process();
	}
}