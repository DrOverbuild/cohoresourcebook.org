<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 8/20/18
 * Time: 7:14 PM
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/Component.php');

class Contact extends Component {
	function __construct() {
		parent::__construct($_SERVER['DOCUMENT_ROOT'] . '/dynamics/html/form/contact.html');
	}
}