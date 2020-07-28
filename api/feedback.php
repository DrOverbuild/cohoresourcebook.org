<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 2/10/19
 * Time: 6:43 PM
 */

require_once("loadall.php");
use api\database\Connection;

$result = [];

if ($_POST['apikey']  && !empty($_POST['apikey']) &&
	$_POST['secret'] && !empty($_POST['secret'])) {


	$conn = new Connection();

	$key = $_POST['apikey'];
	$secret = $_POST['secret'];

	$comments = "";

	if ($_POST['comments'] && !empty($_POST['comments'])) {
		$comments = $_POST['comments'];
	}

	if ($conn->verify_key($key, $secret)) {
		$stmt = $conn->prepare("INSERT INTO `feedback` (resource, type, comments) VALUES (?, ?,?) ");
		$stmt->bind_param("sss", $_POST['resource'], $_POST['type'], $comments);
		$stmt->execute();

		$result['success'] = 1;

		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= 'From: <feedback@cohoresourcebook.org>' . "\r\n";

		$msg = "<p>New feedback from a user of the Resource App. </p><p><strong>Type</strong>: ".$_POST['type'].
			"</p><p><strong>Resource</strong>: ".$_POST['resource'].
			"</p><p><strong>Comments</strong>: ".$comments."</p>";
		mail("cohoresourcebook@gmail.com","New Resource App feedback",$msg, $headers);

	} else {
		$result['error'] = "Cannot verify API key";
	}

} else {
	$result['error'] = "Empty key or secret";
}

header('Content-Type: application/json');

echo json_encode($result);

?>