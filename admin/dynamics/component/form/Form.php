<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 7/8/18
 * Time: 2:56 PM
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/Component.php');

class Form extends Component {

	function __construct() {
		parent::__construct($_SERVER['DOCUMENT_ROOT'] . '/dynamics/html/form/form.html');
	}

	function addFormElement($formElement) {
		array_push($this->assignedValues['FORM_ELEMENTS'], $formElement);
	}

}