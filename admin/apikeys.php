<html>

<head>
	<title>Create API key</title>
</head>

<body style="max-width: 800px; margin: auto">

<h1>Create API Key</h1>

<form id="create_api" action="./apikeys.php" method="post">
	<div>
		<label>Name: </label>
		<input placeholder="Name" width="50px" name="name">
		<button type="submit" form="create_api">Create</button>
	</div>
</form>

<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 2/10/19
 * Time: 5:03 PM
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamics/data/DBManager.php');

var_dump($_POST);

if ($_POST['name'] && !empty($_POST['name'])) {
	$manager = new DBManager();

	$name = $_POST['name'];
	$key = generate20digits();
	$secret = generate20digits();

	$secret_encoded = password_hash($secret, PASSWORD_DEFAULT);

	$stmt = $manager->conn->prepare("INSERT INTO `api_keys` (`name`, `value`, `secret`) VALUES (?, ?, ?)");
	$stmt->bind_param('sss', $name, $key, $secret_encoded);
	$stmt->execute();

	if (!empty($stmt->error)) {
		echo($stmt->error);
	} else {
		echo("<p>Key created successfully</p> <p><strong>Key: </strong> <pre>$key</pre></p><p><strong>Secret: </strong> <pre>$secret</pre></p>");
	}
}

/**
 * Returns a string of 20 digits
 * @return string
 */
function generate20digits() {
	$str = "";

	for ($i = 0; $i < 20; $i++) {
		$str .= "" . (rand(0, 9));
	}

	return $str;
}

?>

</body>

</html>
