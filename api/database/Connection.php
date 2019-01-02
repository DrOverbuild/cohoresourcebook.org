<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 8/28/18
 * Time: 11:02 AM
 */

namespace api\database;


class Connection {
	/** @var \mysqli */
	public $conn;
	public $connect_error;

	public $pageLength = 50;

	public function __construct() {
		$config = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/api/database/db.ini');

		$servername = $config['servername'];
		$username = $config['username'];
		$password = $config['password'];
		$dbname = $config['dbname'];

		$conn = new \mysqli($servername, $username, $password, $dbname);

		if ($conn->connect_error) {
			$this->conn = null;
			$this->connect_error = $conn->connect_error;
		} else {
			$this->conn = $conn;
		}
	}

	function prepare(string $query) {
		if ($this->conn) {
			return $this->conn->prepare($query);
		} else {
			return false;
		}
	}
}