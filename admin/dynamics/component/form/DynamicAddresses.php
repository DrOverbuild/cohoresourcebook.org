<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 8/20/18
 * Time: 5:40 PM
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/Component.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/component/form/Address.php');


class DynamicAddresses extends Component {
	var $addresses = array();

	function __construct() {
		parent::__construct($_SERVER['DOCUMENT_ROOT'] . '/dynamics/html/form/dynamicaddresses.html');

		$template = new Address();

		$template->loadComponents('ID', "TEMPLATE");
		$template->loadComponents('NAME_DESC', '');
		$template->loadComponents('NAME_STR1', '');
		$template->loadComponents('NAME_STR2', '');
		$template->loadComponents('NAME_CITY', '');
		$template->loadComponents('NAME_STATE', '');
		$template->loadComponents('NAME_ZIP', '');
		$template->loadComponents('NAME_ID', '');

		$template->loadComponents('ID', "TEMPLATE");
		$template->loadComponents('ADDR_DESC', '');
		$template->loadComponents('ADDR_STR1', '');
		$template->loadComponents('ADDR_STR2', '');
		$template->loadComponents('ADDR_CITY', '');
		$template->loadComponents('ADDR_STATE', '');
		$template->loadComponents('ADDR_ZIP', '');
		$template->loadComponents('ADDR_ID', '');
		$this->loadComponent('TEMPLATE', $template);

	}

	function process(): string {
		$this->loadComponents('ADDRESSES', $this->addresses);

		return parent::process();
	}

	function addAddress($desc, $str1, $str2, $city, $state, $zip, $id) {
		$address = new Address();
		$locID = count($this->addresses);

		$address->loadComponents('ID', $locID);

		$address->loadComponents('NAME_DESC', 'addresses['.$locID.'][description]');
		$address->loadComponents('NAME_STR1', 'addresses['.$locID.'][street1]');
		$address->loadComponents('NAME_STR2', 'addresses['.$locID.'][street2]');
		$address->loadComponents('NAME_CITY', 'addresses['.$locID.'][city]');
		$address->loadComponents('NAME_STATE', 'addresses['.$locID.'][state]');
		$address->loadComponents('NAME_ZIP', 'addresses['.$locID.'][zip]');
		$address->loadComponents('NAME_ID', 'addresses['.$locID.'][id]');

		$address->loadComponents('ADDR_DESC', $desc);
		$address->loadComponents('ADDR_STR1', $str1);
		$address->loadComponents('ADDR_STR2', $str2);
		$address->loadComponents('ADDR_CITY', $city);
		$address->loadComponents('ADDR_STATE', $state);
		$address->loadComponents('ADDR_ZIP', $zip);
		$address->loadComponents('ADDR_ID', $id);
		array_push($this->addresses, $address);
	}

	function addAddrModel($addr) {
		$this->addAddress($addr->desc, $addr->street1, $addr->street2, $addr->city, $addr->state, $addr->zip, $addr->id);
	}
}