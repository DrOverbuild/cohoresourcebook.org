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

	function verify_key($key, $secret) {

		$stmt = $this->prepare('SELECT `secret` FROM `api_keys` WHERE `value` = ?');
		$stmt->bind_param('s', $key);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows == 1) {
			$hash = $result->fetch_assoc()['secret'];

			if (password_verify($secret, $hash)) {
				return true;
			}

		}

		return false;
//		$stmt = $this->prepare('SELECT `id` FROM `api_keys` WHERE `name` = ? AND `value` = ? AND `secret` = ?');
//		$stmt->bind_param('sss', $name, $key, $secret_encoded);
//		$stmt->execute();
//		$result = $stmt->get_result();
//
//		if ($result->num_rows == 1 ) {
//			return true;
//		} else {
//			return false;
//		}
	}
}