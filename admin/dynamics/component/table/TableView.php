<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 7/5/18
 * Time: 2:05 PM
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/Component.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/form/Form.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/table/TableHeader.php');

class TableView extends Component {
	var $title;
	var $page;

	function __construct($title = '', $class = '', Page $page) {
		parent::__construct($_SERVER['DOCUMENT_ROOT'] . '/dynamics/html/table.html');

		$this->page = $page;

		if (!empty($title)) {
			$this->title = '<h1>' . $title . '</h1>';
		} else {
			$this->title = '';
		}
		$this->assignedValues['TITLE'] = $this->title;
		$this->assignedValues['CLASS'] = $class;
		$this->assignedValues['TABLE_HEADER'] = '';
		$this->assignedValues['TABLE_ELEMENTS'] = '';
		$this->assignedValues['PAGINATION'] = '';
	}

	function enableHeader() {
		$this->assignedValues['TABLE_HEADER'] = new TableHeader($this->page);
	}

	function enablePagination($pageNum, $numPages, $link) {
		$html = '';

		// Prev
		if ($pageNum > 0) {
			$html = '<a href="' . str_replace('{P}',($pageNum - 1), $link) . '">Prev</a> ';
		}

		$first = $pageNum - 5;

		if ($first < 0) {
			$first = 0;
		}

		$numLeft = 10;

		if ($numPages - $first < 10 && $numPages >= 10) {
			$first = $numPages - 9;
			$numLeft = 10;
		}

		if ($numPages < 10) {
			$numLeft = $numPages;
		}

		// prev 5, current, next 4
		for($i = $first; $i < $first + $numLeft; $i++) {

			if ($i == $pageNum) {
				$html .= ' <a class="current" href="' . str_replace('{P}',($i), $link) . '">' . ($i + 1) . '</a> ';
			} else {
				$html .= ' <a href="' . str_replace('{P}',($i), $link) . '">' . ($i + 1) . '</a> ';
			}
		}

		// Next
		if ($pageNum < $numPages - 1) {
			$html .= '<a href="' . str_replace('{P}',($pageNum + 1), $link) . '">Next</a>';
		}

		$this->assignedValues['PAGINATION'] = $html;
	}
}