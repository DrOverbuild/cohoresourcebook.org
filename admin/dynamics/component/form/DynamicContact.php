<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 8/20/18
 * Time: 5:40 PM
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/Component.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/form/Contact.php');


class DynamicContact extends Component {
	var $contact = array();

	function __construct() {
		parent::__construct($_SERVER['DOCUMENT_ROOT'] . '/dynamics/html/form/dynamiccontact.html');

		$template = new Contact();

		$template->loadComponents('ID', "TEMPLATE");
		$template->loadComponents('NAME_CONT_TYPE', '');
		$template->loadComponents('NAME_CONT_NAME', '');
		$template->loadComponents('NAME_CONT_VALUE', '');

		$template->loadComponents('PHONE_SELECTED', '');
		$template->loadComponents('EMAIL_SELECTED', '');
		$template->loadComponents('WEBSITE_SELECTED', '');
		$template->loadComponents('FAX_SELECTED','');
		$template->loadComponents('CONT_NAME', '');
		$template->loadComponents('CONT_VALUE', '');
		$this->loadComponent('TEMPLATE', $template);

	}

	function process(): string {
		$this->loadComponents('CONTACT', $this->contact);

		return parent::process();
	}

	function addContact($type, $name, $value) {
		$contact = new Contact();
		$locID = count($this->contact);

		$contact->loadComponents('ID', $locID);

		$contact->loadComponents('ID', $locID);
		$contact->loadComponents('NAME_CONT_TYPE', 'contact['.$locID.'][type]');
		$contact->loadComponents('NAME_CONT_NAME', 'contact['.$locID.'][name]');
		$contact->loadComponents('NAME_CONT_VALUE', 'contact['.$locID.'][value]');

		$contact->loadComponents('PHONE_SELECTED', '');
		$contact->loadComponents('EMAIL_SELECTED', '');
		$contact->loadComponents('WEBSITE_SELECTED', '');
		$contact->loadComponents('FAX_SELECTED', '');

		if ($type == 0) {
			$contact->loadComponents('PHONE_SELECTED', 'selected="selected"');
		} else if ($type == 1) {
			$contact->loadComponents('EMAIL_SELECTED', 'selected="selected"');
		} else if ($type == 2) {
			$contact->loadComponents('WEBSITE_SELECTED', 'selected="selected"');
		} else if ($type == 3) {
			$contact->loadComponents('FAX_SELECTED', 'selected="selected"');
		}
		else {
			$contact->loadComponents('PHONE_SELECTED', 'type-load-failure');
			$contact->loadComponents('EMAIL_SELECTED', 'type-load-failure');
			$contact->loadComponents('WEBSITE_SELECTED', 'type-load-failure');
			$contact->loadComponents('FAX_SELECTED', 'type-load-failure');
		}

		$contact->loadComponents('CONT_NAME', $name);
		$contact->loadComponents('CONT_VALUE', $value);

		array_push($this->contact, $contact);
	}

	function addContModel($cont) {
		$this->addContact($cont->type, $cont->name, $cont->value);
	}
}