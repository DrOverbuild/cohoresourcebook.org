<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasper
 * Date: 7/4/18
 * Time: 3:33 PM
 */

function load_file($_path) {
	if (!empty($_path) && file_exists($_path)) {
		return file_get_contents($_path);
	} else {
		return "<strong>File Load Error:</strong> Could not load \"<em>".$_path."</em>\".";
	}
}

class Component {
	var $htmlContent;
	var $templateFile;

	var $assignedValues = array();

	function __construct($_path) {
		$this->templateFile = $_path;
		$this->htmlContent = load_file($this->templateFile);
	}

	function show(){
		$this->process();
		echo $this->htmlContent;
	}

	function process(): string {
		foreach ($this->assignedValues as $key => $value) {
			if ($value instanceof Component) {
				$this->htmlContent = str_replace('{' . strtoupper($key) . '}', $value->process(), $this->htmlContent);
			} else if (is_array($value)) {
				foreach ($value as $item) {
					$this->htmlContent = str_replace('{' . strtoupper($key) . '}',
						$item->process() . '{' . strtoupper($key) . '}',
						$this->htmlContent);
				}
				$this->htmlContent = str_replace('{' . strtoupper($key) . '}', '', $this->htmlContent);
			} else {
				$this->htmlContent = str_replace('{' . strtoupper($key) . '}', $value, $this->htmlContent);
			}
		}
		return $this->htmlContent;
	}

	function loadComponent(string $_name, Component $_comp) {
		$this->assignedValues[$_name] = $_comp;
	}

	function loadComponents(string $_name, $_comp) {
		$this->assignedValues[$_name] = $_comp;
	}
}