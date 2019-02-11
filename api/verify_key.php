<html>

<head>
	<title>Create API key</title>
</head>

<body style="max-width: 800px; margin: auto">

<h1>Verify API Key</h1>

<form id="verify_key" action="./verify_key.php" method="post">
	<div>
		<label>Name: </label>
		<input placeholder="Name" width="50px" name="name">
	</div>
	<div>
		<label>Key: </label>
		<input placeholder="Key" width="50px" name="key">
	</div>
	<div>
		<label>Secret: </label>
		<input type="password" placeholder="Secret" width="50px" name="secret">
		<button type="submit" form="verify_key">Verify</button>
	</div>
</form>

<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 2/10/19
 * Time: 5:03 PM
 */

require_once("loadall.php");
use api\database\Connection;

var_dump($_POST);

if ($_POST['name'] && !empty($_POST['name']) &&
	$_POST['key']  && !empty($_POST['key']) &&
	$_POST['secret'] && !empty($_POST['secret'])) {

	$conn = new Connection();

	$name = $_POST['name'];
	$key = $_POST['key'];
	$secret = $_POST['secret'];

//	$secret_encoded = password_hash($secret, PASSWORD_DEFAULT);
	if (!$conn->verify_key($key, $secret)) {
		echo("<p style='color: red'>Unsuccessful</p>");
	} else {
		echo("<p>Key verified successfully</p>");
	}
}

?>

</body>

</html>
